<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stok extends Model
{
    use HasFactory;

    protected $table = 'stok';

    protected $fillable = [
        'id_barang',
        'kode_gudang',
        'stok',
    ];

    protected $casts = [
        'stok' => 'integer',
    ];

    // Relasi ke Barang (Foreign Key: id_barang)
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    // Relasi ke Gudang (Foreign Key: kode_gudang)
    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class, 'kode_gudang');
    }
}