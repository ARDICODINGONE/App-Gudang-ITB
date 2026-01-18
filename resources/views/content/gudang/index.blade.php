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

            <div class="row g-4">
                @foreach ($gudangs as $gudang)
                    <div class="col-md-6 col-lg-4">
                        <div class="border bg-white rounded p-4 h-100 d-flex flex-column">
                            @if($gudang->images)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $gudang->images) }}" alt="{{ $gudang->nama_gudang }}"
                                        class="img-fluid rounded mb-3" style="width:100%; height:160px; object-fit:cover;">
                                </div>
                            @endif

                            <div class="mb-3">
                                <h5 class="mb-0">{{ $gudang->nama_gudang }}</h5>
                                <small class="text-muted">{{ $gudang->lokasi }}</small>
                            </div>

                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="text-muted">Kode: <strong>{{ $gudang->kode_gudang }}</strong></span>
                                @auth
                                    @if(auth()->user() && auth()->user()->role === 'atasan')
                                        <div class="d-flex">
                                            <a class="btn btn-sm btn-outline-primary me-1" href="javascript:void(0);"
                                                data-route="{{ route('gudang.update', $gudang->kode_gudang) }}"
                                                data-kode="{{ $gudang->kode_gudang }}"
                                                data-nama="{{ $gudang->nama_gudang }}"
                                                data-lokasi="{{ $gudang->lokasi }}"
                                                data-image="{{ $gudang->images ? asset('storage/' . $gudang->images) : '' }}"
                                                onclick="openEditFromData(this)">
                                                <i class="ri-pencil-line me-1"></i>Edit
                                            </a>
                                            <a class="btn btn-sm btn-outline-danger" href="javascript:void(0);"
                                                onclick="konfirmasiHapus('{{ route('gudang.destroy', $gudang->kode_gudang) }}')">
                                                <i class="ri-delete-bin-6-line me-1"></i>Delete
                                            </a>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                            <button type="button" class="btn-modern px-4 w-100 d-block mx-auto"
                                onclick="window.location.href='{{ url('/gudang') }}?kode={{ $gudang->kode_gudang }}'">Masuk</button>
                        </div>
                    </div>
                @endforeach

                @if ($gudangs->isEmpty())
                    <div class="col-12 text-center text-muted">Belum ada data gudang.</div>
                @endif
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

        function openEditFromData(elem) {
            editGudang(elem.dataset.route, elem.dataset.kode, elem.dataset.nama, elem.dataset.lokasi, elem.dataset.image || null);
        }

        function editGudang(actionUrl, kode, nama, lokasi, imageUrl) {
            // 1. Isi value input form dengan data yang dikirim dari tombol
            document.getElementById('edit_kode_gudang').value = kode;
            document.getElementById('edit_nama_gudang').value = nama;
            document.getElementById('edit_lokasi').value = lokasi;

            // 1b. Set preview gambar jika ada
            var preview = document.getElementById('edit_image_preview');
            if (imageUrl) {
                preview.src = imageUrl;
                preview.style.display = 'block';
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }

            // 2. Ubah action form agar mengarah ke URL update yang benar
            document.getElementById('formEditGudang').action = actionUrl;
            // reset file input
            var editFileInput = document.getElementById('edit_images');
            if (editFileInput) editFileInput.value = '';

            // 3. Tampilkan modal
            var myModal = new bootstrap.Modal(document.getElementById('modalEditGudang'));
            myModal.show();
        }

        function previewCreateImage(input) {
            var preview = document.getElementById('create_image_preview');
            if (input.files && input.files[0]) {
                preview.src = URL.createObjectURL(input.files[0]);
                preview.style.display = 'block';
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        }

        function previewEditImage(input) {
            var preview = document.getElementById('edit_image_preview');
            if (input.files && input.files[0]) {
                preview.src = URL.createObjectURL(input.files[0]);
                preview.style.display = 'block';
            }
        }
    </script>
@endsection
