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
            $table->string('kode_kas')->unique(); // Contoh: POS-001 atau KM-001
            $table->dateTime('tanggal_transaksi'); // Pakai DateTime agar jam tercatat (penting untuk shift)

            $table->text('keterangan')->nullable(); // Nama pembeli atau Catatan
            $table->string('kategori'); // 'Penjualan', 'Modal Awal', dll

            // PENTING: Diganti jadi 'payment_method' agar sinkron dengan Controller
            // Values: 'cash', 'qris', 'transfer'
            $table->string('payment_method')->default('cash');

            // Untuk POS (Simpan rasa teh, topping, dll disini)
            $table->json('detail_items')->nullable();

            // Nominal
            $table->integer('jumlah')->nullable(); // Boleh null jika pakai POS
            $table->decimal('harga_satuan', 15, 2)->nullable(); // Boleh null jika pakai POS
            $table->decimal('total', 15, 2); // Wajib diisi (Omzet Masuk)
            $table->decimal('kembalian', 15, 2)->default(0); // Sisa uang kembalian

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kas_masuk');
    }
};
