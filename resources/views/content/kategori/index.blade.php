@extends('layouts/app')

@section('title', 'Kategori - Index')

@section('content')
  <style>
    .kt-page {
      background: linear-gradient(180deg, #f8fdf8 0%, #ffffff 100%);
      border-radius: 18px;
      padding: 1.25rem;
      margin: 1.5rem auto;
      max-width: 980px;
      box-shadow: 0 18px 40px -30px rgba(25, 135, 84, 0.35);
    }

    .kt-card {
      border: 1px solid #e3f2e9;
      border-radius: 14px;
      overflow: hidden;
      background: #fff;
    }

    .kt-header {
      padding: 1rem 1.25rem;
      background: linear-gradient(90deg, #198754 0%, #23a066 75%, #35b077 100%);
      color: #fff;
    }

    .kt-subtitle {
      margin: 0;
      font-size: 0.88rem;
      opacity: 0.92;
    }

    .kt-table thead th {
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      font-weight: 700;
      color: #577365;
      border-bottom-width: 1px;
      background: #f7fdf9;
      white-space: nowrap;
    }

    .kt-table tbody td {
      vertical-align: middle;
    }

    .kt-no {
      display: inline-block;
      min-width: 30px;
      text-align: center;
      padding: 0.24rem 0.48rem;
      border-radius: 999px;
      background: #dff5e8;
      color: #198754;
      font-weight: 700;
      font-size: 0.76rem;
    }

    .kt-name {
      font-weight: 600;
      color: #315647;
    }

    .kt-aksi {
      display: flex;
      gap: 0.4rem;
      flex-wrap: wrap;
      justify-content: center;
    }

    .kt-empty {
      padding: 2rem 1rem;
      text-align: center;
      color: #6f8b7d;
    }

    .kt-empty i {
      font-size: 2rem;
      color: #93b3a1;
      margin-bottom: 0.5rem;
      display: inline-block;
    }

    @media (max-width: 767.98px) {
      .kt-page {
        margin: 1rem 0.75rem;
        padding: 0.75rem;
      }

      .kt-header {
        padding: 1rem;
      }
    }
  </style>

  <div class="kt-page">
    <div class="kt-card">
      <div class="kt-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
          <h5 class="mb-1 text-white">Daftar Kategori</h5>
          <p class="kt-subtitle">Kelola kategori barang agar data inventaris lebih terstruktur.</p>
        </div>

        <div>
          <button type="button" class="btn btn-sm btn-light text-success fw-semibold" data-bs-toggle="modal" data-bs-target="#createKategoriModal">
            <i class="icon-base ri ri-add-line me-1"></i>Tambah
          </button>
        </div>
      </div>

      <div class="card-body p-3 p-md-4">
        @if (session('success'))
          <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i>
            <div>{{ session('success') }}</div>
          </div>
        @endif

        <div class="table-responsive">
          <table class="table table-hover kt-table align-middle mb-0">
            <thead>
              <tr>
                <th>No</th>
                <th>Kategori</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse ($kategoris as $kategori)
                <tr>
                  <td><span class="kt-no">{{ $loop->iteration }}</span></td>
                  <td class="kt-name">{{ $kategori->kategori }}</td>
                  <td>
                    <div class="kt-aksi">
                      <a class="btn btn-sm btn-outline-primary" href="javascript:void(0);"
                        onclick='editKategori({!! json_encode(route("kategori.update", $kategori->id)) !!}, {!! json_encode($kategori->kategori) !!})'>
                        <i class="ri-pencil-line me-1"></i>Edit
                      </a>
                      <a class="btn btn-sm btn-outline-danger" href="javascript:void(0);"
                        onclick="konfirmasiHapus('{{ route('kategori.destroy', $kategori->id) }}')">
                        <i class="ri-delete-bin-6-line me-1"></i>Hapus
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3">
                    <div class="kt-empty">
                      <i class="ri-inbox-archive-line"></i>
                      <div class="fw-semibold">Belum ada data kategori</div>
                      <small>Tambahkan kategori untuk memudahkan pengelompokan barang.</small>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  @include('content.kategori.delete')
  @include('content.kategori.create')
  @include('content.kategori.update')

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      @if ($errors->any())
        var myModal = new bootstrap.Modal(document.getElementById('createKategoriModal'));
        myModal.show();
      @endif
    });
  </script>

  <script>
    function konfirmasiHapus(actionUrl) {
      var form = document.getElementById('formHapusKategori');
      form.action = actionUrl;
      var myModal = new bootstrap.Modal(document.getElementById('modalHapusKategori'));
      myModal.show();
    }

    function editKategori(actionUrl, nama) {
      document.getElementById('formEditKategori').action = actionUrl;
      document.getElementById('edit_kategori').value = nama || '';

      var modal = new bootstrap.Modal(document.getElementById('modalEditKategori'));
      modal.show();
    }
  </script>
@endsection
