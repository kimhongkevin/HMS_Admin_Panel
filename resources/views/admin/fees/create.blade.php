@extends('layouts.admin')

@section('title', 'Create New Fee')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Create New Fee</h2>
                <p class="text-gray-600 mt-1">Define a new fee or charge for the hospital.</p>
            </div>
            <a href="{{ route('admin.fees.index') }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Fees List
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden p-8">
            <form action="{{ route('admin.fees.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    {{-- Fee Category --}}
                    <div>
                        <label for="fee_category_id" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                        <select id="fee_category_id" name="fee_category_id" required
                                class="mt-1 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md">
                            <option value="">Select a Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('fee_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('fee_category_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Fee Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Fee Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="mt-1 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md">
                        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Fee Code --}}
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700">Unique Code (e.g., CONSULT-GEN) <span class="text-red-500">*</span></label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}" required maxlength="50"
                               class="mt-1 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md">
                        @error('code') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount ($) <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required min="0" step="0.01"
                               class="mt-1 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md">
                        @error('amount') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Unit --}}
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700">Unit (e.g., per visit, per hour) <span class="text-red-500">*</span></label>
                        <input type="text" name="unit" id="unit" value="{{ old('unit', 'per visit') }}" required maxlength="50"
                               class="mt-1 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md">
                        @error('unit') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tax Percentage --}}
                    <div id="tax_percentage_group" class="{{ old('is_taxable') ? '' : 'hidden' }}">
                        <label for="tax_percentage" class="block text-sm font-medium text-gray-700">Tax Percentage (%)</label>
                        <input type="number" name="tax_percentage" id="tax_percentage" value="{{ old('tax_percentage', 0) }}" min="0" max="100" step="0.01"
                               class="mt-1 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md">
                        @error('tax_percentage') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description (Full Width) --}}
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                        <textarea id="description" name="description" rows="3"
                                  class="mt-1 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Checkboxes --}}
                    <div class="md:col-span-2 flex items-center space-x-6">
                        {{-- Is Taxable --}}
                        <div class="flex items-center">
                            <input id="is_taxable" name="is_taxable" type="checkbox" value="1" {{ old('is_taxable') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_taxable" class="ml-2 block text-sm text-gray-900">Is Taxable?</label>
                        </div>
                        {{-- Is Active --}}
                        <div class="flex items-center">
                            <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">Is Active (Visible for billing)?</label>
                        </div>
                        @error('is_active') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Save Fee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isTaxableCheckbox = document.getElementById('is_taxable');
        const taxPercentageGroup = document.getElementById('tax_percentage_group');

        function toggleTaxInput() {
            if (isTaxableCheckbox.checked) {
                taxPercentageGroup.classList.remove('hidden');
                document.getElementById('tax_percentage').setAttribute('required', 'required');
            } else {
                taxPercentageGroup.classList.add('hidden');
                document.getElementById('tax_percentage').removeAttribute('required');
                document.getElementById('tax_percentage').value = 0; // Clear value if not taxable
            }
        }

        isTaxableCheckbox.addEventListener('change', toggleTaxInput);

        // Run on page load to handle old() values on validation error
        toggleTaxInput();
    });
</script>
@endsection
