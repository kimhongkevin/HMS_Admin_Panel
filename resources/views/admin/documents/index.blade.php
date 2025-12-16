@extends('layouts.admin')

@section('title', 'Document Management')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <div class="mb-6">
                <h2 class="text-3xl font-semibold text-gray-800">Document Management</h2>
                <p class="text-gray-600 mt-1">Manage patient documents and records</p>
            </div>
            <a href="{{ route('documents.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                + Upload New Document
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-4">
            <form method="GET" action="{{ route('documents.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or patient name..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div class="w-full md:w-48">
                    <select name="type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="medical_record" {{ request('type') == 'medical_record' ? 'selected' : '' }}>Medical Record</option>
                        <option value="lab_report" {{ request('type') == 'lab_report' ? 'selected' : '' }}>Lab Report</option>
                        <option value="prescription" {{ request('type') == 'prescription' ? 'selected' : '' }}>Prescription</option>
                    </select>
                </div>
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Filter</button>
            </form>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($documents as $doc)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-gray-100 rounded-lg text-gray-500">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $doc->title }}</div>
                                        <div class="text-xs text-gray-500">{{ Str::limit($doc->file_name, 20) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $doc->patient->first_name }} {{ $doc->patient->last_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $doc->document_type === 'prescription' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $doc->document_type === 'lab_report' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $doc->document_type === 'medical_record' ? 'bg-blue-100 text-blue-800' : '' }}">
                                    {{ $doc->type_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $doc->file_size_formatted }}</div>
                                <div class="text-xs">{{ strtoupper(pathinfo($doc->file_name, PATHINFO_EXTENSION)) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $doc->document_date->format('M d, Y') }}</div>
                                <div class="text-xs">by {{ $doc->uploader->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('documents.show', $doc) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                <form action="{{ route('documents.destroy', $doc) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No documents found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $documents->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
