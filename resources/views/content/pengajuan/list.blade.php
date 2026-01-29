@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Daftar Pengajuan</h3>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Gudang</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $it)
                <tr>
                    <td>{{ $it->kode_pengajuan }}</td>
                    <td>{{ $it->kode_gudang }}</td>
                    <td>{{ \Carbon\Carbon::parse($it->tanggal)->format('d/m') }}</td>
                    <td>{{ ucfirst($it->status) }}</td>
                    <td>
                        <a href="{{ route('pengajuan.show', $it->id) }}" class="btn btn-sm btn-primary">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $items->links() }}
    </div>
</div>
@endsection
