@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Upload Document</h2>
                    <a href="{{ route('documents.index') }}" class="text-gray-500 hover:text-gray-700">Back to List</a>
                </div>

                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient</label>
                        <select name="patient_id" id="patient_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select a patient...</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{$patient->patient_id}} -- {{ $patient->first_name }} {{ $patient->last_name }} 
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="document_type" class="block text-sm font-medium text-gray-700">Document Type</label>
                            <select name="document_type" id="document_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select type...</option>
                                <option value="medical_record" {{ old('document_type') == 'medical_record' ? 'selected' : '' }}>Medical Record</option>
                                <option value="lab_report" {{ old('document_type') == 'lab_report' ? 'selected' : '' }}>Lab Report</option>
                                <option value="prescription" {{ old('document_type') == 'prescription' ? 'selected' : '' }}>Prescription</option>
                            </select>
                            @error('document_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="document_date" class="block text-sm font-medium text-gray-700">Document Date</label>
                            <input type="date" name="document_date" id="document_date" value="{{ old('document_date', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('document_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Document Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="e.g. Blood Test Results" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">File Attachment</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:bg-gray-50 transition">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="file" name="file" type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF, JPG, PNG, DOC up to 10MB</p>
                            </div>
                        </div>
                        @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <div id="file-name-display" class="mt-2 text-sm text-green-600 font-medium"></div>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Upload Document
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('file').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        document.getElementById('file-name-display').textContent = 'Selected: ' + fileName;
    });
</script>
@endsection