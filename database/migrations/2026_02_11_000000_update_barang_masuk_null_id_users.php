<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update barang_masuk records yang memiliki NULL id_users dengan user id default (1)
        // Jika Anda ingin behavior berbeda, sesuaikan nilainya
        DB::table('barang_masuk')
            ->whereNull('id_users')
            ->update(['id_users' => 1]);
    }

    public function down(): void
    {
        // Tidak ada rollback untuk data migration ini
    }
};
