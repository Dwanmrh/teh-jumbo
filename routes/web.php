<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KasMasukController;
use App\Http\Controllers\KasKeluarController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    /** =========================
     *  DASHBOARD (REAL DATA)
     * ========================= */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /** =========================
     *  KAS MASUK
     * ========================= */
    Route::get('/kas-masuk', [KasMasukController::class, 'index'])->name('kas-masuk.index');
    Route::get('/kas-masuk/tambah', [KasMasukController::class, 'create'])->name('kas-masuk.create');
    Route::post('/kas-masuk', [KasMasukController::class, 'store'])->name('kas-masuk.store');
    Route::get('/kas-masuk/{id}/edit', [KasMasukController::class, 'edit'])->name('kas-masuk.edit');
    Route::put('/kas-masuk/{id}', [KasMasukController::class, 'update'])->name('kas-masuk.update');
    Route::delete('/kas-masuk/{id}', [KasMasukController::class, 'destroy'])->name('kas-masuk.destroy');

    /** =========================
     *  KAS KELUAR
     * ========================= */
    Route::get('/kas-keluar', [KasKeluarController::class, 'index'])->name('kas-keluar.index');
    Route::get('/kas-keluar/tambah', [KasKeluarController::class, 'create'])->name('kas-keluar.create');
    Route::post('/kas-keluar', [KasKeluarController::class, 'store'])->name('kas-keluar.store');
    Route::get('/kas-keluar/{id}/edit', [KasKeluarController::class, 'edit'])->name('kas-keluar.edit');
    Route::put('/kas-keluar/{id}', [KasKeluarController::class, 'update'])->name('kas-keluar.update');
    Route::delete('/kas-keluar/{id}', [KasKeluarController::class, 'destroy'])->name('kas-keluar.destroy');

    /** =========================
     *  BARANG
     * ========================= */
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');

    /** =========================
     *  LAPORAN
     * ========================= */
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');

    /** =========================
     *  PROFIL
     * ========================= */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
