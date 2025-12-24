@extends('layouts/app')

@section('title', 'Supplier - Index')

@section('content')
    <div class="card">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Supplier</span>
            <div>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                    data-bs-target="#modalTambahSupplier">
                    <i class="icon-base ri ri-add-line me-1"></i>Tambah Supplier
                </button>
            </div>
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
                            <th>Nama Supplier</th>
                            <th>Alamat</th>
                            <th>No. Telp</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $supplier->nama_supplier }}</strong></td>
                                <td>{{ $supplier->alamat }}</td>
                                <td>{{ $supplier->no_telp }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a class="btn btn-sm btn-outline-primary me-1" href="javascript:void(0);"
                                            onclick='editSupplier({!! json_encode(route('supplier.update', $supplier->id)) !!}, {!! json_encode($supplier->id) !!}, {!! json_encode($supplier->nama_supplier) !!}, {!! json_encode($supplier->alamat) !!}, {!! json_encode($supplier->no_telp) !!} )'>
                                            <i class="ri-pencil-line me-1"></i>
                                            Edit
                                        </a>
                                        <a class="btn btn-sm btn-outline-danger" href="javascript:void(0);"
                                            onclick='konfirmasiHapus({!! json_encode(route('supplier.destroy', $supplier->id)) !!})'>
                                            <i class="ri-delete-bin-6-line me-1"></i>
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if ($suppliers->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data supplier.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('content.supplier.create')
    @include('content.supplier.delete')
    @include('content.supplier.update')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                var myModal = new bootstrap.Modal(document.getElementById('modalTambahSupplier'));
                myModal.show();
            @endif
        });
    </script>

    <script>
        function konfirmasiHapus(actionUrl) {
            var form = document.getElementById('formHapusSupplier');
            form.action = actionUrl;
            var myModal = new bootstrap.Modal(document.getElementById('modalHapusSupplier'));
            myModal.show();
        }

        function editSupplier(actionUrl, id, nama, alamat, no_telp) {
            document.getElementById('edit_id_supplier').value = id;
            document.getElementById('edit_nama_supplier').value = nama;
            document.getElementById('edit_alamat').value = alamat;
            document.getElementById('edit_no_telp').value = no_telp;
            document.getElementById('formEditSupplier').action = actionUrl;
            var myModal = new bootstrap.Modal(document.getElementById('modalEditSupplier'));
            myModal.show();
        }
    </script>
@endsection
