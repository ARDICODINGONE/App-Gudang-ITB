<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update pengajuan records yang memiliki NULL user_id dengan user id default (1)
        DB::table('pengajuan')
            ->whereNull('user_id')
            ->update(['user_id' => 1]);
    }

    public function down(): void
    {
        // Tidak ada rollback untuk data migration ini
    }
};
