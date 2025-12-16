@extends('layouts.admin')

@section('title', 'Invoice Management')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="flex justify-between items-center mb-6">
            <div class="mb-6">
                <h2 class="text-3xl font-semibold text-gray-800">Invoice Management</h2>
                <p class="text-gray-600 mt-1">Handle patient billing and payment records</p>
            </div>
            <a href="{{ route('invoices.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                + Create Invoice
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                <div class="text-sm font-medium text-gray-500">Total Revenue (Paid)</div>
                <div class="text-2xl font-bold text-green-600">${{ number_format($totalRevenue, 2) }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                <div class="text-sm font-medium text-gray-500">Pending Amount</div>
                <div class="text-2xl font-bold text-yellow-600">${{ number_format($pendingAmount, 2) }}</div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('invoices.index') }}" class="flex gap-4">
                <input type="text" name="search" placeholder="Search invoice or patient..." value="{{ request('search') }}" class="border-gray-300 rounded-md shadow-sm w-full md:w-1/3">
                <select name="status" class="border-gray-300 rounded-md shadow-sm">
                    <option value="all">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md">Filter</button>
            </form>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($invoices as $invoice)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $invoice->invoice_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                            ${{ number_format($invoice->total, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' :
                                   ($invoice->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                            <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            @if($invoice->status === 'pending')
                                <a href="{{ route('invoices.edit', $invoice) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
