@extends('layouts/app')

@section('title', 'Barang - Index')


@section('content')
  <div class="card">
    <h5 class="card-header d-flex justify-content-between align-items-center">
      <span>Daftar Barang</span>
      <div>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createBarangModal"><i
            class="icon-base ri ri-add-line me-1"></i>Tambah Barang</button>
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
              <th>Kode</th>
              <th>Nama</th>
              <th>Stok</th>
              <th>Kategori</th>
              <th>Satuan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach ($barangs as $item)
              <tr>
                <td>{{ $item->kode_barang }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ isset($item->stok) && $item->stok->first() ? $item->stok->first()->stok : '-' }}</td>
                <td>{{ $item->kategori ? $item->kategori->kategori : '-' }}</td>
                <td>{{ $item->satuan }}</td>
                <td>
                  <div class="d-flex">
                    <a class="btn btn-sm btn-outline-primary me-1" href="javascript:void(0);"
                      onclick="editBarang(
                          '{{ route('barang.update', $item->kode_barang) }}',
                          '{{ $item->kode_barang }}',
                          '{{ $item->nama_barang }}',
                          '{{ $item->kategori ? $item->kategori->id : '' }}',
                          '{{ $item->satuan }}'
                        )">
                        <i class="ri-pencil-line me-1"></i>
                      Edit
                    </a>

                    <a class="btn btn-sm btn-outline-danger" href="javascript:void(0);"
                      onclick="konfirmasiHapus('{{ route('barang.destroy', $item->kode_barang) }}')">
                      <i class="ri-delete-bin-6-line me-1"></i>
                    Delete
                    </a>
                  </div>
                </td>
              </tr>
            @endforeach
            @if ($barangs->isEmpty())
              <tr>
                <td colspan="6" class="text-center">Belum ada data barang.</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @include('content.barang.delete')
  @include('content.barang.create')
  @include('content.barang.update')

  <!-- Create Modal -->
  

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
          var myModal = new bootstrap.Modal(document.getElementById('createBarangModal'));
          myModal.show();
        @endif
      });
    </script>
  @endpush

  <script>
    function konfirmasiHapus(actionUrl) {
      var form = document.getElementById('formHapusBarang');
      form.action = actionUrl;
      var myModal = new bootstrap.Modal(document.getElementById('modalHapusBarang'));
      myModal.show();
    }

    function editBarang(actionUrl, kode, nama, kategori_id, satuan) {
      document.getElementById('edit_kode_barang').value = kode;
      document.getElementById('edit_nama_barang').value = nama;
      document.getElementById('edit_kategori_id').value = kategori_id;
      document.getElementById('edit_satuan').value = satuan;

      document.getElementById('formEditBarang').action = actionUrl;

      var myModal = new bootstrap.Modal(document.getElementById('modalEditBarang'));
      myModal.show();
    }
  </script>
@endsection
