# Dokumentasi Update Hak Akses Petugas

## Deskripsi
Pembaruan sistem untuk memberikan hak akses kepada user dengan role **'petugas'** yang sama dengan admin/atasan untuk menambah, mengubah, dan menghapus data seperti:
- Gudang
- Barang
- Kategori
- Supplier
- Barang Masuk
- Barang Keluar
- Dan operasi lainnya

## Detail Perubahan

### 1. Database Schema
**File**: `database/migrations/0001_01_01_000000_create_users_table.php`
- Role 'petugas' sudah terdefinisi dalam enum: `['user', 'petugas', 'approval', 'atasan']`
- Tidak memerlukan perubahan database

### 2. View - Welcome Page
**File**: `resources/views/welcome.blade.php`

**Perubahan**:
- **Baris 5**: Tambahkan role 'petugas' pada pengecekan menu admin
```blade
# SEBELUM:
@if(auth()->user()->role === 'atasan' || auth()->user()->role === 'admin')

# SESUDAH:
@if(auth()->user()->role === 'atasan' || auth()->user()->role === 'admin' || auth()->user()->role === 'petugas')
```

**Efek**: Menu admin (Gudang, Barang, Barang Masuk, Barang Keluar, Kategori, Laporan) akan ditampilkan untuk user dengan role 'petugas'

### 3. View - Gudang Index  
**File**: `resources/views/content/gudang/index.blade.php`

**Perubahan**:
- **Baris 47**: Tambahkan role 'petugas' pada pengecekan tombol Edit/Delete
```blade
# SEBELUM:
@if(auth()->user() && auth()->user()->role === 'atasan')

# SESUDAH:
@if(auth()->user() && (auth()->user()->role === 'atasan' || auth()->user()->role === 'admin' || auth()->user()->role === 'petugas'))
```

**Efek**: User dengan role 'petugas' dapat melihat dan menggunakan tombol Edit dan Delete pada gudang

### 4. View - Shop / Pilih Gudang
**File**: `resources/views/shop.blade.php`

**Perubahan**:
- **Baris 44**: Tambahkan role 'petugas' pada pengecekan admin area
```blade
# SEBELUM:
@if (auth()->check() && auth()->user()->role === 'atasan')

# SESUDAH:
@if (auth()->check() && (auth()->user()->role === 'atasan' || auth()->user()->role === 'admin' || auth()->user()->role === 'petugas'))
```

**Efek**: Area admin akan tersedia untuk user dengan role 'petugas'

## File Yang TIDAK Memerlukan Perubahan

File-file berikut sudah mendukung akses untuk semua role (tanpa pembatasan):

1. **resources/views/content/barang/index.blade.php**
   - Tidak ada pembatasan role untuk tombol tambah/edit/delete
   - Sudah bisa diakses oleh semua user

2. **resources/views/content/kategori/index.blade.php**
   - Tidak ada pembatasan role
   - Tombol tambah/edit/delete sudah terbuka untuk semua user

3. **resources/views/content/supplier/index.blade.php**
   - Tidak ada pembatasan role
   - Semua operasi terbuka untuk semua user

4. **resources/views/content/barang_masuk/index.blade.php**
   - Tidak ada pembatasan role
   - Tombol tambah/edit/delete sudah terbuka

5. **resources/views/content/barang_keluar/index.blade.php**
   - Tidak ada pembatasan role
   - Semua user bisa melihat data (display only, tidak ada edit/delete)

6. **resources/views/content/user/index.blade.php**
   - Tidak ada pembatasan role
   - Semua user bisa akses user management

## Controller - Tidak Ada Perubahan Diperlukan

Semua controller sudah tidak memiliki authorization check per role:
- `GudangController.php`
- `BarangController.php`
- `KategoriController.php`
- `SupplierController.php`
- `BarangMasukController.php`
- `BarangKeluarController.php`
- `UserController.php`

Controller tidak mengecek role, sehingga dengan mengubah permission di view, akses sudah sepenuhnya diberikan kepada 'petugas'.

## Routes - Tidak Ada Perubahan Diperlukan

File `routes/web.php` tidak memiliki middleware yang membatasi akses per role, sehingga tidak perlu diubah.

## Level Akses Setelah Update

| Feature | Admin | Atasan | Petugas | User |
|---------|-------|--------|---------|------|
| Tambah Gudang | ✓ | ✓ | ✓ | ✗ |
| Edit Gudang | ✓ | ✓ | ✓ | ✗ |
| Hapus Gudang | ✓ | ✓ | ✓ | ✗ |
| Tambah Barang | ✓ | ✓ | ✓ | ✗ |
| Edit Barang | ✓ | ✓ | ✓ | ✗ |
| Hapus Barang | ✓ | ✓ | ✓ | ✗ |
| Kategori Manage | ✓ | ✓ | ✓ | ✓ |
| Supplier Manage | ✓ | ✓ | ✓ | ✓ |
| Barang Masuk | ✓ | ✓ | ✓ | ✓ |
| Barang Keluar | ✓ | ✓ | ✓ | ✓ |
| User Management | ✓ | ✓ | ✓ | ✓ |

## Testing

Untuk testing, lakukan langkah berikut:

1. **Buat user dengan role 'petugas'** (jika belum ada)
   - Masuk ke halaman User Management
   - Tambah user baru dengan role 'petugas'
   - Set password dan username

2. **Login dengan user petugas**
   - Verifikasi menu admin muncul di halaman welcome
   - Klik menu Gudang, Barang, Kategori, dll
   - Verifikasi tombol Tambah, Edit, Delete muncul

3. **Test operasi CRUD**
   - Coba tambah gudang baru
   - Coba edit gudang yang ada
   - Coba hapus gudang
   - Lakukan hal yang sama untuk Barang, Kategori, Supplier

## Catatan Penting

- Update ini HANYA mengubah permission di level UI (View)
- Tidak ada perubahan database atau migration
- Jika ingin menambah lebih banyak role-based permission di masa depan, pertimbangkan menggunakan Laravel Middleware, Policies, atau Gate
- Dokumentasi ini di-generate pada tanggal 11 Februari 2026

## Troubleshooting

Jika user 'petugas' tidak bisa melihat menu admin:
1. Pastikan user sudah login dengan role 'petugas'
2. Clear browser cache (Ctrl+Shift+Delete)
3. Check database: `SELECT * FROM users WHERE username='xx';` pastikan role field = 'petugas'
4. Restart Laravel: `php artisan serve`

Jika tombol edit/delete tidak muncul di gudang:
1. Pastikan session user aktif
2. Check browser console untuk error JavaScript
3. Verify `resources/views/content/gudang/index.blade.php` sudah diupdate dengan benar
