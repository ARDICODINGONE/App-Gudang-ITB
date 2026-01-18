<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'satuan',
        'deskripsi',
        'harga',
        'image',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(kategori::class, 'kategori_id');
    }

    public function stok(): HasMany
    {
        return $this->hasMany(stok::class, 'id_barang');
    }
}