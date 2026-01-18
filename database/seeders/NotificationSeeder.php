<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        $user = User::first(); // Ambil user pertama
        
        if (!$user) {
            $this->command->error('User tidak ditemukan. Buat user terlebih dahulu.');
            return;
        }

        $notifications = [
            [
                'title' => 'Pesanan Diterima',
                'message' => 'Pesanan #12345 telah berhasil diterima dan sedang diproses',
                'detail' => "Terima kasih telah berbelanja di Gudit!\n\nPesanan Anda dengan nomor #12345 telah kami terima dan saat ini sedang dalam proses verifikasi pembayaran.\n\nDetail Pesanan:\n- Laptop ASUS ROG (1x)\n- Mouse Gaming Logitech (1x)\n- Keyboard Mechanical (1x)\n\nTotal: Rp 15.500.000\n\nPesanan Anda akan segera diproses setelah pembayaran terverifikasi. Estimasi pengiriman 2-3 hari kerja.",
                'type' => 'success',
                'link' => '/orders/12345',
                'is_read' => false,
            ],
            [
                'title' => 'Promo Spesial Akhir Tahun',
                'message' => 'Dapatkan diskon hingga 50% untuk produk elektronik pilihan',
                'detail' => "🎉 PROMO SPESIAL AKHIR TAHUN 🎉\n\nJangan lewatkan kesempatan emas ini!\n\nDiskon hingga 50% untuk:\n✓ Laptop & Komputer\n✓ Smartphone & Tablet\n✓ Aksesoris Gaming\n✓ Smart Home Devices\n\nPeriode promo: 1-31 Januari 2026\n\nGunakan kode: TAHUNBARU2026\n\nBelanja sekarang dan hemat lebih banyak!",
                'type' => 'info',
                'link' => '/promo/tahun-baru',
                'is_read' => false,
            ],
            [
                'title' => 'Update Pengiriman',
                'message' => 'Paket Anda sedang dalam perjalanan',
                'detail' => "Status Pengiriman Terkini:\n\nNomor Resi: JNE123456789\nKurir: JNE Regular\n\nStatus: Paket sedang dalam perjalanan menuju alamat tujuan\nLokasi terakhir: Hub Jakarta Pusat\nEstimasi tiba: Besok, 7 Januari 2026\n\nAlamat pengiriman:\nJl. Sudirman No. 123\nJakarta Pusat, DKI Jakarta\n\nPaket akan diantar oleh kurir JNE. Pastikan ada yang menerima di alamat tujuan.",
                'type' => 'info',
                'link' => '/tracking/JNE123456789',
                'is_read' => false,
            ],
            [
                'title' => 'Pembayaran Berhasil',
                'message' => 'Pembayaran untuk pesanan #12344 telah berhasil diverifikasi',
                'detail' => "Pembayaran Terverifikasi ✓\n\nNomor Pesanan: #12344\nMetode Pembayaran: Transfer Bank BCA\nJumlah: Rp 8.750.000\nWaktu Pembayaran: 5 Januari 2026, 14:30 WIB\n\nPembayaran Anda telah kami terima dan diverifikasi oleh sistem.\n\nPesanan Anda akan segera diproses dan dikirim dalam 1x24 jam.\n\nTerima kasih atas kepercayaan Anda berbelanja di Gudit!",
                'type' => 'success',
                'link' => '/orders/12344',
                'is_read' => true,
            ],
            [
                'title' => 'Peringatan: Stok Terbatas',
                'message' => 'Produk di wishlist Anda hampir habis',
                'detail' => "⚠️ PERINGATAN STOK TERBATAS\n\nProduk yang ada di wishlist Anda saat ini memiliki stok yang sangat terbatas:\n\n1. iPhone 15 Pro Max - Tersisa 3 unit\n2. Samsung Galaxy S24 Ultra - Tersisa 5 unit\n3. MacBook Pro M3 - Tersisa 2 unit\n\nBuruan checkout sebelum kehabisan!\n\nKlik tombol di bawah untuk melihat wishlist Anda.",
                'type' => 'warning',
                'link' => '/wishlist',
                'is_read' => true,
            ],
        ];

        foreach ($notifications as $notif) {
            Notification::create(array_merge($notif, ['user_id' => $user->id]));
        }

        $this->command->info('✅ Notifikasi berhasil ditambahkan!');
    }
}