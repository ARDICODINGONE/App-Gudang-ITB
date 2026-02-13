@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Detail Pengajuan</h3>
            <small class="text-muted">{{ $pengajuan->kode_pengajuan ?? '-' }}</small>
        </div>
        <a href="{{ route('pengajuan.list') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-7">
            <!-- Info Card -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        @if($pengajuan->status === 'pending')
                            <span class="badge bg-warning text-dark">⏳ Menunggu Persetujuan</span>
                        @elseif($pengajuan->status === 'approved')
                            <span class="badge bg-success">✓ Disetujui</span>
                        @else
                            <span class="badge bg-danger">✗ Ditolak</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Gudang</small>
                            <strong class="fs-5">{{ $pengajuan->gudang?->nama_gudang ?? $pengajuan->kode_gudang }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Total Jumlah</small>
                            <strong class="fs-5 badge bg-primary">{{ $pengajuan->jumlah }} item</strong>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Tanggal Pengajuan</small>
                            <strong>{{ \Carbon\Carbon::parse($pengajuan->tanggal)->format('d M Y') }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Dibuat</small>
                            <strong>{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d M Y H:i') }}</strong>
                        </div>
                    </div>

                    @if($pengajuan->note)
                    <div class="alert alert-info mt-3 mb-0">
                        <small><strong>Catatan:</strong> {{ $pengajuan->note }}</small>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Detail Barang -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Detail Barang yang Diajukan</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
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
                <div id="pengajuan-pagination-wrapper" style="display:none; padding: 1.5rem; border-top: 1px solid #dee2e6; background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                    <nav aria-label="Page navigation">
                        <ul id="pengajuan-pagination-list" class="pagination justify-content-center mb-0">
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-5">
            <!-- Pengaju Info Card -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Pengaju</h5>
                </div>
                <div class="card-body">
                    @if($user_pengaju)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-circle" style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 15px;">
                            {{ strtoupper(substr($user_pengaju->nama ?? $user_pengaju->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <strong class="d-block">{{ $user_pengaju->nama ?? $user_pengaju->name ?? '-' }}</strong>
                            <small class="text-muted">{{ $user_pengaju->username ?? '-' }}</small>
                        </div>
                    </div>
                    @else
                    <p class="text-muted mb-0">Informasi pengaju tidak tersedia</p>
                    @endif
                </div>
            </div>

            <!-- Approval Actions - For Approver -->
            @if($isApprover && $pengajuan->status === 'pending')
            <div class="card border-warning shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Aksi Persetujuan</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Silakan tentukan jumlah barang yang akan disetujui untuk setiap item. Jumlah yang tidak disetujui akan otomatis ditolak.</p>

                    <!-- Approval Details Form -->
                    <form id="approval-form-{{ $pengajuan->id }}" method="POST" action="{{ route('pengajuan.approve', $pengajuan->id) }}" class="mb-3">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
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
                        <div id="approval-pagination-wrapper" style="display:none; padding: 1.5rem; border-top: 1px solid #dee2e6; background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                            <nav aria-label="Page navigation">
                                <ul id="approval-pagination-list" class="pagination justify-content-center mb-0">
                                </ul>
                            </nav>
                        </div>

                        <div class="mt-3 d-flex gap-2">
                            <button type="button" class="btn btn-success flex-grow-1" onclick="submitApproval({{ $pengajuan->id }})">
                                <i class="fas fa-check-circle"></i> Setujui
                            </button>
                            <button type="button" class="btn btn-danger flex-grow-1" onclick="askRejectAll({{ $pengajuan->id }})">
                                <i class="fas fa-times-circle"></i> Tolak Semua
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @elseif($isApprover && $pengajuan->status === 'approved')
            <div class="card border-success shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Status Persetujuan</h5>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0"><strong>Pengajuan Telah Disetujui</strong></p>
                    <small class="text-muted d-block">Stok gudang sudah dikurangi sesuai pesanan</small>
                </div>
            </div>
            @elseif($isApprover && $pengajuan->status === 'partial_approved')
            <div class="card border-info shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Status Persetujuan Sebagian</h5>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-circle text-info" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0"><strong>Pengajuan Disetujui Sebagian</strong></p>
                    <small class="text-muted d-block">Ada item yang disetujui dan ada yang ditolak</small>
                </div>
            </div>
            @elseif($isApprover && $pengajuan->status === 'rejected')
            <div class="card border-danger shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Status Penolakan</h5>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-times-circle text-danger" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0"><strong>Pengajuan Telah Ditolak</strong></p>
                    <small class="text-muted d-block">Silakan hubungi tim approval untuk informasi lebih lanjut</small>
                </div>
            </div>
            @endif

            <!-- Status Cards - For Pengaju -->
            @if($isPengaju && $pengajuan->status === 'pending')
            <div class="card border-info shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Status Pengajuan</h5>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-hourglass-half text-info" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0"><strong>Menunggu Persetujuan</strong></p>
                    <small class="text-muted d-block">Tim approval akan memproses pengajuan Anda segera</small>
                </div>
            </div>
            @elseif($isPengaju && $pengajuan->status === 'approved')
            <div class="card border-success shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Status Pengajuan</h5>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0"><strong>Pengajuan Disetujui!</strong></p>
                    <small class="text-muted d-block">Barang Anda sudah disiapkan untuk diambil</small>
                </div>
            </div>
            @elseif($isPengaju && $pengajuan->status === 'partial_approved')
            <div class="card border-info shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Status Pengajuan</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-exclamation-circle text-info" style="font-size: 3rem;"></i>
                        <p class="mt-3 mb-0"><strong>Pengajuan Disetujui Sebagian</strong></p>
                    </div>
                    <div class="alert alert-info mb-0">
                        <small>Beberapa item disetujui, sementara item lainnya tidak tersedia. Lihat detail di atas untuk informasi lengkap.</small>
                    </div>
                </div>
            </div>
            @elseif($isPengaju && $pengajuan->status === 'rejected')
            <div class="card border-danger shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Status Pengajuan</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-times-circle text-danger" style="font-size: 3rem;"></i>
                        <p class="mt-3 mb-0"><strong>Pengajuan Ditolak</strong></p>
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

<script>
function submitApproval(pengajuanId) {
    const form = document.getElementById('approval-form-' + pengajuanId);
    const inputs = form.querySelectorAll('input[name^="approved"]');
    let hasApproved = false;
    let allRejected = true;
    
    inputs.forEach(input => {
        const value = parseInt(input.value) || 0;
        if (value > 0) {
            hasApproved = true;
            allRejected = false;
        }
    });
    
    if (!hasApproved) {
        alert('Minimal ada 1 barang yang harus disetujui atau tolak semua pengajuan.');
        return;
    }
    
    if (confirm('Yakin ingin memproses approval dengan jumlah yang telah ditentukan?')) {
        form.submit();
    }
}

function askRejectAll(pengajuanId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("pengajuan.reject", ":id") }}'.replace(':id', pengajuanId);
    form.innerHTML = '{{ csrf_field() }}';
    document.body.appendChild(form);
    form.submit();
}

// Pagination AJAX Functions
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
        
        // Render pagination if has_pages
        if (payload.pagination && payload.pagination.has_pages) {
            renderPengajuanPagination(payload.pagination);
            document.getElementById('pengajuan-pagination-wrapper').style.display = 'block';
        } else {
            document.getElementById('pengajuan-pagination-wrapper').style.display = 'none';
        }

    } catch (err) {
        console.error("Gagal memuat detail pengajuan:", err);
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
                <td class="text-end">
                    <span class="badge bg-info">${item.jumlah}</span>
                </td>
        `;
        
        if (status === 'approved' || status === 'partial_approved') {
            const badgeClass = item.jumlah_disetujui > 0 ? 'success' : 'danger';
            const statusBadge = item.status === 'approved' ? '✓ Disetujui' : (item.status === 'rejected' ? '✗ Ditolak' : 'Pending');
            const statusBadgeClass = item.status === 'approved' ? 'bg-success' : (item.status === 'rejected' ? 'bg-danger' : 'bg-secondary');
            
            row += `
                <td class="text-end">
                    <span class="badge bg-${badgeClass}">${item.jumlah_disetujui}</span>
                </td>
                <td class="text-center">
                    <span class="badge ${statusBadgeClass}">${statusBadge}</span>
                </td>
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

    // Previous button
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

    // Page numbers dengan range
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

    // Next button
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
        console.error("Gagal memuat halaman detail pengajuan:", err);
    }
}

// Approval Form AJAX Functions
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
        console.error("Gagal memuat detail approval:", err);
    }
}

function renderApprovalDetails(items) {
    const tbody = document.getElementById('approval-barang-tbody');
    tbody.innerHTML = '';

    items.forEach((item) => {
        const row = `
            <tr>
                <td>${item.nama_barang || '-'}</td>
                <td class="text-center">
                    <span class="badge bg-info">${item.jumlah}</span>
                </td>
                <td class="text-center">
                    <input type="number" name="approved[${item.id}]" min="0" max="${item.jumlah}" value="${item.jumlah}" class="form-control form-control-sm text-center" style="max-width: 80px; margin: 0 auto;" />
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

    // Previous button
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

    // Page numbers dengan range
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

    // Next button
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
        console.error("Gagal memuat halaman approval:", err);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    loadPengajuanDetails();
    // Hanya load approval details jika approval form visible
    if (document.getElementById('approval-barang-tbody')) {
        loadApprovalDetails();
    }
});

</script>

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .card {
        border-radius: 0.5rem;
    }

    /* Pagination Styling */
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
        transition: all 0.3s ease;
        min-width: 38px;
        text-align: center;
    }

    .pagination .page-link:hover {
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.25);
        transform: translateY(-2px);
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    .pagination .page-item.disabled .page-link {
        color: #b5bcc7;
        background-color: #f8f9fa;
        border-color: #dee2e6;
        cursor: not-allowed;
    }

    .pagination .page-item.disabled .page-link:hover {
        color: #b5bcc7;
        background-color: #f8f9fa;
        border-color: #dee2e6;
        transform: none;
        box-shadow: none;
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
</style>
@endsection
