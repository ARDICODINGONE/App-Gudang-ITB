@extends('layouts/app')

@section('title', 'Laporan Barang Keluar')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 text-dark fw-bold mb-0">
                            <i class="ri-logout-box-line me-2"></i>Laporan Barang Keluar
                        </h1>
                        <p class="text-muted small mt-1">Pantau riwayat barang yang keluar dari gudang</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('laporan.barang-keluar.export-excel', request()->query()) }}" class="btn btn-outline-success">
                            <i class="ri-file-excel-line me-1"></i>Export Excel
                        </a>
                        <a href="{{ route('laporan.barang-keluar.export-pdf', request()->query()) }}" class="btn btn-outline-danger">
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
                <form method="GET" action="{{ route('laporan.barang-keluar') }}" class="row g-3">
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
                        <label class="form-label" for="gudang">Filter Gudang</label>
                        <select class="form-select" id="gudang" name="gudang">
                            <option value="">-- Semua Gudang --</option>
                            @foreach($gudangs as $g)
                                <option value="{{ $g->kode_gudang }}" {{ request('gudang') == $g->kode_gudang ? 'selected' : '' }}>
                                    {{ $g->nama_gudang }} ({{ $g->kode_gudang }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-search-line me-1"></i>Cari
                        </button>
                        <a href="{{ route('laporan.barang-keluar') }}" class="btn btn-secondary">
                            <i class="ri-refresh-line me-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border-0 bg-danger text-white">
                    <div class="card-body">
                        <h6 class="card-title opacity-75">Total Barang Keluar</h6>
                        <h2 class="mb-0">{{ number_format($totalJumlah, 0, ',', '.') }}</h2>
                        <small class="opacity-75">unit barang</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 bg-warning text-dark">
                    <div class="card-body">
                        <h6 class="card-title opacity-75">Nilai Total</h6>
                        <h2 class="mb-0">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h2>
                        <small class="opacity-75">nilai barang keluar</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Detail Barang Keluar</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Gudang</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Nilai Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangKeluar as $item)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $item->gudang->nama_gudang ?? '-' }}</span>
                                </td>
                                <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                <td class="text-end fw-bold">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($item->barang->harga ?? 0, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold text-danger">
                                    Rp {{ number_format($item->jumlah * ($item->barang->harga ?? 0), 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="ri-inbox-line" style="font-size: 2rem; opacity: 0.5;"></i>
                                    <p class="mt-2">Tidak ada data barang keluar</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($barangKeluar->count() > 0)
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="4" class="text-end">TOTAL</td>
                                <td class="text-end">{{ number_format($totalJumlah, 0, ',', '.') }}</td>
                                <td></td>
                                <td class="text-end">Rp {{ number_format($totalNilai, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
            <!-- Pagination -->
            @if($barangKeluar->hasPages())
                <div class="card-footer bg-white">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mb-0">
                            {{-- Previous Page Link --}}
                            @if ($barangKeluar->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">← Sebelumnya</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $barangKeluar->previousPageUrl() }}&{{ http_build_query(request()->except(['page'])) }}" rel="prev">← Sebelumnya</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($barangKeluar->getUrlRange(1, $barangKeluar->lastPage()) as $page => $url)
                                @if ($page == $barangKeluar->currentPage())
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
                            @if ($barangKeluar->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $barangKeluar->nextPageUrl() }}&{{ http_build_query(request()->except(['page'])) }}" rel="next">Selanjutnya →</a>
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
