@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold">Notifikasi</h1>
            @if($notifications->where('is_read', false)->count() > 0)
                <button class="btn btn-sm btn-outline" onclick="markAllAsRead()">
                    Tandai Semua Dibaca
                </button>
            @endif
        </div>
    </div>

    @if($notifications->isEmpty())
        <div class="alert alert-info">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-6 w-6 shrink-0 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Tidak ada notifikasi.</span>
        </div>
    @else
        <div class="space-y-4">
            @foreach($notifications as $notif)
                <div class="card bg-base-100 shadow-md hover:shadow-lg transition-shadow {{ !$notif->is_read ? 'border-l-4 border-blue-500' : '' }}">
                    <div class="card-body p-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <div class="badge badge-{{ $notif->type === 'success' ? 'success' : ($notif->type === 'danger' ? 'error' : ($notif->type === 'warning' ? 'warning' : 'info')) }}">
                                        <i class="fas {{ $notif->type_icon }} mr-1"></i>
                                        {{ ucfirst($notif->type) }}
                                    </div>
                                    <h2 class="card-title text-lg">{{ $notif->title }}</h2>
                                    @if(!$notif->is_read)
                                        <div class="badge badge-sm badge-blue">Baru</div>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mt-2">{{ $notif->message }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $notif->detail }}</p>
                                <p class="text-xs text-gray-400 mt-2">{{ $notif->created_at->format('d M Y H:i') }} ({{ $notif->time_ago }})</p>
                            </div>
                            <div class="flex gap-2">
                                @if($notif->link)
                                    <a href="{{ $notif->link }}" class="btn btn-sm btn-primary">
                                        Lihat Detail
                                    </a>
                                @endif
                                @if(!$notif->is_read)
                                    <button class="btn btn-sm btn-outline" onclick="markAsRead({{ $notif->id }})">
                                        Tandai Dibaca
                                    </button>
                                @endif
                                <button class="btn btn-sm btn-error" onclick="deleteNotification({{ $notif->id }})">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
function markAsRead(id) {
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteNotification(id) {
    if (confirm('Yakin ingin menghapus notifikasi ini?')) {
        fetch(`/notifications/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>
@endsection
