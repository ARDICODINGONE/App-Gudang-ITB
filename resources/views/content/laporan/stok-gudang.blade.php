@extends('layouts/app')

@section('title', 'Laporan Stok Gudang')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 text-dark fw-bold mb-0">
                            <i class="ri-database-2-line me-2"></i>Laporan Stok Gudang
                        </h1>
                        <p class="text-muted small mt-1">Lihat detail stok barang di setiap gudang</p>
                    </div>
                    <div class="d-flex gap-2">
                        @if(auth()->user()->role === 'atasan')
                        <a href="{{ route('laporan.stok-gudang.export-excel', request()->query()) }}" class="btn btn-outline-success">
                            <i class="ri-file-excel-line me-1"></i>Export Excel
                        </a>
                        <a href="{{ route('laporan.stok-gudang.export-pdf', request()->query()) }}" class="btn btn-outline-danger">
                            <i class="ri-file-pdf-line me-1"></i>Export PDF
                        </a>
                        @endif
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
                <form method="GET" action="{{ route('laporan.stok-gudang') }}" class="row g-3">
                    <div class="col-md-6">
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
                    <div class="col-md-6 d-flex gap-2 align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-search-line me-1"></i>Tampilkan
                        </button>
                        <a href="{{ route('laporan.stok-gudang') }}" class="btn btn-secondary">
                            <i class="ri-refresh-line me-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border-0 bg-primary text-white">
                    <div class="card-body">
                        <h6 class="card-title opacity-75">Total Stok</h6>
                        <h2 class="mb-0">{{ number_format($totalStok, 0, ',', '.') }}</h2>
                        <small class="opacity-75">unit barang</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 bg-success text-white">
                    <div class="card-body">
                        <h6 class="card-title opacity-75">Nilai Total</h6>
                        <h2 class="mb-0">Rp {{ number_format($totalValue, 0, ',', '.') }}</h2>
                        <small class="opacity-75">nilai stok keseluruhan</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Detail Stok Barang</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Gudang</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th class="text-end">Stok</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Nilai Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokWithValue as $stok)
                            <tr>
                                <td>
                                    <span class="badge bg-info">{{ $stok->gudang->nama_gudang ?? '-' }}</span>
                                </td>
                                <td>{{ $stok->barang->kode_barang ?? '-' }}</td>
                                <td>{{ $stok->barang->nama_barang ?? '-' }}</td>
                                <td class="text-end fw-bold">{{ number_format($stok->stok, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($stok->barang->harga ?? 0, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold text-success">Rp {{ number_format($stok->total_value, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="ri-inbox-line" style="font-size: 2rem; opacity: 0.5;"></i>
                                    <p class="mt-2">Tidak ada data stok</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($stokWithValue->count() > 0)
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="3" class="text-end">TOTAL</td>
                                <td class="text-end">{{ number_format($totalStok, 0, ',', '.') }}</td>
                                <td></td>
                                <td class="text-end">Rp {{ number_format($totalValue, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
            <!-- Pagination -->
            @if($stokWithValue->hasPages())
                <div class="card-footer bg-white">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mb-0">
                            {{-- Previous Page Link --}}
                            @if ($stokWithValue->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">← Sebelumnya</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $stokWithValue->previousPageUrl() }}&{{ http_build_query(request()->except(['page'])) }}" rel="prev">← Sebelumnya</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($stokWithValue->getUrlRange(1, $stokWithValue->lastPage()) as $page => $url)
                                @if ($page == $stokWithValue->currentPage())
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
                            @if ($stokWithValue->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $stokWithValue->nextPageUrl() }}&{{ http_build_query(request()->except(['page'])) }}" rel="next">Selanjutnya →</a>
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
