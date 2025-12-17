<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update Products
        DB::statement("
            UPDATE products p
            JOIN users u ON p.user_id = u.id
            SET p.outlet_id = u.outlet_id
            WHERE p.outlet_id IS NULL AND u.outlet_id IS NOT NULL
        ");

        // Update Kas Masuk
        DB::statement("
            UPDATE kas_masuk k
            JOIN users u ON k.user_id = u.id
            SET k.outlet_id = u.outlet_id
            WHERE k.outlet_id IS NULL AND u.outlet_id IS NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No specific rollback needed as data was filled
    }
};
