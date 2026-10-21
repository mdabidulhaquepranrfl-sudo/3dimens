@extends('admin.layouts.app')

@section('title', 'Order #' . $order->order_no)
@section('page_title', 'Order #' . $order->order_no)
@section('page_sub', 'Order details and ordered items')

@section('top_actions')
    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn-primary text-xs font-medium px-3.5 py-2 rounded-lg flex items-center gap-1.5 shadow-sm">
        <i class="fa-solid fa-pen text-xs"></i> Edit Order
    </a>
    <a href="{{ route('admin.orders.index') }}" class="btn-secondary text-xs font-medium px-3 py-2 rounded-lg flex items-center gap-1.5 border border-gray-200">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back to Orders
    </a>
@endsection

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    <!-- Order Header Info Card -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-800">
                        Order <span class="text-brand-600">#{{ $order->order_no }}</span>
                    </h2>
                    <p class="text-xs text-gray-400">Placed on {{ $order->order_date ? $order->order_date->format('M d, Y') : '-' }}</p>
                </div>
            </div>
            <div>
                @php
                    $statusClass = match(strtolower($order->status)) {
                        'delivered' => 'bg-emerald-50 text-emerald-600',
                        'shipped' => 'bg-blue-50 text-blue-600',
                        'processing' => 'bg-amber-50 text-amber-600',
                        'cancelled' => 'bg-rose-50 text-rose-600',
                        default => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <span class="badge {{ $statusClass }} text-xs px-3 py-1">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Details -->
                <div class="bg-gray-50/70 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Customer Details</p>
                    <p class="text-sm font-bold text-gray-800">{{ $order->customer->name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-600 mt-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-phone text-gray-400 text-[10px]"></i>
                        <span>{{ $order->customer->phone ?? 'N/A' }}</span>
                    </p>
                    @if($order->customer)
                        <a href="{{ route('admin.customers.show', $order->customer->id) }}" class="text-xs font-medium text-brand-600 hover:underline mt-2 inline-block">
                            View Customer Profile &rarr;
                        </a>
                    @endif
                </div>

                <!-- Delivery Details -->
                <div class="bg-gray-50/70 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Delivery Details</p>
                    <p class="text-sm text-gray-800 font-medium">{{ $order->address ?: 'No address provided' }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="font-medium text-gray-600">Thana:</span> {{ $order->thana->name ?? 'Not specified' }}
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        <span class="font-medium text-gray-600">Expected Delivery:</span> 
                        {{ $order->exp_delv_date ? $order->exp_delv_date->format('M d, Y') : 'Not set' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Ordered Table -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-800">Items Ordered</h3>
            <span class="badge bg-brand-50 text-brand-600">{{ $order->total_items }} total {{ Str::plural('piece', $order->total_items) }}</span>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase border-b border-gray-100">
                    <th class="text-left px-5 py-3">Product</th>
                    <th class="text-center px-3 py-3">Qty</th>
                    <th class="text-right px-3 py-3">Making</th>
                    <th class="text-right px-3 py-3">Asking</th>
                    <th class="text-right px-5 py-3">Selling</th>
                    <th class="text-right px-5 py-3">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($order->orderDetails as $item)
                    <tr class="hover:bg-brand-50/20">
                        <td class="px-5 py-3.5">
                            @if($item->product)
                                <a href="{{ route('admin.products.show', $item->product->id) }}" class="font-semibold text-gray-800 hover:text-brand-600">
                                    {{ $item->product->name }}
                                </a>
                                @if($item->product->category)
                                    <span class="badge bg-gray-100 text-gray-500 text-[10px] ml-2">{{ $item->product->category }}</span>
                                @endif
                            @else
                                <span class="text-gray-400">Product removed</span>
                            @endif
                        </td>
                        <td class="px-3 py-3.5 text-center font-medium text-gray-700">{{ $item->quantity }}</td>
                        <td class="px-3 py-3.5 text-right text-gray-400">
                            {{ $item->making_price ? '৳ ' . number_format($item->making_price, 2) : '-' }}
                        </td>
                        <td class="px-3 py-3.5 text-right text-gray-400">
                            {{ $item->asking_price ? '৳ ' . number_format($item->asking_price, 2) : '-' }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-medium text-gray-800">
                            ৳ {{ number_format($item->selling_price, 2) }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold text-gray-800">
                            ৳ {{ number_format($item->subtotal, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50/80 border-t-2 border-gray-100">
                    <td colspan="5" class="px-5 py-3.5 text-right text-sm font-bold text-gray-700">
                        Total Amount:
                    </td>
                    <td class="px-5 py-3.5 text-right text-lg font-extrabold text-brand-600">
                        ৳ {{ number_format($order->total_amount, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>
@endsection
