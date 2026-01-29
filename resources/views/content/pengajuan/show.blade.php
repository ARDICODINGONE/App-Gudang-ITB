@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Detail Pengajuan: {{ $pengajuan->kode_pengajuan ?? '-' }}</h3>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Gudang:</strong> {{ $pengajuan->kode_gudang}}</p>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($pengajuan->tanggal)->toDateString() }}</p>
            <p><strong>Status:</strong> {{ ucfirst($pengajuan->status) }}</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $d)
                <tr>
                    <td>{{ $d->nama_barang ?? '-' }}</td>
                    <td>{{ $d->jumlah }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a href="{{ route('pengajuan.list') }}" class="btn btn-secondary mt-3">Kembali</a>
    @if(auth()->check() && in_array(auth()->user()->role, ['approval','atasan','admin']) && ($pengajuan->status ?? '') === 'pending')
    <div class="mt-3">
        <form method="POST" action="{{ route('pengajuan.approve', $pengajuan->id) }}" style="display:inline-block;">
            @csrf
            <button type="submit" class="btn btn-success" onclick="return confirm('Yakin ingin menyetujui pengajuan ini?')">Setujui</button>
        </form>

        <form id="reject-form-{{ $pengajuan->id }}" method="POST" action="{{ route('pengajuan.reject', $pengajuan->id) }}" style="display:inline-block; margin-left:8px;">
            @csrf
            <input type="hidden" name="note" id="reject-note-{{ $pengajuan->id }}" value="">
            <button type="button" class="btn btn-danger" onclick="var reason = prompt('Alasan penolakan (opsional):'); if(reason !== null){ document.getElementById('reject-note-{{ $pengajuan->id }}').value = reason; document.getElementById('reject-form-{{ $pengajuan->id }}').submit(); }">Tolak</button>
        </form>
    </div>
    @endif
</div>
@endsection
