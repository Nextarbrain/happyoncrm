<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RelloApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/product', [ProductController::class, 'index'])->name('getProduct');
Route::get('/productByID', [ProductController::class, 'productByID'])->name('productByID');

// rellow key api
Route::post('v1/reelo/verify-integration', [RelloApiController::class, 'verifyApiPos'])->name('reelo.verifyIntegration');
