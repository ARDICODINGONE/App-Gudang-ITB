@extends('layouts/app')

@section('title', 'Gudang - Index')

@section('content')
    <div class="card">
        {{-- Header disamakan menggunakan h5 dan span, serta button dibungkus div --}}
        <h5 class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Gudang</span>
            <div>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                    data-bs-target="#modalTambahGudang">
                    <i class="icon-base ri ri-add-line me-1"></i>Tambah Gudang
                </button>
            </div>
        </h5>

        {{-- Menambahkan wrapper card-body sesuai struktur Barang --}}
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Error handling dipindahkan logic-nya ke script (buka modal) atau alert sederhana jika diinginkan --}}
            {{-- Jika ingin menampilkan list error di atas tabel seperti sebelumnya, bisa dikembalikan. 
           Namun untuk menyamakan struktur Barang, biasanya error form muncul di dalam modal. --}}

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Lokasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($gudangs as $gudang)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $gudang->kode_gudang }}</strong>
                                </td>
                                <td>{{ $gudang->nama_gudang }}</td>
                                <td>{{ $gudang->lokasi }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a class="btn btn-sm btn-outline-primary me-1" href="javascript:void(0);"
                                            onclick="editGudang(
                                                  '{{ route('gudang.update', $gudang->kode_gudang) }}', 
                                                  '{{ $gudang->kode_gudang }}', 
                                                  '{{ $gudang->nama_gudang }}', 
                                                  '{{ $gudang->lokasi }}'
                                                )">
                                            <i class="ri-pencil-line me-1"></i>
                                            Edit
                                        </a>
                                        <a class="btn btn-sm btn-outline-danger" href="javascript:void(0);"
                                            onclick="konfirmasiHapus('{{ route('gudang.destroy', $gudang->kode_gudang) }}')">
                                            <i class="ri-delete-bin-6-line me-1"></i>
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if ($gudangs->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data gudang.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('content.gudang.create')
    @include('content.gudang.delete')
    @include('content.gudang.update')
    {{-- Menambahkan script untuk handle error validation agar modal otomatis terbuka (seperti di Barang) --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if ($errors->any())
                    // Pastikan ID ini sesuai dengan ID yang ada di file content/gudang/create.blade.php
                    var myModal = new bootstrap.Modal(document.getElementById('modalTambahGudang'));
                    myModal.show();
                @endif
            });
        </script>
    @endpush
    <script>
        function konfirmasiHapus(actionUrl) {
            // 1. Ambil elemen form hapus
            var form = document.getElementById('formHapusGudang');

            // 2. Ubah atribut 'action' pada form sesuai URL dari tombol yang diklik
            form.action = actionUrl;

            // 3. Tampilkan modal secara manual menggunakan Bootstrap API
            var myModal = new bootstrap.Modal(document.getElementById('modalHapusGudang'));
            myModal.show();
        }

        function editGudang(actionUrl, kode, nama, lokasi) {
            // 1. Isi value input form dengan data yang dikirim dari tombol
            document.getElementById('edit_kode_gudang').value = kode;
            document.getElementById('edit_nama_gudang').value = nama;
            document.getElementById('edit_lokasi').value = lokasi;

            // 2. Ubah action form agar mengarah ke URL update yang benar
            document.getElementById('formEditGudang').action = actionUrl;

            // 3. Tampilkan modal
            var myModal = new bootstrap.Modal(document.getElementById('modalEditGudang'));
            myModal.show();
        }
    </script>
@endsection
