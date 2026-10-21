@extends('admin.layouts.app')

@section('title', 'Customer Profile')
@section('page_title', 'Customer Profile')
@section('page_sub', 'Customer details and order history')

@section('top_actions')
    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn-primary text-xs font-medium px-3.5 py-2 rounded-lg flex items-center gap-1.5 shadow-sm">
        <i class="fa-solid fa-pen text-xs"></i> Edit Customer
    </a>
    <a href="{{ route('admin.customers.index') }}" class="btn-secondary text-xs font-medium px-3 py-2 rounded-lg flex items-center gap-1.5 border border-gray-200">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back
    </a>
@endsection

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Customer Card -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-5 mb-6">
            <div class="w-16 h-16 rounded-2xl bg-brand-100 text-brand-600 font-bold text-2xl flex items-center justify-center flex-shrink-0 shadow-sm">
                {{ strtoupper(substr($customer->name, 0, 2)) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $customer->name }}</h2>
                <p class="text-sm text-gray-500 mt-1 flex items-center gap-1.5">
                    <i class="fa-solid fa-phone text-xs text-gray-400"></i>
                    <span>{{ $customer->phone }}</span>
                </p>
                <p class="text-xs text-gray-400 mt-0.5">
                    Customer since {{ $customer->created_at ? $customer->created_at->format('M d, Y') : '-' }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div class="bg-brand-50/80 rounded-xl p-4 text-center">
                <p class="text-2xl font-extrabold text-brand-600">{{ $totalOrders }}</p>
                <p class="text-xs font-medium text-gray-500 mt-0.5">Total Orders</p>
            </div>
            <div class="bg-emerald-50/80 rounded-xl p-4 text-center">
                <p class="text-2xl font-extrabold text-emerald-600">{{ $deliveredOrders }}</p>
                <p class="text-xs font-medium text-gray-500 mt-0.5">Delivered</p>
            </div>
            <div class="bg-amber-50/80 rounded-xl p-4 text-center">
                <p class="text-2xl font-extrabold text-amber-600">{{ $pendingOrders }}</p>
                <p class="text-xs font-medium text-gray-500 mt-0.5">Pending</p>
            </div>
        </div>
    </div>

    <!-- Customer Orders -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-800">Order History</h3>
                <p class="text-xs text-gray-400">All orders placed by {{ $customer->name }}</p>
            </div>
            <a href="{{ route('admin.orders.create') }}?customer_id={{ $customer->id }}" class="text-xs font-medium text-brand-600 hover:text-brand-700 flex items-center gap-1">
                <i class="fa-solid fa-plus text-[10px]"></i> New Order
            </a>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse($recentOrders as $order)
                <div class="px-5 py-3.5 flex items-center justify-between hover:bg-gray-50/60 transition-colors">
                    <div>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-sm font-semibold text-brand-600 hover:underline">
                            #{{ $order->order_no }}
                        </a>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $order->order_date ? $order->order_date->format('M d, Y') : '-' }} &middot;
                            {{ $order->total_items }} {{ Str::plural('item', $order->total_items) }} &middot;
                            <span class="font-semibold text-gray-700">৳ {{ number_format($order->total_amount, 2) }}</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        @php
                            $statusClass = match(strtolower($order->status)) {
                                'delivered' => 'bg-emerald-50 text-emerald-600',
                                'shipped' => 'bg-blue-50 text-blue-600',
                                'processing' => 'bg-amber-50 text-amber-600',
                                'cancelled' => 'bg-rose-50 text-rose-600',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="p-1.5 text-gray-400 hover:text-brand-600 rounded-lg">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400 text-sm">
                    No orders placed by this customer yet.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
