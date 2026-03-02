@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="approval-page">
        <div class="approval-header mb-4">
            <div>
                <p class="approval-overline mb-1">Panel Approval</p>
                <h3 class="mb-1">Daftar Pengajuan</h3>
                <p class="text-muted mb-0">
                    Total {{ $items->total() }} pengajuan
                    <span class="mx-2">•</span>
                    Halaman {{ $items->currentPage() }} dari {{ $items->lastPage() }}
                </p>
            </div>
        </div>

        <div class="filter-card mb-4">
            <div class="card-body p-3 p-md-4">
                <form method="GET" action="{{ route('pengajuan.list') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" for="kode_gudang">Gudang</label>
                        <select class="form-select form-control-soft" id="kode_gudang" name="kode_gudang">
                            <option value="">Semua Gudang</option>
                            @foreach($gudangs as $g)
                                <option value="{{ $g->kode_gudang }}" {{ request('kode_gudang') == $g->kode_gudang ? 'selected' : '' }}>
                                    {{ $g->nama_gudang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" for="status">Status</label>
                        <select class="form-select form-control-soft" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="partial_approved" {{ in_array(request('status'), ['partial_approved', 'partially_approved', 'partial-approved']) ? 'selected' : '' }}>Sebagian Disetujui</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" for="dari_tanggal">Dari Tanggal</label>
                        <input type="date" class="form-control form-control-soft" id="dari_tanggal" name="dari_tanggal"
                            value="{{ request('dari_tanggal') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" for="sampai_tanggal">Sampai Tanggal</label>
                        <input type="date" class="form-control form-control-soft" id="sampai_tanggal" name="sampai_tanggal"
                            value="{{ request('sampai_tanggal') }}">
                    </div>
                    <div class="col-12 d-flex flex-wrap gap-2 mt-1">
                        <button type="submit" class="btn btn-primary px-3">
                            <i class="fas fa-search me-1"></i>Cari
                        </button>
                        <a href="{{ route('pengajuan.list') }}" class="btn btn-outline-secondary px-3">
                            <i class="fas fa-rotate-right me-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if($items->total() > 0)
            <div class="table-shell">
                <div class="table-responsive">
                    <table class="table table-professional align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="min-width: 140px;">Kode Pengajuan</th>
                                <th style="min-width: 140px;">Pengaju</th>
                                <th style="min-width: 160px;">Gudang</th>
                                <th style="min-width: 120px;">Jumlah Item</th>
                                <th style="min-width: 140px;">Tanggal</th>
                                <th style="min-width: 180px;">Status</th>
                                <th style="min-width: 130px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $it)
                            @php
                                $normalizedStatus = str_replace('-', '_', strtolower((string) $it->status));
                                if ($normalizedStatus === 'partially_approved') {
                                    $normalizedStatus = 'partial_approved';
                                }

                                $statusText = 'Ditolak';
                                $statusClass = 'status-rejected';
                                $statusIcon = 'fa-circle-xmark';

                                if ($normalizedStatus === 'pending') {
                                    $statusText = 'Menunggu Persetujuan';
                                    $statusClass = 'status-pending';
                                    $statusIcon = 'fa-clock';
                                } elseif ($normalizedStatus === 'approved') {
                                    $statusText = 'Disetujui';
                                    $statusClass = 'status-approved';
                                    $statusIcon = 'fa-circle-check';
                                } elseif ($normalizedStatus === 'partial_approved') {
                                    $statusText = 'Sebagian Disetujui';
                                    $statusClass = 'status-partial';
                                    $statusIcon = 'fa-circle-exclamation';
                                }
                            @endphp
                            <tr>
                                <td class="fw-semibold text-dark">{{ $it->kode_pengajuan }}</td>
                                <td>{{ $it->user_nama ?? 'Unknown' }}</td>
                                <td>{{ $it->nama_gudang ?? $it->kode_gudang }}</td>
                                <td>
                                    <span class="qty-pill">{{ $it->total_items }} item</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($it->tanggal)->format('d M Y') }}</td>
                                <td>
                                    <span class="status-pill {{ $statusClass }}">
                                        <i class="fas {{ $statusIcon }} me-1"></i>{{ $statusText }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('pengajuan.show', $it->id) }}" class="btn btn-sm btn-outline-primary fw-semibold">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $items->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="empty-state-card">
                <i class="fas fa-clipboard-check mb-3"></i>
                <h5 class="mb-2">Tidak ada pengajuan untuk di-review</h5>
                <p class="mb-0">Semua pengajuan sudah diproses atau belum ada data yang sesuai filter.</p>
            </div>
        @endif
    </div>
</div>

<style>
    .approval-page {
        max-width: 1320px;
        margin: 0 auto;
        padding: 1.2rem 0.4rem 1.8rem;
    }

    .approval-header {
        border: 1px solid #dbe7ff;
        border-radius: 18px;
        background: linear-gradient(110deg, #f0f6ff 0%, #ffffff 70%);
        padding: 1.2rem 1.35rem;
    }

    .approval-overline {
        font-size: 0.78rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
    }

    .filter-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 16px 30px -28px rgba(15, 23, 42, 0.35);
    }

    .form-control-soft {
        border-color: #dbe2ea;
        background-color: #f8fafc;
    }

    .form-control-soft:focus {
        border-color: #93c5fd;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
        background-color: #fff;
    }

    .table-shell {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #ffffff;
        overflow: hidden;
        box-shadow: 0 16px 30px -28px rgba(15, 23, 42, 0.35);
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

    .qty-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.3rem 0.65rem;
        font-size: 0.76rem;
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

    .empty-state-card {
        border: 1px dashed #cbd5e1;
        border-radius: 16px;
        background: #ffffff;
        padding: 3rem 1.2rem;
        text-align: center;
        color: #64748b;
    }

    .empty-state-card i {
        font-size: 2.8rem;
        color: #94a3b8;
    }

    @media (max-width: 575.98px) {
        .approval-header {
            padding: 1rem;
        }
    }
</style>
@endsection
