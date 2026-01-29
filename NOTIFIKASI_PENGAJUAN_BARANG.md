# Sistem Notifikasi Pengajuan Barang

## Deskripsi
Sistem notifikasi otomatis untuk pengajuan barang yang mengirimkan notifikasi ke:
1. **Admin/Approval**: Notifikasi ketika ada pengajuan barang baru dari user
2. **User/Pengaju**: Notifikasi ketika pengajuan mereka disetujui atau ditolak

## Fitur

### 1. Notifikasi Pengajuan Baru
- **Triggered**: Ketika user membuat pengajuan barang (via form atau dari cart)
- **Recipient**: Semua user dengan role `approval`, `atasan`, atau `admin`
- **Content**:
  - Title: "Pengajuan Barang Baru"
  - Message: "Ada pengajuan barang baru dari [Nama User]"
  - Detail: "Kode Pengajuan: [KODE] | Jumlah Item: [QTY]"
  - Type: `info`
  - Link: Mengarah ke halaman detail pengajuan

### 2. Notifikasi Persetujuan
- **Triggered**: Ketika admin/approval menyetujui pengajuan
- **Recipient**: User yang mengajukan (user_id di tabel pengajuan)
- **Content**:
  - Title: "Pengajuan Disetujui"
  - Message: "Pengajuan barang Anda telah disetujui"
  - Detail: "Kode Pengajuan: [KODE]"
  - Type: `success`
  - Link: Mengarah ke halaman detail pengajuan

### 3. Notifikasi Penolakan
- **Triggered**: Ketika admin/approval menolak pengajuan
- **Recipient**: User yang mengajukan (user_id di tabel pengajuan)
- **Content**:
  - Title: "Pengajuan Ditolak"
  - Message: "Pengajuan barang Anda telah ditolak"
  - Detail: "Kode Pengajuan: [KODE]"
  - Type: `danger`
  - Link: Mengarah ke halaman detail pengajuan

## Struktur File

### Models
- **[Pengajuan.php](../app/Models/Pengajuan.php)**: Model untuk tabel pengajuan
- **[PengajuanDetail.php](../app/Models/PengajuanDetail.php)**: Model untuk tabel pengajuan_detail
- **[Notification.php](../app/Models/Notification.php)**: Model untuk tabel notifications

### Controllers
- **[PengajuanController.php](../app/Http/Controllers/PengajuanController.php)**: 
  - `store()`: Membuat pengajuan dan mengirim notifikasi ke approvers
  - `fromCart()`: Membuat pengajuan dari cart dan mengirim notifikasi ke approvers
  - `approve()`: Menyetujui pengajuan dan mengirim notifikasi ke pengaju
  - `reject()`: Menolak pengajuan dan mengirim notifikasi ke pengaju

- **[NotificationController.php](../app/Http/Controllers/NotificationController.php)**: 
  - `index()`: Menampilkan semua notifikasi user
  - `getUnreadCount()`: Get jumlah notifikasi belum dibaca (JSON)
  - `getRecent()`: Get 20 notifikasi terbaru (JSON)
  - `getDetail()`: Get detail notifikasi tertentu (JSON)
  - `markAsRead()`: Tandai notifikasi sebagai dibaca
  - `markAllAsRead()`: Tandai semua notifikasi sebagai dibaca
  - `delete()`: Hapus notifikasi

### Helpers
- **[NotificationHelper.php](../app/Helpers/NotificationHelper.php)**:
  - `notifyApproversOnPengajuanSubmitted()`: Mengirim notifikasi ke semua approvers
  - `notifyApprovalDecision()`: Mengirim notifikasi approval/rejection ke pengaju

### Views
- **[notifications/index.blade.php](../resources/views/notifications/index.blade.php)**: 
  - Halaman untuk melihat semua notifikasi
  - Features: Filter, mark as read, delete, view detail

- **[layouts/navbar.blade.php](../resources/views/layouts/navbar.blade.php)**:
  - Component notifikasi di navbar
  - Side panel untuk menampilkan notifikasi recent
  - Badge count untuk notifikasi belum dibaca

## Routes

```php
// Notification Routes (Require Auth)
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');
Route::get('/notifications/{id}/detail', [NotificationController::class, 'getDetail'])->name('notifications.detail');
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
```

