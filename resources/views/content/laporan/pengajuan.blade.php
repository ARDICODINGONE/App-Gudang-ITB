@extends('layouts/app')

@section('title', 'Laporan Pengajuan')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 text-dark fw-bold mb-0">
                            <i class="ri-file-list-line me-2"></i>Laporan Pengajuan
                        </h1>
                        <p class="text-muted small mt-1">Lihat status dan detail setiap pengajuan</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('laporan.pengajuan.export-excel', request()->query()) }}" class="btn btn-outline-success">
                            <i class="ri-file-excel-line me-1"></i>Export Excel
                        </a>
                        <a href="{{ route('laporan.pengajuan.export-pdf', request()->query()) }}" class="btn btn-outline-danger">
                            <i class="ri-file-pdf-line me-1"></i>Export PDF
                        </a>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('laporan.pengajuan') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" for="dari_tanggal">Dari Tanggal</label>
                        <input type="date" class="form-control" id="dari_tanggal" name="dari_tanggal" 
                            value="{{ request('dari_tanggal') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="sampai_tanggal">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="sampai_tanggal" name="sampai_tanggal" 
                            value="{{ request('sampai_tanggal') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="status">Status Pengajuan</label>
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
                        <a href="{{ route('laporan.pengajuan') }}" class="btn btn-secondary">
                            <i class="ri-refresh-line me-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="row g-3 mb-4">
            <div class="col-md-12">
                <div class="card border-0 bg-warning text-dark">
                    <div class="card-body">
                        <h6 class="card-title opacity-75">Total Pengajuan</h6>
                        <h2 class="mb-0">{{ number_format($totalPengajuan, 0, ',', '.') }}</h2>
                        <small class="opacity-75">pengajuan dalam periode terpilih</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Detail Pengajuan</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID Pengajuan</th>
                            <th>Tanggal</th>
                            <th>User</th>
                            <th class="text-center">Item</th>
                            <th class="text-center">Status</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuans as $p)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">{{ $p->id }}</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                                <td>
                                    @if($p->user)
                                        <small class="text-dark fw-600">{{ $p->user->name ?? $p->user->nama ?? '-' }}</small>
                                    @else
                                        <small class="text-muted"><i class="ri-user-forbid-line me-1" style="color: #dc3545;"></i>Tidak ada</small>
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ $p->total_items ?? 0 }}</td>
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
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">{{ $statusLabel }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ Str::limit($p->note ?? '-', 30) }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('pengajuan.show', $p->id) }}" class="btn btn-sm btn-info" title="Detail">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="ri-inbox-line" style="font-size: 2rem; opacity: 0.5;"></i>
                                    <p class="mt-2">Tidak ada data pengajuan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            @if($pengajuans->hasPages())
                <div class="card-footer bg-white">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mb-0">
                            {{-- Previous Page Link --}}
                            @if ($pengajuans->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">← Sebelumnya</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $pengajuans->previousPageUrl() }}&{{ http_build_query(request()->except(['page'])) }}" rel="prev">← Sebelumnya</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($pengajuans->getUrlRange(1, $pengajuans->lastPage()) as $page => $url)
                                @if ($page == $pengajuans->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}&{{ http_build_query(request()->except(['page'])) }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($pengajuans->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $pengajuans->nextPageUrl() }}&{{ http_build_query(request()->except(['page'])) }}" rel="next">Selanjutnya →</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">Selanjutnya →</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @endif
        </div>

        <!-- Status Statistics -->
        @if($pengajuans->count() > 0)
            <div class="row g-3 mt-4">
                <div class="col-md-3">
                    <div class="card border-0 bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Menunggu</h6>
                            <h3 class="text-warning">{{ $pengajuans->where('status', 'pending')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Disetujui</h6>
                            <h3 class="text-success">{{ $pengajuans->where('status', 'approved')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Ditolak</h6>
                            <h3 class="text-danger">{{ $pengajuans->where('status', 'rejected')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Selesai</h6>
                            <h3 class="text-info">{{ $pengajuans->where('status', 'completed')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
