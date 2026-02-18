@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Daftar Pengajuan - Approval</h3>
            <small class="text-muted">Total {{ $items->total() }} pengajuan</small>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('pengajuan.list') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label" for="kode_gudang">Gudang</label>
                    <select class="form-select" id="kode_gudang" name="kode_gudang">
                        <option value="">-- Semua Gudang --</option>
                        @foreach($gudangs as $g)
                            <option value="{{ $g->kode_gudang }}" {{ request('kode_gudang') == $g->kode_gudang ? 'selected' : '' }}>
                                {{ $g->nama_gudang }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">-- Semua Status --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✓ Disetujui</option>
                        <option value="partial_approved" {{ request('status') == 'partial_approved' ? 'selected' : '' }}>⚠ Sebagian Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>✗ Ditolak</option>
                    </select>
                </div>
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
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-search-line me-1"></i>Cari
                    </button>
                    <a href="{{ route('pengajuan.list') }}" class="btn btn-secondary">
                        <i class="ri-refresh-line me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if($items->total() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="width: 120px;">Kode Pengajuan</th>
                        <th>Pengaju</th>
                        <th>Gudang</th>
                        <th>Jenis Item</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $it)
                    <tr class="@if($it->status === 'pending') table-warning @elseif($it->status === 'approved') table-success @else table-danger @endif">
                        <td>
                            <strong>{{ $it->kode_pengajuan }}</strong>
                        </td>
                        <td>
                            <small>{{ $it->user_nama ?? 'Unknown' }}</small>
                        </td>
                        <td>{{ $it->nama_gudang ?? $it->kode_gudang }}</td>
                        <td>
                            <span class="badge badge-primary">{{ $it->total_items }} item</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($it->tanggal)->format('d M Y') }}</td>
                        <td>
                            @if($it->status === 'pending')
                                <span class="badge bg-warning text-dark">⏳ Pending</span>
                            @elseif($it->status === 'approved')
                                <span class="badge bg-success">✓ Disetujui</span>
                            @elseif($it->status === 'partial_approved')
                                <span class="badge bg-info">⚠ Sebagian Disetujui</span>
                            @else
                                <span class="badge bg-danger">✗ Ditolak</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('pengajuan.show', $it->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation">
            {{ $items->appends(request()->query())->links('pagination::bootstrap-4') }}
        </nav>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
            <p class="mb-0"><strong>Tidak ada pengajuan yang perlu di-review</strong></p>
            <small class="text-muted">Semua pengajuan sudah diproses</small>
        </div>
    @endif
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .badge {
        padding: 0.35rem 0.65rem;
        font-size: 0.75rem;
    }
</style>
@endsection
