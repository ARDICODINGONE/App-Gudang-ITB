@extends('layouts/app')

@section('title', 'Kategori - Index')


@section('content')
  <div class="card">
    <h5 class="card-header d-flex justify-content-between align-items-center">
      <span>Daftar Kategori</span>
      <div>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createKategoriModal"><i
            class="icon-base ri ri-add-line me-1"></i>Tambah Kategori</button>
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
              <th>Kategori</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach ($kategoris as $kategori)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $kategori->kategori }}</td>
                <td>
                    <div class="d-flex">
                      <a class="btn btn-sm btn-outline-primary me-1" href="javascript:void(0);"
                          onclick="editKategori(
                                '{{ route('kategori.update', $kategori->id) }}', 
                                '{{ $kategori->id }}' )">
                          <i class="ri-pencil-line me-1"></i>
                          Edit
                      </a>
                      <a class="btn btn-sm btn-outline-danger" href="javascript:void(0);"
                          onclick="konfirmasiHapus('{{ route('kategori.destroy', $kategori->id) }}')">
                          <i class="ri-delete-bin-6-line me-1"></i>
                          Delete
                      </a>
                    </div>
                  </div>
                </td>
              </tr>
            @endforeach
            @if ($kategoris->isEmpty())
              <tr>
                <td colspan="5" class="text-center">Belum ada data kategori.</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @include('content.kategori.delete')
  @include('content.kategori.create')
  @include('content.kategori.update')

  <!-- Create Modal -->
  

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
          var myModal = new bootstrap.Modal(document.getElementById('createKategoriModal'));
          myModal.show();
        @endif
      });
    </script>
  @endpush

  <script>
    function konfirmasiHapus(actionUrl) {
      var form = document.getElementById('formHapusKategori');
      form.action = actionUrl;
      var myModal = new bootstrap.Modal(document.getElementById('modalHapusKategori'));
      myModal.show();
    }

    function editKategori(actionUrl, id, nama) {
      document.getElementById('formEditKategori').action = actionUrl;
      document.getElementById('edit_kategori').value = nama;

      var modal = new bootstrap.Modal(
        document.getElementById('modalEditKategori')
      );
      modal.show();
    }
  </script>
@endsection
