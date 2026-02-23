@extends('layouts/app')

@section('title', 'Laporan Pengajuan')

@section('content')
  <style>
    .report-page { max-width: 1320px; margin: 1.5rem auto; padding: 1rem; border-radius: 20px; background: linear-gradient(180deg, #fffaf1 0%, #ffffff 100%); box-shadow: 0 20px 44px -34px rgba(109, 72, 15, 0.38);} 
    .report-hero { border: 1px solid #f3dfba; border-radius: 16px; padding: 1rem 1.25rem; background: linear-gradient(100deg, #f59e0b 0%, #f7b12f 72%, #fbc85f 100%); color:#1f1605; }
    .report-hero h1 { margin:0; font-size:1.35rem; font-weight:700; }
    .report-hero p { margin:.35rem 0 0; font-size:.9rem; opacity:.9; }
    .report-panel,.report-table-wrap { background:#fff; border:1px solid #f2e6cf; border-radius:14px; box-shadow:0 12px 28px -30px rgba(109,72,15,.46); }
    .report-table thead th { font-size:.78rem; text-transform:uppercase; letter-spacing:.04em; color:#7c6a48; font-weight:700; white-space:nowrap; }
    .summary-card { border:0; border-radius:14px; background:linear-gradient(100deg, #f59e0b, #f7b12f); color:#1f1605; }
    .empty-state { text-align:center; color:#7b8ba8; padding:2.1rem 1rem; }
    .stat-card { border:1px solid #ebedf2; border-radius:12px; background:#fff; }
    @media (max-width: 767.98px){ .report-page{ margin:1rem .75rem; padding:.75rem;} }
  </style>

  <div class="report-page">
    <div class="report-hero d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
      <div>
        <h1><i class="bi bi-card-checklist me-2"></i>Laporan Pengajuan</h1>
        <p>Lihat status dan detail setiap pengajuan.</p>
      </div>
      <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('laporan.pengajuan.export-excel', request()->query()) }}" class="btn btn-sm btn-light text-success fw-semibold"><i class="bi bi-file-earmark-excel me-1"></i>Export Excel</a>
        <a href="{{ route('laporan.pengajuan.export-pdf', request()->query()) }}" class="btn btn-sm btn-light text-danger fw-semibold"><i class="bi bi-file-earmark-pdf me-1"></i>Export PDF</a>
        <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-outline-dark fw-semibold"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
      </div>
    </div>

    <div class="report-panel p-3 p-md-4 mt-3">
      <form method="GET" action="{{ route('laporan.pengajuan') }}" class="row g-3">
        <div class="col-md-4"><label class="form-label" for="dari_tanggal">Dari Tanggal</label><input type="date" class="form-control" id="dari_tanggal" name="dari_tanggal" value="{{ request('dari_tanggal') }}"></div>
        <div class="col-md-4"><label class="form-label" for="sampai_tanggal">Sampai Tanggal</label><input type="date" class="form-control" id="sampai_tanggal" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}"></div>
        <div class="col-md-4">
          <label class="form-label" for="status">Status Pengajuan</label>
          <select class="form-select" id="status" name="status">
            <option value="">-- Semua Status --</option>
            @foreach($statusOptions as $s)
              <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 d-flex gap-2">
          <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Cari</button>
          <a href="{{ route('laporan.pengajuan') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-clockwise me-1"></i>Reset</a>
        </div>
      </form>
    </div>

    <div class="summary-card p-3 mt-3 mb-3">
      <div class="small opacity-75">Total Pengajuan</div>
      <div class="h3 mb-0 fw-bold">{{ number_format($totalPengajuan, 0, ',', '.') }}</div>
      <small class="opacity-75">pengajuan dalam periode terpilih</small>
    </div>

    <div class="report-table-wrap mt-3">
      <div class="px-3 px-md-4 pt-3"><h5 class="mb-2">Detail Pengajuan</h5></div>
      <div class="table-responsive">
        <table class="table table-hover report-table mb-0 align-middle">
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
                <td><span class="badge bg-secondary">{{ $p->id }}</span></td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                <td>
                  @if($p->user)
                    <small class="fw-semibold">{{ $p->user->name ?? $p->user->nama ?? '-' }}</small>
                  @else
                    <small class="text-muted"><i class="bi bi-person-x me-1"></i>Tidak ada</small>
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
                <td><small class="text-muted">{{ Str::limit($p->note ?? '-', 40) }}</small></td>
                <td>
                  <a href="{{ route('pengajuan.show', $p->id) }}" class="btn btn-sm btn-outline-info" title="Detail">
                    <i class="bi bi-eye"></i>
                  </a>
                </td>
              </tr>
            @empty
              <tr><td colspan="7"><div class="empty-state"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Tidak ada data pengajuan</div></td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($pengajuans->hasPages())
        <div class="p-3 border-top">{{ $pengajuans->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
      @endif
    </div>

    @if($pengajuans->count() > 0)
      <div class="row g-3 mt-3">
        <div class="col-md-3"><div class="stat-card p-3 text-center"><div class="text-muted small">Menunggu</div><div class="h4 text-warning mb-0">{{ $pengajuans->where('status', 'pending')->count() }}</div></div></div>
        <div class="col-md-3"><div class="stat-card p-3 text-center"><div class="text-muted small">Disetujui</div><div class="h4 text-success mb-0">{{ $pengajuans->where('status', 'approved')->count() }}</div></div></div>
        <div class="col-md-3"><div class="stat-card p-3 text-center"><div class="text-muted small">Ditolak</div><div class="h4 text-danger mb-0">{{ $pengajuans->where('status', 'rejected')->count() }}</div></div></div>
        <div class="col-md-3"><div class="stat-card p-3 text-center"><div class="text-muted small">Selesai</div><div class="h4 text-info mb-0">{{ $pengajuans->where('status', 'completed')->count() }}</div></div></div>
      </div>
    @endif
  </div>
@endsection
