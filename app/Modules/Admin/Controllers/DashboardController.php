<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Status;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $totalOrders = Order::count();

        // Calculate total revenue from order details
        $totalRevenue = OrderDetail::selectRaw('SUM(quantity * selling_price) as total')
            ->value('total') ?? 0;

        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();

        $recentOrders = Order::with(['customer', 'thana', 'orderDetails'])
            ->latest('id')
            ->take(5)
            ->get();

        $recentProducts = Product::latest('id')->take(4)->get();

        return view('admin.dashboard.index', compact(
            'totalProducts',
            'totalCustomers',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'processingOrders',
            'deliveredOrders',
            'recentOrders',
            'recentProducts'
        ));
    }
}
