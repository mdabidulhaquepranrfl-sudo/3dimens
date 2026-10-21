@extends('admin.layouts.app')

@section('title', 'Edit Product')
@section('page_title', 'Edit Product')
@section('page_sub', 'Update product information')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <p class="text-sm font-semibold text-gray-800">Edit Product: {{ $product->name }}</p>
                <p class="text-xs text-gray-400">Update product details below</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Product Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 transition-all" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Product Link <span class="text-red-400">*</span>
                    </label>
                    <input type="url" name="link" value="{{ old('link', $product->link) }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 transition-all" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Image URL</label>
                        <input type="url" name="image" value="{{ old('image', $product->image) }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 transition-all" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Or Replace Image File</label>
                        <input type="file" name="image_file" accept="image/*"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 bg-gray-50/50 file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100" />
                    </div>
                </div>

                @if($product->image)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <img src="{{ $product->image }}" class="w-12 h-12 rounded object-cover border" onerror="this.src='https://placehold.co/48x48/e0e7ff/4f72d9?text=P'" />
                        <span class="text-xs text-gray-500">Current image preview</span>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Category</label>
                        <input type="text" name="category" value="{{ old('category', $product->category) }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 transition-all" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Size</label>
                        <input type="text" name="size" value="{{ old('size', $product->size) }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 transition-all" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Regular Price (৳)</label>
                        <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 transition-all" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Selling Price (৳) <span class="text-red-400">*</span>
                        </label>
                        <input type="number" step="0.01" min="0" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 transition-all" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-400">
                    Last updated: {{ $product->updated_at ? $product->updated_at->format('M d, Y') : '-' }}
                </p>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.products.index') }}" class="btn-secondary text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary text-sm font-medium px-5 py-2 rounded-lg transition-colors flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-check text-xs"></i> Update Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
