@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('invoices.index') }}" class="text-gray-600 hover:text-gray-900">&larr; Back to List</a>
            <div class="flex gap-2">
                @if($invoice->status !== 'cancelled')
                    <a href="{{ route('invoices.pdf', $invoice) }}" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download PDF
                    </a>
                @endif
                
                @if($invoice->status === 'pending')
                    <form action="{{ route('invoices.status', $invoice) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="paid">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Mark as Paid</button>
                    </form>
                    <form action="{{ route('invoices.status', $invoice) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Cancel</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-8 py-10 border-b border-gray-200">
                <div class="flex justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">INVOICE</h1>
                        <p class="text-gray-500 mt-1">#{{ $invoice->invoice_number }}</p>
                        <div class="mt-2">
                            <span class="px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                               ($invoice->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <h2 class="text-xl font-bold text-gray-700">Hospital Name</h2>
                        <p class="text-gray-500 text-sm mt-1">123 Health St, Medical City</p>
                        <p class="text-gray-500 text-sm">contact@hospital.com</p>
                    </div>
                </div>
            </div>

            <div class="px-8 py-8 grid grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Bill To</h3>
                    <div class="mt-2 text-gray-900">
                        <p class="font-bold">{{ $invoice->patient->name }}</p>
                        <p>{{ $invoice->patient->email }}</p>
                        <p>{{ $invoice->patient->phone ?? '' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="mb-2">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Invoice Date:</span>
                        <span class="text-gray-900 ml-2">{{ $invoice->invoice_date->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Due Date:</span>
                        <span class="text-gray-900 ml-2">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="px-8 py-4">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                            <th class="py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="py-3 text-right text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                            <th class="py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($invoice->items as $item)
                        <tr>
                            <td class="py-4 text-sm text-gray-900">{{ $item->description }}</td>
                            <td class="py-4 text-right text-sm text-gray-900">{{ $item->quantity }}</td>
                            <td class="py-4 text-right text-sm text-gray-900">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-4 text-right text-sm text-gray-900 font-medium">${{ number_format($item->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-8 py-8 border-t border-gray-200">
                <div class="flex justify-end">
                    <div class="w-1/2">
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium text-gray-900">${{ number_format($invoice->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium text-gray-900">${{ number_format($invoice->tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Discount</span>
                            <span class="font-medium text-red-600">-${{ number_format($invoice->discount, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-4">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span class="text-lg font-bold text-blue-600">${{ number_format($invoice->total, 2) }}</span>
                        </div>
                    </div>
                </div>
                @if($invoice->notes)
                <div class="mt-8 text-sm text-gray-500">
                    <span class="font-bold">Notes:</span> {{ $invoice->notes }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection