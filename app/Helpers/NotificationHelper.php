<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;

class NotificationHelper
{
    /**
     * Create notification for approval team when new pengajuan is submitted
     */
    public static function notifyApproversOnPengajuanSubmitted($pengajuan)
    {
        // Get all users with approval/atasan/admin role
        $approvers = User::whereIn('role', ['approval', 'atasan', 'admin'])->get();

        $title = 'Pengajuan Barang Baru';
        $message = 'Ada pengajuan barang baru dari ' . ($pengajuan->user->nama ?? 'User');
        $detail = 'Kode Pengajuan: ' . $pengajuan->kode_pengajuan . ' | Jumlah Item: ' . $pengajuan->jumlah;
        $type = 'info';
        $link = route('pengajuan.show', $pengajuan->id);

        foreach ($approvers as $approver) {
            Notification::create([
                'user_id' => $approver->id,
                'title' => $title,
                'message' => $message,
                'detail' => $detail,
                'type' => $type,
                'link' => $link,
                'is_read' => false
            ]);
        }
    }

    /**
     * Create notification when pengajuan is approved
     */
    public static function notifyApprovalDecision($pengajuan, $approved = true)
    {
        if (!$pengajuan->user_id) {
            return;
        }

        $title = $approved ? 'Pengajuan Disetujui' : 'Pengajuan Ditolak';
        $message = 'Pengajuan barang Anda telah ' . ($approved ? 'disetujui' : 'ditolak');
        $detail = 'Kode Pengajuan: ' . $pengajuan->kode_pengajuan;
        $type = $approved ? 'success' : 'danger';
        $link = route('pengajuan.show', $pengajuan->id);

        Notification::create([
            'user_id' => $pengajuan->user_id,
            'title' => $title,
            'message' => $message,
            'detail' => $detail,
            'type' => $type,
            'link' => $link,
            'is_read' => false
        ]);
    }
}
