<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(): View
    {
        $customers = Customer::withCount('orders')
            ->latest('id')
            ->get();

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create(): View
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50', 'unique:customers,phone'],
        ]);

        Customer::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'created_by' => Auth::guard('admin')->id(),
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Quick AJAX store for modal creation inside order form.
     */
    public function quickStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50', 'unique:customers,phone'],
        ]);

        $customer = Customer::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'created_by' => Auth::guard('admin')->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully.',
            'customer' => $customer,
        ]);
    }

    /**
     * Display the specified customer.
     */
    public function show(int $id): View
    {
        $customer = Customer::with(['orders.orderDetails.product', 'orders.thana'])
            ->withCount('orders')
            ->findOrFail($id);

        $totalOrders = $customer->orders_count;
        $deliveredOrders = $customer->orders->where('status', 'delivered')->count();
        $pendingOrders = $customer->orders->where('status', 'pending')->count();
        $recentOrders = $customer->orders()->latest('id')->take(10)->get();

        return view('admin.customers.show', compact('customer', 'totalOrders', 'deliveredOrders', 'pendingOrders', 'recentOrders'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(int $id): View
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50', Rule::unique('customers', 'phone')->ignore($customer->id)],
        ]);

        $customer->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
