@extends('layouts/app')

@section('title', 'Barang Masuk - Index')

@section('content')
    <div class="card">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Barang Masuk</span>
            <div>
                <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#modalTambahBarangMasuk">
                    <i class="ri ri-add-line me-1"></i>Tambah Barang Masuk
                </button>
                <button type="button" class="btn btn-sm btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#importBarangMasukModal">
                    <i class="ri-upload-line me-1"></i>Import
                </button>
                <a href="{{ route('barang-masuk.export') }}" class="btn btn-sm btn-success me-2">
                    <i class="ri-download-line me-1"></i>Export
                </a>
            </div>
        </h5>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('import_errors'))
                <div class="alert alert-warning">
                    <strong>Beberapa baris gagal diimpor:</strong>
                    <ul class="mb-0">
                        @foreach(session('import_errors') as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
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
                            <th>User</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ optional($item->barang)->nama_barang }}</td>
                                <td>{{ optional($item->gudang)->nama_gudang }}</td>
                            
                                <td>{{ $item->jumlah }}</td>
                                <td>{{ $item->tanggal }}</td>
                                <td>{{ auth()->user()->nama ?? auth()->user()->username }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a class="btn btn-sm btn-outline-primary me-1" href="javascript:void(0);"
                                            onclick='editBarangMasuk({!! json_encode(route("barang-masuk.update", $item->id)) !!}, {!! json_encode($item->id) !!}, {!! json_encode($item->id_barang) !!}, {!! json_encode($item->kode_gudang) !!}, {!! json_encode($item->jumlah) !!}, {!! json_encode($item->tanggal) !!})'>
                                            <i class="ri-pencil-line me-1"></i> Edit
                                        </a>
                                        <a class="btn btn-sm btn-outline-danger" href="javascript:void(0);"
                                            onclick='konfirmasiHapus({!! json_encode(route("barang-masuk.destroy", $item->id)) !!})'>
                                            <i class="ri-delete-bin-6-line me-1"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if ($items->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data barang masuk.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('content.barang_masuk.create')
    @include('content.barang_masuk.delete')
    @include('content.barang_masuk.update')
    @include('content.barang_masuk.import')

        <script>
        function konfirmasiHapus(actionUrl) {
            var form = document.getElementById('formHapusBarangMasuk');
            form.action = actionUrl;
            var myModal = new bootstrap.Modal(document.getElementById('modalHapusBarangMasuk'));
            myModal.show();
        }

        function editBarangMasuk(actionUrl, id, id_barang, kode_gudang, jumlah, tanggal) {
            document.getElementById('edit_id_bm').value = id;
            document.getElementById('edit_id_barang').value = id_barang;
            document.getElementById('edit_kode_gudang').value = kode_gudang;
            document.getElementById('edit_jumlah').value = jumlah;
            document.getElementById('edit_tanggal').value = tanggal;
            document.getElementById('formEditBarangMasuk').action = actionUrl;
            var myModal = new bootstrap.Modal(document.getElementById('modalEditBarangMasuk'));
            myModal.show();
        }
    </script>
@endsection
