<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DetailPeminjamanController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Dashboard routes
Route::middleware('auth:sanctum')->get('/dashboard-data', [DashboardController::class, 'api']);

// Resource routes
Route::apiResource('barang', BarangController::class);
Route::apiResource('pengguna', PenggunaController::class);
Route::apiResource('peminjaman', PeminjamanController::class);
Route::apiResource('detail-peminjaman', DetailPeminjamanController::class);

// Peminjaman user route
Route::get('/peminjaman/user/{id}', [PeminjamanController::class, 'apiUserIndex']);
Route::put('/peminjaman/{id}/return', [PeminjamanController::class, 'updateStatus']);
