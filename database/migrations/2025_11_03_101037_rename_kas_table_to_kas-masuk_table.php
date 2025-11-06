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
        Schema::create('kas_masuk', function (Blueprint $table) {
            $table->uuid('id')->primay();
            $table->string('kode_kas')->unique();
            $table->date('tanggal_transaksi');      // tanggal transaksi
            $table->text('keterangan');
            $table->string('kategori');
            $table->string('metode_pembayaran');            // nama barang / menu yang dijual
            $table->integer('jumlah');              // jumlah item
            $table->decimal('harga_satuan', 15, 2); // harga per item
            $table->decimal('total', 15, 2);        // total = jumlah * harga_satuan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas-masuk');
    }
};
