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
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_pengajuan')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('barang_id')->nullable();
            $table->string('kode_gudang')->nullable();
            $table->unsignedInteger('jumlah')->default(0);
            $table->date('tanggal');
            $table->string('status')->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuan');
    }
};
