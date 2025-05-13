<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PosSetting;
use Illuminate\Http\Request;

class RelloApiController extends Controller
{
    public function verifyApiPos(Request $request) {
        $merchantId = $request->input('merchant_id');
        $posSetting = PosSetting::first(); // Assuming PosSetting model exists

        if ($merchantId == env('MERCHANT_ID')) {
            return response()->json(['status' => 200, 'customer_key' => $posSetting->pos_key]);
        } else {
            return response()->json(['status' => 400, 'message' => 'Merchant ID is not configured']);
        }
    }
}
