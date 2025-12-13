@extends('layouts.admin')

@section('title', 'Fee Details')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $fee->name }}</h2>
                    <nav class="text-sm text-gray-600 mt-2">
                        <a href="{{ route('dashboard') }}" class="hover:text-gray-900">Dashboard</a>
                        <span class="mx-2">/</span>
                        <a href="{{ route('admin.fees.index') }}" class="hover:text-gray-900">Fees</a>
                        <span class="mx-2">/</span>
                        <span>{{ $fee->name }}</span>
                    </nav>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.fees.edit', $fee) }}"
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Fee
                    </a>
                    <form action="{{ route('admin.fees.destroy', $fee) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center"
                                onclick="return confirm('Are you sure you want to delete this fee? This action cannot be undone.')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Main Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Fee Information Card -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Fee Information</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Fee Name -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-1">Fee Name</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $fee->name }}</dd>
                            </div>

                            <!-- Fee Code -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-1">Fee Code</dt>
                                <dd class="text-lg font-mono font-semibold text-gray-900 bg-gray-100 inline-block px-3 py-1 rounded">
                                    {{ $fee->code }}
                                </dd>
                            </div>

                            <!-- Category -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-1">Category</dt>
                                <dd>
                                    <a href="{{ route('admin.fee-categories.show', $fee->category) }}"
                                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-800 hover:bg-purple-200">
                                        {{ $fee->category->name }}
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </dd>
                            </div>

                            <!-- Unit -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-1">Unit</dt>
                                <dd class="text-lg text-gray-900 capitalize">{{ $fee->unit }}</dd>
                            </div>

                            <!-- Status -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-1">Status</dt>
                                <dd>
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $fee->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $fee->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </dd>
                            </div>

                            <!-- Created Date -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-1">Created Date</dt>
                                <dd class="text-lg text-gray-900">{{ $fee->created_at->format('M d, Y') }}</dd>
                                <dd class="text-xs text-gray-500">{{ $fee->created_at->diffForHumans() }}</dd>
                            </div>

                            <!-- Description -->
                            @if($fee->description)
                            <div class="col-span-2">
                                <dt class="text-sm font-medium text-gray-500 mb-1">Description</dt>
                                <dd class="text-base text-gray-700 bg-gray-50 p-4 rounded-lg">
                                    {{ $fee->description }}
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Pricing Details Card -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Pricing Details</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Base Amount -->
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <dt class="text-sm font-medium text-blue-600 mb-2">Base Amount</dt>
                                <dd class="text-3xl font-bold text-blue-900">${{ number_format($fee->amount, 2) }}</dd>
                                <dd class="text-xs text-blue-600 mt-1">{{ $fee->unit }}</dd>
                            </div>

                            <!-- Tax Information -->
                            <div class="text-center p-4 {{ $fee->is_taxable ? 'bg-yellow-50' : 'bg-gray-50' }} rounded-lg">
                                <dt class="text-sm font-medium {{ $fee->is_taxable ? 'text-yellow-600' : 'text-gray-600' }} mb-2">Tax</dt>
                                @if($fee->is_taxable)
                                    <dd class="text-3xl font-bold text-yellow-900">{{ $fee->tax_percentage }}%</dd>
                                    <dd class="text-xs text-yellow-600 mt-1">
                                        ${{ number_format(($fee->amount * $fee->tax_percentage) / 100, 2) }}
                                    </dd>
                                @else
                                    <dd class="text-2xl font-bold text-gray-900">No Tax</dd>
                                    <dd class="text-xs text-gray-600 mt-1">Not taxable</dd>
                                @endif
                            </div>

                            <!-- Total Amount -->
                            <div class="text-center p-4 bg-green-50 rounded-lg border-2 border-green-300">
                                <dt class="text-sm font-medium text-green-600 mb-2">Total Amount</dt>
                                <dd class="text-3xl font-bold text-green-900">
                                    ${{ number_format($fee->total_amount, 2) }}
                                </dd>
                                <dd class="text-xs text-green-600 mt-1">Including all charges</dd>
                            </div>
                        </div>

                        <!-- Pricing Breakdown -->
                        @if($fee->is_taxable)
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Pricing Breakdown</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Base Fee ({{ $fee->unit }})</span>
                                    <span class="font-semibold text-gray-900">${{ number_format($fee->amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tax ({{ $fee->tax_percentage }}%)</span>
                                    <span class="font-semibold text-gray-900">
                                        ${{ number_format(($fee->amount * $fee->tax_percentage) / 100, 2) }}
                                    </span>
                                </div>
                                <div class="border-t border-gray-300 pt-2 flex justify-between">
                                    <span class="font-bold text-gray-900">Total</span>
                                    <span class="font-bold text-green-600">${{ number_format($fee->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endif>
                    </div>
                </div>

                <!-- Usage Examples Card -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Usage Examples</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Example 1: Single Unit -->
                            <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                                <h4 class="font-semibold text-indigo-900 mb-2">Single Unit</h4>
                                <div class="text-sm space-y-1">
                                    <div class="flex justify-between">
                                        <span class="text-indigo-700">1 × {{ $fee->name }}</span>
                                        <span class="font-semibold text-indigo-900">${{ number_format($fee->total_amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Example 2: Multiple Units -->
                            <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                                <h4 class="font-semibold text-indigo-900 mb-2">Multiple Units (Example: 5)</h4>
                                <div class="text-sm space-y-1">
                                    <div class="flex justify-between">
                                        <span class="text-indigo-700">5 × {{ $fee->name }}</span>
                                        <span class="font-semibold text-indigo-900">${{ number_format($fee->total_amount * 5, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-indigo-600">({{ $fee->unit }})</span>
                                        <span class="text-indigo-600">${{ number_format($fee->total_amount, 2) }} each</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Example 3: With Quantity Calculator -->
                            <div class="p-4 bg-white rounded-lg border-2 border-indigo-300">
                                <h4 class="font-semibold text-indigo-900 mb-3">Calculate Total</h4>
                                <div class="flex items-center space-x-3">
                                    <label for="quantity" class="text-sm text-gray-700">Quantity:</label>
                                    <input type="number"
                                           id="quantity"
                                           value="1"
                                           min="1"
                                           class="w-24 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                           oninput="calculateCustomTotal()">
                                    <span class="text-sm text-gray-700">×</span>
                                    <span class="text-sm font-semibold text-gray-900">${{ number_format($fee->total_amount, 2) }}</span>
                                    <span class="text-sm text-gray-700">=</span>
                                    <span id="customTotal" class="text-xl font-bold text-indigo-600">
                                        ${{ number_format($fee->total_amount, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Quick Actions & Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Quick Actions Card -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="bg-gray-800 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Quick Actions</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        <a href="{{ route('admin.fees.edit', $fee) }}"
                           class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center font-semibold py-2 px-4 rounded transition">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Fee
                        </a>

                        <button onclick="toggleStatus()"
                                id="toggleStatusBtn"
                                class="block w-full {{ $fee->is_active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white text-center font-semibold py-2 px-4 rounded transition">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            {{ $fee->is_active ? 'Deactivate Fee' : 'Activate Fee' }}
                        </button>

                        <a href="{{ route('admin.fees.index') }}"
                           class="block w-full bg-gray-500 hover:bg-gray-600 text-white text-center font-semibold py-2 px-4 rounded transition">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to List
                        </a>

                        <button onclick="window.print()"
                                class="block w-full bg-gray-700 hover:bg-gray-800 text-white text-center font-semibold py-2 px-4 rounded transition">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print Details
                        </button>
                    </div>
                </div>

                <!-- Category Info Card -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="bg-purple-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Category Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-4">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-3">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900">{{ $fee->category->name }}</h4>
                            <p class="text-sm text-gray-600 font-mono">{{ $fee->category->code }}</p>
                        </div>

                        @if($fee->category->description)
                        <div class="mb-4 p-3 bg-purple-50 rounded-lg">
                            <p class="text-sm text-gray-700">{{ $fee->category->description }}</p>
                        </div>
                        @endif

                        <a href="{{ route('admin.fee-categories.show', $fee->category) }}"
                           class="block w-full bg-purple-500 hover:bg-purple-600 text-white text-center font-semibold py-2 px-4 rounded transition">
                            View Category Details →
                        </a>
                    </div>
                </div>

                <!-- Fee Statistics Card -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="bg-gray-800 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Fee Statistics</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                            <span class="text-sm text-gray-700">Times Used</span>
                            <span class="text-2xl font-bold text-blue-600">0</span>
                        </div>

                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                            <span class="text-sm text-gray-700">Total Revenue</span>
                            <span class="text-2xl font-bold text-green-600">$0.00</span>
                        </div>

                        <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                            <span class="text-sm text-gray-700">Last Used</span>
                            <span class="text-sm font-semibold text-purple-600">Never</span>
                        </div>

                        <p class="text-xs text-gray-500 text-center">
                            Statistics will be available once this fee is used in invoices
                        </p>
                    </div>
                </div>

                <!-- Audit Trail Card -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="bg-gray-800 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Audit Trail</h3>
                    </div>
                    <div class="p-6 space-y-3 text-sm">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-900">Created</p>
                                <p class="text-gray-600">{{ $fee->created_at->format('M d, Y \a\t h:i A') }}</p>
                                <p class="text-xs text-gray-500">{{ $fee->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        @if($fee->updated_at != $fee->created_at)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-900">Last Updated</p>
                                <p class="text-gray-600">{{ $fee->updated_at->format('M d, Y \a\t h:i A') }}</p>
                                <p class="text-xs text-gray-500">{{ $fee->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Calculate custom total based on quantity
function calculateCustomTotal() {
    const quantity = parseFloat(document.getElementById('quantity').value) || 1;
    const unitPrice = parseFloat("{{ $fee->total_amount }}");
    const total = quantity * unitPrice;

    document.getElementById('customTotal').textContent = '$' + total.toFixed(2);
}

// Toggle fee status
function toggleStatus() {
    if (!confirm('Are you sure you want to change the status of this fee?')) return;

    fetch('{{ route("admin.fees.toggle-status", $fee) }}', {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload(); // Reload to show updated status
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the status');
    });
}

// Print styles
window.addEventListener('beforeprint', function() {
    // Hide action buttons when printing
    document.querySelectorAll('.no-print, button, .bg-gray-800').forEach(el => {
        el.style.display = 'none';
    });
});

window.addEventListener('afterprint', function() {
    location.reload(); // Reload to restore hidden elements
});
</script>
@endsection
