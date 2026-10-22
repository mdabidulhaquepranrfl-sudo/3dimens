@extends('admin.layouts.app')

@section('title', 'Add Product')
@section('page_title', 'Add Product')
@section('page_sub', 'Create a new product in the catalog')

@section('content')
<div class="w-full max-w-4xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-gray-100 flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <p class="text-sm font-semibold text-gray-800">Add New Product</p>
                <p class="text-xs text-gray-400">Fill in the product details below</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="p-4 sm:p-6 space-y-4 sm:space-y-5">
            @csrf

            <div class="grid grid-cols-1 gap-4 sm:gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Product Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Classic White Tee"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 transition-all" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Product Link <span class="text-red-400">*</span>
                    </label>
                    <input type="url" name="link" value="{{ old('link') }}" required placeholder="https://example.com/product..."
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 transition-all" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Image URL</label>
                        <input type="url" name="image" value="{{ old('image') }}" placeholder="https://images.unsplash.com/..."
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 transition-all" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Or Upload Image File</label>
                        <input type="file" name="image_file" accept="image/*"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 bg-gray-50/50 file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Category</label>
                        <input type="text" name="category" value="{{ old('category') }}" placeholder="e.g. T-Shirt, Dress, Pants"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 transition-all" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Size</label>
                        <input type="text" name="size" value="{{ old('size') }}" placeholder="e.g. S, M, L, XL"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 transition-all" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Regular Price (৳)</label>
                        <input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" placeholder="0.00"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 transition-all" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Selling Price (৳) <span class="text-red-400">*</span>
                        </label>
                        <input type="number" step="0.01" min="0" name="selling_price" value="{{ old('selling_price') }}" required placeholder="0.00"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 transition-all" />
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2.5 sm:gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.products.index') }}" class="btn-secondary text-sm font-medium px-4 py-2.5 rounded-lg transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="btn-primary text-sm font-medium px-5 py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm">
                    <i class="fa-solid fa-check text-xs"></i> Save Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
