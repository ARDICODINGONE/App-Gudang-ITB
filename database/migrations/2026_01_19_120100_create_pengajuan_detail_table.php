<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pengajuan_id');
            $table->unsignedBigInteger('barang_id')->nullable();
            $table->unsignedInteger('jumlah')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('pengajuan_id')
                  ->references('id')->on('pengajuan')
                  ->onDelete('cascade');

            $table->foreign('barang_id')
                  ->references('id')->on('barang')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuan_detail');
    }
};
