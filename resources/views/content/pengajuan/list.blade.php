@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Pengajuan Barang Saya</h3>
            <small class="text-muted">Total {{ $items->total() }} pengajuan</small>
        </div>
    </div>

    @if($items->total() > 0)
        <div class="row">
            @foreach($items as $it)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0 
                    @if($it->status === 'pending') border-warning 
                    @elseif($it->status === 'approved') border-success 
                    @else border-danger 
                    @endif" style="border-left: 4px solid;">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-truncate" style="max-width: 200px;" title="{{ $it->kode_pengajuan }}">
                            {{ $it->kode_pengajuan }}
                        </h6>
                        @if($it->status === 'pending')
                            <span class="badge bg-warning text-dark">⏳ Pending</span>
                        @elseif($it->status === 'approved')
                            <span class="badge bg-success">✓ Disetujui</span>
                        @elseif($it->status === 'partial_approved')
                            <span class="badge bg-info">⚠ Sebagian Disetujui</span>
                        @else
                            <span class="badge bg-danger">✗ Ditolak</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Gudang</small>
                            <strong>{{ $it->nama_gudang ?? $it->kode_gudang }}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Jumlah Item</small>
                            <strong class="badge bg-primary">{{ $it->jumlah }} item</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Tanggal Pengajuan</small>
                            <strong>{{ \Carbon\Carbon::parse($it->tanggal)->format('d M Y') }}</strong>
                        </div>
                        @if($it->note)
                        <div class="alert alert-sm alert-info py-2 px-3 mb-0">
                            <small><strong>Catatan:</strong> {{ $it->note }}</small>
                        </div>
                        @endif
                    </div>
                    <div class="card-footer bg-light">
                        <a href="{{ route('pengajuan.show', $it->id) }}" class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <nav aria-label="Page navigation">
            {{ $items->links('pagination::bootstrap-4') }}
        </nav>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
                <h5 class="mb-2">Belum Ada Pengajuan</h5>
                <p class="text-muted mb-0">Anda belum membuat pengajuan barang. Gunakan menu di navbar untuk membuat pengajuan baru.</p>
            </div>
        </div>
    @endif
</div>

<style>
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2px);
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }
</style>
@endsection
