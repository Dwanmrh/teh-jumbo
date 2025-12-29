<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KasMasukController;
use App\Http\Controllers\KasKeluarController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ====================================================
// LANDING PAGE
// ====================================================
Route::get('/', function () {
    return view('welcome');
});

// Middleware Group (Auth & Verified)
Route::middleware(['auth', 'verified'])->group(function () {

    /** ===================================================
     * 1. KHUSUS ADMIN (Middleware role:admin)
     * (Full Control: Dashboard, Laporan, User, Outlet, Create/Edit/Delete Produk)
     * ==================================================== */
    Route::middleware(['role:admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // --- LAPORAN ---
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
        Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');

        // --- MANAJEMEN DATA ---
        Route::delete('/outlets/bulk-destroy', [OutletController::class, 'bulkDestroy'])->name('outlets.bulk_destroy');
        Route::resource('outlets', OutletController::class);

        Route::delete('/users/bulk-destroy', [UserController::class, 'bulkDestroy'])->name('users.bulk_destroy');
        Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);

        // --- MANAJEMEN PRODUK (FULL ACCESS) ---
        // Admin bisa Create, Store, Edit, Update, Destroy
        // Kita exclude 'index' karena 'index' ditaruh di Shared agar Staff juga bisa lihat daftar.
        Route::resource('products', ProductController::class)->except(['index']);

        // --- DELETE LOGIC (Advanced) ---
        Route::delete('/kas-masuk/bulk-destroy', [KasMasukController::class, 'bulkDestroy'])->name('kas-masuk.bulk_destroy');
        Route::delete('/kas-masuk/{id}', [KasMasukController::class, 'destroy'])->name('kas-masuk.destroy');

        Route::delete('/kas-keluar/bulk-destroy', [KasKeluarController::class, 'bulkDestroy'])->name('kas-keluar.bulk_destroy');
        Route::delete('/kas-keluar/{id}', [KasKeluarController::class, 'destroy'])->name('kas-keluar.destroy');
    });

    /** ===================================================
     * 2. SHARED ACCESS (Admin & Kasir)
     * (POS, Input Kas, Read-Only Produk)
     * ==================================================== */

    // Redirect Logic
    Route::get('/redirect-role', function() {
        if(auth()->user()->role === 'admin') {
            return redirect()->route('dashboard');
        }
        return redirect()->route('pos.index');
    })->name('redirect.role');

    // POS System
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/cart/add', [PosController::class, 'addToCart'])->name('pos.cart.add');
    Route::post('/pos/cart/plus', [PosController::class, 'qtyPlus'])->name('pos.cart.plus');
    Route::post('/pos/cart/minus', [PosController::class, 'qtyMinus'])->name('pos.cart.minus');
    Route::post('/pos/cart/remove', [PosController::class, 'removeItem'])->name('pos.cart.remove');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');

    // --- PRODUK (READ ONLY) ---
    // Staff hanya boleh melihat daftar produk (untuk cek harga/stok), tidak boleh edit.
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // --- KAS MASUK & KELUAR (Input Only) ---
    Route::resource('kas-masuk', KasMasukController::class)->except(['destroy', 'show']);
    Route::resource('kas-keluar', KasKeluarController::class)->except(['destroy', 'show']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- PANDUAN PENGGUNAAN ---
    Route::get('/panduan', function () {
        return view('panduan');
    })->name('panduan');
});

require __DIR__ . '/auth.php';
