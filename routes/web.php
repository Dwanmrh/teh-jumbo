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
// LANDING PAGE (Halaman Depan / Welcome)
// ====================================================
Route::get('/', function () {
    return view('welcome');
});

// Middleware Group (Auth & Verified)
Route::middleware(['auth', 'verified'])->group(function () {

    /** ===================================================
     * 1. KHUSUS ADMIN (Middleware role:admin)
     * (Berisi Dashboard, Laporan, Manajemen User/Outlet, dan DELETE Data)
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

        // --- HAPUS KAS MASUK (FIXED) ---
        // PENTING: Route bulk-destroy harus diatas route {id}
        Route::delete('/kas-masuk/bulk-destroy', [KasMasukController::class, 'bulkDestroy'])->name('kas-masuk.bulk_destroy');
        // REVISI: Menggunakan DELETE method, bukan GET. URL disesuaikan standar RESTful.
        Route::delete('/kas-masuk/{id}', [KasMasukController::class, 'destroy'])->name('kas-masuk.destroy');

        // --- HAPUS KAS KELUAR ---
        Route::delete('/kas-keluar/bulk-destroy', [KasKeluarController::class, 'bulkDestroy'])->name('kas-keluar.bulk_destroy');
        Route::delete('/kas-keluar/{id}', [KasKeluarController::class, 'destroy'])->name('kas-keluar.destroy');
    });

    /** ===================================================
     * 2. SHARED ACCESS (Admin & Kasir)
     * (Berisi POS, Input Data Kas, Input Produk)
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

    // Produk (Resource controller menangani index, create, store, edit, update, destroy)
    // Jika kasir tidak boleh hapus produk, tambahkan ->except(['destroy'])
    Route::resource('products', ProductController::class);

    // --- KAS MASUK (Resource minus destroy) ---
    // Ini otomatis membuat route: index, create, store, edit, update.
    // Route destroy sudah ditangani di grup Admin di atas.
    Route::resource('kas-masuk', KasMasukController::class)->except(['destroy', 'show']);

    // --- KAS KELUAR (Resource minus destroy) ---
    Route::resource('kas-keluar', KasKeluarController::class)->except(['destroy', 'show']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
