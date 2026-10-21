@extends('admin.layouts.app')

@section('title', 'Products')
@section('page_title', 'Products')
@section('page_sub', 'Manage your product catalog')

@section('top_actions')
    <a href="{{ route('admin.products.create') }}" class="btn-primary text-sm font-medium px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm">
        <i class="fa-solid fa-plus text-xs"></i>
        <span>Add Product</span>
    </a>
@endsection

@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <p class="text-sm font-semibold text-gray-700">All Products</p>
        <span class="badge bg-brand-50 text-brand-600">{{ $products->count() }} items</span>
    </div>

    <div class="p-4 overflow-x-auto">
        <table id="productsTable" class="w-full">
            <thead>
                <tr>
                    <th class="w-12">#</th>
                    <th class="w-16">Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Selling Price</th>
                    <th>Created At</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td class="text-gray-500 text-sm">{{ $loop->iteration }}</td>
                        <td>
                            @if($product->image)
                                <img src="{{ $product->image }}" class="w-10 h-10 rounded-lg object-cover border border-gray-100" onerror="this.src='https://placehold.co/40x40/e0e7ff/4f72d9?text=P'" />
                            @else
                                <div class="w-10 h-10 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($product->name, 0, 2)) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.products.show', $product->id) }}" class="font-medium text-gray-800 text-sm hover:text-brand-600">
                                {{ $product->name }}
                            </a>
                            <div class="text-[11px] text-gray-400 truncate max-w-xs">
                                <a href="{{ $product->link }}" target="_blank" class="hover:underline flex items-center gap-1 text-gray-400 hover:text-brand-600">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                    <span class="truncate">{{ $product->link }}</span>
                                </a>
                            </div>
                        </td>
                        <td>
                            @if($product->category)
                                <span class="badge bg-gray-100 text-gray-600">{{ $product->category }}</span>
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="text-sm text-gray-600">{{ $product->size ?? '-' }}</td>
                        <td class="text-sm text-gray-400">
                            {{ $product->price ? '৳ ' . number_format($product->price, 2) : '-' }}
                        </td>
                        <td class="text-sm font-semibold text-gray-800">
                            ৳ {{ number_format($product->selling_price, 2) }}
                        </td>
                        <td class="text-xs text-gray-400">
                            {{ $product->created_at ? $product->created_at->format('Y-m-d') : '-' }}
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.products.show', $product->id) }}"
                                    class="p-1.5 rounded-lg hover:bg-blue-50 text-blue-500 transition-colors"
                                    title="View"><i class="fa-solid fa-eye text-xs"></i></a>
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                    class="p-1.5 rounded-lg hover:bg-amber-50 text-amber-500 transition-colors"
                                    title="Edit"><i class="fa-solid fa-pen text-xs"></i></a>
                                <button type="button"
                                    onclick="confirmDelete('{{ route('admin.products.destroy', $product->id) }}', 'Product')"
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
        $('#productsTable').DataTable({
            pageLength: 10,
            language: {
                search: '',
                searchPlaceholder: 'Search products...',
                lengthMenu: 'Show _MENU_ rows'
            },
            order: []
        });
    });
</script>
@endpush
