<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kas_keluar', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_kas')->unique();
            $table->date('tanggal');
            $table->string('kategori'); // pembelian, operasional, gaji

            // PERBAIKAN: Disamakan dengan kas_masuk jadi 'payment_method'
            $table->string('payment_method');

            $table->string('penerima');
            $table->decimal('nominal', 15, 2);
            $table->string('bukti_pembayaran')->nullable();
            $table->text('deskripsi')->nullable();

            // PERBAIKAN: Tambahkan user_id karena di controller dipanggil
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kas_keluar');
    }
};
