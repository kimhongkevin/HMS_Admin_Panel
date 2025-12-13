<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['patient', 'creator']);

        // Filters [cite: 24]
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->paginate(10);

        // Stats for the top cards
        $totalRevenue = Invoice::where('status', 'paid')->sum('total');
        $pendingAmount = Invoice::where('status', 'pending')->sum('total');

        return view('admin.invoices.index', compact('invoices', 'totalRevenue', 'pendingAmount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::select('patient_id', 'first_name', 'last_name', 'email')->get();
        return view('admin.invoices.create', compact('patients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($validated) {
            // Calculate totals
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $tax = $validated['tax'] ?? 0;
            $discount = $validated['discount'] ?? 0;
            $total = $subtotal + $tax - $discount;

            // Create Invoice
            $invoice = Invoice::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'patient_id' => $validated['patient_id'],
                'created_by' => auth()->id,
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'status' => 'pending',
                'notes' => $validated['notes']
            ]);

            // Create Items
            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'amount' => $item['quantity'] * $item['unit_price']
                ]);
            }
        });

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['patient', 'items', 'creator']);
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        // Restriction: Only pending invoices can be edited [cite: 24]
        if ($invoice->status !== 'pending') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Only pending invoices can be edited.');
        }

        $patients = Patient::select('id', 'name')->get();
        $invoice->load('items');

        return view('admin.invoices.edit', compact('invoice', 'patients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->status !== 'pending') {
            return back()->with('error', 'Cannot update a finalized invoice.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($validated, $invoice) {
            // Recalculate Logic
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $tax = $validated['tax'] ?? 0;
            $discount = $validated['discount'] ?? 0;
            $total = $subtotal + $tax - $discount;

            // Update Invoice
            $invoice->update([
                'patient_id' => $validated['patient_id'],
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'notes' => $validated['notes']
            ]);

            // Recreate Items (Simplest approach for data integrity)
            $invoice->items()->delete();
            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'amount' => $item['quantity'] * $item['unit_price']
                ]);
            }
        });

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->status !== 'pending') {
            return back()->with('error', 'Only pending invoices can be deleted.');
        }
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted.');
    }

    /**
     * Generate Invoice Number
     */
    private function generateInvoiceNumber()
    {
        // Format: INV-YYYY-00001 [cite: 24]
        $year = date('Y');
        $lastInvoice = Invoice::whereYear('created_at', $year)->orderBy('id', 'desc')->first();

        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->invoice_number, -5));
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }

        return 'INV-' . $year . '-' . $newNumber;
    }

    /**
     * Update Status (Paid/Cancelled)
     */
    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate(['status' => 'required|in:paid,cancelled']);

        $invoice->update(['status' => $request->status]);

        return back()->with('success', 'Invoice status updated to ' . ucfirst($request->status));
    }

    /**
     * Download PDF
     */
    public function downloadPDF(Invoice $invoice)
    {
        $invoice->load(['patient', 'items', 'creator']);
        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'));
        return $pdf->download($invoice->invoice_number . '.pdf');
    }
}
