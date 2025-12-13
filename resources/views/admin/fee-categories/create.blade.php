@extends('layouts.admin')

@section('title', isset($feeCategory) ? 'Edit Fee Category' : 'Create Fee Category')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">
                {{ isset($feeCategory) ? 'Edit Fee Category' : 'Create Fee Category' }}
            </h2>
            <nav class="text-sm text-gray-600 mt-2">
                <a href="{{ route('dashboard') }}" class="hover:text-gray-900">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('admin.fee-categories.index') }}" class="hover:text-gray-900">Fee Categories</a>
                <span class="mx-2">/</span>
                <span>{{ isset($feeCategory) ? 'Edit' : 'Create' }}</span>
            </nav>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <form action="{{ isset($feeCategory) ? route('admin.fee-categories.update', $feeCategory) : route('admin.fee-categories.store') }}"
                  method="POST">
                @csrf
                @if(isset($feeCategory))
                    @method('PUT')
                @endif

                <!-- Category Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $feeCategory->name ?? '') }}"
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-500 @enderror"
                           placeholder="e.g., Medical Operations, Medicines, Laboratory Tests"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category Code -->
                <div class="mb-6">
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        Category Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="code"
                           name="code"
                           value="{{ old('code', $feeCategory->code ?? '') }}"
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md uppercase @error('code') border-red-500 @enderror"
                           placeholder="e.g., MED_OP, MED, LAB"
                           style="text-transform: uppercase;"
                           required>
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Use uppercase letters and underscores only (e.g., MED_OP)</p>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('description') border-red-500 @enderror"
                              placeholder="Describe what types of fees belong to this category">{{ old('description', $feeCategory->description ?? '') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="checkbox"
                               id="is_active"
                               name="is_active"
                               value="1"
                               {{ old('is_active', $feeCategory->is_active ?? true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Active (Category is available for use)
                        </label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.fee-categories.index') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ isset($feeCategory) ? 'Update Category' : 'Create Category' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-uppercase code input
document.getElementById('code').addEventListener('input', function(e) {
    this.value = this.value.toUpperCase();
});
</script>
@endsection
