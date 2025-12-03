<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kas_masuk', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_kas')->unique();
            $table->date('tanggal_transaksi');

            // PERBAIKAN: Menambahkan nullable() agar tidak wajib diisi
            $table->text('keterangan')->nullable();

            $table->string('kategori');
            $table->string('metode_pembayaran');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('total', 15, 2);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kas_masuk');
    }
};
