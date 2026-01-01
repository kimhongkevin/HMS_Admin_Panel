@extends('layouts.admin')

@section('title', 'Staff Profile: ' . $staff->name)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                Staff Profile: <span class="text-indigo-600">{{ $staff->name }}</span>
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.staff.edit', $staff) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Edit Profile
                </a>
                @if ($staff->is_active)
                    <form action="{{ route('admin.staff.deactivate', $staff) }}" method="POST" onsubmit="return confirm('Are you sure you want to deactivate this staff member? All related documents and invoices will remain intact and traceable.');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Deactivate Staff
                        </button>
                    </form>
                @else
                    <button disabled class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed">
                        Already Deactivated
                    </button>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{!! session('success') !!}</span>
            </div>
        @endif

        <div class="bg-white shadow-xl sm:rounded-lg p-6">
            <div class="border-b border-gray-200 pb-5 mb-5">
                <h3 class="text-xl font-semibold leading-6 text-gray-900">General Information</h3>
            </div>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-8">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $staff->name }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Employee ID</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $staff->employee_id }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $staff->email }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Department</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $staff->department->name ?? 'N/A' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ optional($staff->profile)->phone ?? 'N/A' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ optional(optional($staff->profile)->date_of_birth)->format('Y-m-d') ?? 'N/A' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Gender</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ ucwords(optional($staff->profile)->gender ?? 'N/A') }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $staff->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $staff->is_active ? 'Active' : 'Inactive/On Leave' }}
                        </span>
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ optional($staff->profile)->address ?? 'N/A' }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white shadow-xl sm:rounded-lg p-6 mt-6">
            <div class="border-b border-gray-200 pb-5 mb-5">
                <h3 class="text-xl font-semibold leading-6 text-gray-900">Activity & Performance</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-5">
                <div class="bg-indigo-50 p-4 rounded-lg">
                    <p class="text-sm font-medium text-indigo-700">Patients Registered (Total)</p>
                    <p class="text-2xl font-bold text-indigo-900 mt-1">{{ $staff->patients->count() }}</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <p class="text-sm font-medium text-yellow-700">Invoices Created (Total)</p>
                    <p class="text-2xl font-bold text-yellow-900 mt-1">{{ $staff->invoices->count() }}</p>
                </div>
            </div>

            <h4 class="text-lg font-semibold text-gray-700 mb-3">Recent Activity Log</h4>
            <ul role="list" class="divide-y divide-gray-200">
                @forelse ($activityLog as $log)
                    <li class="py-3 flex justify-between items-center text-sm">
                        <span class="text-gray-700">{{ $log['action'] }} - **{{ $log['details'] }}**</span>
                        <span class="text-gray-500 text-xs">{{ $log['date'] }}</span>
                    </li>
                @empty
                    <li class="py-3 text-sm text-gray-500">No recent activity logged.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
