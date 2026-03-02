@extends('layouts.app')

@section('content')
@php
    $statusText = 'Ditolak';
    $statusClass = 'status-rejected';
    $statusIcon = 'fa-circle-xmark';

    if ($pengajuan->status === 'pending') {
        $statusText = 'Menunggu Persetujuan';
        $statusClass = 'status-pending';
        $statusIcon = 'fa-clock';
    } elseif ($pengajuan->status === 'approved') {
        $statusText = 'Disetujui';
        $statusClass = 'status-approved';
        $statusIcon = 'fa-circle-check';
    } elseif ($pengajuan->status === 'partial_approved') {
        $statusText = 'Sebagian Disetujui';
        $statusClass = 'status-partial';
        $statusIcon = 'fa-circle-exclamation';
    }
@endphp

<div class="container-fluid">
    <div class="detail-page">
        <div class="detail-header mb-4">
            <div>
                <p class="detail-overline mb-1">Detail Pengajuan</p>
                <h3 class="mb-1">{{ $pengajuan->kode_pengajuan ?? '-' }}</h3>
                <span class="status-pill {{ $statusClass }}">
                    <i class="fas {{ $statusIcon }} me-1"></i>{{ $statusText }}
                </span>
            </div>
            <a href="{{ route('pengajuan.list') }}" class="btn btn-outline-secondary fw-semibold mt-3 mt-md-0">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card panel-card mb-4">
                    <div class="panel-head">
                        <h5 class="mb-0">Informasi Pengajuan</h5>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <p class="label mb-1">Gudang</p>
                                <p class="value mb-0">{{ $pengajuan->gudang?->nama_gudang ?? $pengajuan->kode_gudang }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="label mb-1">Total Jumlah</p>
                                <span class="qty-pill">{{ $pengajuan->jumlah }} item</span>
                            </div>
                            <div class="col-md-6">
                                <p class="label mb-1">Tanggal Pengajuan</p>
                                <p class="value mb-0">{{ \Carbon\Carbon::parse($pengajuan->tanggal)->format('d M Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="label mb-1">Dibuat</p>
                                <p class="value mb-0">{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        @if($pengajuan->note)
                        <div class="note-box mt-3">
                            <p class="label mb-1">Catatan</p>
                            <p class="mb-0 note-text">{{ $pengajuan->note }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card panel-card">
                    <div class="panel-head d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Barang yang Diajukan</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-professional align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width:70px;">No</th>
                                    <th>Nama Barang</th>
                                    <th class="text-end">Diajukan</th>
                                    @if($pengajuan->status === 'approved' || $pengajuan->status === 'partial_approved')
                                    <th class="text-end">Disetujui</th>
                                    <th class="text-center">Status</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="detail-barang-tbody">
                            </tbody>
                        </table>
                    </div>
                    <div id="pengajuan-pagination-wrapper" class="pagination-wrap" style="display:none;">
                        <nav aria-label="Page navigation">
                            <ul id="pengajuan-pagination-list" class="pagination justify-content-center mb-0"></ul>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card panel-card mb-4">
                    <div class="panel-head">
                        <h5 class="mb-0">Informasi Pengaju</h5>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        @if($user_pengaju)
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle">
                                {{ strtoupper(substr($user_pengaju->nama ?? $user_pengaju->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <p class="value mb-0">{{ $user_pengaju->nama ?? $user_pengaju->name ?? '-' }}</p>
                                <p class="text-muted mb-0">{{ $user_pengaju->username ?? '-' }}</p>
                            </div>
                        </div>
                        @else
                        <p class="text-muted mb-0">Informasi pengaju tidak tersedia</p>
                        @endif
                    </div>
                </div>

                @if($isApprover && $pengajuan->status === 'pending')
                <div class="card panel-card panel-accent-warning">
                    <div class="panel-head">
                        <h5 class="mb-0">Aksi Persetujuan</h5>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        <p class="text-muted small mb-3">Tentukan jumlah barang yang disetujui untuk setiap item. Sisa jumlah otomatis ditolak.</p>

                        <form id="approval-form-{{ $pengajuan->id }}" method="POST" action="{{ route('pengajuan.approve', $pengajuan->id) }}" class="mb-3">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-sm table-professional mb-0">
                                    <thead>
                                        <tr>
                                            <th>Barang</th>
                                            <th class="text-center">Diajukan</th>
                                            <th class="text-center">Disetujui</th>
                                        </tr>
                                    </thead>
                                    <tbody id="approval-barang-tbody">
                                    </tbody>
                                </table>
                            </div>
                            <div id="approval-pagination-wrapper" class="pagination-wrap" style="display:none;">
                                <nav aria-label="Page navigation">
                                    <ul id="approval-pagination-list" class="pagination justify-content-center mb-0"></ul>
                                </nav>
                            </div>

                            <div class="mt-3 d-flex gap-2">
                                <button type="button" class="btn btn-success flex-grow-1" onclick="openDecisionModal('approve', {{ $pengajuan->id }})">
                                    <i class="fas fa-check-circle me-1"></i>Setujui
                                </button>
                                <button type="button" class="btn btn-danger flex-grow-1" onclick="openDecisionModal('reject', {{ $pengajuan->id }})">
                                    <i class="fas fa-times-circle me-1"></i>Tolak Semua
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @elseif($isApprover && $pengajuan->status === 'approved')
                <div class="card panel-card panel-accent-success text-center">
                    <div class="panel-head"><h5 class="mb-0">Status Persetujuan</h5></div>
                    <div class="card-body p-4">
                        <i class="fas fa-check-circle status-icon text-success"></i>
                        <p class="mt-3 mb-0 fw-semibold">Pengajuan Telah Disetujui</p>
                        <small class="text-muted d-block">Stok gudang sudah dikurangi sesuai pesanan</small>
                    </div>
                </div>
                @elseif($isApprover && $pengajuan->status === 'partial_approved')
                <div class="card panel-card panel-accent-info text-center">
                    <div class="panel-head"><h5 class="mb-0">Status Persetujuan</h5></div>
                    <div class="card-body p-4">
                        <i class="fas fa-circle-exclamation status-icon text-info"></i>
                        <p class="mt-3 mb-0 fw-semibold">Pengajuan Disetujui Sebagian</p>
                        <small class="text-muted d-block">Sebagian item disetujui dan sebagian ditolak</small>
                    </div>
                </div>
                @elseif($isApprover && $pengajuan->status === 'rejected')
                <div class="card panel-card panel-accent-danger text-center">
                    <div class="panel-head"><h5 class="mb-0">Status Persetujuan</h5></div>
                    <div class="card-body p-4">
                        <i class="fas fa-times-circle status-icon text-danger"></i>
                        <p class="mt-3 mb-0 fw-semibold">Pengajuan Telah Ditolak</p>
                        <small class="text-muted d-block">Silakan hubungi tim approval untuk informasi lebih lanjut</small>
                    </div>
                </div>
                @endif

                @if($isPengaju && $pengajuan->status === 'pending')
                <div class="card panel-card panel-accent-info text-center">
                    <div class="panel-head"><h5 class="mb-0">Status Pengajuan</h5></div>
                    <div class="card-body p-4">
                        <i class="fas fa-hourglass-half status-icon text-info"></i>
                        <p class="mt-3 mb-0 fw-semibold">Menunggu Persetujuan</p>
                        <small class="text-muted d-block">Tim approval akan memproses pengajuan Anda segera</small>
                    </div>
                </div>
                @elseif($isPengaju && $pengajuan->status === 'approved')
                <div class="card panel-card panel-accent-success text-center">
                    <div class="panel-head"><h5 class="mb-0">Status Pengajuan</h5></div>
                    <div class="card-body p-4">
                        <i class="fas fa-check-circle status-icon text-success"></i>
                        <p class="mt-3 mb-0 fw-semibold">Pengajuan Disetujui</p>
                        <small class="text-muted d-block">Barang Anda sudah disiapkan untuk diambil</small>
                    </div>
                </div>
                @elseif($isPengaju && $pengajuan->status === 'partial_approved')
                <div class="card panel-card panel-accent-info">
                    <div class="panel-head"><h5 class="mb-0">Status Pengajuan</h5></div>
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-circle-exclamation status-icon text-info"></i>
                            <p class="mt-3 mb-0 fw-semibold">Pengajuan Disetujui Sebagian</p>
                        </div>
                        <div class="note-box">
                            <p class="mb-0 note-text">
                                {{ $pengajuan->rejection_reason ?: 'Beberapa item disetujui, sementara item lainnya tidak tersedia. Lihat detail untuk informasi lengkap.' }}
                            </p>
                        </div>
                    </div>
                </div>
                @elseif($isPengaju && $pengajuan->status === 'rejected')
                <div class="card panel-card panel-accent-danger">
                    <div class="panel-head"><h5 class="mb-0">Status Pengajuan</h5></div>
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-times-circle status-icon text-danger"></i>
                            <p class="mt-3 mb-0 fw-semibold">Pengajuan Ditolak</p>
                        </div>
                        @if($pengajuan->rejection_reason)
                        <div class="alert alert-danger mb-0">
                            <small><strong>Alasan:</strong> {{ $pengajuan->rejection_reason }}</small>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@include('pesan.pesan')

<script>
const pengajuanId = {{ $pengajuan->id }};
const pengajuanStatus = '{{ $pengajuan->status }}';

async function loadPengajuanDetails() {
    try {
        const res = await fetch(`/pengajuan/${pengajuanId}/details`);
        const payload = await res.json();

        if (!payload.items || payload.items.length === 0) {
            document.getElementById('detail-barang-tbody').innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="fas fa-inbox"></i> Tidak ada detail barang
                    </td>
                </tr>
            `;
            document.getElementById('pengajuan-pagination-wrapper').style.display = 'none';
            return;
        }

        renderPengajuanDetails(payload.items, payload.pengajuan_status);

        if (payload.pagination && payload.pagination.has_pages) {
            renderPengajuanPagination(payload.pagination);
            document.getElementById('pengajuan-pagination-wrapper').style.display = 'block';
        } else {
            document.getElementById('pengajuan-pagination-wrapper').style.display = 'none';
        }
    } catch (err) {
        console.error('Gagal memuat detail pengajuan:', err);
    }
}

function renderPengajuanDetails(items, status) {
    const tbody = document.getElementById('detail-barang-tbody');
    tbody.innerHTML = '';

    items.forEach((item, index) => {
        const rowClass = item.status === 'rejected' ? 'table-danger' : (item.status === 'approved' ? 'table-success' : '');

        let row = `
            <tr class="${rowClass}">
                <td>${index + 1}</td>
                <td>${item.nama_barang || '-'}</td>
                <td class="text-end"><span class="badge bg-info-subtle text-info-emphasis">${item.jumlah}</span></td>
        `;

        if (status === 'approved' || status === 'partial_approved') {
            const badgeClass = item.jumlah_disetujui > 0 ? 'success' : 'danger';
            const statusBadge = item.status === 'approved' ? 'Disetujui' : (item.status === 'rejected' ? 'Ditolak' : 'Pending');
            const statusBadgeClass = item.status === 'approved' ? 'bg-success' : (item.status === 'rejected' ? 'bg-danger' : 'bg-secondary');

            row += `
                <td class="text-end"><span class="badge bg-${badgeClass}">${item.jumlah_disetujui}</span></td>
                <td class="text-center"><span class="badge ${statusBadgeClass}">${statusBadge}</span></td>
            `;
        }

        row += `</tr>`;
        tbody.innerHTML += row;
    });
}

function renderPengajuanPagination(pagination) {
    const paginationList = document.getElementById('pengajuan-pagination-list');
    paginationList.innerHTML = '';

    const currentPage = pagination.current_page || 1;
    const lastPage = pagination.last_page || 1;

    if (currentPage > 1) {
        const prevLi = document.createElement('li');
        prevLi.className = 'page-item';
        prevLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="loadPengajuanPage(${currentPage - 1})" title="Halaman Sebelumnya"><i class="fa fa-chevron-left"></i> Sebelumnya</a>`;
        paginationList.appendChild(prevLi);
    } else {
        const prevLi = document.createElement('li');
        prevLi.className = 'page-item disabled';
        prevLi.innerHTML = `<span class="page-link" title="Tidak ada halaman sebelumnya"><i class="fa fa-chevron-left"></i> Sebelumnya</span>`;
        paginationList.appendChild(prevLi);
    }

    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(lastPage, currentPage + 2);

    if (startPage > 1) {
        const firstLi = document.createElement('li');
        firstLi.className = 'page-item';
        firstLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="loadPengajuanPage(1)">1</a>`;
        paginationList.appendChild(firstLi);

        if (startPage > 2) {
            const dotsLi = document.createElement('li');
            dotsLi.className = 'page-item disabled';
            dotsLi.innerHTML = `<span class="page-link">...</span>`;
            paginationList.appendChild(dotsLi);
        }
    }

    for (let i = startPage; i <= endPage; i++) {
        const li = document.createElement('li');
        li.className = currentPage === i ? 'page-item active' : 'page-item';
        li.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="loadPengajuanPage(${i})">${i}</a>`;
        paginationList.appendChild(li);
    }

    if (endPage < lastPage) {
        if (endPage < lastPage - 1) {
            const dotsLi = document.createElement('li');
            dotsLi.className = 'page-item disabled';
            dotsLi.innerHTML = `<span class="page-link">...</span>`;
            paginationList.appendChild(dotsLi);
        }

        const lastLi = document.createElement('li');
        lastLi.className = 'page-item';
        lastLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="loadPengajuanPage(${lastPage})">${lastPage}</a>`;
        paginationList.appendChild(lastLi);
    }

    if (currentPage < lastPage) {
        const nextLi = document.createElement('li');
        nextLi.className = 'page-item';
        nextLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="loadPengajuanPage(${currentPage + 1})" title="Halaman Berikutnya">Berikutnya <i class="fa fa-chevron-right"></i></a>`;
        paginationList.appendChild(nextLi);
    } else {
        const nextLi = document.createElement('li');
        nextLi.className = 'page-item disabled';
        nextLi.innerHTML = `<span class="page-link" title="Tidak ada halaman berikutnya">Berikutnya <i class="fa fa-chevron-right"></i></span>`;
        paginationList.appendChild(nextLi);
    }
}

async function loadPengajuanPage(page) {
    try {
        const res = await fetch(`/pengajuan/${pengajuanId}/details?page=${page}`);
        const payload = await res.json();

        const items = payload.items || [];
        const pagination = payload.pagination || {};

        if (!items || items.length === 0) {
            loadPengajuanDetails();
            return;
        }

        renderPengajuanDetails(items, payload.pengajuan_status);

        if (pagination && pagination.has_pages) {
            renderPengajuanPagination(pagination);
            document.getElementById('pengajuan-pagination-wrapper').style.display = 'block';
        }
    } catch (err) {
        console.error('Gagal memuat halaman detail pengajuan:', err);
    }
}

async function loadApprovalDetails() {
    try {
        const res = await fetch(`/pengajuan/${pengajuanId}/details`);
        const payload = await res.json();

        if (!payload.items || payload.items.length === 0) {
            document.getElementById('approval-barang-tbody').innerHTML = `
                <tr>
                    <td colspan="3" class="text-center text-muted py-2">Tidak ada detail barang</td>
                </tr>
            `;
            document.getElementById('approval-pagination-wrapper').style.display = 'none';
            return;
        }

        renderApprovalDetails(payload.items);

        if (payload.pagination && payload.pagination.has_pages) {
            renderApprovalPagination(payload.pagination);
            document.getElementById('approval-pagination-wrapper').style.display = 'block';
        } else {
            document.getElementById('approval-pagination-wrapper').style.display = 'none';
        }
    } catch (err) {
        console.error('Gagal memuat detail approval:', err);
    }
}

function renderApprovalDetails(items) {
    const tbody = document.getElementById('approval-barang-tbody');
    tbody.innerHTML = '';

    items.forEach((item) => {
        const row = `
            <tr>
                <td>${item.nama_barang || '-'}</td>
                <td class="text-center"><span class="badge bg-info-subtle text-info-emphasis">${item.jumlah}</span></td>
                <td class="text-center">
                    <input type="number" name="approved[${item.id}]" min="0" max="${item.jumlah}" value="${item.jumlah}" class="form-control form-control-sm text-center" style="max-width: 90px; margin: 0 auto;" />
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function renderApprovalPagination(pagination) {
    const paginationList = document.getElementById('approval-pagination-list');
    paginationList.innerHTML = '';

    const currentPage = pagination.current_page || 1;
    const lastPage = pagination.last_page || 1;

    if (currentPage > 1) {
        const prevLi = document.createElement('li');
        prevLi.className = 'page-item';
        prevLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="loadApprovalPage(${currentPage - 1})" title="Halaman Sebelumnya"><i class="fa fa-chevron-left"></i> Sebelumnya</a>`;
        paginationList.appendChild(prevLi);
    } else {
        const prevLi = document.createElement('li');
        prevLi.className = 'page-item disabled';
        prevLi.innerHTML = `<span class="page-link" title="Tidak ada halaman sebelumnya"><i class="fa fa-chevron-left"></i> Sebelumnya</span>`;
        paginationList.appendChild(prevLi);
    }

    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(lastPage, currentPage + 2);

    if (startPage > 1) {
        const firstLi = document.createElement('li');
        firstLi.className = 'page-item';
        firstLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="loadApprovalPage(1)">1</a>`;
        paginationList.appendChild(firstLi);

        if (startPage > 2) {
            const dotsLi = document.createElement('li');
            dotsLi.className = 'page-item disabled';
            dotsLi.innerHTML = `<span class="page-link">...</span>`;
            paginationList.appendChild(dotsLi);
        }
    }

    for (let i = startPage; i <= endPage; i++) {
        const li = document.createElement('li');
        li.className = currentPage === i ? 'page-item active' : 'page-item';
        li.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="loadApprovalPage(${i})">${i}</a>`;
        paginationList.appendChild(li);
    }

    if (endPage < lastPage) {
        if (endPage < lastPage - 1) {
            const dotsLi = document.createElement('li');
            dotsLi.className = 'page-item disabled';
            dotsLi.innerHTML = `<span class="page-link">...</span>`;
            paginationList.appendChild(dotsLi);
        }

        const lastLi = document.createElement('li');
        lastLi.className = 'page-item';
        lastLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="loadApprovalPage(${lastPage})">${lastPage}</a>`;
        paginationList.appendChild(lastLi);
    }

    if (currentPage < lastPage) {
        const nextLi = document.createElement('li');
        nextLi.className = 'page-item';
        nextLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="loadApprovalPage(${currentPage + 1})" title="Halaman Berikutnya">Berikutnya <i class="fa fa-chevron-right"></i></a>`;
        paginationList.appendChild(nextLi);
    } else {
        const nextLi = document.createElement('li');
        nextLi.className = 'page-item disabled';
        nextLi.innerHTML = `<span class="page-link" title="Tidak ada halaman berikutnya">Berikutnya <i class="fa fa-chevron-right"></i></span>`;
        paginationList.appendChild(nextLi);
    }
}

async function loadApprovalPage(page) {
    try {
        const res = await fetch(`/pengajuan/${pengajuanId}/details?page=${page}`);
        const payload = await res.json();

        const items = payload.items || [];
        const pagination = payload.pagination || {};

        if (!items || items.length === 0) {
            loadApprovalDetails();
            return;
        }

        renderApprovalDetails(items);

        if (pagination && pagination.has_pages) {
            renderApprovalPagination(pagination);
            document.getElementById('approval-pagination-wrapper').style.display = 'block';
        }
    } catch (err) {
        console.error('Gagal memuat halaman approval:', err);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    loadPengajuanDetails();
    if (document.getElementById('approval-barang-tbody')) {
        loadApprovalDetails();
    }
});
</script>

<style>
    .detail-page {
        max-width: 1320px;
        margin: 0 auto;
        padding: 1.2rem 0.4rem 1.8rem;
    }

    .detail-header {
        border: 1px solid #dbe7ff;
        border-radius: 18px;
        background: linear-gradient(110deg, #f0f6ff 0%, #ffffff 70%);
        padding: 1.2rem 1.35rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .detail-overline {
        font-size: 0.78rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
    }

    .panel-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 16px 30px -28px rgba(15, 23, 42, 0.35);
        overflow: hidden;
    }

    .panel-head {
        padding: 0.95rem 1.2rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .panel-accent-warning {
        border-top: 4px solid #f59e0b;
    }

    .panel-accent-success {
        border-top: 4px solid #10b981;
    }

    .panel-accent-info {
        border-top: 4px solid #0ea5e9;
    }

    .panel-accent-danger {
        border-top: 4px solid #ef4444;
    }

    .label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .value {
        font-weight: 600;
        color: #0f172a;
    }

    .qty-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.35rem 0.7rem;
        font-size: 0.8rem;
        font-weight: 700;
        color: #1e40af;
        background: #eaf2ff;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.35rem 0.7rem;
        font-size: 0.74rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-pending {
        background: #fff7e6;
        color: #b45309;
    }

    .status-approved {
        background: #ecfdf5;
        color: #047857;
    }

    .status-partial {
        background: #f0f9ff;
        color: #0369a1;
    }

    .status-rejected {
        background: #fef2f2;
        color: #b91c1c;
    }

    .note-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.7rem 0.8rem;
    }

    .note-text {
        font-size: 0.88rem;
        color: #334155;
        line-height: 1.45;
    }

    .avatar-circle {
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, #2563eb, #60a5fa);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 1.05rem;
        box-shadow: 0 8px 20px -10px rgba(37, 99, 235, 0.6);
    }

    .status-icon {
        font-size: 2.8rem;
    }

    .table-professional thead th {
        background: #f8fafc;
        color: #334155;
        font-weight: 700;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.9rem 1rem;
    }

    .table-professional tbody td {
        border-color: #f1f5f9;
        padding: 0.9rem 1rem;
        color: #334155;
        vertical-align: middle;
    }

    .table-professional tbody tr:hover {
        background: #f8fbff;
    }

    .pagination-wrap {
        padding: 1.1rem 1rem;
        border-top: 1px solid #e2e8f0;
        background: #fbfdff;
    }

    .pagination {
        gap: 6px;
    }

    .pagination .page-link {
        color: #0d6efd;
        background-color: #fff;
        border: 1.5px solid #dee2e6;
        padding: 0.5rem 0.75rem;
        font-weight: 500;
        font-size: 13px;
        border-radius: 6px;
        transition: all 0.25s ease;
        min-width: 38px;
        text-align: center;
    }

    .pagination .page-link:hover {
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.2);
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
    }

    .pagination .page-item.disabled .page-link {
        color: #b5bcc7;
        background-color: #f8f9fa;
        border-color: #dee2e6;
        cursor: not-allowed;
    }

    .page-link i {
        margin: 0 4px;
    }

    @media (max-width: 768px) {
        .pagination {
            flex-wrap: wrap;
        }

        .pagination .page-link {
            padding: 0.4rem 0.6rem;
            font-size: 12px;
            min-width: 35px;
        }
    }

    @media (max-width: 575.98px) {
        .detail-header {
            padding: 1rem;
        }
    }
</style>
@endsection
