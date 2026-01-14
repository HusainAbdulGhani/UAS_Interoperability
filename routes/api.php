<?php

use App\Http\Controllers\API\StockLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Route Publik: Siapapun bisa daftar atau login untuk dapet token
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route Terproteksi: Hanya bisa diakses kalau punya Token (Bearer Token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logs',[StockLogController::class,'index']);
    // Endpoint untuk cek data profil user yang sedang login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Endpoint Logout untuk menghapus token
    Route::post('/logout', [AuthController::class, 'logout']);

    // CRUD Utama Barang (Semua fungsi: index, store, show, update, destroy)
    Route::apiResource('items', ItemController::class);
});