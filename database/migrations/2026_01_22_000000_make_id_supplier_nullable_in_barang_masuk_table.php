<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Make id_supplier nullable so existing code can omit it when inserting
        DB::statement('ALTER TABLE `barang_masuk` MODIFY `id_supplier` BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to NOT NULL (may fail if NULL values exist)
        DB::statement('ALTER TABLE `barang_masuk` MODIFY `id_supplier` BIGINT UNSIGNED NOT NULL');
    }
};
