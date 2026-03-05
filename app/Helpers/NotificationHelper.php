<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\Pengajuan;
use App\Models\User;

class NotificationHelper
{
    /**
     * Create notification for approval team when new pengajuan is submitted
     */
    public static function notifyApproversOnPengajuanSubmitted($pengajuan)
    {
        // Get all users with approval/admin/admin role
        $approvers = User::whereIn('role', ['approval', 'admin'])->get();

        $title = 'Pengajuan Barang Baru';
        $message = 'Ada pengajuan barang baru dari ' . ($pengajuan->user->nama ?? 'User');
        
        // Get barang details
        $details = $pengajuan->details()->with('barang')->get();
        $barangList = $details->map(function($d) {
            return $d->barang->nama_barang . ' (' . $d->jumlah . ' pcs)';
        })->implode(', ');
        
        $detail = 'Barang: ' . $barangList;
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
     * Create notification when pengajuan is approved (fully or partially)
     */
    public static function notifyApprovalDecision($pengajuan, $approved = true, $totalApproved = 0, $totalRejected = 0, $customMessage = null)
    {
        // ensure we have an Eloquent model so relations can be called
        if (!is_object($pengajuan) || !method_exists($pengajuan, 'details')) {
            if (is_object($pengajuan) && isset($pengajuan->id)) {
                $pengajuan = Pengajuan::find($pengajuan->id);
            } else {
                $pengajuan = null;
            }
        }

        if (!$pengajuan || !$pengajuan->user_id) {
            return;
        }

        $title = $approved ? 'Pengajuan Diproses' : 'Pengajuan Ditolak';
        
        if ($totalApproved > 0 && $totalRejected > 0) {
            // Partial approval
            $message = 'Pengajuan barang Anda disetujui sebagian: ' . $totalApproved . ' disetujui, ' . $totalRejected . ' ditolak';
            $type = 'warning';
        } elseif ($totalApproved > 0) {
            // Full approval
            $message = 'Pengajuan barang Anda telah disetujui seluruhnya';
            $type = 'success';
        } else {
            // Full rejection
            $message = 'Pengajuan barang Anda telah ditolak seluruhnya';
            $type = 'danger';
        }

        $customMessage = is_string($customMessage) ? trim($customMessage) : '';
        if ($customMessage !== '') {
            $message = $customMessage;
        }
        
        // build list of barang involved in the pengajuan so user can see what was requested
        $details = $pengajuan->details()->with('barang')->get();
        $barangList = $details->map(function($d) {
            return $d->barang->nama_barang . ' (' . $d->jumlah . ' pcs)';
        })->implode(', ');

        // build detail field without kode pengajuan: only barang list
        $detail = '';
        if ($barangList !== '') {
            $detail .= 'Barang: ' . $barangList;
        }
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
