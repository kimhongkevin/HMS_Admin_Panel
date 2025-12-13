<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeCategory;
use Illuminate\Http\Request;

class FeeCategoryController extends Controller
{
    /**
     * Display a listing of fee categories
     */
    public function index()
    {
        $categories = FeeCategory::withCount('fees')
            ->latest()
            ->paginate(15);

        return view('admin.fee-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new fee category
     */
    public function create()
    {
        return view('admin.fee-categories.create');
    }

    /**
     * Store a newly created fee category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:fee_categories,code',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Auto-generate code if not provided or convert to uppercase
        $validated['code'] = strtoupper($validated['code']);

        FeeCategory::create($validated);

        return redirect()->route('fee-categories.index')
            ->with('success', 'Fee category created successfully.');
    }

    /**
     * Display the specified fee category
     */
    public function show(FeeCategory $feeCategory)
    {
        $feeCategory->load(['fees' => function ($query) {
            $query->latest();
        }]);

        return view('admin.fee-categories.show', compact('feeCategory'));
    }

    /**
     * Show the form for editing the specified fee category
     */
    public function edit(FeeCategory $feeCategory)
    {
        return view('admin.fee-categories.create', compact('feeCategory'));
    }

    /**
     * Update the specified fee category
     */
    public function update(Request $request, FeeCategory $feeCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:fee_categories,code,' . $feeCategory->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);

        $feeCategory->update($validated);

        return redirect()->route('fee-categories.index')
            ->with('success', 'Fee category updated successfully.');
    }

    /**
     * Remove the specified fee category
     */
    public function destroy(FeeCategory $feeCategory)
    {
        // Check if category has fees
        if ($feeCategory->fees()->count() > 0) {
            return redirect()->route('Admin.fee-categories.index')
                ->with('error', 'Cannot delete category with existing fees.');
        }

        $feeCategory->delete();

        return redirect()->route('fee-categories.index')
            ->with('success', 'Fee category deleted successfully.');
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(FeeCategory $feeCategory)
    {
        $feeCategory->update([
            'is_active' => !$feeCategory->is_active
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $feeCategory->is_active,
            'message' => 'Category status updated successfully.'
        ]);
    }
}
