@extends('layouts/app')

@section('title', 'Barang Keluar - Index')

@section('content')
    <div class="card">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Barang Keluar</span>
        </h5>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Gudang</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ optional($item->barang)->nama_barang }}</td>
                                <td>{{ optional($item->gudang)->kode_gudang }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>{{ $item->tanggal }}</td>
                            </tr>
                        @endforeach

                        @if ($items->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data barang keluar.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('content.barang_keluar.create')
    @include('content.barang_keluar.delete')
    @include('content.barang_keluar.update')

    <script>
        function konfirmasiHapus(actionUrl) {
            var form = document.getElementById('formHapusBarangKeluar');
            form.action = actionUrl;
            var myModal = new bootstrap.Modal(document.getElementById('modalHapusBarangKeluar'));
            myModal.show();
        }

        function editBarangKeluar(actionUrl, id, id_barang, kode_gudang, jumlah, tanggal) {
            document.getElementById('edit_id_bk').value = id;
            document.getElementById('edit_id_barang').value = id_barang;
            document.getElementById('edit_kode_gudang').value = kode_gudang;
            document.getElementById('edit_jumlah').value = jumlah;
            document.getElementById('edit_tanggal').value = tanggal;
            document.getElementById('formEditBarangKeluar').action = actionUrl;
            var myModal = new bootstrap.Modal(document.getElementById('modalEditBarangKeluar'));
            myModal.show();
        }
    </script>
@endsection
