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
        Schema::table('kas_masuk', function (Blueprint $table) {
            // Menambahkan kolom detail_items (JSON) setelah keterangan
            $table->json('detail_items')->nullable()->after('keterangan');

            // Menambahkan kolom kembalian setelah total
            $table->decimal('kembalian', 15, 2)->default(0)->after('total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kas_masuk', function (Blueprint $table) {
            // Hapus kolom jika rollback
            $table->dropColumn(['detail_items', 'kembalian']);
        });
    }
};
