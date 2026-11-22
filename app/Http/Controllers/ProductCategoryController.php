<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\Branch;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * List categories (JSON for AJAX).
     */
    public function index(Request $request)
    {
        if (!can('admin') && !can('admin-products') && !auth('admin')->user()->isSuperAdmin) {
            abort(401);
        }
        $branchId = $request->branch_id ?: auth('admin')->user()->branch_id;
        $categories = ProductCategory::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->orderBy('sort_order')
            ->get();
        return response()->json($categories);
    }

    /**
     * Store a new category.
     */
    public function store(Request $request)
    {
        if (!can('admin') && !can('admin-products-add') && !auth('admin')->user()->isSuperAdmin) {
            abort(401);
        }
        $request->validate([
            'name'       => 'required|string|max:100',
            'colour'     => 'nullable|string|max:20',
            'icon'       => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
        ]);

        $branchId = $request->branch_id ?: auth('admin')->user()->branch_id;
        if (!$branchId && !auth('admin')->user()->isSuperAdmin) {
            return redirect()->back()->with('error', 'Please select a branch first.');
        }

        ProductCategory::create([
            'name'       => $request->name,
            'colour'     => $request->colour ?: '#0A5F59',
            'icon'       => $request->icon ?: 'fa fa-tags',
            'sort_order' => (int) ($request->sort_order ?? 0),
            'branch_id'  => $branchId,
        ]);

        return redirect()->back()->with('success', 'Category created successfully!');
    }

    /**
     * Update a category.
     */
    public function update(Request $request)
    {
        if (!can('admin') && !can('admin-products-edit') && !auth('admin')->user()->isSuperAdmin) {
            abort(401);
        }
        $request->validate([
            'id'         => 'required|exists:product_categories,id',
            'name'       => 'required|string|max:100',
            'colour'     => 'nullable|string|max:20',
            'icon'       => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
        ]);

        $category = ProductCategory::findOrFail($request->id);
        $category->update([
            'name'       => $request->name,
            'colour'     => $request->colour ?: $category->colour,
            'icon'       => $request->icon ?: $category->icon,
            'sort_order' => (int) ($request->sort_order ?? $category->sort_order),
            'branch_id'  => $request->branch_id ?: $category->branch_id,
        ]);

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    /**
     * Delete a category.
     */
    public function destroy(Request $request)
    {
        if (!can('admin') && !can('admin-products') && !auth('admin')->user()->isSuperAdmin) {
            abort(401);
        }
        $selectedItems = $request->input('selectedItems');
        ProductCategory::whereIn('id', (array) $selectedItems)->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Fetch single category for edit modal (JSON).
     */
    public function show(Request $request)
    {
        $id = $request->input('selectedItems') ?: $request->input('id');
        $category = ProductCategory::findOrFail($id);
        return response()->json($category);
    }
}
