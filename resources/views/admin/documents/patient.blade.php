@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-semibold text-2xl text-gray-800">
                Documents for {{ $patient->first_name }} {{ $patient->last_name }}
            </h2>
            <a href="{{ route('documents.create', ['patient_id' => $patient->id]) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md shadow hover:bg-indigo-700">
                + Upload for this Patient
            </a>
        </div>

        @if($documents->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow text-center text-gray-500">
                No documents found for this patient.
            </div>
        @else
            @foreach($documents as $type => $docs)
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-700 mb-3 capitalize border-b pb-2">
                        {{ str_replace('_', ' ', $type) }}s
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($docs as $doc)
                            <div class="bg-white rounded-lg shadow hover:shadow-md transition p-4 border border-gray-100">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center">
                                        <div class="bg-indigo-50 p-2 rounded">
                                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-bold text-gray-900 truncate w-40" title="{{ $doc->title }}">{{ $doc->title }}</h4>
                                            <p class="text-xs text-gray-500">{{ $doc->document_date->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg py-1 z-50 ring-1 ring-black ring-opacity-5" style="display: none;">
                                            <a href="{{ route('documents.show', $doc) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View</a>
                                            <a href="{{ route('documents.download', $doc) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Download</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 text-xs text-gray-500">
                                    {{ $doc->file_size_formatted }} &bull; {{ strtoupper(pathinfo($doc->file_name, PATHINFO_EXTENSION)) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection