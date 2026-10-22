@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_sub', 'Overview of your store performance, orders, and products')

@section('top_actions')
    <a href="{{ route('admin.orders.create') }}" class="btn-primary text-sm font-medium px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm">
        <i class="fa-solid fa-plus text-xs"></i>
        <span>New Order</span>
    </a>
    <a href="{{ route('admin.products.create') }}" class="btn-secondary text-sm font-medium px-4 py-2 rounded-lg flex items-center gap-2 transition-colors border border-gray-200">
        <i class="fa-solid fa-box text-xs"></i>
        <span>Add Product</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- KPI STATS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Products -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Products</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalProducts) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-box-open"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between text-xs text-gray-500">
                <a href="{{ route('admin.products.index') }}" class="text-brand-600 hover:text-brand-700 font-medium flex items-center gap-1">
                    <span>View all products</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
                <span class="text-gray-400">In catalog</span>
            </div>
        </div>

        <!-- Customers -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Customers</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalCustomers) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between text-xs text-gray-500">
                <a href="{{ route('admin.customers.index') }}" class="text-purple-600 hover:text-purple-700 font-medium flex items-center gap-1">
                    <span>View customers</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
                <span class="text-gray-400">Registered</span>
            </div>
        </div>

        <!-- Orders -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Orders</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalOrders) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between text-xs text-gray-500">
                <span class="text-amber-600 font-medium">{{ $pendingOrders }} Pending</span>
                <span class="text-emerald-600 font-medium">{{ $deliveredOrders }} Delivered</span>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Revenue</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">৳ {{ number_format($totalRevenue, 2) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between text-xs text-gray-500">
                <a href="{{ route('admin.orders.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium flex items-center gap-1">
                    <span>View sales</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
                <span class="text-gray-400">All time</span>
            </div>
        </div>
    </div>

    <!-- MAIN TWO COLUMN GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- RECENT ORDERS (2 Cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-800">Recent Orders</h3>
                    <p class="text-xs text-gray-400">Latest orders placed by customers</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-medium text-brand-600 hover:text-brand-700">View all &rarr;</a>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full text-left min-w-[650px]">
                    <thead>
                        <tr class="bg-gray-50/50 text-[11px] font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100 whitespace-nowrap">
                            <th class="py-3 px-5">Order No</th>
                            <th class="py-3 px-4">Customer</th>
                            <th class="py-3 px-4">Date</th>
                            <th class="py-3 px-4">Amount</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-brand-50/30 transition-colors">
                                <td class="py-3.5 px-5 font-semibold text-brand-600">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:underline">
                                        #{{ $order->order_no }}
                                    </a>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-gray-800">{{ $order->customer->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-400">{{ $order->customer->phone ?? '' }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-xs text-gray-500">
                                    {{ $order->order_date ? $order->order_date->format('M d, Y') : '-' }}
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-gray-800">
                                    ৳ {{ number_format($order->total_amount, 2) }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @php
                                        $statusClass = match(strtolower($order->status)) {
                                            'delivered' => 'bg-emerald-50 text-emerald-600',
                                            'shipped' => 'bg-blue-50 text-blue-600',
                                            'processing' => 'bg-amber-50 text-amber-600',
                                            'cancelled' => 'bg-rose-50 text-rose-600',
                                            default => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-right">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="p-1.5 rounded-lg hover:bg-blue-50 text-blue-500 transition-colors inline-block"
                                        title="View"><i class="fa-solid fa-eye text-xs"></i></a>
                                    <a href="{{ route('admin.orders.edit', $order->id) }}"
                                        class="p-1.5 rounded-lg hover:bg-amber-50 text-amber-500 transition-colors inline-block"
                                        title="Edit"><i class="fa-solid fa-pen text-xs"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-400 text-sm">
                                    No orders found. <a href="{{ route('admin.orders.create') }}" class="text-brand-600 underline">Create one now</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RECENT PRODUCTS (1 Col) -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-800">Recent Products</h3>
                    <p class="text-xs text-gray-400">Recently added items</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-medium text-brand-600 hover:text-brand-700">View all &rarr;</a>
            </div>

            <div class="p-4 space-y-3 flex-1">
                @forelse($recentProducts as $prod)
                    <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 transition-colors">
                        @if($prod->image)
                            <img src="{{ $prod->image }}" alt="{{ $prod->name }}" class="w-11 h-11 rounded-lg object-cover border border-gray-100 flex-shrink-0" onerror="this.src='https://placehold.co/44x44/e0e7ff/4f72d9?text=P'">
                        @else
                            <div class="w-11 h-11 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                {{ strtoupper(substr($prod->name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('admin.products.show', $prod->id) }}" class="text-sm font-semibold text-gray-800 hover:text-brand-600 truncate block">
                                {{ $prod->name }}
                            </a>
                            <div class="flex items-center gap-2 mt-0.5">
                                @if($prod->category)
                                    <span class="badge bg-gray-100 text-gray-600 text-[10px]">{{ $prod->category }}</span>
                                @endif
                                <span class="text-xs font-bold text-brand-600">৳ {{ number_format($prod->selling_price, 2) }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.products.edit', $prod->id) }}" class="p-1.5 text-gray-400 hover:text-brand-600 rounded-lg">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400 text-sm">
                        No products added yet.
                    </div>
                @endforelse
            </div>

            <div class="p-4 border-t border-gray-100 bg-gray-50/40">
                <a href="{{ route('admin.products.create') }}" class="btn-primary w-full text-xs font-medium py-2 rounded-lg flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i> Add New Product
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
