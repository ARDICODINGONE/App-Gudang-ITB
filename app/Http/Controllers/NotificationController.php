<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // HAPUS constructor ini
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->notifications()
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function getRecent()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $unreadCount = Auth::user()->notifications()
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications->map(function($notif) {
                return [
                    'id' => $notif->id,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'detail' => $notif->detail,
                    'type' => $notif->type,
                    'type_icon' => $notif->type_icon,
                    'type_color' => $notif->type_color,
                    'link' => $notif->link,
                    'time' => $notif->time_ago,
                    'created_at' => $notif->created_at->format('d M Y, H:i'),
                    'is_read' => $notif->is_read,
                ];
            }),
            'unread_count' => $unreadCount
        ]);
    }

    public function getDetail($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        return response()->json([
            'id' => $notification->id,
            'title' => $notification->title,
            'message' => $notification->message,
            'detail' => $notification->detail,
            'type' => $notification->type,
            'type_icon' => $notification->type_icon,
            'type_color' => $notification->type_color,
            'link' => $notification->link,
            'time' => $notification->time_ago,
            'created_at' => $notification->created_at->format('d M Y, H:i'),
            'is_read' => $notification->is_read,
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json(['success' => true]);
    }
}