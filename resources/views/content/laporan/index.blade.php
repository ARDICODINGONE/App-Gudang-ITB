@extends('layouts/app')

@section('title', 'Laporan - Index')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 text-dark fw-bold">
                    <i class="ri-bar-chart-line me-2"></i>Laporan Sistem
                </h1>
                <p class="text-muted">Kelola dan lihat laporan stok, barang masuk, barang keluar, dan pengajuan</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Laporan Stok Gudang -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 transition-card" style="cursor: pointer;"
                    onclick="window.location.href = '{{ route('laporan.stok-gudang') }}'">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="ri-database-2-line" style="font-size: 3rem; color: #007bff;"></i>
                        </div>
                        <h5 class="card-title fw-bold">Laporan Stok Gudang</h5>
                        <p class="card-text text-muted small">Lihat detail stok barang di setiap gudang</p>
                        <a href="{{ route('laporan.stok-gudang') }}" class="btn btn-sm btn-primary mt-2">
                            Buka Laporan <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Laporan Barang Masuk -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 transition-card" style="cursor: pointer;"
                    onclick="window.location.href = '{{ route('laporan.barang-masuk') }}'">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="ri-inbox-archive-line" style="font-size: 3rem; color: #28a745;"></i>
                        </div>
                        <h5 class="card-title fw-bold">Laporan Barang Masuk</h5>
                        <p class="card-text text-muted small">Pantau riwayat barang yang masuk ke gudang</p>
                        <a href="{{ route('laporan.barang-masuk') }}" class="btn btn-sm btn-success mt-2">
                            Buka Laporan <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Laporan Barang Keluar -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 transition-card" style="cursor: pointer;"
                    onclick="window.location.href = '{{ route('laporan.barang-keluar') }}'">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="ri-logout-box-line" style="font-size: 3rem; color: #dc3545;"></i>
                        </div>
                        <h5 class="card-title fw-bold">Laporan Barang Keluar</h5>
                        <p class="card-text text-muted small">Pantau riwayat barang yang keluar dari gudang</p>
                        <a href="{{ route('laporan.barang-keluar') }}" class="btn btn-sm btn-danger mt-2">
                            Buka Laporan <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Laporan Pengajuan -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 transition-card" style="cursor: pointer;"
                    onclick="window.location.href = '{{ route('laporan.pengajuan') }}'">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="ri-file-list-line" style="font-size: 3rem; color: #ffc107;"></i>
                        </div>
                        <h5 class="card-title fw-bold">Laporan Pengajuan</h5>
                        <p class="card-text text-muted small">Lihat status dan detail setiap pengajuan</p>
                        <a href="{{ route('laporan.pengajuan') }}" class="btn btn-sm btn-warning mt-2">
                            Buka Laporan <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Laporan Riwayat Pengajuan -->
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 transition-card" style="cursor: pointer;"
                    onclick="window.location.href = '{{ route('laporan.riwayat-pengajuan') }}'">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="ri-history-line" style="font-size: 3rem; color: #17a2b8;"></i>
                        </div>
                        <h5 class="card-title fw-bold">Riwayat Pengajuan</h5>
                        <p class="card-text text-muted small">Lihat riwayat pengajuan dengan kontrol akses user</p>
                        <a href="{{ route('laporan.riwayat-pengajuan') }}" class="btn btn-sm btn-info mt-2">
                            Buka Laporan <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .transition-card {
            transition: all 0.3s ease;
        }

        .transition-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
@endsection
