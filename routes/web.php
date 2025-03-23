<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/barang', [BarangController::class, 'index'])->name('admin.barang.index');
        Route::resource('barang', BarangController::class)->except(['index'])->names([
            'create' => 'admin.barang.create',
            'store' => 'admin.barang.store',
            'show' => 'admin.barang.show',
            'edit' => 'admin.barang.edit',
            'update' => 'admin.barang.update',
            'destroy' => 'admin.barang.destroy',
        ]);
        
        Route::resource('pengguna', PenggunaController::class)->names([
            'index' => 'admin.pengguna.index',
            'create' => 'admin.pengguna.create',
            'store' => 'admin.pengguna.store',
            'show' => 'admin.pengguna.show',
            'edit' => 'admin.pengguna.edit',
            'update' => 'admin.pengguna.update',
            'destroy' => 'admin.pengguna.destroy',
        ]);
        Route::get('/peminjaman', [PeminjamanController::class, 'adminIndex'])->name('admin.peminjaman.index');
        Route::get('/peminjaman/barang/{barang_id}', [PeminjamanController::class, 'adminBarangPeminjaman'])->name('admin.peminjaman.barang');
        Route::put('/peminjaman/{peminjaman}/status', [PeminjamanController::class, 'updateStatus'])->name('admin.peminjaman.status');
    });

    // User Routes
    Route::middleware(['role:user'])->prefix('user')->group(function () {
        Route::get('/barang', [BarangController::class, 'userIndex'])->name('user.barang.index');
        Route::resource('peminjaman', PeminjamanController::class)
            ->only(['index', 'create', 'store'])
            ->names([
                'index' => 'user.peminjaman.index',
                'create' => 'user.peminjaman.create',
                'store' => 'user.peminjaman.store',
            ]);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// API Routes
Route::middleware(['auth'])->prefix('api')->group(function () {
    // All API endpoints - tanpa role middleware sementara
    Route::get('/barang', [BarangController::class, 'apiIndex']);
    Route::post('/barang', [BarangController::class, 'store']);
    Route::put('/barang/{barang}', [BarangController::class, 'update']);
    Route::delete('/barang/{id}', [BarangController::class, 'destroy']);
    
    // User API endpoints - bisa diakses semua user termasuk admin
    Route::get('/user/barang', [BarangController::class, 'apiUserIndex']);
    
    // Peminjaman API endpoints
    Route::get('/peminjaman', [PeminjamanController::class, 'apiIndex']);
    Route::post('/peminjaman', [PeminjamanController::class, 'store']);
    Route::put('/peminjaman/{peminjaman}/return', [PeminjamanController::class, 'returnItems']);
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'apiDestroy']);
    Route::get('/peminjaman/user/{user_id}', [PeminjamanController::class, 'apiUserIndex']);
    
    // Pengguna API endpoints
    Route::get('/pengguna', [PenggunaController::class, 'apiIndex']);
    
    // Dummy data route
    Route::get('/create-dummy-data', [BarangController::class, 'createDummyData']);
    
    // Debug route
    Route::get('/debug-barang', [BarangController::class, 'debug']);
});

require __DIR__.'/auth.php';
