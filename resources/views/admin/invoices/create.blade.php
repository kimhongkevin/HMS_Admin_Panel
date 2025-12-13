@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-6">Create New Invoice</h2>

            <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Patient</label>
                        <select name="patient_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Invoice Date</label>
                        <input type="date" name="invoice_date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Due Date</label>
                        <input type="date" name="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium mb-4">Invoice Items</h3>
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
                            </tbody>
                    </table>
                    <button type="button" onclick="addItem()" class="mt-4 px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700">
                        + Add Item
                    </button>
                </div>

                <div class="flex justify-end">
                    <div class="w-full md:w-1/3 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Subtotal:</span>
                            <span id="subtotalDisplay" class="font-medium">$0.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tax ($):</span>
                            <input type="number" name="tax" id="taxInput" value="0" step="0.01" min="0" class="w-24 text-right rounded-md border-gray-300 shadow-sm p-1" oninput="calculateTotals()">
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Discount ($):</span>
                            <input type="number" name="discount" id="discountInput" value="0" step="0.01" min="0" class="w-24 text-right rounded-md border-gray-300 shadow-sm p-1" oninput="calculateTotals()">
                        </div>
                        <div class="flex justify-between items-center border-t pt-4">
                            <span class="text-lg font-bold">Total:</span>
                            <span id="totalDisplay" class="text-lg font-bold text-blue-600">$0.00</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('invoices.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let itemCount = 0;

    function addItem() {
        const tbody = document.getElementById('itemsBody');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-6 py-2">
                <input type="text" name="items[${itemCount}][description]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500" required placeholder="Item description">
            </td>
            <td class="px-6 py-2">
                <input type="number" name="items[${itemCount}][quantity]" value="1" min="1" class="w-full rounded-md border-gray-300 shadow-sm qty-input" oninput="updateRowTotal(this)" required>
            </td>
            <td class="px-6 py-2">
                <input type="number" name="items[${itemCount}][unit_price]" value="0.00" step="0.01" min="0" class="w-full rounded-md border-gray-300 shadow-sm price-input" oninput="updateRowTotal(this)" required>
            </td>
            <td class="px-6 py-2 text-right font-medium row-total">
                $0.00
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

        const tax = parseFloat(document.getElementById('taxInput').value) || 0;
        const discount = parseFloat(document.getElementById('discountInput').value) || 0;
        const total = subtotal + tax - discount;

        document.getElementById('subtotalDisplay').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('totalDisplay').textContent = '$' + total.toFixed(2);
    }

    // Initialize with one row
    document.addEventListener('DOMContentLoaded', () => {
        addItem();
    });
</script>
@endsection