@extends('layouts/app')

@section('title', 'Barang Masuk - Index')

@section('content')
  <style>
    .bm-page {
      background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
      border-radius: 18px;
      padding: 1.25rem;
      margin: 1.5rem auto;
      max-width: 1280px;
      box-shadow: 0 18px 40px -30px rgba(11, 94, 215, 0.45);
    }

    .bm-card {
      border: 1px solid #e9eff8;
      border-radius: 14px;
      overflow: hidden;
      background: #fff;
    }

    .bm-header {
      padding: 1rem 1.25rem;
      background: linear-gradient(90deg, #0d6efd 0%, #1f7cff 75%, #3f8fff 100%);
      color: #fff;
    }

    .bm-subtitle {
      margin: 0;
      font-size: 0.88rem;
      opacity: 0.92;
    }

    .bm-actions {
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .bm-table thead th {
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      font-weight: 700;
      color: #5b6b84;
      border-bottom-width: 1px;
      background: #f9fbff;
      white-space: nowrap;
    }

    .bm-table tbody td {
      vertical-align: middle;
    }

    .bm-no {
      display: inline-block;
      min-width: 30px;
      text-align: center;
      padding: 0.24rem 0.48rem;
      border-radius: 999px;
      background: #e8f1ff;
      color: #0d6efd;
      font-weight: 700;
      font-size: 0.76rem;
    }

    .bm-jumlah {
      font-weight: 700;
      color: #0f3d91;
    }

    .bm-tanggal {
      color: #5d6d85;
      font-size: 0.88rem;
      white-space: nowrap;
    }

    .bm-user {
      color: #4e5f79;
      font-size: 0.88rem;
    }

    .bm-aksi {
      display: flex;
      gap: 0.4rem;
      flex-wrap: wrap;
      justify-content: center;
    }

    .bm-empty {
      padding: 2rem 1rem;
      text-align: center;
      color: #70819b;
    }

    .bm-empty i {
      font-size: 2rem;
      color: #96aac8;
      margin-bottom: 0.5rem;
      display: inline-block;
    }

    @media (max-width: 767.98px) {
      .bm-page {
        margin: 1rem 0.75rem;
        padding: 0.75rem;
      }

      .bm-header {
        padding: 1rem;
      }
    }
  </style>

  <div class="bm-page">
    <div class="bm-card">
      <div class="bm-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
          <h5 class="mb-1 text-white">Daftar Barang Masuk</h5>
          <p class="bm-subtitle">Pantau transaksi barang masuk ke gudang secara terstruktur.</p>
        </div>

        <div class="d-flex bm-actions">
          <button type="button" class="btn btn-sm btn-light text-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambahBarangMasuk">
            <i class="ri ri-add-line me-1"></i>Tambah
          </button>
          <button type="button" class="btn btn-sm btn-outline-light fw-semibold" data-bs-toggle="modal" data-bs-target="#importBarangMasukModal">
            <i class="ri-upload-line me-1"></i>Import
          </button>
          <a href="{{ route('barang-masuk.export') }}" class="btn btn-sm btn-success fw-semibold">
            <i class="ri-download-line me-1"></i>Export
          </a>
        </div>
      </div>

      <div class="card-body p-3 p-md-4">
        @if (session('success'))
          <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i>
            <div>{{ session('success') }}</div>
          </div>
        @endif

        @if (session('import_errors'))
          <div class="alert alert-warning mb-3" role="alert">
            <strong class="d-block mb-2">Beberapa baris gagal diimpor:</strong>
            <ul class="mb-0 ps-3">
              @foreach (session('import_errors') as $err)
                <li>{{ $err }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="table-responsive">
          <table class="table table-hover bm-table align-middle mb-0">
            <thead>
              <tr>
                <th>No</th>
                <th>Barang</th>
                <th>Gudang</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>User</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse ($items as $item)
                <tr>
                  <td>
                    <span class="bm-no">{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</span>
                  </td>
                  <td>
                    <div class="fw-semibold">{{ optional($item->barang)->nama_barang ?? '-' }}</div>
                    <small class="text-muted">
                      {{ optional($item->barang)->deskripsi ? \Illuminate\Support\Str::limit(optional($item->barang)->deskripsi, 80) : '-' }}
                    </small>
                  </td>
                  <td>{{ optional($item->gudang)->nama_gudang ?? '-' }}</td>
                  <td class="bm-jumlah">{{ $item->jumlah }}</td>
                  <td class="bm-tanggal">{{ $item->tanggal }}</td>
                  <td class="bm-user">{{ optional($item->user)->nama ?? optional($item->user)->username ?? '-' }}</td>
                  <td>
                    <div class="bm-aksi">
                      <a class="btn btn-sm btn-outline-primary" href="javascript:void(0);"
                        onclick='editBarangMasuk({!! json_encode(route("barang-masuk.update", $item->id)) !!}, {!! json_encode($item->id) !!}, {!! json_encode($item->id_barang) !!}, {!! json_encode($item->kode_gudang) !!}, {!! json_encode($item->jumlah) !!}, {!! json_encode($item->tanggal) !!})'>
                        <i class="ri-pencil-line me-1"></i>Edit
                      </a>
                      <a class="btn btn-sm btn-outline-danger" href="javascript:void(0);"
                        onclick='konfirmasiHapus({!! json_encode(route("barang-masuk.destroy", $item->id)) !!})'>
                        <i class="ri-delete-bin-6-line me-1"></i>Hapus
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7">
                    <div class="bm-empty">
                      <i class="ri-inbox-archive-line"></i>
                      <div class="fw-semibold">Belum ada data barang masuk</div>
                      <small>Tambahkan transaksi barang masuk untuk mulai pencatatan.</small>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
          {{ $items->render('pagination::bootstrap-4') }}
        </div>
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
