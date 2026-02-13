@extends('layouts/app')

@section('title', 'Laporan Riwayat Pengajuan')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 text-dark fw-bold mb-0">
                            <i class="ri-history-line me-2"></i>Riwayat Pengajuan
                        </h1>
                        <p class="text-muted small mt-1">
                            @if($user && ($user->role === 'admin' || $user->role === 'supervisor'))
                                Lihat riwayat pengajuan dari semua user
                            @else
                                Lihat riwayat pengajuan Anda
                            @endif
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('laporan.riwayat-pengajuan.export-excel', request()->query()) }}" class="btn btn-outline-success">
                            <i class="ri-file-excel-line me-1"></i>Export Excel
                        </a>
                        <a href="{{ route('laporan.riwayat-pengajuan.export-pdf', request()->query()) }}" class="btn btn-outline-danger">
                            <i class="ri-file-pdf-line me-1"></i>Export PDF
                        </a>
                        <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('laporan.riwayat-pengajuan') }}" class="row g-3">
                    @if($user && ($user->role === 'admin' || $user->role === 'supervisor'))
                    <div class="col-md-3">
                        <label class="form-label" for="user">User</label>
                        <select class="form-select" id="user" name="user">
                            <option value="">-- Semua User --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user') == $u->id ? 'selected' : '' }}>
                                    {{ $u->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    <div class="col-md-3">
                        <label class="form-label" for="dari_tanggal">Dari Tanggal</label>
                        <input type="date" class="form-control" id="dari_tanggal" name="dari_tanggal" 
                            value="{{ request('dari_tanggal') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="sampai_tanggal">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="sampai_tanggal" name="sampai_tanggal" 
                            value="{{ request('sampai_tanggal') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">-- Semua Status --</option>
                            @foreach($statusOptions as $s)
                                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-search-line me-1"></i>Cari
                        </button>
                        <a href="{{ route('laporan.riwayat-pengajuan') }}" class="btn btn-secondary">
                            <i class="ri-refresh-line me-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="card border-0 bg-primary text-white">
                            <div class="card-body">
                                <h6 class="card-title opacity-75">Total Pengajuan</h6>
                                <h2 class="mb-0">{{ number_format($totalPengajuan, 0, ',', '.') }}</h2>
                                <small class="opacity-75">pengajuan dalam periode terpilih</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-warning text-dark">
                            <div class="card-body">
                                <h6 class="card-title">Menunggu Approval</h6>
                                <h2 class="mb-0">{{ $pengajuans->where('status', 'pending')->count() }}</h2>
                                <small>perlu diproses</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-success text-white">
                            <div class="card-body">
                                <h6 class="card-title opacity-75">Disetujui</h6>
                                <h2 class="mb-0">{{ $pengajuans->where('status', 'approved')->count() }}</h2>
                                <small class="opacity-75">sudah approved</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-danger text-white">
                            <div class="card-body">
                                <h6 class="card-title opacity-75">Ditolak</h6>
                                <h2 class="mb-0">{{ $pengajuans->where('status', 'rejected')->count() }}</h2>
                                <small class="opacity-75">ditolak</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Detail Riwayat Pengajuan</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">No</th>
                            <th>Tanggal</th>
                            <th>Kode Pengajuan</th>
                            @if($user && ($user->role === 'admin' || $user->role === 'supervisor'))
                            <th>User</th>
                            @endif
                            <th>Gudang</th>
                            <th class="text-center">Total Item</th>
                            <th class="text-center">Status</th>
                            <th>Catatan</th>
                            <th class="text-center" style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuans as $index => $p)
                            <tr>
                                <td class="text-center">{{ ($pengajuans->currentPage() - 1) * $pengajuans->perPage() + $index + 1 }}</td>
                                <td>
                                    <span class="text-dark fw-600">{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $p->kode_pengajuan }}</span>
                                </td>
                                @if($user && ($user->role === 'admin' || $user->role === 'supervisor'))
                                <td>
                                    @if($p->user)
                                        <small class="text-dark fw-600">{{ $p->user->nama ?? '-' }}</small>
                                        <br>
                                        <small class="text-muted">{{ $p->user->role ?? '-' }}</small>
                                    @else
                                        <small class="text-muted"><i class="ri-user-forbid-line me-1"></i>Tidak ada</small>
                                    @endif
                                </td>
                                @endif
                                <td>
                                    <small class="text-dark">{{ $p->gudang->nama_gudang ?? '-' }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark">{{ $p->details->sum('jumlah') ?? 0 }}</span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusColor = match($p->status ?? 'pending') {
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'completed' => 'info',
                                            default => 'warning'
                                        };
                                        $statusLabel = match($p->status ?? 'pending') {
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            'completed' => 'Selesai',
                                            default => 'Menunggu'
                                        };
                                        $statusIcon = match($p->status ?? 'pending') {
                                            'approved' => 'ri-check-line',
                                            'rejected' => 'ri-close-line',
                                            'completed' => 'ri-checkbox-circle-line',
                                            default => 'ri-time-line'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">
                                        <i class="{{ $statusIcon }} me-1"></i>{{ $statusLabel }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted" title="{{ $p->note ?? '-' }}">
                                        {{ Str::limit($p->note ?? '-', 30) }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('pengajuan.show', $p->id) }}" class="btn btn-sm btn-info" title="Detail Pengajuan">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="ri-inbox-archive-line" style="font-size: 2rem; opacity: 0.5;"></i>
                                    <p class="mt-2">Tidak ada riwayat pengajuan untuk periode ini</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($pengajuans->hasPages())
            <div class="card-footer">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end mb-0">
                        {{-- Previous Page Link --}}
                        @if ($pengajuans->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Sebelumnya</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $pengajuans->previousPageUrl() }}">Sebelumnya</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($pengajuans->getUrlRange(1, $pengajuans->lastPage()) as $page => $url)
                            @if ($page == $pengajuans->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">
                                        {{ $page }}
                                        <span class="sr-only">(current)</span>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($pengajuans->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $pengajuans->nextPageUrl() }}">Selanjutnya</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">Selanjutnya</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            @endif
        </div>

        <!-- Info Card -->
        <div class="card bg-light mt-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">
                            <i class="ri-shield-check-line me-2 text-success"></i>Kontrol Akses
                        </h6>
                        <ul class="small list-unstyled">
                            @if($user && ($user->role === 'admin' || $user->role === 'supervisor'))
                                <li class="mb-2">
                                    <i class="ri-check-line text-success me-2"></i>
                                    Anda dapat melihat semua pengajuan dari semua user
                                </li>
                                <li class="mb-2">
                                    <i class="ri-check-line text-success me-2"></i>
                                    Anda dapat memfilter berdasarkan user tertentu
                                </li>
                            @else
                                <li class="mb-2">
                                    <i class="ri-check-line text-success me-2"></i>
                                    Anda hanya dapat melihat pengajuan Anda sendiri
                                </li>
                                <li class="mb-2">
                                    <i class="ri-lock-line text-warning me-2"></i>
                                    Pengajuan pengguna lain tidak dapat diakses
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">
                            <i class="ri-information-line me-2 text-info"></i>Keterangan Status
                        </h6>
                        <ul class="small list-unstyled">
                            <li class="mb-2"><span class="badge bg-warning">Menunggu</span> - Pengajuan belum diproses</li>
                            <li class="mb-2"><span class="badge bg-success">Disetujui</span> - Pengajuan telah disetujui</li>
                            <li class="mb-2"><span class="badge bg-danger">Ditolak</span> - Pengajuan telah ditolak</li>
                            <li class="mb-2"><span class="badge bg-info">Selesai</span> - Pengajuan telah selesai</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
