@extends('layouts.admin')

@section('title', 'Create New Department')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl sm:rounded-lg">
            <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight mb-6">
                    Create New Department
                </h2>

                <form method="POST" action="{{ route('admin.departments.store') }}">
                    @csrf <div class="mb-4">
                        <label for="name" class="block font-medium text-sm text-gray-700">Department Name <span class="text-red-500">*</span></label>
                        <input id="name" name="name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('name') }}" required autofocus />
                        @error('name') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="code" class="block font-medium text-sm text-gray-700">Code <span class="text-red-500">*</span></label>
                        <input id="code" name="code" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm uppercase" value="{{ old('code') }}" required maxlength="50" />
                        <p class="text-xs text-gray-500 mt-1">Short, unique code (e.g., CARDIO, NEURO). Will be saved in uppercase[cite: 5].</p>
                        @error('code') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
                        @error('description') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="head_doctor_id" class="block font-medium text-sm text-gray-700">Head Doctor (Optional) [cite: 3]</label>
                        <select id="head_doctor_id" name="head_doctor_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- Select Head Doctor --</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('head_doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }} (ID: {{ $doctor->id }})
                                </option>
                            @endforeach
                        </select>
                        @error('head_doctor_id') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6 flex items-center">
                        <input id="is_active" name="is_active" type="checkbox" class="h-4 w-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">
                            Is Active
                        </label>
                        @error('is_active') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Save Department
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection