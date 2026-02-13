<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Gudit - Gudang Digital</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{--primary:#2563eb;--primary-light:#eff6ff;--text-dark:#0f172a;--text-muted:#64748b;--danger:#ef4444;--success:#10b981;--warning:#f59e0b}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:#f8fafc;padding-top:76px}
        .navbar-gudit{background:rgba(255,255,255,.98);backdrop-filter:blur(10px);border-bottom:1px solid #e2e8f0;height:76px;box-shadow:0 4px 20px rgba(0,0,0,.05);padding:0!important}
        .navbar-gudit .container-xl{max-width:100%;padding:0 24px}
        .navbar-gudit.scrolled{box-shadow:0 8px 30px rgba(0,0,0,.08)}
        .navbar-brand{font-weight:800;font-size:1.5rem;color:var(--primary)!important;display:flex;flex-direction:column;padding:8px 0;text-decoration:none;pointer-events:none;line-height:1.2}
        .navbar-brand .brand-subtitle{font-size:.85rem;font-weight:600;color:var(--text-muted)}
        .actions-section{display:flex;align-items:center;gap:8px}
        .nav-action-icon{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;color:var(--text-muted);background:#fff;border:1px solid #e2e8f0;position:relative;transition:all .3s;text-decoration:none;font-size:1.1rem}
        .nav-action-icon:hover,.nav-action-icon.active{color:var(--primary);background:var(--primary-light);border-color:var(--primary);transform:translateY(-2px);box-shadow:0 6px 16px rgba(37,99,235,.15)}
        .badge-count{position:absolute;top:-6px;right:-6px;background:var(--danger);color:#fff;font-size:.7rem;font-weight:700;padding:3px 8px;border-radius:10px;border:2px solid #fff;min-width:20px;box-shadow:0 2px 8px rgba(239,68,68,.3)}
        .cart .badge-count{background:var(--warning)}
        .user-area{display:flex;align-items:center;gap:12px;margin-left:8px}
        .user-profile{display:flex;align-items:center;gap:12px;padding:8px 16px 8px 12px;background:#fff;border:1px solid #e2e8f0;border-radius:12px;text-decoration:none;color:var(--text-dark);font-weight:600;transition:all .3s;min-width:180px}
        .user-profile:hover{background:var(--primary-light);border-color:var(--primary);transform:translateY(-1px);box-shadow:0 6px 16px rgba(37,99,235,.1)}
        .user-avatar{width:40px;height:40px;background:linear-gradient(135deg,var(--primary),#60a5fa);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:600;box-shadow:0 4px 12px rgba(37,99,235,.25);overflow:hidden}
        .user-avatar img{width:100%;height:100%;object-fit:cover}
        .user-info{flex:1;min-width:0}
        .user-name{font-size:.9rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .user-role{font-size:.75rem;color:var(--text-muted);font-weight:500}
        .user-dropdown{color:var(--text-muted);transition:all .3s}
        .user-profile:hover .user-dropdown{color:var(--primary);transform:translateX(2px)}
        .user-dropdown-menu{position:absolute;top:calc(100% + 10px);right:0;width:260px;background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,.12);opacity:0;visibility:hidden;transform:translateY(-10px);transition:all .3s;z-index:1000;padding:8px 0}
        .user-dropdown-menu.show{opacity:1;visibility:visible;transform:translateY(0)}
        .dropdown-header{padding:12px 16px;border-bottom:1px solid #f1f5f9;margin-bottom:8px}
        .dropdown-header .user-email{font-size:.8rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .dropdown-item{display:flex;align-items:center;gap:12px;padding:12px 16px;color:var(--text-dark);text-decoration:none;transition:all .3s;border-left:3px solid transparent}
        .dropdown-item:hover{background:var(--primary-light);border-left-color:var(--primary);color:var(--primary)}
        .dropdown-item i{width:20px;text-align:center}
        .dropdown-divider{margin:8px 0;border-top:1px solid #f1f5f9}
        .dropdown-logout{color:var(--danger)!important}
        .dropdown-logout:hover{background:#fef2f2!important}
        .btn-login{background:linear-gradient(135deg,var(--primary),#3b82f6);color:#fff!important;padding:10px 24px;border-radius:50px;font-weight:700;text-decoration:none;box-shadow:0 4px 15px rgba(37,99,235,.25);transition:all .3s;display:inline-flex;align-items:center;gap:10px}
        .btn-login:hover{background:linear-gradient(135deg,#1d4ed8,#2563eb);transform:translateY(-2px);box-shadow:0 8px 20px rgba(37,99,235,.35)}
        .notification-overlay{position:fixed;inset:0;background:rgba(15,23,42,.3);backdrop-filter:blur(4px);z-index:1040;opacity:0;visibility:hidden;transition:.3s}
        .notification-overlay.show{opacity:1;visibility:visible}
        .side-panel{position:fixed;top:0;right:0;height:100vh;background:#fff;z-index:1050;box-shadow:-5px 0 30px rgba(0,0,0,.1);transform:translateX(100%);transition:transform .3s;display:flex;flex-direction:column;width:420px;max-width:100%}
        .side-panel.show{transform:translateX(0)}
        .panel-header{padding:20px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;background:var(--primary-light)}
        .panel-header h5{margin:0;font-weight:700;color:var(--primary)}
        .btn-close-panel{background:none;border:none;color:#94a3b8;font-size:1.2rem;cursor:pointer;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;transition:all .3s}
        .btn-close-panel:hover{background:#f8fafc;color:var(--danger)}
        .notif-list{flex:1;overflow-y:auto}
        .notif-item{padding:16px 20px;border-bottom:1px solid #f1f5f9;cursor:pointer;transition:.2s;position:relative}
        .notif-item:hover{background:#f8fafc}
        .notif-item.unread{background:#f0f9ff;border-left:4px solid var(--primary)}
        .notif-item.unread::before{content:'';position:absolute;left:6px;top:50%;transform:translateY(-50%);width:8px;height:8px;background:var(--primary);border-radius:50%}
        .notif-type-badge{display:inline-block;padding:2px 8px;border-radius:4px;font-size:.7rem;font-weight:600;margin-right:8px;margin-bottom:5px}
        .notif-type-badge.info{background:#e7f1ff;color:var(--primary)}
        .notif-type-badge.success{background:#e7f7f2;color:var(--success)}
        .notif-type-badge.warning{background:#fff7e6;color:var(--warning)}
        .notif-type-badge.danger{background:#fef2f2;color:var(--danger)}
        .notif-title{display:block;font-size:.95rem;margin-bottom:4px;font-weight:600;color:var(--text-dark)}
        .notif-item.unread .notif-title{color:var(--primary)}
        .notif-msg{font-size:.85rem;color:var(--text-muted);line-height:1.4;margin-bottom:8px}
        .notif-time{font-size:.75rem;color:#94a3b8;display:flex;align-items:center;gap:4px}
        .notif-empty{text-align:center;padding:60px 20px;color:var(--text-muted)}
        .notif-empty i{font-size:3rem;margin-bottom:20px;opacity:.3}
        .confirm-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);backdrop-filter:blur(4px);z-index:2000;display:none;align-items:center;justify-content:center}
        .confirm-overlay.show{display:flex}
        .confirm-dialog{background:#fff;border-radius:16px;padding:24px;width:90%;max-width:400px;box-shadow:0 20px 60px rgba(0,0,0,.3);animation:slideUp .3s}
        @keyframes slideUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
        .confirm-icon{width:56px;height:56px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;color:var(--danger);font-size:1.5rem}
        .confirm-title{font-size:1.25rem;font-weight:700;text-align:center;margin-bottom:8px;color:var(--text-dark)}
        .confirm-message{font-size:.95rem;text-align:center;color:var(--text-muted);margin-bottom:24px;line-height:1.5}
        .confirm-actions{display:flex;gap:12px}
        .confirm-btn{flex:1;padding:12px;border-radius:12px;font-weight:600;border:none;cursor:pointer;transition:all .3s;font-size:.95rem}
        .confirm-btn-cancel{background:#f1f5f9;color:var(--text-dark)}
        .confirm-btn-cancel:hover{background:#e2e8f0}
        .confirm-btn-delete{background:var(--danger);color:#fff}
        .confirm-btn-delete:hover{background:#dc2626;transform:translateY(-2px);box-shadow:0 4px 12px rgba(239,68,68,.3)}
        @media(max-width:991.98px){
            body{padding-top:64px}
            .navbar-gudit{height:64px}
            .navbar-gudit .container-xl{padding:0 16px}
            .navbar-brand{font-size:1.3rem}
            .navbar-brand .brand-subtitle{font-size:.75rem}
            .navbar-collapse{position:absolute;top:100%;left:0;right:0;background:#fff;box-shadow:0 8px 30px rgba(0,0,0,.15);border-top:1px solid #e2e8f0;max-height:0;overflow:hidden;transition:max-height .3s}
            .navbar-collapse.show{max-height:600px}
            .actions-section{padding:16px;border-bottom:1px solid #f1f5f9;justify-content:flex-start;margin:0;flex-wrap:nowrap;gap:12px}
            .user-area{padding:16px;flex-direction:column;align-items:stretch;margin:0;gap:0}
            .user-profile{width:100%;min-width:auto}
            .user-dropdown-menu{position:static;width:100%;box-shadow:none;border:none;border-top:1px solid #f1f5f9;opacity:1;visibility:visible;transform:none;padding:0;max-height:0;overflow:hidden;transition:max-height .3s}
            .user-dropdown-menu.show{max-height:500px}
            .auth-buttons{width:100%}
            .btn-login{width:100%;justify-content:center}
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-gudit fixed-top">
    <div class="container-xl">
        <a href="{{ url('/fojdsf') }}" class="navbar-brand">
            <span>GUDIT</span>
            <span class="brand-subtitle">Gudang Digital</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
            <i class="fas fa-bars text-dark fs-4"></i>
        </button>
        <div class="collapse navbar-collapse" id="navContent">
            <div class="actions-section ms-auto">
                <a href="{{ url('/') }}" class="nav-action-icon {{ Request::is('/') ? 'active' : '' }}" title="Beranda">
                    <i class="fas fa-home"></i>
                </a>
                @auth
                @if(auth()->user()->role !== 'approval')
                @php $cartCount = session('cart_count', 0); @endphp
                <a href="{{ url('/cart') }}" class="nav-action-icon cart {{ Request::is('cart') ? 'active' : '' }}" title="Keranjang">
                    <i class="fas fa-shopping-cart"></i>
                    @if($cartCount > 0)<span class="badge-count">{{ $cartCount }}</span>@endif
                </a>
                @endif
                @endauth
                <a href="{{ url('/pengajuan/list') }}" class="nav-action-icon {{ Request::is('pengajuan*') ? 'active' : '' }}" title="Pengajuan">
                    <i class="fas fa-paper-plane"></i>
                </a>
                @auth
                @if(method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin'))
                <a href="{{ url('/user') }}" class="nav-action-icon {{ Request::is('user*') ? 'active' : '' }}" title="User">
                    <i class="fas fa-user"></i>
                </a>
                @endif
                @endauth
                @auth
                <a href="#" class="nav-action-icon" id="notificationBtn" title="Notifikasi">
                    <i class="fas fa-bell"></i>
                    @php $unreadCount = class_exists('App\Models\Notification') ? \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count() : 0; @endphp
                    @if($unreadCount > 0)<span class="badge-count" id="notifBadge">{{ $unreadCount }}</span>@endif
                </a>
                @endauth
            </div>
            <div class="user-area">
                @auth
                <div class="user-profile-container position-relative">
                    <a href="#" class="user-profile" id="userToggle">
                        <div class="user-avatar">
                            @if(auth()->user()->avatar ?? false)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar">
                            @else
                            {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->name ?? 'U', 0, 1)) }}
                            @endif
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ Str::limit(auth()->user()->nama ?? auth()->user()->name ?? 'User', 20) }}</div>
                            <div class="user-role">
                                @php
                                $role = 'Member';
                                if (method_exists(auth()->user(), 'hasRole')) {
                                    if (auth()->user()->hasRole('admin')) $role = 'Administrator';
                                    elseif (auth()->user()->hasRole('staff')) $role = 'Staff';
                                }
                                @endphp
                                {{ $role }}
                            </div>
                        </div>
                        <div class="user-dropdown"><i class="fas fa-chevron-down"></i></div>
                    </a>
                    <div class="user-dropdown-menu" id="userMenu">
                        <div class="dropdown-header">
                            <div class="user-name">{{ auth()->user()->nama ?? auth()->user()->name ?? 'User' }}</div>
                            <div class="user-email">{{ auth()->user()->email ?? 'user@example.com' }}</div>
                        </div>
                        @if($role === 'Administrator')
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.dashboard') ?? '#' }}" class="dropdown-item">
                            <i class="fas fa-shield-alt"></i><span>Admin Dashboard</span>
                        </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item dropdown-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i><span>Keluar</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
                    </div>
                </div>
                @endauth
                @guest
                <div class="auth-buttons">
                    <a href="{{ route('login') }}" class="btn-login"><i class="fas fa-sign-in-alt"></i>Masuk Akun</a>
                </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

@auth
<div class="notification-overlay" id="overlay"></div>
<div class="side-panel" id="panelList">
    <div class="panel-header">
        <h5><i class="fas fa-bell me-2"></i> Notifikasi</h5>
        <button class="btn-close-panel" id="closeList"><i class="fas fa-times"></i></button>
    </div>
    <div class="notif-list" id="listBody"></div>
    <div class="p-3 border-top bg-light">
        <button id="markAllRead" class="btn btn-outline-primary w-100 btn-sm rounded-pill">Tandai Semua Dibaca</button>
    </div>
</div>
<div class="side-panel" id="panelDetail">
    <div class="panel-header bg-light">
        <button class="btn-close-panel" id="backToList"><i class="fas fa-arrow-left"></i></button>
        <h5 class="ms-3">Detail Notifikasi</h5>
        <div style="width:24px"></div>
    </div>
    <div class="notif-list" id="detailBody"></div>
    <div class="p-3 border-top">
        <button id="deleteBtn" class="btn btn-danger w-100">Hapus Notifikasi</button>
    </div>
</div>

<div class="confirm-overlay" id="confirmDialog">
    <div class="confirm-dialog">
        <div class="confirm-icon"><i class="fas fa-trash-alt"></i></div>
        <h3 class="confirm-title">Hapus Notifikasi?</h3>
        <p class="confirm-message">Notifikasi yang sudah dihapus tidak dapat dikembalikan lagi.</p>
        <div class="confirm-actions">
            <button class="confirm-btn confirm-btn-cancel" id="confirmCancel">Batal</button>
            <button class="confirm-btn confirm-btn-delete" id="confirmDelete">Ya, Hapus</button>
        </div>
    </div>
</div>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// UTILITIES
function esc(t){if(!t)return'';const d=document.createElement('div');d.textContent=t;return d.innerHTML}
window.addEventListener('scroll',()=>document.querySelector('.navbar-gudit').classList.toggle('scrolled',window.scrollY>10));

// NAVBAR TOGGLE
const userToggle=document.getElementById('userToggle'),userMenu=document.getElementById('userMenu');
if(userToggle){
    userToggle.addEventListener('click',e=>{e.preventDefault();userMenu.classList.toggle('show')});
    document.addEventListener('click',e=>{if(!userToggle.contains(e.target)&&!userMenu.contains(e.target))userMenu.classList.remove('show')});
}

@auth
// VARIABLES
const csrf=document.querySelector('meta[name="csrf-token"]').content;
let currId=null,notifCache=[],deleteCallback=null;
const overlay=document.getElementById('overlay'),panelList=document.getElementById('panelList'),panelDetail=document.getElementById('panelDetail'),confirmDialog=document.getElementById('confirmDialog');

// OPEN PANEL (LIST)
document.getElementById('notificationBtn')?.addEventListener('click',e=>{
    e.preventDefault();
    document.getElementById('navContent')?.classList.remove('show');
    userMenu?.classList.remove('show');
    overlay.classList.add('show');
    panelList.classList.add('show');
    loadNotifs();
});

// CLOSE ALL
function closeAll(){overlay.classList.remove('show');panelList.classList.remove('show');panelDetail.classList.remove('show')}
overlay.addEventListener('click',closeAll);
document.getElementById('closeList')?.addEventListener('click',closeAll);
document.getElementById('backToList')?.addEventListener('click',()=>{panelDetail.classList.remove('show');panelList.classList.add('show')});

// UPDATE BADGE (COUNT)
function updateBadgeUI(count){
    const b=document.getElementById('notifBadge');
    if(b){
        b.textContent=count;
        b.style.display=count>0?'inline-block':'none';
    }
}
async function updateBadge(){
    try{
        const r=await fetch('{{route("notifications.unread-count")??"/notifications/unread-count"}}',{headers:{'X-CSRF-TOKEN':csrf}});
        if(r.ok){const d=await r.json();updateBadgeUI(d.count)}
    }catch(e){}
}

// LOAD LIST
async function loadNotifs(){
    const lb=document.getElementById('listBody');
    if(notifCache.length===0) lb.innerHTML='<div class="p-5 text-center text-muted"><i class="fas fa-circle-notch fa-spin"></i></div>';
    
    try{
        const r=await fetch('{{route("notifications.recent")??"/notifications/recent"}}',{headers:{'X-CSRF-TOKEN':csrf}});
        if(r.ok){
            const d=await r.json();
            notifCache=d.notifications||[];
            renderNotifs();
        }
    }catch(e){
        if(notifCache.length===0) lb.innerHTML='<div class="p-5 text-center text-danger"><p>Gagal memuat</p></div>';
    }
}

function renderNotifs(){
    const lb=document.getElementById('listBody');
    if(!notifCache.length){lb.innerHTML='<div class="notif-empty"><i class="far fa-bell-slash"></i><h5>Tidak ada notifikasi</h5></div>';return}
    // Perhatikan onclick di sini mengirim ID sebagai string
    lb.innerHTML=notifCache.map(n=>`
        <div class="notif-item ${n.is_read?'':'unread'}" onclick="openDetail('${n.id}')" id="item-${n.id}">
            <div class="notif-type-badge ${n.type}">${n.type==='info'?'Info':n.type==='success'?'Sukses':n.type==='warning'?'Peringatan':'Penting'}</div>
            <span class="notif-title">${esc(n.title)}</span>
            <p class="notif-msg">${esc(n.message)}</p>
            <span class="notif-time"><i class="far fa-clock me-1"></i>${n.time}</span>
        </div>
    `).join('');
}

// --- PERBAIKAN UTAMA ADA DI FUNGSI INI ---
window.openDetail = function(id) {
    currId = id;
    
    // PERBAIKAN: Gunakan '==' bukan '===' agar string '5' sama dengan number 5
    const notif = notifCache.find(x => x.id == id);
    
    // Debugging: Jika masih tidak bisa dibuka, cek Console browser (F12)
    if (!notif) {
        console.error("Notifikasi tidak ditemukan di cache. ID:", id, "Cache:", notifCache);
        return;
    }

    // 1. UI Switch Instant
    panelList.classList.remove('show');
    panelDetail.classList.add('show');
    
    // 2. Render Data Cache (Instant)
    const colors={info:'#0d6efd',success:'#198754',warning:'#ffc107',danger:'#dc3545'},
          icons={info:'fa-info-circle',success:'fa-check-circle',warning:'fa-exclamation-triangle',danger:'fa-times-circle'};
    
    const db = document.getElementById('detailBody');
    db.innerHTML = `
        <div class="p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="width:48px;height:48px;background:${colors[notif.type]||'#ccc'};border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem">
                    <i class="fas ${icons[notif.type]||'fa-bell'}"></i>
                </div>
                <div>
                    <h3 style="font-size:1.25rem;font-weight:700;margin:0">${esc(notif.title)}</h3>
                    <p style="font-size:.85rem;color:#64748b;margin:0"><i class="far fa-clock me-1"></i>${notif.time||notif.created_at}</p>
                </div>
            </div>
            <div id="detailContent" style="background:#f8fafc;border-radius:12px;padding:20px;margin-bottom:25px;white-space:pre-line;line-height:1.6">
                ${esc(notif.detail || notif.message)} 
                ${!notif.detail ? '<div class="mt-2 text-muted fs-7"><i class="fas fa-sync fa-spin me-1"></i> Mengambil detail lengkap...</div>' : ''}
            </div>
        </div>`;

    // 3. Update Status Read (Instant - Optimistic UI)
    if (!notif.is_read) {
        notif.is_read = true;
        const item = document.getElementById(`item-${id}`);
        if(item) item.classList.remove('unread');
        
        const badge = document.getElementById('notifBadge');
        if(badge) {
            let curr = parseInt(badge.textContent) || 0;
            if(curr > 0) updateBadgeUI(curr-1);
        }
        
        // Kirim ke server di background
        fetch(`/notifications/${id}/read`, {method: 'POST', headers: {'X-CSRF-TOKEN': csrf}});
    }

    // 4. Fetch Full Detail (Background) jika detail belum ada
    // Jika detail sudah ada di cache (misal sudah pernah dibuka), tidak perlu fetch lagi
    if(!notif.full_loaded) {
        fetch(`/notifications/${id}/detail`, {headers: {'X-CSRF-TOKEN': csrf}})
            .then(r => r.ok ? r.json() : null)
            .then(n => {
                if(n) {
                    notif.detail = n.detail || n.message; 
                    notif.link = n.link;
                    notif.full_loaded = true; // Tandai sudah full load
                    
                    const contentDiv = document.getElementById('detailContent');
                    if(contentDiv) {
                        contentDiv.innerHTML = esc(n.detail || n.message);
                        if(n.link) {
                            const linkBtn = document.createElement('div');
                            linkBtn.innerHTML = `<a href="${n.link}" class="btn btn-primary w-100 mt-3"><i class="fas fa-external-link-alt me-2"></i>Buka Link</a>`;
                            contentDiv.parentNode.appendChild(linkBtn);
                        }
                    }
                }
            })
            .catch(e => {
                // Jika gagal fetch detail, hilangkan spinner loading saja
                const contentDiv = document.getElementById('detailContent');
                if(contentDiv) contentDiv.innerHTML = esc(notif.message);
            });
    }
}

// MARK ALL READ
document.getElementById('markAllRead')?.addEventListener('click', function(){
    const btn = this;
    btn.innerHTML = '<i class="fas fa-check"></i> Selesai';
    btn.disabled = true;
    
    notifCache.forEach(n => n.is_read = true);
    renderNotifs();
    updateBadgeUI(0);

    fetch('{{route("notifications.mark-all-read")??"/notifications/mark-all-read"}}', {
        method: 'POST', 
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf}
    });

    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = 'Tandai Semua Dibaca';
    }, 2000);
});

// DELETE NOTIFICATION
document.getElementById('deleteBtn')?.addEventListener('click', () => {
    if(!currId) return;
    confirmDialog.classList.add('show');
    
    deleteCallback = () => {
        confirmDialog.classList.remove('show');
        panelDetail.classList.remove('show');
        panelList.classList.add('show');
        
        const idx = notifCache.findIndex(n => n.id == currId); // Gunakan == di sini juga
        if(idx > -1) {
            const wasUnread = !notifCache[idx].is_read;
            notifCache.splice(idx, 1);
            renderNotifs();
            
            if(wasUnread) {
                const b = document.getElementById('notifBadge');
                let curr = parseInt(b?.textContent||0);
                if(curr>0) updateBadgeUI(curr-1);
            }
        }

        fetch(`/notifications/${currId}`, {
            method: 'DELETE', 
            headers: {'X-CSRF-TOKEN': csrf}
        });
    };
});

document.getElementById('confirmCancel')?.addEventListener('click',()=>confirmDialog.classList.remove('show'));
document.getElementById('confirmDelete')?.addEventListener('click',()=>{if(deleteCallback)deleteCallback()});

updateBadge();
setInterval(updateBadge, 60000);
@endauth
</script>
</body>
</html>