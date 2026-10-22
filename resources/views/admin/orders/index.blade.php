@extends('admin.layouts.app')

@section('title', 'Orders')
@section('page_title', 'Orders')
@section('page_sub', 'Manage all customer orders')

@section('top_actions')
    <a href="{{ route('admin.orders.create') }}" class="btn-primary text-sm font-medium px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm">
        <i class="fa-solid fa-plus text-xs"></i>
        <span>New Order</span>
    </a>
@endsection

@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden w-full">
    <div class="px-4 sm:px-5 py-3.5 sm:py-4 border-b border-gray-100 flex items-center justify-between">
        <p class="text-sm font-semibold text-gray-700">All Orders</p>
        <span class="badge bg-brand-50 text-brand-600">{{ $orders->count() }} orders</span>
    </div>

    <div class="p-3 sm:p-4 overflow-x-auto w-full">
        <table id="ordersTable" class="w-full min-w-[800px]">
            <thead>
                <tr>
                    <th>Order No</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Order Date</th>
                    <th>Exp. Delivery</th>
                    <th>Thana</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td class="text-sm font-bold text-brand-600">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:underline">
                                #{{ $order->order_no }}
                            </a>
                        </td>
                        <td class="text-sm font-medium text-gray-800">
                            @if($order->customer)
                                <a href="{{ route('admin.customers.show', $order->customer->id) }}" class="hover:text-brand-600">
                                    {{ $order->customer->name }}
                                </a>
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="text-sm text-gray-500">
                            {{ $order->customer->phone ?? '-' }}
                        </td>
                        <td class="text-xs text-gray-500">
                            {{ $order->order_date ? $order->order_date->format('M d, Y') : '-' }}
                        </td>
                        <td class="text-xs text-gray-500">
                            {{ $order->exp_delv_date ? $order->exp_delv_date->format('M d, Y') : '-' }}
                        </td>
                        <td class="text-xs text-gray-600">
                            {{ $order->thana->name ?? '-' }}
                        </td>
                        <td class="text-sm font-semibold text-gray-800">
                            ৳ {{ number_format($order->total_amount, 2) }}
                        </td>
                        <td>
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
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                    class="p-1.5 rounded-lg hover:bg-blue-50 text-blue-500 transition-colors"
                                    title="View"><i class="fa-solid fa-eye text-xs"></i></a>
                                <a href="{{ route('admin.orders.edit', $order->id) }}"
                                    class="p-1.5 rounded-lg hover:bg-amber-50 text-amber-500 transition-colors"
                                    title="Edit"><i class="fa-solid fa-pen text-xs"></i></a>
                                <button type="button"
                                    onclick="confirmDelete('{{ route('admin.orders.destroy', $order->id) }}', 'Order')"
                                    class="p-1.5 rounded-lg hover:bg-red-50 text-red-400 transition-colors"
                                    title="Delete"><i class="fa-solid fa-trash text-xs"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#ordersTable').DataTable({
            pageLength: 10,
            language: {
                search: '',
                searchPlaceholder: 'Search orders...',
                lengthMenu: 'Show _MENU_ rows'
            },
            order: []
        });
    });
</script>
@endpush
