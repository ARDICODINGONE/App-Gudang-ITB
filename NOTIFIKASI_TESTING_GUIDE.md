# 🎯 PANDUAN LENGKAP SISTEM NOTIFIKASI PENGAJUAN BARANG

## 📌 Quick Start

### 1. Verifikasi Setup
```bash
# Pastikan database sudah migrate
php artisan migrate

# Verify migrations status
php artisan migrate:status
```

### 2. Testing dengan Command
```bash
# Test semua scenario
php artisan test:notifications all

# Test individual scenario
php artisan test:notifications create    # Test create pengajuan
php artisan test:notifications approve   # Test approve notification
php artisan test:notifications reject    # Test reject notification

# Cleanup test data
php artisan test:notifications cleanup
```

### 3. Manual Testing via Web Interface

#### Step 1: Create Pengajuan
1. Login sebagai user biasa
2. Go to `/pengajuan` (Pengajuan page)
3. Select warehouse dan pilih items
4. Click submit

**Expected**: Admin/approval menerima notifikasi di notification bell

#### Step 2: Approve Pengajuan
1. Login sebagai admin/approval
2. Go to `/pengajuan/list` 
3. Pilih pengajuan dengan status 'pending'
4. Click 'Approve' button

**Expected**: User yang mengajukan menerima notifikasi success

#### Step 3: Reject Pengajuan
1. Login sebagai admin/approval
2. Go to `/pengajuan/list`
3. Pilih pengajuan dengan status 'pending'
4. Click 'Reject' button
5. Isi note (opsional)

**Expected**: User yang mengajukan menerima notifikasi danger

---

## 🔍 Debugging

### Check Database
```bash
# List semua notifications
php artisan tinker
>>> App\Models\Notification::latest()->limit(10)->get()

# Check pengajuan & user_id
>>> App\Models\Pengajuan::latest()->limit(5)->with('user')->get()

# Check user roles
>>> App\Models\User::pluck('nama', 'role')
```

### View Logs
```bash
# Check laravel logs
tail -f storage/logs/laravel.log
```

### Verify Routes
```bash
# List notification routes
php artisan route:list --name=notification
```

---

## 📱 UI Features Checklist

### Notification Bell in Navbar
- [x] Bell icon visible in navbar
- [x] Red badge shows unread count
- [x] Badge disappears when no unread notifications
- [x] Click opens side panel

### Side Panel
- [x] Shows 20 recent notifications
- [x] Unread items highlighted with blue left border
- [x] Click item shows detail
- [x] "Mark all as read" button works
- [x] Close button (X) works
- [x] Click overlay to close

### Notification Detail
- [x] Show full notification details
- [x] Display type badge with color/icon
- [x] Show timestamp
- [x] "Lihat Detail" button (if have link)
- [x] Delete button with confirmation
- [x] Automatically mark as read when opened

### Full Notification Page (/notifications)
- [x] View all notifications
- [x] Filter by type (optional)
- [x] Mark as read/Mark all as read
- [x] Delete with confirmation
- [x] Pagination (jika banyak)
- [x] Responsive on mobile

---

## 🎨 Notification Types & Styling

### Type: info (Blue #0d6efd)
- Used for: Pengajuan baru diterima
- Badge text: "Info"
- Icon: fa-info-circle

### Type: success (Green #198754)
- Used for: Pengajuan disetujui
- Badge text: "Sukses"
- Icon: fa-check-circle

### Type: warning (Yellow #ffc107)
- Used for: Warning/Caution messages
- Badge text: "Peringatan"
- Icon: fa-exclamation-triangle

### Type: danger (Red #dc3545)
- Used for: Pengajuan ditolak
- Badge text: "Penting"
- Icon: fa-times-circle

---

## 🔐 Permission & Access Control

### Who receives "Pengajuan Barang Baru" notification?
```php
Users with role:
- 'approval'  (Approval staff)
- 'atasan'    (Manager/Supervisor)
- 'admin'     (Administrator)
```

### Who receives "Pengajuan Disetujui/Ditolak" notification?
```php
User with id = pengajuan->user_id (the requester)
```

### To modify roles:
Edit `NotificationHelper.php` line:
```php
User::whereIn('role', ['approval', 'atasan', 'admin'])->get()
```

---

## 🛠️ Customization Guide

### Change Notification Message
Location: `app/Helpers/NotificationHelper.php`

```php
// Before
$message = 'Ada pengajuan barang baru dari ' . ($pengajuan->user->nama ?? 'User');

// After (customize as needed)
$message = 'Pengajuan baru dari ' . ($pengajuan->user->nama ?? 'Unknown');
```

### Change Notification Type Color
Location: `resources/views/layouts/navbar.blade.php` or `notifications/index.blade.php`

```js
const colors = {
    info: '#0d6efd',     // Blue
    success: '#198754',  // Green
    warning: '#ffc107',  // Yellow
    danger: '#dc3545'    // Red
}
```

