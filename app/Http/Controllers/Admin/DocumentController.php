<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Document::with(['patient', 'uploader'])->latest();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
        }

        // Type Filter
        if ($request->filled('type')) {
            $query->where('document_type', $request->type);
        }

        $documents = $query->paginate(10)->withQueryString();

        return view('admin.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::orderBy('first_name')->get();
        return view('admin.documents.create', compact('patients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'document_type' => ['required', Rule::in(['medical_record', 'lab_report', 'prescription'])],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'document_date' => ['required', 'date'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240'], // Max 10MB
        ]);

        $file = $request->file('file');

        // Store in 'documents' folder within the default disk (usually storage/app/private)
        // We use private storage for medical records
        $path = $file->store('documents');

        Document::create([
            'patient_id' => $request->patient_id,
            'uploaded_by' => Auth::id(),
            'document_type' => $request->document_type,
            'title' => $request->title,
            'description' => $request->description,
            'document_date' => $request->document_date,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return redirect()->route('documents.index')->with('success', 'Document uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        return view('admin.documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        $patients = Patient::orderBy('first_name')->get();
        return view('admin.documents.edit', compact('document', 'patients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'document_type' => ['required', Rule::in(['medical_record', 'lab_report', 'prescription'])],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'document_date' => ['required', 'date'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240'], // Max 10MB, nullable
        ]);

        $data = [
            'patient_id' => $request->patient_id,
            'document_type' => $request->document_type,
            'title' => $request->title,
            'description' => $request->description,
            'document_date' => $request->document_date,
        ];

        // Handle file update if provided
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Delete old file
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }

            // Store new file
            $path = $file->store('documents');

            $data = array_merge($data, [
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }

        $document->update($data);

        return redirect()->route('documents.index')->with('success', 'Document updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        // Delete physical file
        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }

    /**
     * Download the file securely
     */
    public function download(Document $document)
    {
        if (!Storage::exists($document->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::download($document->file_path, $document->file_name);
    }

    /**
     * Show documents for a specific patient
     */
    public function patientDocuments(Patient $patient)
    {
        $documents = $patient->documents()->latest()->get()->groupBy('document_type');
        return view('admin.documents.patient', compact('patient', 'documents'));
    }
}
