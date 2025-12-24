<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class barang_keluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar';

    protected $fillable = [
        'id_barang',
        'kode_gudang',
        'jumlah',
        'tanggal',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(barang::class, 'id_barang');
    }

    public function gudang(): BelongsTo
    {
        return $this->belongsTo(gudang::class, 'kode_gudang', 'kode_gudang');
    }
}
