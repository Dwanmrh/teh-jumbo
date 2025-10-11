<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

// Halaman awal (welcome)
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Middleware untuk halaman yang hanya bisa diakses jika login
Route::middleware('auth')->group(function () {

    // Kas Masuk
    Route::get('/kas-masuk', [KasController::class, 'masuk'])->name('kas.masuk');

    // Kas Keluar
    Route::get('/kas-keluar', [KasController::class, 'keluar'])->name('kas.keluar');

    // Persediaan Barang / Barang
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');

    // Laporan Keuangan / Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.keuangan');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
