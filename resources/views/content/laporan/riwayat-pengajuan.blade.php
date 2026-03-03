@extends('layouts/app')

@section('title', 'Laporan Riwayat Pengajuan')

@section('content')
  @php
    $canViewAllRiwayat = false;
  @endphp
  <style>
    .report-page { max-width: 1320px; margin: 1.5rem auto; padding: 1rem; border-radius: 20px; background: linear-gradient(180deg, #f7fbff 0%, #ffffff 100%); box-shadow: 0 20px 44px -34px rgba(24, 72, 128, 0.38);} 
    .report-hero { border: 1px solid #d8e8fb; border-radius: 16px; padding: 1rem 1.25rem; background: linear-gradient(100deg, #0ea5c6 0%, #23b7d8 72%, #53c8e3 100%); color:#fff; }
    .report-hero h1 { margin:0; font-size:1.35rem; font-weight:700; }
    .report-hero p { margin:.35rem 0 0; font-size:.9rem; opacity:.93; }
    .report-panel,.report-table-wrap,.info-card { background:#fff; border:1px solid #deebf9; border-radius:14px; box-shadow:0 12px 28px -30px rgba(24,72,128,.46); }
    .report-table thead th { font-size:.78rem; text-transform:uppercase; letter-spacing:.04em; color:#617993; font-weight:700; white-space:nowrap; }
    .summary-card { border:0; border-radius:14px; color:#fff; }
    .summary-card.primary { background:linear-gradient(100deg, #0d6efd, #3b92ff); }
    .summary-card.warning { background:linear-gradient(100deg, #f59e0b, #f8b93a); color:#261a03; }
    .summary-card.success { background:linear-gradient(100deg, #198754, #35a46e); }
    .summary-card.danger { background:linear-gradient(100deg, #dc3545, #e35c68); }
    .empty-state { text-align:center; color:#7b8ba8; padding:2.1rem 1rem; }
    .badge-soft { background:#eff5fb; color:#325d8f; border:1px solid #dbe8f8; border-radius:999px; padding:.28rem .58rem; font-weight:600; }
    @media (max-width: 767.98px){ .report-page{ margin:1rem .75rem; padding:.75rem;} }
  </style>

  <div class="report-page">
    <div class="report-hero d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
      <div>
        <h1><i class="bi bi-clock-history me-2"></i>Riwayat Pengajuan</h1>
        <p>
          Lihat riwayat pengajuan Anda
        </p>
      </div>
      <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('laporan.riwayat-pengajuan.export-excel', request()->query()) }}" class="btn btn-sm btn-light text-success fw-semibold"><i class="bi bi-file-earmark-excel me-1"></i>Export Excel</a>
        <a href="{{ route('laporan.riwayat-pengajuan.export-pdf', request()->query()) }}" class="btn btn-sm btn-light text-danger fw-semibold"><i class="bi bi-file-earmark-pdf me-1"></i>Export PDF</a>
        <a href="{{ url('/') }}" class="btn btn-sm btn-outline-light fw-semibold"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
      </div>
    </div>

    <div class="report-panel p-3 p-md-4 mt-3">
      <form method="GET" action="{{ route('laporan.riwayat-pengajuan') }}" class="row g-3">
        @if($canViewAllRiwayat)
          <div class="col-md-3">
            <label class="form-label" for="user">User</label>
            <select class="form-select" id="user" name="user">
              <option value="">-- Semua User --</option>
              @foreach($users as $u)
                <option value="{{ $u->id }}" {{ request('user') == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
              @endforeach
            </select>
          </div>
        @endif

        <div class="col-md-3"><label class="form-label" for="dari_tanggal">Dari Tanggal</label><input type="date" class="form-control" id="dari_tanggal" name="dari_tanggal" value="{{ request('dari_tanggal') }}"></div>
        <div class="col-md-3"><label class="form-label" for="sampai_tanggal">Sampai Tanggal</label><input type="date" class="form-control" id="sampai_tanggal" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}"></div>
        <div class="col-md-3">
          <label class="form-label" for="status">Status</label>
          <select class="form-select" id="status" name="status">
            <option value="">-- Semua Status --</option>
            @foreach($statusOptions as $s)
              <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 d-flex gap-2">
          <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Cari</button>
          <a href="{{ route('laporan.riwayat-pengajuan') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-clockwise me-1"></i>Reset</a>
        </div>
      </form>
    </div>

    <div class="row g-3 mt-1 mb-2">
      <div class="col-md-3"><div class="summary-card primary p-3 h-100"><div class="small opacity-75">Total Pengajuan</div><div class="h3 mb-0 fw-bold">{{ number_format($totalPengajuan, 0, ',', '.') }}</div><small class="opacity-75">dalam periode terpilih</small></div></div>
      <div class="col-md-3"><div class="summary-card warning p-3 h-100"><div class="small opacity-75">Menunggu</div><div class="h3 mb-0 fw-bold">{{ $pengajuans->where('status', 'pending')->count() }}</div><small class="opacity-75">perlu diproses</small></div></div>
      <div class="col-md-3"><div class="summary-card success p-3 h-100"><div class="small opacity-75">Disetujui</div><div class="h3 mb-0 fw-bold">{{ $pengajuans->where('status', 'approved')->count() }}</div><small class="opacity-75">sudah approved</small></div></div>
      <div class="col-md-3"><div class="summary-card danger p-3 h-100"><div class="small opacity-75">Ditolak</div><div class="h3 mb-0 fw-bold">{{ $pengajuans->where('status', 'rejected')->count() }}</div><small class="opacity-75">ditolak</small></div></div>
    </div>

    <div class="report-table-wrap mt-3">
      <div class="px-3 px-md-4 pt-3"><h5 class="mb-2">Detail Riwayat Pengajuan</h5></div>
      <div class="table-responsive">
        <table class="table table-hover report-table mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th class="text-center" style="width: 60px;">No</th>
              <th>Tanggal</th>
              <th>Kode Pengajuan</th>
              @if($canViewAllRiwayat)
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
                <td><span class="fw-semibold">{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</span></td>
                <td><span class="badge bg-secondary">{{ $p->kode_pengajuan }}</span></td>
                @if($canViewAllRiwayat)
                  <td>
                    @if($p->user)
                      <small class="fw-semibold">{{ $p->user->nama ?? '-' }}</small><br>
                      <small class="text-muted">{{ $p->user->role ?? '-' }}</small>
                    @else
                      <small class="text-muted"><i class="bi bi-person-x me-1"></i>Tidak ada</small>
                    @endif
                  </td>
                @endif
                <td><small class="text-dark">{{ $p->gudang->nama_gudang ?? '-' }}</small></td>
                <td class="text-center"><span class="badge-soft">{{ $p->details->sum('jumlah') ?? 0 }}</span></td>
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
                <td><small class="text-muted" title="{{ $p->note ?? '-' }}">{{ Str::limit($p->note ?? '-', 30) }}</small></td>
                <td class="text-center"><a href="{{ route('pengajuan.show', $p->id) }}" class="btn btn-sm btn-outline-info" title="Detail Pengajuan"><i class="bi bi-eye"></i></a></td>
              </tr>
            @empty
              <tr>
                <td colspan="{{ $canViewAllRiwayat ? 9 : 8 }}">
                  <div class="empty-state"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Tidak ada riwayat pengajuan untuk periode ini</div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($pengajuans->hasPages())
        <div class="p-3 border-top">{{ $pengajuans->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
      @endif
    </div>

    <div class="info-card mt-4 p-3 p-md-4">
      <div class="row g-3">
        <div class="col-md-6">
          <h6 class="fw-bold mb-2"><i class="bi bi-shield-check text-success me-2"></i>Kontrol Akses</h6>
          <ul class="small list-unstyled mb-0">
            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Anda hanya dapat melihat pengajuan Anda sendiri</li>
            <li class="mb-2"><i class="bi bi-lock text-warning me-2"></i>Pengajuan pengguna lain tidak dapat diakses</li>
          </ul>
        </div>
        <div class="col-md-6">
          <h6 class="fw-bold mb-2"><i class="bi bi-info-circle text-info me-2"></i>Keterangan Status</h6>
          <ul class="small list-unstyled mb-0">
            <li class="mb-2"><span class="badge bg-warning">Menunggu</span> - Pengajuan belum diproses</li>
            <li class="mb-2"><span class="badge bg-success">Disetujui</span> - Pengajuan telah disetujui</li>
            <li class="mb-2"><span class="badge bg-danger">Ditolak</span> - Pengajuan telah ditolak</li>
            <li class="mb-2"><span class="badge bg-info">Selesai</span> - Pengajuan telah selesai</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection
