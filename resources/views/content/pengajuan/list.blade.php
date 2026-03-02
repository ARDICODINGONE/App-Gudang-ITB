@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="pengajuan-page">
        <div class="pengajuan-header mb-4">
            <div>
                <p class="pengajuan-overline mb-1">Riwayat Pengajuan</p>
                <h3 class="mb-1">Pengajuan Barang Saya</h3>
                <p class="text-muted mb-0">
                    Total {{ $items->total() }} pengajuan
                    <span class="mx-2">•</span>
                    Halaman {{ $items->currentPage() }} dari {{ $items->lastPage() }}
                </p>
            </div>
        </div>

        @if($items->total() > 0)
            <div class="row g-4">
                @foreach($items as $it)
                @php
                    $normalizedStatus = str_replace('-', '_', strtolower((string) $it->status));
                    if ($normalizedStatus === 'partially_approved') {
                        $normalizedStatus = 'partial_approved';
                    }

                    $statusText = 'Ditolak';
                    $statusClass = 'status-rejected';
                    $statusIcon = 'fa-circle-xmark';
                    $accentClass = 'accent-rejected';

                    if ($normalizedStatus === 'pending') {
                        $statusText = 'Menunggu Persetujuan';
                        $statusClass = 'status-pending';
                        $statusIcon = 'fa-clock';
                        $accentClass = 'accent-pending';
                    } elseif ($normalizedStatus === 'approved') {
                        $statusText = 'Disetujui';
                        $statusClass = 'status-approved';
                        $statusIcon = 'fa-circle-check';
                        $accentClass = 'accent-approved';
                    } elseif ($normalizedStatus === 'partial_approved') {
                        $statusText = 'Sebagian Disetujui';
                        $statusClass = 'status-partial';
                        $statusIcon = 'fa-circle-exclamation';
                        $accentClass = 'accent-partial';
                    }
                @endphp
                <div class="col-md-6 col-xl-4">
                    <article class="pengajuan-card h-100 {{ $accentClass }}">
                        <div class="card-main p-3 p-md-4">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                                <div class="pe-2">
                                    <p class="label mb-1">Kode Pengajuan</p>
                                    <h6 class="mb-0 code-text text-truncate" title="{{ $it->kode_pengajuan }}">
                                        {{ $it->kode_pengajuan }}
                                    </h6>
                                </div>
                                <span class="status-pill {{ $statusClass }}">
                                    <i class="fas {{ $statusIcon }} me-1"></i>{{ $statusText }}
                                </span>
                            </div>

                            <div class="detail-grid mb-3">
                                <div class="detail-item">
                                    <p class="label mb-1">Gudang</p>
                                    <p class="value mb-0">{{ $it->nama_gudang ?? $it->kode_gudang }}</p>
                                </div>
                                <div class="detail-item">
                                    <p class="label mb-1">Jumlah Item</p>
                                    <p class="value mb-0">{{ $it->jumlah }} item</p>
                                </div>
                                <div class="detail-item">
                                    <p class="label mb-1">Tanggal Pengajuan</p>
                                    <p class="value mb-0">{{ \Carbon\Carbon::parse($it->tanggal)->format('d M Y') }}</p>
                                </div>
                            </div>

                            @if($it->note)
                            <div class="note-box mb-3">
                                <p class="label mb-1">Catatan</p>
                                <p class="mb-0 note-text">{{ $it->note }}</p>
                            </div>
                            @endif

                            <a href="{{ route('pengajuan.show', $it->id) }}" class="btn btn-primary btn-sm w-100 fw-semibold">
                                <i class="fas fa-eye me-1"></i>Lihat Detail
                            </a>
                        </div>
                    </article>
                </div>
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $items->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="empty-state-card">
                <i class="fas fa-inbox mb-3"></i>
                <h5 class="mb-2">Belum Ada Pengajuan</h5>
                <p class="mb-0">Anda belum membuat pengajuan barang. Silakan gunakan menu pengajuan untuk membuat permintaan baru.</p>
            </div>
        @endif
    </div>
</div>

<style>
    .pengajuan-page {
        max-width: 1320px;
        margin: 0 auto;
        padding: 1.2rem 0.4rem 1.8rem;
    }

    .pengajuan-header {
        border: 1px solid #dbe7ff;
        border-radius: 18px;
        background: linear-gradient(110deg, #f0f6ff 0%, #ffffff 70%);
        padding: 1.2rem 1.35rem;
    }

    .pengajuan-overline {
        font-size: 0.78rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
    }

    .pengajuan-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 16px 30px -28px rgba(15, 23, 42, 0.45);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
    }

    .pengajuan-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 34px -28px rgba(15, 23, 42, 0.5);
    }

    .pengajuan-card::before {
        content: "";
        display: block;
        height: 4px;
        width: 100%;
    }

    .accent-pending::before {
        background: #f59e0b;
    }

    .accent-approved::before {
        background: #10b981;
    }

    .accent-partial::before {
        background: #0ea5e9;
    }

    .accent-rejected::before {
        background: #ef4444;
    }

    .label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .code-text {
        font-weight: 700;
        color: #0f172a;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.8rem;
    }

    .detail-item .value {
        font-weight: 600;
        color: #0f172a;
        line-height: 1.4;
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
        .pengajuan-header {
            padding: 1rem;
        }

        .status-pill {
            font-size: 0.7rem;
            padding: 0.3rem 0.55rem;
        }
    }
</style>
@endsection
