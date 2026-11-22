<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Branch;
use App\Models\DayPrice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PosController extends Controller
{
    /**
     * Show the visual POS ticketing screen.
     */
    public function index(Request $request)
    {
        if (!can('tickets') && !can('tickets-add') && !can('admin') && !auth('admin')->user()->isSuperAdmin) {
            abort(401);
        }

        $branches = Branch::select('id', 'name')->where('status', 'Active')->get()->toArray();
        $branchId = $request->get('branch_id')
            ? (int) $request->get('branch_id')
            : auth('admin')->user()->branch_id;

        if ($branchId === null && !empty($branches)) {
            $branchId = $branches[0]['id'] ?? null;
        }

        $categories = ProductCategory::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->orderBy('sort_order')
            ->get();

        $products = Product::with('category')
            ->where('is_active', true)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->orderBy('sort_order')
            ->get();

        // Build the same $result array the ticket screen uses for kid pricing / extras
        $result = [];
        $data   = [];
        foreach ($products as $p) {
            $data[$p->product_name] = $p->product_price;
        }
        $result[] = $data;

        // Day prices for today's play passes
        $today = Carbon::now('Asia/Kolkata');
        $dayName = strtolower($today->format('l'));
        $dayPrices = DayPrice::where(function ($q) use ($branchId) {
            if ($branchId) {
                $q->where('branch_id', $branchId)->orWhereNull('branch_id');
            }
        })->get()->map(function ($dp) use ($dayName) {
            return [
                'hour'  => $dp->Hour,
                'price' => (float) ($dp->{$dayName} ?? 0),
            ];
        });

        return view('happyView.pos', compact(
            'products',
            'categories',
            'branches',
            'branchId',
            'result',
            'dayPrices'
        ));
    }

    /**
     * Return products as JSON (for live search / filtering / barcode scanning).
     */
    public function search(Request $request)
    {
        $branchId   = $request->get('branch_id') ?: auth('admin')->user()->branch_id;
        $q          = trim($request->get('q', ''));
        $categoryId = $request->get('category_id');

        $products = Product::with('category')
            ->where('is_active', true)
            ->when($branchId, fn($qb) => $qb->where('branch_id', $branchId))
            ->when($categoryId, fn($qb) => $qb->where('category_id', $categoryId))
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where(function ($inner) use ($q) {
                    $inner->where('product_name', 'like', "%{$q}%")
                          ->orWhere('sku', 'like', "%{$q}%");
                });
            })
            ->orderBy('sort_order')
            ->get()
            ->map(function ($p) {
                return [
                    'id'            => $p->id,
                    'name'          => $p->product_name,
                    'price'         => (float) $p->product_price,
                    'tax'           => (int) $p->product_tax,
                    'image_url'     => $p->image_url,
                    'category_id'   => $p->category_id,
                    'category_name' => optional($p->category)->name ?? 'General',
                    'sku'           => $p->sku,
                ];
            });

        return response()->json($products);
    }
}
