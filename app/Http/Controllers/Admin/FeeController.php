<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\FeeCategory;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    /**
     * Display a listing of fees
     */
    public function index(Request $request)
    {
        $query = Fee::with('category');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('fee_category_id', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $fees = $query->latest()->paginate(20);
        $categories = FeeCategory::active()->get();

        // Statistics
        $statistics = [
            'total_fees' => Fee::count(),
            'active_fees' => Fee::where('is_active', true)->count(),
            'total_categories' => FeeCategory::where('is_active', true)->count(),
            'total_revenue_potential' => Fee::where('is_active', true)->sum('amount'),
        ];

        return view('admin.fees.index', compact('fees', 'categories', 'statistics'));
    }

    /**
     * Show the form for creating a new fee
     */
    public function create()
    {
        $categories = FeeCategory::active()->get();
        return view('admin.fees.create', compact('categories'));
    }

    /**
     * Store a newly created fee
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fee_category_id' => 'required|exists:fee_categories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:fees,code',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'is_taxable' => 'boolean',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        // Auto-generate code or convert to uppercase
        $validated['code'] = strtoupper($validated['code']);

        // If not taxable, set tax percentage to 0
        if (!($validated['is_taxable'] ?? false)) {
            $validated['tax_percentage'] = 0;
        }

        Fee::create($validated);

        return redirect()->route('admin.fees.index')
            ->with('success', 'Fee created successfully.');
    }

    /**
     * Display the specified fee
     */
    public function show(Fee $fee)
    {
        //
    }

    /**
     * Show the form for editing the specified fee
     */
    public function edit(Fee $fee)
    {
        $categories = FeeCategory::active()->get();
        return view('admin.fees.create', compact('fee', 'categories'));
    }

    /**
     * Update the specified fee
     */
    public function update(Request $request, Fee $fee)
    {
        $validated = $request->validate([
            'fee_category_id' => 'required|exists:fee_categories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:fees,code,' . $fee->id,
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'is_taxable' => 'boolean',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);

        if (!($validated['is_taxable'] ?? false)) {
            $validated['tax_percentage'] = 0;
        }

        $fee->update($validated);

        return redirect()->route('admin.fees.index')
            ->with('success', 'Fee updated successfully.');
    }

    /**
     * Remove the specified fee
     */
    public function destroy(Fee $fee)
    {
        $fee->delete();

        return redirect()->route('admin.fees.index')
            ->with('success', 'Fee deleted successfully.');
    }

    /**
     * Toggle fee status
     */
    public function toggleStatus(Fee $fee)
    {
        $fee->update([
            'is_active' => !$fee->is_active
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $fee->is_active,
            'message' => 'Fee status updated successfully.'
        ]);
    }

    /**
     * Get fees by category (AJAX)
     */
    public function getByCategory(Request $request)
    {
        $fees = Fee::where('fee_category_id', $request->category_id)
            ->where('is_active', true)
            ->get(['id', 'name', 'code', 'amount', 'unit', 'is_taxable', 'tax_percentage']);

        return response()->json($fees);
    }
}
