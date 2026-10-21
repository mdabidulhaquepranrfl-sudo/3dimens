@extends('admin.layouts.app')

@section('title', 'Customers')
@section('page_title', 'Customers')
@section('page_sub', 'Manage customer records and details')

@section('top_actions')
    <a href="{{ route('admin.customers.create') }}" class="btn-primary text-sm font-medium px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm">
        <i class="fa-solid fa-user-plus text-xs"></i>
        <span>Add Customer</span>
    </a>
@endsection

@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <p class="text-sm font-semibold text-gray-700">All Customers</p>
        <span class="badge bg-brand-50 text-brand-600">{{ $customers->count() }} customers</span>
    </div>

    <div class="p-4 overflow-x-auto">
        <table id="customersTable" class="w-full">
            <thead>
                <tr>
                    <th class="w-12">#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Orders</th>
                    <th>Created At</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                    <tr>
                        <td class="text-gray-500 text-sm">{{ $loop->iteration }}</td>
                        <td>
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-600 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                                </div>
                                <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-sm font-semibold text-gray-800 hover:text-brand-600">
                                    {{ $customer->name }}
                                </a>
                            </div>
                        </td>
                        <td class="text-sm text-gray-600">
                            <span class="inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-phone text-xs text-gray-400"></i>
                                <span>{{ $customer->phone }}</span>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-brand-50 text-brand-600">
                                {{ $customer->orders_count }} {{ Str::plural('order', $customer->orders_count) }}
                            </span>
                        </td>
                        <td class="text-xs text-gray-400">
                            {{ $customer->created_at ? $customer->created_at->format('M d, Y') : '-' }}
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.customers.show', $customer->id) }}"
                                    class="p-1.5 rounded-lg hover:bg-blue-50 text-blue-500 transition-colors"
                                    title="View Profile"><i class="fa-solid fa-eye text-xs"></i></a>
                                <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                    class="p-1.5 rounded-lg hover:bg-amber-50 text-amber-500 transition-colors"
                                    title="Edit"><i class="fa-solid fa-pen text-xs"></i></a>
                                <button type="button"
                                    onclick="confirmDelete('{{ route('admin.customers.destroy', $customer->id) }}', 'Customer')"
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
        $('#customersTable').DataTable({
            pageLength: 10,
            language: {
                search: '',
                searchPlaceholder: 'Search customers...',
                lengthMenu: 'Show _MENU_ rows'
            },
            order: []
        });
    });
</script>
@endpush
