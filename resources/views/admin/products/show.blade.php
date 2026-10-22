@extends('admin.layouts.app')

@section('title', 'Product Details')
@section('page_title', 'Product Details')
@section('page_sub', 'Detailed view of ' . $product->name)

@section('top_actions')
    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-primary text-xs font-medium px-3.5 py-2 rounded-lg flex items-center gap-1.5 shadow-sm">
        <i class="fa-solid fa-pen text-xs"></i> Edit Product
    </a>
    <a href="{{ route('admin.products.index') }}" class="btn-secondary text-xs font-medium px-3 py-2 rounded-lg flex items-center gap-1.5 border border-gray-200">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back
    </a>
@endsection

@section('content')
<div class="w-full max-w-4xl mx-auto space-y-4 sm:space-y-6">

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row items-start gap-6 pb-6 border-b border-gray-100">
                @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-32 h-32 rounded-2xl object-cover border border-gray-100 shadow-sm flex-shrink-0" onerror="this.src='https://placehold.co/128x128/e0e7ff/4f72d9?text=Product'" />
                @else
                    <div class="w-32 h-32 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-3xl shadow-sm flex-shrink-0">
                        {{ strtoupper(substr($product->name, 0, 2)) }}
                    </div>
                @endif
                <div class="flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-xl font-bold text-gray-800">{{ $product->name }}</h2>
                        @if($product->category)
                            <span class="badge bg-gray-100 text-gray-600">{{ $product->category }}</span>
                        @endif
                    </div>

                    <div class="mt-3 flex items-baseline gap-3">
                        <span class="text-3xl font-extrabold text-brand-600">৳ {{ number_format($product->selling_price, 2) }}</span>
                        @if($product->price)
                            <span class="text-base text-gray-400 line-through">৳ {{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>

                    <div class="mt-4">
                        <a href="{{ $product->link }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-medium text-brand-600 hover:text-brand-700 bg-brand-50/70 hover:bg-brand-50 px-3 py-1.5 rounded-lg transition-colors">
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            <span>View External Product Page</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6">
                <div class="bg-gray-50/80 rounded-xl p-3.5">
                    <p class="text-xs text-gray-400 mb-0.5">Size</p>
                    <p class="text-sm font-semibold text-gray-700">{{ $product->size ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-50/80 rounded-xl p-3.5">
                    <p class="text-xs text-gray-400 mb-0.5">Created By</p>
                    <p class="text-sm font-semibold text-gray-700">{{ $product->creator->name ?? 'Admin' }}</p>
                </div>
                <div class="bg-gray-50/80 rounded-xl p-3.5">
                    <p class="text-xs text-gray-400 mb-0.5">Created At</p>
                    <p class="text-sm font-semibold text-gray-700">{{ $product->created_at ? $product->created_at->format('M d, Y') : '-' }}</p>
                </div>
                <div class="bg-gray-50/80 rounded-xl p-3.5">
                    <p class="text-xs text-gray-400 mb-0.5">Updated At</p>
                    <p class="text-sm font-semibold text-gray-700">{{ $product->updated_at ? $product->updated_at->format('M d, Y') : '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Appearances -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-800">Order History for this Product</h3>
            <span class="badge bg-brand-50 text-brand-600">{{ $product->orderDetails->count() }} sales</span>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm min-w-[550px]">
                <thead>
                    <tr class="bg-gray-50/50 text-[11px] font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100 whitespace-nowrap">
                        <th class="py-3 px-5">Order No</th>
                        <th class="py-3 px-4">Qty</th>
                        <th class="py-3 px-4">Selling Price</th>
                        <th class="py-3 px-4">Subtotal</th>
                        <th class="py-3 px-4">Order Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($product->orderDetails as $detail)
                        <tr class="hover:bg-brand-50/30">
                            <td class="py-3 px-5 font-medium text-brand-600">
                                @if($detail->order)
                                    <a href="{{ route('admin.orders.show', $detail->order->id) }}" class="hover:underline">
                                        #{{ $detail->order->order_no }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-4">{{ $detail->quantity }}</td>
                            <td class="py-3 px-4">৳ {{ number_format($detail->selling_price, 2) }}</td>
                            <td class="py-3 px-4 font-semibold text-gray-800">৳ {{ number_format($detail->subtotal, 2) }}</td>
                            <td class="py-3 px-4 text-xs text-gray-500">
                                {{ $detail->order && $detail->order->order_date ? $detail->order->order_date->format('M d, Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-400 text-xs">This product has not been ordered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
