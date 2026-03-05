<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user
        $user = User::first();
        
        if ($user) {
            // Create sample notifications
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Pengajuan Barang Baru',
                'message' => 'Ada pengajuan barang baru dari John Doe',
                'detail' => 'Barang: Printer HP (3 pcs), Monitor Samsung (2 pcs)',
                'type' => 'info',
                'link' => '/pengajuan/1/detail',
                'is_read' => false
            ]);

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Pengajuan Disetujui',
                'message' => 'Pengajuan barang Anda telah disetujui',
                'detail' => 'Stok sudah dikurangi',
                'type' => 'success',
                'link' => '/pengajuan/2/detail',
                'is_read' => false
            ]);

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Pengajuan Ditolak',
                'message' => 'Pengajuan barang Anda telah ditolak',
                'detail' => 'Alasan: Stok tidak cukup',
                'type' => 'danger',
                'link' => '/pengajuan/3/detail',
                'is_read' => true
            ]);

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Barang Masuk',
                'message' => 'Barang baru telah masuk ke gudang',
                'detail' => 'Barang: Printer HP | Jumlah: 10 unit',
                'type' => 'success',
                'link' => '/barang-masuk',
                'is_read' => true
            ]);

            echo "Test notifications created successfully!\n";
        } else {
            echo "No users found. Please create a user first.\n";
        }
    }
}
