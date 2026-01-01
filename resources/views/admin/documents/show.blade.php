@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="md:flex md:gap-6">

            <div class="md:w-1/3 mb-6">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Document Details</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Title</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $document->title }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Type</label>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $document->type_label }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Patient</label>
                            <p class="mt-1 text-sm text-gray-900 font-semibold">{{ $document->patient->first_name }} {{ $document->patient->last_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $document->document_date->format('F j, Y') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Uploaded By</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $document->uploader->name }}</p>
                            <p class="text-xs text-gray-500">{{ $document->created_at->diffForHumans() }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">File Info</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $document->file_size_formatted }}</p>
                            <p class="text-xs text-gray-500">{{ $document->file_type }}</p>
                        </div>
                        @if($document->description)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Description</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $document->description }}</p>
                        </div>
                        @endif

                        <div class="pt-4">
                            <a href="{{ route('documents.download', $document) }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download File
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:w-2/3">
                <div class="bg-white shadow sm:rounded-lg h-full min-h-[500px] flex flex-col">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Preview</h3>
                    </div>
                    <div class="flex-1 p-4 bg-gray-50 flex items-center justify-center">
                        @if(Str::contains($document->file_type, 'image'))
                            <img src="{{ route('documents.download', $document) }}" alt="Preview" class="max-h-[600px] max-w-full rounded shadow">
                        @elseif(Str::contains($document->file_type, 'pdf'))
                            <iframe src="{{ route('documents.download', $document) }}" class="w-full h-[600px] rounded border" frameborder="0"></iframe>
                        @else
                            <div class="text-center">
                                <svg class="mx-auto h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-2 text-gray-600">Preview not available for this file type.</p>
                                <a href="{{ route('documents.download', $document) }}" class="mt-2 text-indigo-600 hover:text-indigo-800 font-medium">Download to view</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
