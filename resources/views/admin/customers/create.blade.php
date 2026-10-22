@extends('admin.layouts.app')

@section('title', 'Add Customer')
@section('page_title', 'Add Customer')
@section('page_sub', 'Register a new customer record')

@section('content')
<div class="w-full max-w-lg mx-auto">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-gray-100 flex items-center gap-3">
            <a href="{{ route('admin.customers.index') }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <p class="text-sm font-semibold text-gray-800">Add New Customer</p>
                <p class="text-xs text-gray-400">Enter customer details below</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.customers.store') }}" class="p-4 sm:p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                    Full Name <span class="text-red-400">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Rahim Ahmed"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 transition-all" />
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                    Phone Number <span class="text-red-400">*</span>
                </label>
                <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="e.g. 01711-000000"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 transition-all" />
                <p class="text-[11px] text-gray-400 mt-1">Phone number must be unique across all customers.</p>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2.5 sm:gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.customers.index') }}" class="btn-secondary text-sm font-medium px-4 py-2.5 rounded-lg transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="btn-primary text-sm font-medium px-5 py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm">
                    <i class="fa-solid fa-check text-xs"></i> Save Customer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
