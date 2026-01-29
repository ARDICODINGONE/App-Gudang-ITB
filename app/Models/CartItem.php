<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
	use HasFactory;

	protected $table = 'cart_items';

	protected $fillable = [
		'cart_id',
		'barang_id',
		'quantity',
		'price',
	];

	public function cart(): BelongsTo
	{
		return $this->belongsTo(Cart::class, 'cart_id');
	}

	public function barang(): BelongsTo
	{
		return $this->belongsTo(Barang::class, 'barang_id');
	}
}
