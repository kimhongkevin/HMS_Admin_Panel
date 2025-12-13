@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-6">Edit Invoice #{{ $invoice->invoice_number }}</h2>

            {{-- Form Header --}}
            <form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoiceForm">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    {{-- Patient --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Patient *</label>
                        <select name="patient_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id', $invoice->patient_id) == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->patient_id }} -- {{ $patient->first_name }} {{ $patient->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Invoice Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Invoice Date *</label>
                        <input type="date" name="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        @error('invoice_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Due Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Due Date</label>
                        <input type="date" name="due_date" value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('due_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Add Items Section with Tabs --}}
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Invoice Items</h3>

                        {{-- Tab Buttons --}}
                        <div class="flex gap-2">
                            <button type="button" onclick="showTab('manual')" id="manualTab" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                Manual Entry
                            </button>
                            <button type="button" onclick="showTab('fees')" id="feesTab" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                                From Fee List
                            </button>
                        </div>
                    </div>

                    {{-- Manual Entry Tab --}}
                    <div id="manualContent" class="tab-content">
                        <table class="min-w-full divide-y divide-gray-200" id="itemsTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-24">Qty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-32">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-32">Total</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="itemsBody">
                                {{-- Existing items will be injected here by JavaScript --}}
                            </tbody>
                        </table>
                        <button type="button" onclick="addManualItem()" class="mt-4 px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700">
                            + Add Manual Item
                        </button>
                    </div>

                    {{-- From Fee List Tab --}}
                    <div id="feesContent" class="tab-content hidden">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-blue-800">
                                💡 Select from pre-configured hospital fees. Prices and taxes are automatically applied.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            {{-- Fee Category Filter --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fee Category</label>
                                <select id="feeCategorySelect" class="block w-full rounded-md border-gray-300 shadow-sm" onchange="loadFeesByCategory()">
                                    <option value="">All Categories</option>
                                    @foreach($feeCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Fee Selection --}}
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Fee</label>
                                <select id="feeSelect" class="block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Choose a fee to add...</option>
                                    @foreach($fees as $fee)
                                        <option value="{{ $fee->id }}"
                                                data-name="{{ $fee->name }}"
                                                data-code="{{ $fee->code }}"
                                                data-amount="{{ $fee->amount }}"
                                                data-unit="{{ $fee->unit }}"
                                                data-taxable="{{ $fee->is_taxable }}"
                                                data-tax-percentage="{{ $fee->tax_percentage }}"
                                                data-total="{{ $fee->total_amount }}">
                                            {{ $fee->name }} - ${{ number_format($fee->total_amount, 2) }} ({{ $fee->unit }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="button" onclick="addFeeItem()" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                            + Add Selected Fee
                        </button>
                    </div>
                </div>

                {{-- Totals Section --}}
                <div class="flex justify-end">
                    <div class="w-full md:w-1/3 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Subtotal:</span>
                            <span id="subtotalDisplay" class="font-medium">$0.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tax ($):</span>
                            <input type="number" name="tax" id="taxInput" value="{{ old('tax', $invoice->tax) }}" step="0.01" min="0" class="w-24 text-right rounded-md border-gray-300 shadow-sm p-1" oninput="calculateTotals()">
                            @error('tax') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Discount ($):</span>
                            <input type="number" name="discount" id="discountInput" value="{{ old('discount', $invoice->discount) }}" step="0.01" min="0" class="w-24 text-right rounded-md border-gray-300 shadow-sm p-1" oninput="calculateTotals()">
                            @error('discount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex justify-between items-center border-t pt-4">
                            <span class="text-lg font-bold">Total:</span>
                            <span id="totalDisplay" class="text-lg font-bold text-blue-600">$0.00</span>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes', $invoice->notes) }}</textarea>
                    @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('invoices.show', $invoice) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Update Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let itemCount = 0;
    // Prepare existing items data for JavaScript
    const existingItems = @json($invoice->items);

    // Tab switching logic (Same as create.blade.php fix)
    function showTab(tab) {
        const manualContent = document.getElementById('manualContent');
        const feesContent = document.getElementById('feesContent');
        const manualInputs = manualContent.querySelectorAll('#itemsBody input[required], #itemsBody textarea[required], #itemsBody select[required]');

        if (tab === 'manual') {
            manualContent.classList.remove('hidden');
            feesContent.classList.add('hidden');
            document.getElementById('manualTab').classList.remove('bg-gray-200', 'text-gray-700');
            document.getElementById('manualTab').classList.add('bg-blue-600', 'text-white');
            document.getElementById('feesTab').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('feesTab').classList.add('bg-gray-200', 'text-gray-700');
            manualInputs.forEach(input => input.removeAttribute('disabled'));

        } else {
            manualContent.classList.add('hidden');
            feesContent.classList.remove('hidden');
            document.getElementById('feesTab').classList.remove('bg-gray-200', 'text-gray-700');
            document.getElementById('feesTab').classList.add('bg-blue-600', 'text-white');
            document.getElementById('manualTab').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('manualTab').classList.add('bg-gray-200', 'text-gray-700');
            manualInputs.forEach(input => input.setAttribute('disabled', 'disabled'));
        }
    }

    // Function to add a single item row (used by both existing and new items)
    function addItemRow(item = null) {
        const tbody = document.getElementById('itemsBody');
        const isFee = item?.is_fee || false;
        const row = document.createElement('tr');
        row.classList.add(isFee ? 'bg-blue-50' : 'bg-white');

        // Determine if the item's price/description should be editable (not editable if it's a fixed Fee)
        const priceInputClasses = isFee ? 'bg-gray-100' : '';
        const readonlyAttr = isFee ? 'readonly' : '';
        const totalAmount = item ? parseFloat(item.quantity) * parseFloat(item.unit_price) : 0;
        const totalAmountDisplay = `$${totalAmount.toFixed(2)}`;

        row.innerHTML = `
            <td class="px-6 py-2">
                <input type="text" name="items[${itemCount}][description]" value="${item?.description || ''}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 ${priceInputClasses}" required ${readonlyAttr} placeholder="Item description">
                ${isFee ? `<div class="text-xs text-gray-500 mt-1">Fee Item (Fixed Price)</div>` : ''}
            </td>
            <td class="px-6 py-2">
                <input type="number" name="items[${itemCount}][quantity]" value="${item?.quantity || 1}" min="1" class="w-full rounded-md border-gray-300 shadow-sm qty-input" oninput="updateRowTotal(this)" required>
            </td>
            <td class="px-6 py-2">
                ${isFee ? `<input type="hidden" name="items[${itemCount}][is_fee]" value="1">` : ''}
                ${isFee && item?.fee_id ? `<input type="hidden" name="items[${itemCount}][fee_id]" value="${item.fee_id}">` : ''}
                <input type="number" name="items[${itemCount}][unit_price]" value="${parseFloat(item?.unit_price || 0).toFixed(2)}" step="0.01" min="0" class="w-full rounded-md border-gray-300 shadow-sm price-input ${priceInputClasses}" oninput="updateRowTotal(this)" required ${readonlyAttr}>
            </td>
            <td class="px-6 py-2 text-right font-medium row-total">
                ${item ? totalAmountDisplay : '$0.00'}
            </td>
            <td class="px-6 py-2 text-center">
                <button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </td>
        `;
        tbody.appendChild(row);
        itemCount++;
    }

    // Functions to add a new item (Manual and Fee) - identical to create.blade.php
    function addManualItem() {
        addItemRow();
        calculateTotals();
    }

    function addFeeItem() {
        const feeSelect = document.getElementById('feeSelect');
        const selectedOption = feeSelect.options[feeSelect.selectedIndex];

        if (!selectedOption.value) {
            alert('Please select a fee first');
            return;
        }

        const feeName = selectedOption.dataset.name;
        const feeCode = selectedOption.dataset.code;
        const feeTotal = parseFloat(selectedOption.dataset.total);

        const newItem = {
            description: `${feeName} (${feeCode})`,
            quantity: 1,
            unit_price: feeTotal,
            is_fee: true,
            fee_id: selectedOption.value
        };

        addItemRow(newItem);

        // Reset select and calculate totals
        feeSelect.selectedIndex = 0;
        calculateTotals();
    }

    // Existing functions for AJAX/Totals (Keep the same)
    function loadFeesByCategory() {
        const categoryId = document.getElementById('feeCategorySelect').value;
        const feeSelect = document.getElementById('feeSelect');

        if (!categoryId) {
            // Optional: Reload original fee list if available, or just clear/stop
            return;
        }

        fetch(`/api/fees-by-category?category_id=${categoryId}`)
            .then(response => response.json())
            .then(fees => {
                feeSelect.innerHTML = '<option value="">Choose a fee to add...</option>';
                fees.forEach(fee => {
                    const option = document.createElement('option');
                    option.value = fee.id;
                    option.dataset.name = fee.name;
                    option.dataset.code = fee.code;
                    option.dataset.amount = fee.amount;
                    option.dataset.unit = fee.unit;
                    option.dataset.taxable = fee.is_taxable ? '1' : '0';
                    option.dataset.taxPercentage = fee.tax_percentage;

                    const taxRate = parseFloat(fee.tax_percentage) / 100;
                    const amount = parseFloat(fee.amount);
                    const total = fee.is_taxable ? amount + (amount * taxRate) : amount;

                    option.dataset.total = total.toFixed(2);
                    option.textContent = `${fee.name} - $${total.toFixed(2)} (${fee.unit})`;
                    feeSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading fees:', error);
                alert('Error loading fees. Please try again.');
            });
    }

    function removeRow(btn) {
        btn.closest('tr').remove();
        calculateTotals();
    }

    function updateRowTotal(input) {
        const row = input.closest('tr');
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const total = qty * price;

        row.querySelector('.row-total').textContent = '$' + total.toFixed(2);
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('#itemsBody tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            subtotal += (qty * price);
        });

        // Use the explicit tax and discount inputs from the form
        const tax = parseFloat(document.getElementById('taxInput').value) || 0;
        const discount = parseFloat(document.getElementById('discountInput').value) || 0;
        const total = subtotal + tax - discount;

        document.getElementById('subtotalDisplay').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('totalDisplay').textContent = '$' + total.toFixed(2);
    }

    // Initialization: Load existing items and calculate totals
    document.addEventListener('DOMContentLoaded', () => {
        // Load existing items
        existingItems.forEach(item => {
            addItemRow(item);
        });

        // If no items exist, start with one manual item (optional, depending on desired UX)
        if (existingItems.length === 0) {
            addManualItem();
        }

        // Calculate totals based on loaded items and pre-filled tax/discount
        calculateTotals();
    });
</script>
@endsection
