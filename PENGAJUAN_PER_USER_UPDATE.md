# 📋 UPDATE: HALAMAN PENGAJUAN BERBEDA PER USER

## ✅ Apa yang Sudah Diubah

### 1️⃣ **User Biasa (Pengaju) - Melihat Pengajuan Mereka Sendiri**
- Halaman: `/pengajuan/list` (list.blade.php)
- Tampilan: **Card View** (lebih user-friendly)
- Data: Hanya menampilkan pengajuan yang dibuat oleh user tersebut
- Features:
  - Melihat status pengajuan mereka (Pending, Disetujui, Ditolak)
  - Melihat detail setiap pengajuan
  - Tombol untuk membuat pengajuan baru

### 2️⃣ **Admin/Approval/Atasan - Melihat Semua Pengajuan**
- Halaman: `/pengajuan/list` (list-approval.blade.php)
- Tampilan: **Table View** (professional, untuk review)
- Data: Menampilkan SEMUA pengajuan dari semua user
- Info tambahan: Nama user yang mengajukan
- Features:
  - Filter berdasarkan status (Pending, Disetujui, Ditolak)
  - Melihat siapa yang mengajukan
  - Quick access ke detail untuk approval

### 3️⃣ **Detail Pengajuan - Beda Tampilan Berdasarkan Role**
- Halaman: `/pengajuan/{id}/detail` (show.blade.php)
- Authorization: User hanya bisa lihat jika:
  - Dia yang membuat pengajuan, ATAU
  - Dia adalah approval/atasan/admin
- User Pengaju melihat:
  - Status pengajuan (Pending/Disetujui/Ditolak)
  - Catatan dari approval (jika ditolak)
  - Informasi approval team (jika sudah disetujui)
- Approval Team melihat:
  - Semua informasi + tombol Setujui/Tolak
  - Informasi lengkap tentang pengaju

---

## 🎨 UI/UX Improvements

### User Biasa View
```
┌─────────────────────────────────┐
│ Pengajuan Barang Saya           │
│ Total 3 pengajuan               │
│ [Tombol: Pengajuan Baru]        │
├─────────────────────────────────┤
│                                 │
│  ┌─────────────────────────┐   │
│  │ PJ202601291542ABCD      │   │
│  │ ⏳ Pending              │   │
│  │                         │   │
│  │ Gudang: GD001          │   │
│  │ Jumlah: 5 item         │   │
│  │ Tanggal: 29 Jan 2026   │   │
│  │                         │   │
│  │ [Lihat Detail]          │   │
│  └─────────────────────────┘   │
│                                 │
│  ┌─────────────────────────┐   │
│  │ PJ202601281430XYZ1      │   │
│  │ ✓ Disetujui             │   │
│  │ ...                     │   │
│  └─────────────────────────┘   │
│                                 │
└─────────────────────────────────┘
```

### Admin/Approval View
```
┌──────────────────────────────────────────────┐
│ Daftar Pengajuan - Approval                  │
│ Total 25 pengajuan                           │
├──────────────────────────────────────────────┤
│ Kode     │ Pengaju    │ Gdng │ Item │ Status │
├──────────────────────────────────────────────┤
│ PJ20.... │ John Doe   │ GD01 │ 5    │ ⏳ Pnd │
│ PJ20.... │ Jane Smith │ GD02 │ 10   │ ✓ OK  │
│ PJ20.... │ Bob Miller │ GD01 │ 3    │ ✗ Tol │
└──────────────────────────────────────────────┘
```

---

## 📁 Files yang Diubah/Dibuat

| File | Status | Perubahan |
|------|--------|-----------|
| `app/Http/Controllers/PengajuanController.php` | ✏️ Updated | `list()` - Filter data berdasarkan role<br>`show()` - Add authorization checks |
| `resources/views/content/pengajuan/list.blade.php` | ✏️ Updated | Card view untuk user biasa |
| `resources/views/content/pengajuan/list-approval.blade.php` | ✨ Created | Table view untuk approval team |
| `resources/views/content/pengajuan/show.blade.php` | ✏️ Updated | Multi-layout berdasarkan role |

---

## 🔍 Technical Details

