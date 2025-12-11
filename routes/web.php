<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KasMasukController;
use App\Http\Controllers\KasKeluarController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    /** =========================
     * ADMIN ONLY
     * ========================= */
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('outlets', OutletController::class);
        Route::delete('/users/bulk-destroy', [UserController::class, 'bulkDestroy'])->name('users.bulk_destroy');
        Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);

        // Admin can see full reports and manage other things
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Bulk Delete Routes
        Route::delete('/kas-masuk/bulk-destroy', [KasMasukController::class, 'bulkDestroy'])->name('kas-masuk.bulk_destroy');
        Route::delete('/kas-keluar/bulk-destroy', [KasKeluarController::class, 'bulkDestroy'])->name('kas-keluar.bulk_destroy');

        Route::get('/kas-masuk', [KasMasukController::class, 'index'])->name('kas-masuk.index');
        Route::get('/kas-masuk/create', [KasMasukController::class, 'create'])->name('kas-masuk.create');
        Route::post('/kas-masuk', [KasMasukController::class, 'store'])->name('kas-masuk.store');
        Route::get('/kas-masuk/{id}/edit', [KasMasukController::class, 'edit'])->name('kas-masuk.edit');
        Route::put('/kas-masuk/{id}', [KasMasukController::class, 'update'])->name('kas-masuk.update');
        Route::get('/kas-masuk/{id}/delete', [KasMasukController::class, 'destroy'])->name('kas-masuk.destroy');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
        Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
    });

    /** =========================
     * SHARED / KASIR ACCESS
     * ========================= */
    // Kas Masuk (Kasir might need to see their own?)
    // The user said Kasir has: POS, Kas Keluar, Produk.
    // So Kas Masuk is likely automated from POS, but maybe they can view it?
    // Let's assume Kasir can access these but we filter data in Controller.

    Route::resource('products', ProductController::class);

    // Kas Keluar
    Route::get('/kas-masuk', [KasMasukController::class, 'index'])->name('kas-masuk.index');
    Route::get('/kas-keluar', [KasKeluarController::class, 'index'])->name('kas-keluar.index');
    Route::get('/kas-keluar/tambah', [KasKeluarController::class, 'create'])->name('kas-keluar.create');
    Route::post('/kas-keluar', [KasKeluarController::class, 'store'])->name('kas-keluar.store');
    Route::get('/kas-keluar/{id}/edit', [KasKeluarController::class, 'edit'])->name('kas-keluar.edit');
    Route::put('/kas-keluar/{id}', [KasKeluarController::class, 'update'])->name('kas-keluar.update');
    Route::delete('/kas-keluar/{id}', [KasKeluarController::class, 'destroy'])->name('kas-keluar.destroy');

    // POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/cart/add', [PosController::class, 'addToCart'])->name('pos.cart.add');
    Route::post('/pos/cart/plus', [PosController::class, 'qtyPlus'])->name('pos.cart.plus');
    Route::post('/pos/cart/minus', [PosController::class, 'qtyMinus'])->name('pos.cart.minus');
    Route::post('/pos/cart/remove', [PosController::class, 'removeItem'])->name('pos.cart.remove');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');

    /** =========================
     * PROFIL
     * ========================= */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__ . '/auth.php';
