<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'kategori',
    ];

    // Relasi: Satu Kategori punya banyak Barang
    public function barang(): HasMany
    {
        return $this->hasMany(barang::class, 'kategori_id');
    }
}