### Controller Logic (PengajuanController.php)

```php
public function list(Request $request)
{
    $user = Auth::user();
    $isApprover = $user && in_array($user->role, ['approval', 'atasan', 'admin']);

    if ($isApprover) {
        // Show all pengajuan + user nama
        $items = DB::table('pengajuan')
            ->leftJoin('users as u', 'pengajuan.user_id', '=', 'u.id')
            ->select('pengajuan.*', 'u.nama as user_nama')
            ->orderBy('pengajuan.created_at', 'desc')
            ->paginate(20);
        $view = 'content.pengajuan.list-approval';
    } else {
        // Show only user's own pengajuan
        $items = DB::table('pengajuan')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        $view = 'content.pengajuan.list';
    }

    return view($view, compact('items', 'isApprover'));
}
```

### Authorization in Show (show.blade.php)

```php
$isApprover = $user && in_array($user->role, ['approval', 'atasan', 'admin']);
$isPengaju = $user && $pengajuan->user_id == $user->id;

if (!$isApprover && !$isPengaju) {
    abort(403, 'Anda tidak memiliki akses ke pengajuan ini');
}
```

---

## 📊 Role-Based Access Matrix

| Action | User Biasa | Approval | Atasan | Admin |
|--------|-----------|----------|--------|-------|
| Lihat pengajuan sendiri | ✅ | ✅ | ✅ | ✅ |
| Lihat semua pengajuan | ❌ | ✅ | ✅ | ✅ |
| Create pengajuan | ✅ | ❌ | ❌ | ❌ |
| Approve pengajuan | ❌ | ✅ | ✅ | ✅ |
| Reject pengajuan | ❌ | ✅ | ✅ | ✅ |

---

## 🧪 Testing

### Test Scenario 1: User Biasa
1. Login dengan user role: `member` atau `null`
2. Go to `/pengajuan/list`
3. ✅ Hanya lihat pengajuan milik mereka sendiri (card view)
4. Click detail pengajuan sendiri
5. ✅ Bisa lihat detail lengkap

### Test Scenario 2: User Approval
1. Login dengan user role: `approval`
2. Go to `/pengajuan/list`
3. ✅ Lihat semua pengajuan dari semua user (table view)
4. Click detail pengajuan user lain
5. ✅ Bisa lihat detail + tombol Setujui/Tolak

### Test Scenario 3: Authorization Check
1. Login dengan user biasa (ID: 5)
2. Try akses pengajuan user lain (cth: `/pengajuan/123`)
3. ✅ Error 403 (forbidden)

---

## 🎯 Features Overview

### Untuk User Biasa
✨ **Lebih Personal & User-Friendly**
- Card layout yang menarik
- Status visual dengan badge dan warna
- Tombol aksi yang jelas
- Info ringkas di setiap kartu

### Untuk Approval Team
✨ **Lebih Efisien untuk Review**
- Table layout untuk melihat banyak item sekaligus
- Informasi pengaju langsung di tampilan
- Status warna-warni untuk quick scanning
- Pagination untuk list yang panjang

### Untuk Semua User
✨ **Security & Experience**
- Authorization checks on every access
- Role-based conditional rendering
- Clear visual hierarchy
- Responsive design (mobile-friendly)

---

## 🚀 URL Routes

```
/pengajuan                    → Form pengajuan baru
/pengajuan/list               → Daftar pengajuan (berbeda per role)
/pengajuan/{id}/detail        → Detail pengajuan (dengan auth check)
/pengajuan/{id}/approve       → Approve action (auth required)
/pengajuan/{id}/reject        → Reject action (auth required)
```

---

## ✅ Status

- ✅ Logic updated
- ✅ Views created/updated
- ✅ Authorization implemented
- ✅ No errors detected
- ✅ Production ready

---

## 📝 Notes

1. **Backward Compatible**: Routes sama, hanya logic & view yang berubah
2. **Secure**: Authorization checks di controller & view
3. **UX Optimized**: Different layout untuk different users
4. **Mobile Responsive**: All views work on mobile
5. **No Data Migration**: Uses existing tables & structure

---

**Update Completed**: 29 Januari 2026
**Status**: ✅ Ready to Use
