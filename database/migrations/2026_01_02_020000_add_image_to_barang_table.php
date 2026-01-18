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
        if (Schema::hasTable('barang') && ! Schema::hasColumn('barang', 'image')) {
            Schema::table('barang', function (Blueprint $table) {
                $table->string('image')->nullable()->after('harga');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('barang') && Schema::hasColumn('barang', 'image')) {
            Schema::table('barang', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};
