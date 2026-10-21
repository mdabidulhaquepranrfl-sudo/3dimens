@extends('admin.layouts.app')

@section('title', 'Edit Customer')
@section('page_title', 'Edit Customer')
@section('page_sub', 'Update customer record')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <a href="{{ route('admin.customers.index') }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <p class="text-sm font-semibold text-gray-800">Edit Customer: {{ $customer->name }}</p>
                <p class="text-xs text-gray-400">Update customer information below</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.customers.update', $customer->id) }}" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                    Full Name <span class="text-red-400">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $customer->name) }}" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 transition-all" />
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                    Phone Number <span class="text-red-400">*</span>
                </label>
                <input type="tel" name="phone" value="{{ old('phone', $customer->phone) }}" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 transition-all" />
                <p class="text-[11px] text-gray-400 mt-1">Must be unique per customer.</p>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-400">
                    Added: {{ $customer->created_at ? $customer->created_at->format('M d, Y') : '-' }}
                </p>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.customers.index') }}" class="btn-secondary text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary text-sm font-medium px-5 py-2 rounded-lg transition-colors flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-check text-xs"></i> Update Customer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
