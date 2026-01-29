@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Detail Pengajuan</h3>
            <small class="text-muted">{{ $pengajuan->kode_pengajuan ?? '-' }}</small>
        </div>
        <a href="{{ route('pengajuan.list') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-7">
            <!-- Info Card -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        @if($pengajuan->status === 'pending')
                            <span class="badge bg-warning text-dark">⏳ Menunggu Persetujuan</span>
                        @elseif($pengajuan->status === 'approved')
                            <span class="badge bg-success">✓ Disetujui</span>
                        @else
                            <span class="badge bg-danger">✗ Ditolak</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Gudang</small>
                            <strong class="fs-5">{{ $pengajuan->kode_gudang }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Total Jumlah</small>
                            <strong class="fs-5 badge bg-primary">{{ $pengajuan->jumlah }} item</strong>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Tanggal Pengajuan</small>
                            <strong>{{ \Carbon\Carbon::parse($pengajuan->tanggal)->format('d M Y') }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Dibuat</small>
                            <strong>{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d M Y H:i') }}</strong>
                        </div>
                    </div>

                    @if($pengajuan->note)
                    <div class="alert alert-info mt-3 mb-0">
                        <small><strong>Catatan:</strong> {{ $pengajuan->note }}</small>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Detail Barang -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Detail Barang yang Diajukan</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($details as $index => $d)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $d->nama_barang ?? '-' }}</td>
                                <td class="text-end">
                                    <span class="badge bg-secondary">{{ $d->jumlah }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox"></i> Tidak ada detail barang
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-5">
            <!-- Pengaju Info Card -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Pengaju</h5>
                </div>
                <div class="card-body">
                    @if($user_pengaju)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-circle" style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 15px;">
                            {{ strtoupper(substr($user_pengaju->nama ?? $user_pengaju->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <strong class="d-block">{{ $user_pengaju->nama ?? $user_pengaju->name ?? '-' }}</strong>
                            <small class="text-muted">{{ $user_pengaju->username ?? '-' }}</small>
                        </div>
                    </div>
                    @else
                    <p class="text-muted mb-0">Informasi pengaju tidak tersedia</p>
                    @endif
                </div>
            </div>

            <!-- Approval Actions -->
            @if($isApprover && $pengajuan->status === 'pending')
            <div class="card border-warning shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Aksi Persetujuan</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Anda adalah approval team. Silakan setujui atau tolak pengajuan ini.</p>

                    <form method="POST" action="{{ route('pengajuan.approve', $pengajuan->id) }}" style="margin-bottom: 10px;">
                        @csrf
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Yakin ingin menyetujui pengajuan ini?')">
                            <i class="fas fa-check-circle"></i> Setujui Pengajuan
                        </button>
                    </form>

                    <form id="reject-form-{{ $pengajuan->id }}" method="POST" action="{{ route('pengajuan.reject', $pengajuan->id) }}">
                        @csrf
                        <input type="hidden" name="note" id="reject-note-{{ $pengajuan->id }}" value="">
                        <button type="button" class="btn btn-danger w-100" onclick="askReason({{ $pengajuan->id }})">
                            <i class="fas fa-times-circle"></i> Tolak Pengajuan
                        </button>
                    </form>
                </div>
            </div>
            @elseif($isApprover && $pengajuan->status === 'approved')
            <div class="card border-success shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Status Persetujuan</h5>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0"><strong>Pengajuan Telah Disetujui</strong></p>
                    <small class="text-muted d-block">Stok gudang sudah dikurangi sesuai pesanan</small>
                </div>
            </div>
            @elseif($isApprover && $pengajuan->status === 'rejected')
            <div class="card border-danger shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Status Penolakan</h5>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-times-circle text-danger" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0"><strong>Pengajuan Telah Ditolak</strong></p>
                    <small class="text-muted d-block">Silakan hubungi tim approval untuk informasi lebih lanjut</small>
                </div>
            </div>
            @elseif($isPengaju && $pengajuan->status === 'pending')
            <div class="card border-info shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Status Pengajuan</h5>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-hourglass-half text-info" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0"><strong>Menunggu Persetujuan</strong></p>
                    <small class="text-muted d-block">Tim approval akan memproses pengajuan Anda segera</small>
                </div>
            </div>
            @elseif($isPengaju && $pengajuan->status === 'approved')
            <div class="card border-success shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Status Pengajuan</h5>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0"><strong>Pengajuan Disetujui!</strong></p>
                    <small class="text-muted d-block">Barang Anda sudah disiapkan untuk diambil</small>
                </div>
            </div>
            @elseif($isPengaju && $pengajuan->status === 'rejected')
            <div class="card border-danger shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Status Pengajuan</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-times-circle text-danger" style="font-size: 3rem;"></i>
                        <p class="mt-3 mb-0"><strong>Pengajuan Ditolak</strong></p>
                    </div>
                    <div class="alert alert-danger mb-0">
                        <small><strong>Alasan:</strong> {{ $pengajuan->note ?? 'Tidak ada alasan diberikan' }}</small>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function askReason(pengajuanId) {
    var reason = prompt('Alasan penolakan (opsional):');
    if (reason !== null) {
        document.getElementById('reject-note-' + pengajuanId).value = reason;
        document.getElementById('reject-form-' + pengajuanId).submit();
    }
}
</script>

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .card {
        border-radius: 0.5rem;
    }
</style>
@endsection
