<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Status;
use App\Models\Thana;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(): View
    {
        $orders = Order::with(['customer', 'thana', 'orderDetails'])
            ->latest('id')
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create(): View
    {
        $customers = Customer::orderBy('name')->get();
        $thanas = Thana::orderBy('name')->get();
        $statuses = Status::orderBy('id')->get();
        $products = Product::orderBy('name')->get();

        return view('admin.orders.create', compact('customers', 'thanas', 'statuses', 'products'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'integer'],
            'order_date' => ['required', 'date'],
            'exp_delv_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:1000'],
            'thana_id' => ['nullable', 'integer'],
            'status_id' => ['required', 'integer'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.making_price' => ['nullable', 'numeric', 'min:0'],
            'items.*.asking_price' => ['nullable', 'numeric', 'min:0'],
            'items.*.selling_price' => ['required', 'numeric', 'min:0'],
        ]);

        DB::beginTransaction();
        try {
            // Generate unique order number: e.g. ORD-1001 or sequential
            $lastOrder = Order::latest('id')->first();
            $nextNumber = $lastOrder ? ($lastOrder->id + 1) : 1;
            $orderNo = 'ORD-' . str_pad((string)$nextNumber, 4, '0', STR_PAD_LEFT);

            // Double check uniqueness
            while (Order::where('order_no', $orderNo)->exists()) {
                $nextNumber++;
                $orderNo = 'ORD-' . str_pad((string)$nextNumber, 4, '0', STR_PAD_LEFT);
            }

            $statusModel = Status::find($validated['status_id']);
            $statusTag = $statusModel?->tag ?? 'pending';

            $order = Order::create([
                'order_no' => $orderNo,
                'customer_id' => $validated['customer_id'],
                'order_date' => $validated['order_date'],
                'exp_delv_date' => $validated['exp_delv_date'] ?? null,
                'address' => $validated['address'] ?? null,
                'thana_id' => $validated['thana_id'] ?? null,
                'status_id' => $validated['status_id'],
                'status' => $statusTag,
                'created_by' => Auth::guard('admin')->id(),
            ]);

            foreach ($validated['items'] as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'making_price' => $item['making_price'] ?? null,
                    'asking_price' => $item['asking_price'] ?? null,
                    'selling_price' => $item['selling_price'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', 'Order #' . $order->order_no . ' created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order.
     */
    public function show(int $id): View
    {
        $order = Order::with(['customer', 'thana', 'orderDetails.product', 'creator', 'updater'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(int $id): View
    {
        $order = Order::with(['orderDetails.product'])->findOrFail($id);
        $customers = Customer::orderBy('name')->get();
        $thanas = Thana::orderBy('name')->get();
        $statuses = Status::orderBy('id')->get();
        $products = Product::orderBy('name')->get();

        return view('admin.orders.edit', compact('order', 'customers', 'thanas', 'statuses', 'products'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'order_no' => ['required', 'string', 'max:50', Rule::unique('orders', 'order_no')->ignore($order->id)],
            'customer_id' => ['required', 'exists:customers,id'],
            'order_date' => ['required', 'date'],
            'exp_delv_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:1000'],
            'thana_id' => ['nullable', 'exists:thana,id'],
            'status_id' => ['required', 'integer', 'exists:status,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.making_price' => ['nullable', 'numeric', 'min:0'],
            'items.*.asking_price' => ['nullable', 'numeric', 'min:0'],
            'items.*.selling_price' => ['required', 'numeric', 'min:0'],
        ]);

        DB::beginTransaction();
        try {
            $statusModel = Status::find($validated['status_id']);
            $statusTag = $statusModel?->tag ?? $order->status;

            $order->update([
                'order_no' => $validated['order_no'],
                'customer_id' => $validated['customer_id'],
                'order_date' => $validated['order_date'],
                'exp_delv_date' => $validated['exp_delv_date'] ?? null,
                'address' => $validated['address'] ?? null,
                'thana_id' => $validated['thana_id'] ?? null,
                'status_id' => $validated['status_id'],
                'status' => $statusTag,
                'updated_by' => Auth::guard('admin')->id(),
            ]);

            // Sync order details: delete existing and re-insert
            $order->orderDetails()->delete();

            foreach ($validated['items'] as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'making_price' => $item['making_price'] ?? null,
                    'asking_price' => $item['asking_price'] ?? null,
                    'selling_price' => $item['selling_price'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', 'Order #' . $order->order_no . ' updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update order: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $orderNo = $order->order_no;
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order #' . $orderNo . ' deleted successfully.');
    }
}