### Add New Notification Type
1. Add new type case in `NotificationHelper.php`
2. Update color mapping in views
3. Update badge styling

### Modify Notification Link
Location: `app/Helpers/NotificationHelper.php`

```php
$link = route('pengajuan.show', $pengajuan->id); // Change route as needed
```

---

## 📊 Database Schema Reference

### notifications table
```sql
CREATE TABLE notifications (
  id BIGINT PRIMARY KEY,
  user_id BIGINT,
  title VARCHAR(255),
  message TEXT,
  detail TEXT,
  type VARCHAR(50),
  link VARCHAR(255),
  is_read BOOLEAN,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
)
```

### pengajuan table
```sql
CREATE TABLE pengajuan (
  id BIGINT PRIMARY KEY,
  kode_pengajuan VARCHAR(255) UNIQUE,
  user_id BIGINT,
  barang_id BIGINT,
  kode_gudang VARCHAR(255),
  jumlah INT,
  tanggal DATE,
  status VARCHAR(50),
  note TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
)
```

---

## 🐛 Common Issues & Solutions

### Issue 1: Notification tidak muncul di navbar
**Solution**:
1. Verify user sudah login: `Auth::check()`
2. Check database: `Notification::where('user_id', auth()->id())->count()`
3. Clear cache: `php artisan cache:clear`
4. Check navbar script di `resources/views/layouts/navbar.blade.php`

### Issue 2: Badge count tidak update
**Solution**:
1. Check CSRF token in navbar
2. Verify `/notifications/unread-count` endpoint works
3. Check browser console for JS errors (F12)
4. Try refresh page

### Issue 3: Notification tidak terkirim saat approve/reject
**Solution**:
1. Verify `NotificationHelper` import di `PengajuanController`
2. Check user_id di pengajuan record (bisa null?)
3. Verify user dengan id tersebut ada di database
4. Check logs: `tail -f storage/logs/laravel.log`

### Issue 4: Detail notifikasi tidak muncul saat diklik
**Solution**:
1. Check browser console (F12) untuk JS errors
2. Verify `/notifications/{id}/detail` API returns valid JSON
3. Clear browser cache (Ctrl+Shift+Del)
4. Try refresh page

### Issue 5: Database transaction error saat create pengajuan
**Solution**:
1. Ensure database connection working: `php artisan tinker`
2. Check foreign key constraints
3. Verify table pengajuan_detail exists
4. Run migrations: `php artisan migrate`

---

## 📈 Performance Optimization

### 1. Database Indexes
```sql
-- Add indexes untuk faster queries
ALTER TABLE notifications ADD INDEX idx_user_id (user_id);
ALTER TABLE notifications ADD INDEX idx_is_read (is_read);
ALTER TABLE notifications ADD INDEX idx_created_at (created_at);
ALTER TABLE pengajuan ADD INDEX idx_user_id (user_id);
ALTER TABLE pengajuan ADD INDEX idx_status (status);
```

### 2. Pagination Limit
Modify di `NotificationController.php`:
```php
->limit(20)  // Change to your preferred limit
```

### 3. Eager Loading
```php
// Optimize queries
Notification::with('user')->where(...)->get()
Pengajuan::with('user', 'details')->where(...)->get()
```

---

## 🚀 Deployment Checklist

- [ ] Run migrations: `php artisan migrate --force`
- [ ] Clear cache: `php artisan cache:clear`
- [ ] Optimize: `php artisan optimize`
- [ ] Test notification flow dalam production
- [ ] Monitor logs untuk errors
- [ ] Backup database sebelum production
- [ ] Test on different browsers & devices

---

## 📞 Support Information

### Files Modified
- `app/Http/Controllers/PengajuanController.php`
- `routes/web.php`

### Files Created
- `app/Models/Pengajuan.php`
- `app/Models/PengajuanDetail.php`
- `app/Helpers/NotificationHelper.php`
- `app/Console/Commands/TestNotificationSystem.php`
- `resources/views/notifications/index.blade.php`
- `database/seeders/NotificationTestSeeder.php`

### Documentation Files
- `NOTIFIKASI_PENGAJUAN_BARANG.md`
- `NOTIFIKASI_IMPLEMENTATION_SUMMARY.md`
- `NOTIFIKASI_TESTING_GUIDE.md` (this file)

---

## ✅ Final Verification

Run this checklist to ensure everything is working:

```bash
# 1. Database check
php artisan migrate:status

# 2. Model check
php artisan tinker
>>> App\Models\Pengajuan::count()
>>> App\Models\Notification::count()

# 3. Route check
php artisan route:list | grep notification

# 4. File check
ls app/Models/Pengajuan.php
ls app/Helpers/NotificationHelper.php
ls resources/views/notifications/index.blade.php

# 5. Test functionality
php artisan test:notifications all
```

If all passes, system is ready! ✅

---

**Last Updated**: 29 Januari 2026  
**Version**: 1.0  
**Status**: ✅ Production Ready
