<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ItemController;

// Endpoint untuk cek user (butuh login sanctum)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// CRUD utama barang
Route::apiResource('items', ItemController::class);