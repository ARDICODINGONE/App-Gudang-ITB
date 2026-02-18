<?php

use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ReportController;

use App\Models\Kategori;
use App\Http\Controllers\CartController;

Route::get('/shop', function () {
    $kategoris = Kategori::all();
    return view('shop', compact('kategoris'));
});

// API endpoint to return products as JSON for the shop page
Route::get('/shop/products', [BarangController::class, 'apiIndex'])->name('shop.products');

// API endpoint to return categories as JSON for the shop page
Route::get('/shop/categories', [KategoriController::class, 'apiIndex'])->name('shop.categories');

Route::get('/cart', function () {
    return view('cart');
});

Route::put('/cart/note', [CartController::class, 'updateNote']);

// Cart API
Route::get('/cart/items', [CartController::class, 'items']);
Route::post('/cart/items', [CartController::class, 'add']);
Route::put('/cart/items/{id}', [CartController::class, 'updateItem']);
Route::delete('/cart/items/{id}', [CartController::class, 'removeItem']);
Route::delete('/cart/clear', [CartController::class, 'clear']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/single', function () {
    return view('single');
})->name('single');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

Route::middleware(['auth', 'petugas'])->group(function () {
// Gudang
Route::get('/gudang', [GudangController::class, 'index'])->name('gudang-index');

Route::post('/gudang/store', [GudangController::class, 'store'])->name('gudang.store');
// add/remove product endpoints removed (handled via admin UI or other flows)
Route::delete('/gudang/{kode_gudang}', [GudangController::class, 'destroy'])->name('gudang.destroy');
Route::put('/gudang/{kode_gudang}', [GudangController::class, 'update'])->name('gudang.update');
// Barang
Route::get('/barang', [BarangController::class, 'index'])->name('barang-index');
Route::get('/barang/create', [BarangController::class, 'create'])->name('barang-create');
Route::post('/barang', [BarangController::class, 'store'])->name('barang-store');
Route::get('/barang/next-kode', [BarangController::class, 'nextKode'])->name('barang.nextKode');
Route::get('/barang/check-duplicate', [BarangController::class, 'checkDuplicate'])->name('barang.checkDuplicate');
// Import routes for barang
Route::get('/barang/template', [BarangController::class, 'downloadTemplate'])->name('barang.template');
Route::get('/barang/export', [BarangController::class, 'export'])->name('barang.export');
Route::post('/barang/import', [BarangController::class, 'import'])->name('barang.import');
Route::delete('/barang/{kode_barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
Route::put('/barang/{kode_barang}', [BarangController::class, 'update'])->name('barang.update');
// Kategori
Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori-index');
Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori-store');
Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
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
// Bulk delete for barang masuk
Route::post('/barang-masuk/bulk-delete', [BarangMasukController::class, 'bulkDestroy'])->name('barang-masuk.bulkDestroy');
// Import routes for barang masuk
Route::get('/barang-masuk/template', [BarangMasukController::class, 'downloadTemplate'])->name('barang-masuk.template');
Route::get('/barang-masuk/export', [BarangMasukController::class, 'export'])->name('barang-masuk.export');
Route::post('/barang-masuk/import', [BarangMasukController::class, 'import'])->name('barang-masuk.import');

// Barang Keluar
Route::get('/barang-keluar', [BarangKeluarController::class, 'index'])->name('barang-keluar-index');
Route::get('/barang-keluar/create', [BarangKeluarController::class, 'create'])->name('barang-keluar.create');
Route::post('/barang-keluar/store', [BarangKeluarController::class, 'store'])->name('barang-keluar.store');
Route::put('/barang-keluar/{barang_keluar}', [BarangKeluarController::class, 'update'])->name('barang-keluar.update');
Route::delete('/barang-keluar/{barang_keluar}', [BarangKeluarController::class, 'destroy'])->name('barang-keluar.destroy');

// Reports (Laporan)
Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
Route::get('/laporan/stok-gudang', [ReportController::class, 'stokGudang'])->name('laporan.stok-gudang');
Route::get('/laporan/barang-masuk', [ReportController::class, 'barangMasuk'])->name('laporan.barang-masuk');
Route::get('/laporan/barang-masuk/export-excel', [ReportController::class, 'exportBarangMasukExcel'])->name('laporan.barang-masuk.export-excel');
Route::get('/laporan/barang-masuk/export-pdf', [ReportController::class, 'exportBarangMasukPdf'])->name('laporan.barang-masuk.export-pdf');
Route::get('/laporan/barang-keluar', [ReportController::class, 'barangKeluar'])->name('laporan.barang-keluar');
Route::get('/laporan/barang-keluar/export-excel', [ReportController::class, 'exportBarangKeluarExcel'])->name('laporan.barang-keluar.export-excel');
Route::get('/laporan/barang-keluar/export-pdf', [ReportController::class, 'exportBarangKeluarPdf'])->name('laporan.barang-keluar.export-pdf');
Route::get('/laporan/riwayat-pengajuan', [ReportController::class, 'riwayatPengajuan'])->name('laporan.riwayat-pengajuan');
Route::get('/laporan/riwayat-pengajuan/export-excel', [ReportController::class, 'exportRiwayatPengajuanExcel'])->name('laporan.riwayat-pengajuan.export-excel');
Route::get('/laporan/riwayat-pengajuan/export-pdf', [ReportController::class, 'exportRiwayatPengajuanPdf'])->name('laporan.riwayat-pengajuan.export-pdf');
});

// Notification
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');
    Route::get('/notifications/{id}/detail', [NotificationController::class, 'getDetail'])->name('notifications.detail');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
});

// Admin only routes
Route::middleware(['auth', 'admin'])->group(function () {
    // Supplier
    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier-index');
    Route::post('/supplier/store', [SupplierController::class, 'store'])->name('supplier.store');
    Route::delete('/supplier/{supplier}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
    Route::put('/supplier/{supplier}', [SupplierController::class, 'update'])->name('supplier.update');
    // User
    Route::get('/user', [UserController::class, 'index'])->name('user-index');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    // Admin reports
    Route::get('/laporan/stok-gudang/export-excel', [ReportController::class, 'exportStokGudangExcel'])->name('laporan.stok-gudang.export-excel');
    Route::get('/laporan/stok-gudang/export-pdf', [ReportController::class, 'exportStokGudangPdf'])->name('laporan.stok-gudang.export-pdf');
    Route::get('/laporan/pengajuan', [ReportController::class, 'pengajuan'])->name('laporan.pengajuan');
    Route::get('/laporan/pengajuan/export-excel', [ReportController::class, 'exportPengajuanExcel'])->name('laporan.pengajuan.export-excel');
    Route::get('/laporan/pengajuan/export-pdf', [ReportController::class, 'exportPengajuanPdf'])->name('laporan.pengajuan.export-pdf');
});

// Pengajuan (authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::post('/pengajuan/from-cart', [PengajuanController::class, 'fromCart'])->name('pengajuan.fromCart');
    Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('/pengajuan/list', [PengajuanController::class, 'list'])->name('pengajuan.list');
    Route::get('/pengajuan/{id}/detail', [PengajuanController::class, 'show'])->name('pengajuan.show');
    Route::get('/pengajuan/{id}/details', [PengajuanController::class, 'getDetails'])->name('pengajuan.getDetails');
    Route::post('/pengajuan/{id}/approve', [PengajuanController::class, 'approve'])->name('pengajuan.approve');
    Route::post('/pengajuan/{id}/reject', [PengajuanController::class, 'reject'])->name('pengajuan.reject');
});

// Authentication routes
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/');
    }
    return view('auth.Login');
})->name('login.show');

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');