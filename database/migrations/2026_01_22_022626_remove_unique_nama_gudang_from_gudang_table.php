<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            $row = DB::selectOne("SELECT COUNT(*) as c FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = 'gudang' AND index_name = 'gudang_nama_gudang_unique'");
            if ($row && ($row->c ?? $row->C ?? 0) > 0) {
                DB::statement("ALTER TABLE `gudang` DROP INDEX `gudang_nama_gudang_unique`");
                return;
            }
        } catch (\Throwable $e) {
            // information_schema may not be available, fall back below
        }

        try {
            Schema::table('gudang', function (Blueprint $table) {
                $table->dropUnique('gudang_nama_gudang_unique');
            });
        } catch (\Throwable $e) {
            // index doesn't exist or can't be dropped; ignore to allow migration to continue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('gudang', function (Blueprint $table) {
                $table->unique('nama_gudang');
            });
        } catch (\Throwable $e) {
            // ignore if unique already exists
        }
    }
};
