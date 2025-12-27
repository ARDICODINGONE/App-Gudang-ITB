<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class gudang extends Model
{
    use HasFactory;

    protected $table = 'gudang';

    // Konfigurasi Primary Key Custom (String)
    protected $primaryKey = 'kode_gudang';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_gudang',
        'nama_gudang',
        'lokasi',
        'images',
    ];

    // Relasi ke Stok
    public function stok(): HasMany
    {
        return $this->hasMany(stok::class, 'kode_gudang');
    }
}