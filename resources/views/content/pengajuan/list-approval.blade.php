@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Daftar Pengajuan - Approval</h3>
            <small class="text-muted">Total {{ $items->total() }} pengajuan</small>
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
                        <th>Jumlah Item</th>
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
                        <td>{{ $it->kode_gudang }}</td>
                        <td>
                            <span class="badge badge-primary">{{ $it->jumlah }} item</span>
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
            {{ $items->links('pagination::bootstrap-4') }}
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
