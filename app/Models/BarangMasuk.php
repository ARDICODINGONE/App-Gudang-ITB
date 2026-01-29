<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'id_barang',
        'kode_gudang',
        'jumlah',
        'tanggal',
        'id_users',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class, 'kode_gudang', 'kode_gudang');
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}
