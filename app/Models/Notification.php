<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'detail',
        'type',
        'link',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTimeAgoAttribute()
    {
        $diff = $this->created_at->diffInMinutes(now());
        
        if ($diff < 1) return 'Baru saja';
        if ($diff < 60) return intval($diff) . ' menit yang lalu';
        if ($diff < 1440) return intval(round($diff / 60)) . ' jam yang lalu';
        if ($diff < 43200) return intval(round($diff / 1440)) . ' hari yang lalu';
        
        return $this->created_at->format('d M Y H:i');
    }

    public function getTypeIconAttribute()
    {
        $icons = [
            'info' => 'fa-info-circle',
            'success' => 'fa-check-circle',
            'warning' => 'fa-exclamation-triangle',
            'danger' => 'fa-times-circle'
        ];
        
        return $icons[$this->type] ?? 'fa-bell';
    }

    public function getTypeColorAttribute()
    {
        $colors = [
            'info' => '#0d6efd',
            'success' => '#198754',
            'warning' => '#ffc107',
            'danger' => '#dc3545'
        ];
        
        return $colors[$this->type] ?? '#6c757d';
    }
}