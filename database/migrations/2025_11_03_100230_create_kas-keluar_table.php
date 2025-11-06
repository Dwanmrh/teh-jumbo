<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('kas_keluar', function (Blueprint $table) {
            $table->uuid('id')->primary(); // kolom id (auto increment)
            $table->string('kode_kas')->unique(); 
            $table->date('tanggal'); // kolom tanggal
            $table->string('kategori'); // kolom kategori (misal: pembelian, operasional, dll)
            $table->string('metode_pembayaran'); // kolom metode pembayaran (tunai, transfer, e-wallet, dll)
            $table->string('penerima'); // nama penerima uang
            $table->decimal('nominal', 15, 2); 
            $table->string('bukti_pembayaran')->nullable(); // file bukti (opsional)
            $table->text('deskripsi')->nullable(); // kolom deskripsi (boleh kosong)
            $table->timestamps(); // created_at & updated_at
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas-keluar');
    }
};