## Database Schema

### Notifications Table
```
- id: bigint (primary key)
- user_id: bigint (foreign key ke users)
- title: string
- message: text
- detail: text
- type: string (info|success|warning|danger)
- link: string (nullable - URL tujuan)
- is_read: boolean (default: false)
- created_at: timestamp
- updated_at: timestamp
```

## Usage Example

### Membuat Notifikasi Manual
```php
use App\Helpers\NotificationHelper;
use App\Models\Pengajuan;

$pengajuan = Pengajuan::find($id);

// Notifikasi ke approvers
NotificationHelper::notifyApproversOnPengajuanSubmitted($pengajuan);

// Notifikasi approval decision
NotificationHelper::notifyApprovalDecision($pengajuan, true); // true = approved
NotificationHelper::notifyApprovalDecision($pengajuan, false); // false = rejected
```

## UI Components

### Notification Bell di Navbar
- Icon bell dengan badge count notifikasi belum dibaca
- Click untuk membuka side panel
- Side panel menampilkan 20 notifikasi terbaru
- Items yang belum dibaca ditandai dengan indicator visual

### Notification Detail Page
- Full page untuk melihat semua notifikasi
- Features:
  - View recent notification
  - Mark as read / Mark all read
  - Delete notification
  - Filter by type (belum diimplementasi, bisa ditambahkan)

### Notification Type Styling
- **info** (Blue): Notifikasi informasi umum
- **success** (Green): Notifikasi berhasil/approved
- **warning** (Yellow): Notifikasi warning
- **danger** (Red): Notifikasi error/rejected

## Flow Diagram

```
1. User mengajukan barang
   ├─ store() method di PengajuanController
   ├─ Insert ke table pengajuan
   ├─ Insert ke table pengajuan_detail
   └─ Call NotificationHelper::notifyApproversOnPengajuanSubmitted()
      └─ Buat notifikasi untuk semua approvers

2. Admin approve pengajuan
   ├─ approve() method di PengajuanController
   ├─ Check stok tersedia
   ├─ Deduct stok
   ├─ Update status pengajuan menjadi 'approved'
   └─ Call NotificationHelper::notifyApprovalDecision($pengajuan, true)
      └─ Buat notifikasi ke pengaju dengan tipe 'success'

3. Admin reject pengajuan
   ├─ reject() method di PengajuanController
   ├─ Update status pengajuan menjadi 'rejected'
   └─ Call NotificationHelper::notifyApprovalDecision($pengajuan, false)
      └─ Buat notifikasi ke pengaju dengan tipe 'danger'

4. User membuka notifikasi
   ├─ Click bell icon di navbar
   ├─ Load recent notifications (20 items)
   ├─ Click item untuk lihat detail
   ├─ Notifikasi otomatis ditandai sebagai 'read'
   ├─ Badge count terupdate
   └─ User bisa delete atau mark all as read
```

## Testing Checklist

- [ ] Create pengajuan, verify notifikasi dikirim ke approvers
- [ ] Approve pengajuan, verify notifikasi dikirim ke pengaju (type success)
- [ ] Reject pengajuan, verify notifikasi dikirim ke pengaju (type danger)
- [ ] Open notification, verify mark as read berfungsi
- [ ] Check badge count berubah saat notifikasi dibaca
- [ ] Delete notification berfungsi dengan baik
- [ ] Mark all as read berfungsi dengan baik
- [ ] Notification detail page (full page) berfungsi
- [ ] Side panel dalam navbar berfungsi smooth
- [ ] Mobile responsive UI bekerja dengan baik

## Future Enhancements

1. **Email Notifications**: Kirim notifikasi via email juga
2. **Notification Preferences**: User bisa atur preferensi notifikasi
3. **Real-time Notifications**: Implementasi WebSocket untuk notifikasi real-time
4. **Notification Templates**: Buat template untuk berbagai jenis notifikasi
5. **Batch Processing**: Kirim notifikasi batch jika banyak item
6. **Archive Notifications**: Implement archive vs delete
7. **Notification History**: View notifikasi yang sudah dihapus
8. **Unread Notifications Badge**: Di berbagai halaman/sidebar
