@extends('admin.layouts.app')

@section('title', 'Edit Order')
@section('page_title', 'Edit Order #' . $order->order_no)
@section('page_sub', 'Update order details and items')

@section('content')
<div class="w-full max-w-5xl mx-auto space-y-4 sm:space-y-5">

    <form id="orderForm" method="POST" action="{{ route('admin.orders.update', $order->id) }}" class="space-y-4 sm:space-y-5">
        @csrf
        @method('PUT')

        <!-- Order Information Card -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.orders.index') }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
                        <i class="fa-solid fa-arrow-left text-sm"></i>
                    </a>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Edit Order: <span class="text-brand-600 font-bold">#{{ $order->order_no }}</span></p>
                        <p class="text-xs text-gray-400 hidden sm:block">Update customer and delivery details</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <input type="text" name="order_no" value="{{ old('order_no', $order->order_no) }}" required
                        class="text-xs font-mono font-bold px-3 py-1.5 border border-gray-200 rounded-lg text-gray-700 w-32 text-center" title="Order Number" />
                </div>
            </div>

            <div class="p-4 sm:p-6 space-y-4 sm:space-y-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Customer <span class="text-red-400">*</span></label>
                    <select id="customerSelect" name="customer_id" required class="select2 w-full">
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ (old('customer_id', $order->customer_id) == $customer->id) ? 'selected' : '' }}>
                                {{ $customer->name }} — {{ $customer->phone }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Order Date <span class="text-red-400">*</span></label>
                        <input type="date" name="order_date" value="{{ old('order_date', $order->order_date ? $order->order_date->format('Y-m-d') : '') }}" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 transition-all bg-white" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Expected Delivery Date</label>
                        <input type="date" name="exp_delv_date" value="{{ old('exp_delv_date', $order->exp_delv_date ? $order->exp_delv_date->format('Y-m-d') : '') }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 transition-all bg-white" />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Delivery Address</label>
                    <textarea name="address" rows="2"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 resize-none transition-all">{{ old('address', $order->address) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Thana</label>
                        <select name="thana_id" class="select2 w-full">
                            <option value="">Select Thana...</option>
                            @foreach($thanas as $thana)
                                <option value="{{ $thana->id }}" {{ (old('thana_id', $order->thana_id) == $thana->id) ? 'selected' : '' }}>
                                    {{ $thana->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status <span class="text-red-400">*</span></label>
                        <select name="status_id" required class="select2 w-full">
                            @foreach($statuses as $st)
                                <option value="{{ $st->id }}" {{ (old('status_id', $order->status_id ?? ($statuses->firstWhere('tag', $order->status)?->id ?? '')) == $st->id) ? 'selected' : '' }}>
                                    {{ $st->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items Card -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold text-gray-700">Order Items</p>
                    <p class="text-xs text-gray-400">Modify items and unit pricing</p>
                </div>
                <button type="button" onclick="addOrderRow()"
                    class="btn-secondary text-xs font-semibold px-3 py-1.5 rounded-lg text-brand-600 hover:text-brand-700 flex items-center justify-center gap-1.5 border border-gray-200 self-start sm:self-auto">
                    <i class="fa-solid fa-plus text-xs"></i> Add Item
                </button>
            </div>

            <div class="p-3 sm:p-4 overflow-x-auto w-full">
                <table class="w-full text-sm min-w-[700px]">
                    <thead>
                        <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wide border-b border-gray-100 whitespace-nowrap">
                            <th class="text-left pb-3 pr-3" style="min-width: 220px;">Product <span class="text-red-400">*</span></th>
                            <th class="text-left pb-3 pr-3 w-20">Qty <span class="text-red-400">*</span></th>
                            <th class="text-left pb-3 pr-3 w-28">Making Price</th>
                            <th class="text-left pb-3 pr-3 w-28">Asking Price</th>
                            <th class="text-left pb-3 pr-3 w-28">Selling Price <span class="text-red-400">*</span></th>
                            <th class="text-right pb-3 pr-3 w-28">Subtotal</th>
                            <th class="w-8"></th>
                        </tr>
                    </thead>
                    <tbody id="orderItemsBody">
                        @foreach($order->orderDetails as $i => $detail)
                            <tr class="border-t border-gray-100 order-item-row">
                                <td class="py-2.5 pr-3">
                                    <select name="items[{{ $i }}][product_id]" required class="select2 product-select w-full" onchange="onProductSelect(this)">
                                        <option value="">Select product...</option>
                                        @foreach($products as $prod)
                                            <option value="{{ $prod->id }}" data-price="{{ $prod->selling_price }}" data-regular="{{ $prod->price }}"
                                                {{ $detail->product_id == $prod->id ? 'selected' : '' }}>
                                                {{ $prod->name }} (৳{{ number_format($prod->selling_price, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-2.5 pr-3">
                                    <input type="number" name="items[{{ $i }}][quantity]" value="{{ $detail->quantity }}" min="1" required
                                        class="item-qty w-full border border-gray-200 rounded-lg px-2.5 py-2 text-sm text-center" oninput="calculateRowTotal(this)" />
                                </td>
                                <td class="py-2.5 pr-3">
                                    <input type="number" step="0.01" min="0" name="items[{{ $i }}][making_price]" value="{{ $detail->making_price }}" placeholder="৳ 0.00"
                                        class="w-full border border-gray-200 rounded-lg px-2.5 py-2 text-sm" />
                                </td>
                                <td class="py-2.5 pr-3">
                                    <input type="number" step="0.01" min="0" name="items[{{ $i }}][asking_price]" value="{{ $detail->asking_price }}" placeholder="৳ 0.00"
                                        class="item-asking w-full border border-gray-200 rounded-lg px-2.5 py-2 text-sm" />
                                </td>
                                <td class="py-2.5 pr-3">
                                    <input type="number" step="0.01" min="0" name="items[{{ $i }}][selling_price]" value="{{ $detail->selling_price }}" required placeholder="৳ 0.00"
                                        class="item-selling w-full border border-gray-200 rounded-lg px-2.5 py-2 text-sm font-semibold text-gray-800" oninput="calculateRowTotal(this)" />
                                </td>
                                <td class="py-2.5 pr-3 text-right font-bold text-gray-800 item-subtotal">
                                    ৳ {{ number_format($detail->subtotal, 2) }}
                                </td>
                                <td class="py-2.5 text-center">
                                    <button type="button" class="text-gray-300 hover:text-red-500 transition-colors p-1" onclick="removeOrderRow(this)">
                                        <i class="fa-solid fa-times text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-100 bg-gray-50/50 whitespace-nowrap">
                            <td colspan="5" class="py-3 pr-3 text-right text-sm font-bold text-gray-700">Estimated Total:</td>
                            <td class="py-3 pr-3 text-right text-base font-extrabold text-brand-600" id="orderGrandTotal">৳ 0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-3 bg-gray-50/20">
                <p class="text-xs text-gray-400">
                    Last updated: {{ $order->updated_at ? $order->updated_at->format('M d, Y') : '-' }}
                </p>
                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3">
                    <a href="{{ route('admin.orders.index') }}" class="btn-secondary text-sm font-medium px-4 py-2.5 rounded-lg text-center">Cancel</a>
                    <button type="submit" class="btn-primary text-sm font-medium px-6 py-2.5 rounded-lg flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-solid fa-check text-xs"></i> Update Order
                    </button>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    let rowIndex = {{ $order->orderDetails->count() + 1 }};

    const productOptionsHtml = `
        <option value="">Select product...</option>
        @foreach($products as $prod)
            <option value="{{ $prod->id }}" data-price="{{ $prod->selling_price }}" data-regular="{{ $prod->price }}">
                {{ $prod->name }} (৳{{ number_format($prod->selling_price, 2) }})
            </option>
        @endforeach
    `;

    function addOrderRow() {
        const row = `
        <tr class="border-t border-gray-100 order-item-row">
            <td class="py-2.5 pr-3">
                <select name="items[${rowIndex}][product_id]" required class="select2 product-select w-full" onchange="onProductSelect(this)">
                    ${productOptionsHtml}
                </select>
            </td>
            <td class="py-2.5 pr-3">
                <input type="number" name="items[${rowIndex}][quantity]" value="1" min="1" required
                    class="item-qty w-full border border-gray-200 rounded-lg px-2.5 py-2 text-sm text-center" oninput="calculateRowTotal(this)" />
            </td>
            <td class="py-2.5 pr-3">
                <input type="number" step="0.01" min="0" name="items[${rowIndex}][making_price]" placeholder="৳ 0.00"
                    class="w-full border border-gray-200 rounded-lg px-2.5 py-2 text-sm" />
            </td>
            <td class="py-2.5 pr-3">
                <input type="number" step="0.01" min="0" name="items[${rowIndex}][asking_price]" placeholder="৳ 0.00"
                    class="item-asking w-full border border-gray-200 rounded-lg px-2.5 py-2 text-sm" />
            </td>
            <td class="py-2.5 pr-3">
                <input type="number" step="0.01" min="0" name="items[${rowIndex}][selling_price]" required placeholder="৳ 0.00"
                    class="item-selling w-full border border-gray-200 rounded-lg px-2.5 py-2 text-sm font-semibold text-gray-800" oninput="calculateRowTotal(this)" />
            </td>
            <td class="py-2.5 pr-3 text-right font-bold text-gray-800 item-subtotal">
                ৳ 0.00
            </td>
            <td class="py-2.5 text-center">
                <button type="button" class="text-gray-300 hover:text-red-500 transition-colors p-1" onclick="removeOrderRow(this)">
                    <i class="fa-solid fa-times text-xs"></i>
                </button>
            </td>
        </tr>`;

        const $row = $(row);
        $('#orderItemsBody').append($row);
        $row.find('.select2').select2({ width: '100%' });
        rowIndex++;
        calculateGrandTotal();
    }

    function removeOrderRow(btn) {
        if ($('.order-item-row').length > 1) {
            $(btn).closest('tr').remove();
            calculateGrandTotal();
        } else {
            alert('At least one order item is required.');
        }
    }

    function onProductSelect(el) {
        const $option = $(el).find('option:selected');
        const price = $option.data('price') || '';
        const regular = $option.data('regular') || price;
        const $tr = $(el).closest('tr');
        
        $tr.find('.item-selling').val(price);
        $tr.find('.item-asking').val(regular);
        calculateRowTotal(el);
    }

    function calculateRowTotal(el) {
        const $tr = $(el).closest('tr');
        const qty = parseFloat($tr.find('.item-qty').val()) || 0;
        const selling = parseFloat($tr.find('.item-selling').val()) || 0;
        const subtotal = qty * selling;
        $tr.find('.item-subtotal').text('৳ ' + subtotal.toFixed(2));
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grand = 0;
        $('.order-item-row').each(function() {
            const qty = parseFloat($(this).find('.item-qty').val()) || 0;
            const selling = parseFloat($(this).find('.item-selling').val()) || 0;
            grand += (qty * selling);
        });
        $('#orderGrandTotal').text('৳ ' + grand.toFixed(2));
    }

    $(document).ready(function() {
        calculateGrandTotal();
    });
</script>
@endpush
