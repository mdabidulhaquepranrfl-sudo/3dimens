@extends('admin.layouts.app')

@section('title', 'New Order')
@section('page_title', 'New Order')
@section('page_sub', 'Create a new customer order')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

    <form id="orderForm" method="POST" action="{{ route('admin.orders.store') }}" class="space-y-5">
        @csrf

        <!-- Order Information Card -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.orders.index') }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
                        <i class="fa-solid fa-arrow-left text-sm"></i>
                    </a>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Order Header</p>
                        <p class="text-xs text-gray-400">Fill in the order and customer information</p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-5">
                <!-- Customer Select with Quick Add -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold text-gray-600">
                            Customer <span class="text-red-400">*</span>
                        </label>
                        <button type="button" onclick="openCustomerModal()" class="text-xs font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                            <i class="fa-solid fa-plus text-[10px]"></i> Add New Customer
                        </button>
                    </div>
                    <select id="customerSelect" name="customer_id" required class="select2 w-full">
                        <option value="">Select a customer...</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ (old('customer_id', request('customer_id')) == $customer->id) ? 'selected' : '' }}>
                                {{ $customer->name }} — {{ $customer->phone }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Order Date <span class="text-red-400">*</span></label>
                        <input type="date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 transition-all bg-white" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Expected Delivery Date</label>
                        <input type="date" name="exp_delv_date" value="{{ old('exp_delv_date') }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 transition-all bg-white" />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Delivery Address</label>
                    <textarea name="address" rows="2" placeholder="Full street address, house/road number..."
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 resize-none transition-all">{{ old('address') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Thana</label>
                        <select name="thana_id" class="select2 w-full">
                            <option value="">Select Thana...</option>
                            @foreach($thanas as $thana)
                                <option value="{{ $thana->id }}" {{ old('thana_id') == $thana->id ? 'selected' : '' }}>
                                    {{ $thana->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status <span class="text-red-400">*</span></label>
                        <select name="status" required class="select2 w-full">
                            @foreach($statuses as $st)
                                <option value="{{ $st->tag }}" {{ old('status', 'pending') == $st->tag ? 'selected' : '' }}>
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
            <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-700">Order Items</p>
                    <p class="text-xs text-gray-400">Select products and set item pricing</p>
                </div>
                <button type="button" onclick="addOrderRow()"
                    class="btn-secondary text-xs font-semibold px-3 py-1.5 rounded-lg text-brand-600 hover:text-brand-700 flex items-center gap-1.5 border border-gray-200">
                    <i class="fa-solid fa-plus text-xs"></i> Add Item
                </button>
            </div>

            <div class="p-4 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wide border-b border-gray-100">
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
                        <!-- Initial Row -->
                        <tr class="border-t border-gray-100 order-item-row">
                            <td class="py-2.5 pr-3">
                                <select name="items[0][product_id]" required class="select2 product-select w-full" onchange="onProductSelect(this)">
                                    <option value="">Select product...</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}" data-price="{{ $prod->selling_price }}" data-regular="{{ $prod->price }}">
                                            {{ $prod->name }} (৳{{ number_format($prod->selling_price, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="py-2.5 pr-3">
                                <input type="number" name="items[0][quantity]" value="1" min="1" required
                                    class="item-qty w-full border border-gray-200 rounded-lg px-2.5 py-2 text-sm text-center" oninput="calculateRowTotal(this)" />
                            </td>
                            <td class="py-2.5 pr-3">
                                <input type="number" step="0.01" min="0" name="items[0][making_price]" placeholder="৳ 0.00"
                                    class="w-full border border-gray-200 rounded-lg px-2.5 py-2 text-sm" />
                            </td>
                            <td class="py-2.5 pr-3">
                                <input type="number" step="0.01" min="0" name="items[0][asking_price]" placeholder="৳ 0.00"
                                    class="item-asking w-full border border-gray-200 rounded-lg px-2.5 py-2 text-sm" />
                            </td>
                            <td class="py-2.5 pr-3">
                                <input type="number" step="0.01" min="0" name="items[0][selling_price]" required placeholder="৳ 0.00"
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
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-100 bg-gray-50/50">
                            <td colspan="5" class="py-3 pr-3 text-right text-sm font-bold text-gray-700">Estimated Total:</td>
                            <td class="py-3 pr-3 text-right text-base font-extrabold text-brand-600" id="orderGrandTotal">৳ 0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 bg-gray-50/20">
                <a href="{{ route('admin.orders.index') }}" class="btn-secondary text-sm font-medium px-4 py-2 rounded-lg">Cancel</a>
                <button type="submit" class="btn-primary text-sm font-medium px-6 py-2 rounded-lg flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-check text-xs"></i> Place Order
                </button>
            </div>
        </div>
    </form>

</div>

<!-- QUICK ADD CUSTOMER MODAL -->
<div id="customerModal" class="fixed inset-0 z-50 hidden flex items-center justify-center modal-backdrop">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-800">Add New Customer</p>
                <p class="text-xs text-gray-400">Quick customer registration</p>
            </div>
            <button type="button" onclick="closeCustomerModal()" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 transition-colors">
                <i class="fa-solid fa-times text-sm"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div id="modalAlert" class="hidden p-2.5 rounded-lg text-xs"></div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Full Name <span class="text-red-400">*</span></label>
                <input type="text" id="modalCustomerName" placeholder="Customer name"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 placeholder-gray-400" />
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Phone <span class="text-red-400">*</span></label>
                <input type="tel" id="modalCustomerPhone" placeholder="01X-XXXXXXXX"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 placeholder-gray-400" />
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeCustomerModal()" class="flex-1 btn-secondary text-sm font-medium py-2 rounded-lg">Cancel</button>
                <button type="button" onclick="saveCustomerFromModal()" id="btnSaveCustomer"
                    class="flex-1 btn-primary text-sm font-medium py-2 rounded-lg flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-check text-xs"></i> Save Customer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let rowIndex = 1;

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

    // Modal Helpers
    function openCustomerModal() {
        $('#modalAlert').addClass('hidden').removeClass('bg-rose-50 text-rose-700 bg-emerald-50 text-emerald-700');
        $('#modalCustomerName').val('');
        $('#modalCustomerPhone').val('');
        $('#customerModal').removeClass('hidden').addClass('flex');
    }

    function closeCustomerModal() {
        $('#customerModal').addClass('hidden').removeClass('flex');
    }

    function saveCustomerFromModal() {
        const name = $('#modalCustomerName').val().trim();
        const phone = $('#modalCustomerPhone').val().trim();

        if (!name || !phone) {
            $('#modalAlert').removeClass('hidden bg-emerald-50 text-emerald-700').addClass('bg-rose-50 text-rose-700').text('Both name and phone are required.');
            return;
        }

        $('#btnSaveCustomer').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: "{{ route('admin.customers.quick-store') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                name: name,
                phone: phone
            },
            success: function(res) {
                if (res.success && res.customer) {
                    const newOption = new Option(res.customer.name + ' — ' + res.customer.phone, res.customer.id, true, true);
                    $('#customerSelect').append(newOption).trigger('change');
                    closeCustomerModal();
                }
            },
            error: function(xhr) {
                let msg = 'Failed to create customer.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join(' ');
                }
                $('#modalAlert').removeClass('hidden bg-emerald-50 text-emerald-700').addClass('bg-rose-50 text-rose-700').text(msg);
            },
            complete: function() {
                $('#btnSaveCustomer').prop('disabled', false).html('<i class="fa-solid fa-check text-xs"></i> Save Customer');
            }
        });
    }

    $(document).ready(function() {
        calculateGrandTotal();
    });
</script>
@endpush
