@extends('layouts.admin')

@section('title', 'Staff Management')

@section('content')
<div class="py-8">
    <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-3xl font-semibold text-gray-800">Staff Management</h2>
            <p class="text-gray-600 mt-1">Maintain staff records and roles</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{!! session('success') !!}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{!! session('error') !!}</span>
            </div>
        @endif

        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <form action="{{ route('admin.staff.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-center">
                <div class="flex-grow">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search staff by name, ID, role, or department..." class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <select name="status" class="form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All Statuses</option>
                    <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>On Leave/Inactive</option>
                </select>

                <div class="flex gap-2">
                    <x-primary-button type="submit">Filter</x-primary-button>
                    <a href="{{ route('admin.staff.index') }}" class="text-sm font-semibold py-2 px-4 rounded-md text-gray-700 border border-gray-300 hover:bg-gray-100 transition duration-150 ease-in-out">Reset</a>
                </div>

                <div class="flex-shrink-0">
                    <a href="{{ route('admin.staff.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Staff
                    </a>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                <dl><dt class="text-sm font-medium text-gray-500 truncate">Total Staff</dt>
                    <dd class="text-2xl font-semibold text-gray-900">{{ $staff->total() }}</dd>
                </dl>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                <dl><dt class="text-sm font-medium text-gray-500 truncate">Active</dt>
                    <dd class="text-2xl font-semibold text-green-600">{{ $staff->where('is_active', true)->count() }}</dd>
                </dl>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                <dl><dt class="text-sm font-medium text-gray-500 truncate">On Leave</dt>
                    <dd class="text-2xl font-semibold text-yellow-600">{{ $staff->where('is_active', false)->count() }}</dd>
                </dl>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                <dl><dt class="text-sm font-medium text-gray-500 truncate">Departments</dt>
                    <dd class="text-2xl font-semibold text-purple-600">{{ $departments->count() }}</dd>
                </dl>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($staff as $member)
                <div class="bg-white overflow-hidden shadow rounded-lg p-6 border-l-4 {{ $member->is_active ? 'border-green-500' : 'border-red-500' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500">
                                <span class="text-sm font-medium leading-none text-white">{{ strtoupper(substr($member->name, 0, 2)) }}</span>
                            </span>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $member->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $member->is_active ? 'Active' : 'On Leave' }}
                        </span>
                    </div>

                    <div class="mt-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $member->name }}</h3>
                        <p class="text-sm font-medium text-gray-500">{{ $member->employee_id }}</p>
                    </div>

                    <div class="mt-4 text-sm space-y-2">
                        <p><strong>Department:</strong> <span class="text-gray-700">{{ $member->department->name ?? 'N/A' }}</span></p>
                        <p><strong>Role:</strong> <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ ucwords($member->role) }}</span></p>
                        <p><strong>Joined:</strong> <span class="text-gray-700">{{ optional($member->created_at)->toDateString() }}</span></p>
                    </div>

                    <div class="mt-6 flex justify-between items-center">
                        <a href="{{ route('admin.staff.show', $member) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            View Profile
                        </a>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.staff.edit', $member) }}" class="text-yellow-600 hover:text-yellow-900 text-sm font-medium">
                                Edit
                            </a>
                            <form action="{{ route('admin.staff.destroy', $member) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white shadow rounded-lg p-6 text-center">
                    <p class="text-gray-500">No staff members found.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $staff->links() }}
        </div>
    </div>
</div>
@endsection
