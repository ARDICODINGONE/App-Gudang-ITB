@extends('layouts/app')

@section('title', 'Laporan Barang Keluar')

@section('content')
  <style>
    .report-page { max-width: 1320px; margin: 1.5rem auto; padding: 1rem; border-radius: 20px; background: linear-gradient(180deg, #fff8f8 0%, #ffffff 100%); box-shadow: 0 20px 44px -34px rgba(121, 26, 36, 0.42);} 
    .report-hero { border: 1px solid #f3d7dc; border-radius: 16px; padding: 1rem 1.25rem; background: linear-gradient(100deg, #dc3545 0%, #e85d69 72%, #ef818b 100%); color:#fff; }
    .report-hero h1 { margin:0; font-size:1.35rem; font-weight:700; }
    .report-hero p { margin:.35rem 0 0; font-size:.9rem; opacity:.93; }
    .report-panel,.report-table-wrap { background:#fff; border:1px solid #f1e1e4; border-radius:14px; box-shadow:0 12px 28px -30px rgba(121,26,36,.5); }
    .report-table thead th { font-size:.78rem; text-transform:uppercase; letter-spacing:.04em; color:#81616a; font-weight:700; white-space:nowrap; }
    .badge-soft-info { background:#e9f5ff; color:#0c6bcb; border:1px solid #cce8ff; border-radius:999px; padding:.28rem .58rem; font-weight:600; }
    .summary-card { border:0; border-radius:14px; color:#fff; }
    .summary-card.danger { background:linear-gradient(100deg, #dc3545, #e35a66); }
    .summary-card.warn { background:linear-gradient(100deg, #f59e0b, #f8b938); color:#1b1302; }
    .empty-state { text-align:center; color:#7b8ba8; padding:2.1rem 1rem; }
    @media (max-width: 767.98px){ .report-page{ margin:1rem .75rem; padding:.75rem;} }
  </style>

  <div class="report-page">
    <div class="report-hero d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
      <div>
        <h1><i class="bi bi-box-arrow-up-right me-2"></i>Laporan Barang Keluar</h1>
        <p>Pantau riwayat barang yang keluar dari gudang.</p>
      </div>
      <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('laporan.barang-keluar.export-excel', request()->query()) }}" class="btn btn-sm btn-light text-success fw-semibold"><i class="bi bi-file-earmark-excel me-1"></i>Export Excel</a>
        <a href="{{ route('laporan.barang-keluar.export-pdf', request()->query()) }}" class="btn btn-sm btn-light text-danger fw-semibold"><i class="bi bi-file-earmark-pdf me-1"></i>Export PDF</a>
        <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-outline-light fw-semibold"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
      </div>
    </div>

    <div class="report-panel p-3 p-md-4 mt-3">
      <form method="GET" action="{{ route('laporan.barang-keluar') }}" class="row g-3">
        <div class="col-md-4"><label class="form-label" for="dari_tanggal">Dari Tanggal</label><input type="date" class="form-control" id="dari_tanggal" name="dari_tanggal" value="{{ request('dari_tanggal') }}"></div>
        <div class="col-md-4"><label class="form-label" for="sampai_tanggal">Sampai Tanggal</label><input type="date" class="form-control" id="sampai_tanggal" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}"></div>
        <div class="col-md-4">
          <label class="form-label" for="gudang">Filter Gudang</label>
          <select class="form-select" id="gudang" name="gudang">
            <option value="">-- Semua Gudang --</option>
            @foreach($gudangs as $g)
              <option value="{{ $g->kode_gudang }}" {{ request('gudang') == $g->kode_gudang ? 'selected' : '' }}>{{ $g->nama_gudang }} ({{ $g->kode_gudang }})</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 d-flex gap-2">
          <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Cari</button>
          <a href="{{ route('laporan.barang-keluar') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-clockwise me-1"></i>Reset</a>
        </div>
      </form>
    </div>

    <div class="row g-3 mt-1 mb-2">
      <div class="col-md-6"><div class="summary-card danger p-3 h-100"><div class="small opacity-75">Total Barang Keluar</div><div class="h3 mb-0 fw-bold">{{ number_format($totalJumlah, 0, ',', '.') }}</div><small class="opacity-75">unit barang</small></div></div>
      <div class="col-md-6"><div class="summary-card warn p-3 h-100"><div class="small opacity-75">Nilai Total</div><div class="h3 mb-0 fw-bold">Rp {{ number_format($totalNilai, 0, ',', '.') }}</div><small class="opacity-75">nilai barang keluar</small></div></div>
    </div>

    <div class="report-table-wrap mt-3">
      <div class="px-3 px-md-4 pt-3"><h5 class="mb-2">Detail Barang Keluar</h5></div>
      <div class="table-responsive">
        <table class="table table-hover report-table mb-0 align-middle">
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
                <td><span class="badge-soft-info">{{ $item->gudang->nama_gudang ?? '-' }}</span></td>
                <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                <td class="fw-semibold">{{ $item->barang->nama_barang ?? '-' }}</td>
                <td class="text-end fw-bold">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($item->barang->harga ?? 0, 0, ',', '.') }}</td>
                <td class="text-end fw-bold text-danger">Rp {{ number_format($item->jumlah * ($item->barang->harga ?? 0), 0, ',', '.') }}</td>
              </tr>
            @empty
              <tr><td colspan="7"><div class="empty-state"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Tidak ada data barang keluar</div></td></tr>
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
      @if($barangKeluar->hasPages())
        <div class="p-3 border-top">{{ $barangKeluar->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
      @endif
    </div>
  </div>
@endsection
