<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class barang_masuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'id_barang',
        'kode_gudang',
        'id_supplier',
        'jumlah',
        'tanggal',
        'id_users',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(barang::class, 'id_barang');
    }

    public function gudang(): BelongsTo
    {
        return $this->belongsTo(gudang::class, 'kode_gudang', 'kode_gudang');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(supplier::class, 'id_supplier');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}
