<?php

use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;

Route::get('/shop', function () {
    return view('shop');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/single', function () {
    return view('single');
})->name('single');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

// Gudang
Route::get('/gudang', [GudangController::class, 'index'])->name('gudang-index');
Route::post('/gudang/store', [GudangController::class, 'store'])->name('gudang.store');
Route::delete('/gudang/{kode_gudang}', [GudangController::class, 'destroy'])->name('gudang.destroy');
Route::put('/gudang/{kode_gudang}', [GudangController::class, 'update'])->name('gudang.update');
// Barang
Route::get('/barang', [BarangController::class, 'index'])->name('barang-index');
Route::get('/barang/create', [BarangController::class, 'create'])->name('barang-create');
Route::post('/barang', [BarangController::class, 'store'])->name('barang-store');
Route::delete('/barang/{kode_barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
// Kategori
Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori-index');
Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori-store');
Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
//supplier
Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier-index');
Route::post('/supplier/store', [SupplierController::class, 'store'])->name('supplier.store');
Route::delete('/supplier/{supplier}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
Route::put('/supplier/{supplier}', [SupplierController::class, 'update'])->name('supplier.update');
// User
Route::get('/user', [UserController::class, 'index'])->name('user-index');
Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');

// Barang Masuk
Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->name('barang-masuk-index');
Route::get('/barang-masuk/create', [BarangMasukController::class, 'create'])->name('barang-masuk.create');
Route::post('/barang-masuk/store', [BarangMasukController::class, 'store'])->name('barang-masuk.store');
Route::put('/barang-masuk/{barang_masuk}', [BarangMasukController::class, 'update'])->name('barang-masuk.update');
Route::delete('/barang-masuk/{barang_masuk}', [BarangMasukController::class, 'destroy'])->name('barang-masuk.destroy');

// Barang Keluar
Route::get('/barang-keluar', [BarangKeluarController::class, 'index'])->name('barang-keluar-index');
Route::get('/barang-keluar/create', [BarangKeluarController::class, 'create'])->name('barang-keluar.create');
Route::post('/barang-keluar/store', [BarangKeluarController::class, 'store'])->name('barang-keluar.store');
Route::put('/barang-keluar/{barang_keluar}', [BarangKeluarController::class, 'update'])->name('barang-keluar.update');
Route::delete('/barang-keluar/{barang_keluar}', [BarangKeluarController::class, 'destroy'])->name('barang-keluar.destroy');