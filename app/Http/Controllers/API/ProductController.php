<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $req)
    {
        $branchId = auth('admin')->user()->branch_id ?  auth('admin')->user()->branch_id : null;
        $query = $req->get('query');
        $product = Product::where('product_name', 'LIKE',  $query . '%');
        if ($branchId) {
            $product->where('branch_id', $branchId);
        }
        $product = $product->get();
        if (!is_null($product)) {
            return response()->json(['success' => true, 'data' => $product]);
        } else {
            return response()->json(['success' => true, 'data' => null]);
        }
    }

    public function productByID(Request $req)
    {
        $id = $req->get('id');
        $product = Product::find($id);
        if (!is_null($product)) {
            return response()->json($product);
        }
    }
}
