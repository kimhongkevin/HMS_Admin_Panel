@extends('layouts.admin')

@section('title', 'Add New Staff')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight mb-6">
            Add New Staff
        </h2>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{!! session('success') !!}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Validation Error!</strong>
                <ul class="mt-3 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-xl sm:rounded-lg p-6">
            <form method="POST" action="{{ route('admin.staff.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="employee_id" :value="__('Employee ID (Format: STF-XXX)')" />
                        <x-text-input id="employee_id" class="block mt-1 w-full" type="text" name="employee_id" :value="old('employee_id')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('employee_id')" />
                    </div>
                    
                    <div>
                        <x-input-label for="department_id" :value="__('Department')" />
                        <select id="department_id" name="department_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                            <option value="">Select Department (Optional)</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('department_id')" />
                    </div>

                    <div>
                        <x-input-label for="phone" :value="__('Phone')" />
                        <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>

                    <div>
                        <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                        <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" :value="old('date_of_birth')" />
                        <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
                    </div>

                    <div>
                        <x-input-label for="gender" :value="__('Gender')" />
                        <select id="gender" name="gender" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                            <option value="">Select Gender (Optional)</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                    </div>
                    
                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                    </div>
                </div>

                <div class="mt-6">
                    <x-input-label for="address" :value="__('Address')" />
                    <textarea id="address" name="address" rows="3" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('address') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('address')" />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <x-primary-button class="ms-4">
                        {{ __('Create Staff') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection