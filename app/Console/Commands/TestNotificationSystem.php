<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Pengajuan;
use App\Models\Notification;
use App\Helpers\NotificationHelper;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TestNotificationSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notifications {action=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test notification system (all|create|approve|reject|cleanup)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        match($action) {
            'all' => $this->testAll(),
            'create' => $this->testCreateNotification(),
            'approve' => $this->testApproveNotification(),
            'reject' => $this->testRejectNotification(),
            'cleanup' => $this->testCleanup(),
            default => $this->info('Available actions: all, create, approve, reject, cleanup')
        };
    }

    private function testAll()
    {
        $this->info('=== Testing Notification System ===');
        
        // Get users
        $pengaju = User::where('role', 'member')->orWhereNull('role')->first();
        $approver = User::whereIn('role', ['approval', 'atasan', 'admin'])->first();

        if (!$pengaju || !$approver) {
            $this->error('❌ Minimal perlu 1 user dengan role member dan 1 user dengan role approval/atasan/admin');
            return;
        }

        $this->info("👤 Pengaju: {$pengaju->nama} (ID: {$pengaju->id})");
        $this->info("👤 Approver: {$approver->nama} (ID: {$approver->id})");
        $this->newLine();

        // Test 1: Create Pengajuan
        $this->testCreateNotification($pengaju, $approver);
        $this->newLine();

        // Test 2: Approve Pengajuan
        $this->testApproveNotification($pengaju, $approver);
        $this->newLine();

        // Test 3: Reject Pengajuan
        $this->testRejectNotification($pengaju, $approver);
        $this->newLine();

        $this->info('✅ All tests completed!');
    }

    private function testCreateNotification($pengaju = null, $approver = null)
    {
        if (!$pengaju) $pengaju = User::first();
        if (!$approver) $approver = User::whereIn('role', ['approval', 'atasan', 'admin'])->first();

        $this->info('📝 Test 1: Create Pengajuan & Notify Approvers');

        // Create test pengajuan
        $pengajuan = Pengajuan::create([
            'kode_pengajuan' => 'TEST' . Str::upper(Str::random(8)),
            'user_id' => $pengaju->id,
            'kode_gudang' => 'GD001',
            'jumlah' => 5,
            'tanggal' => Carbon::now()->toDateString(),
            'status' => 'pending',
        ]);

        $this->info("  ✓ Pengajuan created: {$pengajuan->kode_pengajuan}");

        // Send notification
        NotificationHelper::notifyApproversOnPengajuanSubmitted($pengajuan);

        $notif = Notification::where('title', 'Pengajuan Barang Baru')->latest()->first();
        if ($notif && $notif->type === 'info') {
            $this->info("  ✓ Notification created: {$notif->title}");
            $this->info("  ✓ Type: {$notif->type} (info)");
            $this->info("  ✓ Recipient: User ID {$notif->user_id}");
        } else {
            $this->error('  ❌ Notification not created');
        }

        return $pengajuan;
    }

    private function testApproveNotification($pengaju = null, $approver = null)
    {
        if (!$pengaju) $pengaju = User::first();

        $this->info('✅ Test 2: Approve Pengajuan & Notify Pengaju');

        // Get latest test pengajuan
        $pengajuan = Pengajuan::where('status', 'pending')->latest()->first();
        if (!$pengajuan) {
            $this->error('  ❌ No pending pengajuan found');
            return;
        }

        $this->info("  ✓ Pengajuan: {$pengajuan->kode_pengajuan}");

        // Simulate approval
        $pengajuan->update(['status' => 'approved']);
        NotificationHelper::notifyApprovalDecision($pengajuan, true);

        $notif = Notification::where('user_id', $pengajuan->user_id)
                             ->where('title', 'Pengajuan Disetujui')
                             ->latest()
                             ->first();

        if ($notif && $notif->type === 'success') {
            $this->info("  ✓ Notification created: {$notif->title}");
            $this->info("  ✓ Type: {$notif->type} (success)");
            $this->info("  ✓ Sent to: User ID {$notif->user_id}");
        } else {
            $this->error('  ❌ Notification not created');
        }

        return $pengajuan;
    }

    private function testRejectNotification($pengaju = null, $approver = null)
    {
        if (!$pengaju) $pengaju = User::first();

        $this->info('❌ Test 3: Reject Pengajuan & Notify Pengaju');

        // Create new pengajuan for rejection test
        $pengajuan = Pengajuan::create([
            'kode_pengajuan' => 'TEST' . Str::upper(Str::random(8)),
            'user_id' => $pengaju->id,
            'kode_gudang' => 'GD001',
            'jumlah' => 3,
            'tanggal' => Carbon::now()->toDateString(),
            'status' => 'pending',
        ]);

        $this->info("  ✓ Pengajuan created: {$pengajuan->kode_pengajuan}");

        // Simulate rejection
        $pengajuan->update(['status' => 'rejected', 'note' => 'Stok tidak cukup']);
        NotificationHelper::notifyApprovalDecision($pengajuan, false);

        $notif = Notification::where('user_id', $pengajuan->user_id)
                             ->where('title', 'Pengajuan Ditolak')
                             ->latest()
                             ->first();

        if ($notif && $notif->type === 'danger') {
            $this->info("  ✓ Notification created: {$notif->title}");
            $this->info("  ✓ Type: {$notif->type} (danger)");
            $this->info("  ✓ Sent to: User ID {$notif->user_id}");
        } else {
            $this->error('  ❌ Notification not created');
        }

        return $pengajuan;
    }

    private function testCleanup()
    {
        $this->info('🧹 Cleaning up test data...');

        $deleted_pengajuan = Pengajuan::where('kode_pengajuan', 'like', 'TEST%')->delete();
        $deleted_notif = Notification::where('title', 'like', '%Test%')->delete();

        $this->info("  ✓ Deleted {$deleted_pengajuan} test pengajuan");
        $this->info("  ✓ Deleted {$deleted_notif} test notifications");
        $this->info('✅ Cleanup completed!');
    }
}
