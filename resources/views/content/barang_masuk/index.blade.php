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
                <button id="btnBulkDelete" type="button" class="btn btn-sm btn-danger" onclick="konfirmasiHapusMultiple()" disabled>
                    <i class="ri ri-delete-bin-6-line me-1"></i>Hapus Terpilih
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
                            <th><input type="checkbox" id="checkAll"></th>
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
                                <td><input type="checkbox" class="row-check" value="{{ $item->id }}"></td>
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

        <!-- Bulk delete modal -->
        <div class="modal fade" id="modalHapusMultipleBarangMasuk" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus Terpilih</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formHapusMultipleBarangMasuk" action="{{ route('barang-masuk.bulkDestroy') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menghapus data barang masuk yang dipilih?</p>
                            <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Ya, Hapus Terpilih</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <script>
        function konfirmasiHapus(actionUrl) {
            var form = document.getElementById('formHapusBarangMasuk');
            form.action = actionUrl;
            var myModal = new bootstrap.Modal(document.getElementById('modalHapusBarangMasuk'));
            myModal.show();
        }

        // Bulk delete handlers
        document.addEventListener('DOMContentLoaded', function () {
            const checkAll = document.getElementById('checkAll');
            const btnBulk = document.getElementById('btnBulkDelete');

            function updateBulkState() {
                const any = Array.from(document.querySelectorAll('.row-check')).some(cb => cb.checked);
                btnBulk.disabled = !any;
            }

            if (checkAll) {
                checkAll.addEventListener('change', function () {
                    document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
                    updateBulkState();
                });
            }

            document.querySelectorAll('.row-check').forEach(cb => cb.addEventListener('change', updateBulkState));
        });

        function konfirmasiHapusMultiple() {
            const selected = Array.from(document.querySelectorAll('.row-check')).filter(cb => cb.checked).map(cb => cb.value);
            if (!selected.length) return;

            const form = document.getElementById('formHapusMultipleBarangMasuk');
            // remove existing hidden inputs
            form.querySelectorAll('input[name="ids[]"]').forEach(n => n.remove());
            // append selected ids
            selected.forEach(id => {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'ids[]';
                inp.value = id;
                form.appendChild(inp);
            });

            var myModal = new bootstrap.Modal(document.getElementById('modalHapusMultipleBarangMasuk'));
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
