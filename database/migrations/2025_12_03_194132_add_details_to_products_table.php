<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Menambahkan user_id untuk multi-user (Wajib ada karena di controller dipakai)
            if (!Schema::hasColumn('products', 'user_id')) {
                 $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            }

            // Kolom Ukuran (Jumbo, Sedang, Kecil)
            if (!Schema::hasColumn('products', 'ukuran')) {
                $table->string('ukuran')->default('-')->after('kategori');
            }

            // Kolom Modal / HPP
            if (!Schema::hasColumn('products', 'modal')) {
                $table->integer('modal')->default(0)->after('harga');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'ukuran', 'modal']);
        });
    }
};
