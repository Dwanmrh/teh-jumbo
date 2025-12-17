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
    // Cek jika user sudah login, kita bisa arahkan langsung ke sistem (Opsional)
    // if (auth()->check()) {
    //     return redirect()->route('redirect.role');
    // }

    return view('welcome');
});

// Middleware Group (Auth & Verified)
Route::middleware(['auth', 'verified'])->group(function () {

    /** =========================
     * 1. KHUSUS ADMIN (Middleware role:admin)
     * ========================= */
    Route::middleware(['role:admin'])->group(function () {
        // Dashboard & Laporan HANYA di sini
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

        // --- HAPUS KAS (ADMIN) ---
        Route::get('/kas-masuk/{id}/delete', [KasMasukController::class, 'destroy'])->name('kas-masuk.destroy');
        Route::delete('/kas-masuk/bulk-destroy', [KasMasukController::class, 'bulkDestroy'])->name('kas-masuk.bulk_destroy');
        Route::delete('/kas-keluar/bulk-destroy', [KasKeluarController::class, 'bulkDestroy'])->name('kas-keluar.bulk_destroy');
        Route::delete('/kas-keluar/{id}', [KasKeluarController::class, 'destroy'])->name('kas-keluar.destroy');
    });

    /** =========================
     * 2. SHARED ACCESS (Admin & Kasir)
     * ========================= */

    // Redirect User Biasa (Kasir) dari /dashboard ke /pos jika mereka login
    Route::get('/redirect-role', function() {
        if(auth()->user()->role === 'admin') {
            return redirect()->route('dashboard');
        }
        return redirect()->route('pos.index');
    })->name('redirect.role');

    // POS System (Kasir Kerja Disini)
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/cart/add', [PosController::class, 'addToCart'])->name('pos.cart.add');
    Route::post('/pos/cart/plus', [PosController::class, 'qtyPlus'])->name('pos.cart.plus');
    Route::post('/pos/cart/minus', [PosController::class, 'qtyMinus'])->name('pos.cart.minus');
    Route::post('/pos/cart/remove', [PosController::class, 'removeItem'])->name('pos.cart.remove');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');

    // Produk & Kas (Kasir boleh input, tapi delete hanya admin di atas)
    Route::resource('products', ProductController::class);

    Route::get('/kas-masuk', [KasMasukController::class, 'index'])->name('kas-masuk.index');
    Route::get('/kas-masuk/create', [KasMasukController::class, 'create'])->name('kas-masuk.create');
    Route::post('/kas-masuk', [KasMasukController::class, 'store'])->name('kas-masuk.store');
    Route::get('/kas-masuk/{id}/edit', [KasMasukController::class, 'edit'])->name('kas-masuk.edit');
    Route::put('/kas-masuk/{id}', [KasMasukController::class, 'update'])->name('kas-masuk.update');

    Route::get('/kas-keluar', [KasKeluarController::class, 'index'])->name('kas-keluar.index');
    Route::get('/kas-keluar/create', [KasKeluarController::class, 'create'])->name('kas-keluar.create');
    Route::post('/kas-keluar', [KasKeluarController::class, 'store'])->name('kas-keluar.store');
    Route::get('/kas-keluar/{id}/edit', [KasKeluarController::class, 'edit'])->name('kas-keluar.edit');
    Route::put('/kas-keluar/{id}', [KasKeluarController::class, 'update'])->name('kas-keluar.update');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';