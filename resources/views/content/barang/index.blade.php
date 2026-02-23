@extends('layouts/app')

@section('title', 'Barang - Index')

@section('content')
  <style>
    .barang-page {
      background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
      border-radius: 18px;
      padding: 1.25rem;
      margin: 1.5rem auto;
      max-width: 1280px;
      box-shadow: 0 18px 40px -30px rgba(11, 94, 215, 0.45);
    }

    .barang-card {
      border: 1px solid #e9eff8;
      border-radius: 14px;
      overflow: hidden;
      background: #ffffff;
    }

    .barang-header {
      padding: 1rem 1.25rem;
      background: linear-gradient(90deg, #0d6efd 0%, #1f7cff 75%, #3f8fff 100%);
      color: #fff;
    }

    .barang-subtitle {
      font-size: 0.88rem;
      opacity: 0.9;
      margin: 0;
    }

    .barang-actions {
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .barang-table thead th {
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      font-weight: 700;
      color: #5b6b84;
      border-bottom-width: 1px;
      background: #f9fbff;
      white-space: nowrap;
    }

    .barang-table tbody td {
      vertical-align: middle;
    }

    .kode-badge {
      display: inline-block;
      padding: 0.28rem 0.58rem;
      border-radius: 999px;
      background: #e8f1ff;
      color: #0d6efd;
      font-weight: 600;
      font-size: 0.76rem;
    }

    .barang-thumb {
      width: 48px;
      height: 48px;
      border-radius: 10px;
      object-fit: cover;
      border: 1px solid #dbe7ff;
      background: #f7f9fc;
    }

    .text-deskripsi {
      max-width: 280px;
      color: #5d6d85;
      font-size: 0.88rem;
    }

    .badge-kategori {
      background: #e6f4ea;
      color: #1e7e34;
      border: 1px solid #cde9d5;
      font-weight: 600;
      font-size: 0.73rem;
      padding: 0.28rem 0.58rem;
      border-radius: 999px;
    }

    .harga-cell {
      font-weight: 700;
      color: #0f3d91;
      white-space: nowrap;
    }

    .aksi-wrap {
      display: flex;
      gap: 0.4rem;
      flex-wrap: wrap;
    }

    .empty-state {
      padding: 2rem 1rem;
      text-align: center;
      color: #70819b;
    }

    .empty-state i {
      font-size: 2rem;
      color: #96aac8;
      margin-bottom: 0.5rem;
      display: inline-block;
    }

    @media (max-width: 767.98px) {
      .barang-page {
        margin: 1rem 0.75rem;
        padding: 0.75rem;
      }

      .barang-header {
        padding: 1rem;
      }

      .text-deskripsi {
        max-width: 200px;
      }
    }
  </style>

  <div class="barang-page">
    <div class="barang-card">
      <div class="barang-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
          <h5 class="mb-1 text-white">Daftar Barang</h5>
          <p class="barang-subtitle">Kelola data inventaris barang dengan lebih cepat dan terstruktur.</p>
        </div>

        <div class="d-flex barang-actions">
          <button type="button" class="btn btn-sm btn-light text-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#createBarangModal">
            <i class="ri-add-line me-1"></i>Tambah
          </button>
          <button type="button" class="btn btn-sm btn-outline-light fw-semibold" data-bs-toggle="modal" data-bs-target="#importBarangModal">
            <i class="ri-upload-line me-1"></i>Import
          </button>
          <a href="{{ route('barang.export') }}" class="btn btn-sm btn-success fw-semibold">
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
          <table class="table table-hover barang-table align-middle mb-0">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Gambar</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse ($barangs as $item)
                <tr>
                  <td>
                    <span class="kode-badge">{{ $item->kode_barang }}</span>
                  </td>
                  <td class="fw-semibold">{{ $item->nama_barang }}</td>
                  <td>
                    <span class="text-deskripsi d-inline-block">
                      {{ $item->deskripsi ? \Illuminate\Support\Str::limit($item->deskripsi, 80) : '-' }}
                    </span>
                  </td>
                  <td class="harga-cell">
                    Rp {{ isset($item->harga) ? number_format($item->harga, 0, ',', '.') : '-' }}
                  </td>
                  <td>
                    @if ($item->image)
                      <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->nama_barang }}" class="barang-thumb">
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>
                    @if ($item->kategori)
                      <span class="badge-kategori">{{ $item->kategori->kategori }}</span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>{{ $item->satuan }}</td>
                  <td>
                    <div class="aksi-wrap justify-content-center">
                      <a class="btn btn-sm btn-outline-primary" href="javascript:void(0);" onclick="editBarangFromElement(this)"
                        data-action="{{ route('barang.update', $item->kode_barang) }}" data-kode="{{ $item->kode_barang }}" data-nama="{{ $item->nama_barang }}"
                        data-kategori="{{ $item->kategori ? $item->kategori->id : '' }}" data-satuan="{{ $item->satuan }}" data-deskripsi="{{ $item->deskripsi }}"
                        data-harga="{{ $item->harga }}" data-image="{{ $item->image }}">
                        <i class="ri-pencil-line me-1"></i>Edit
                      </a>

                      <a class="btn btn-sm btn-outline-danger" href="javascript:void(0);"
                        onclick="konfirmasiHapus('{{ route('barang.destroy', $item->kode_barang) }}')">
                        <i class="ri-delete-bin-6-line me-1"></i>Hapus
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8">
                    <div class="empty-state">
                      <i class="ri-inbox-archive-line"></i>
                      <div class="fw-semibold">Belum ada data barang</div>
                      <small>Tambahkan barang baru untuk mulai mengelola inventaris.</small>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
          {{ $barangs->render('pagination::bootstrap-4') }}
        </div>
      </div>
    </div>
  </div>

  @include('content.barang.delete')
  @include('content.barang.create')
  @include('content.barang.import')
  @include('content.barang.update')

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      @if ($errors->any())
        var myModal = new bootstrap.Modal(document.getElementById('createBarangModal'));
        myModal.show();
      @endif
      @if (session('import_errors'))
        var importModal = new bootstrap.Modal(document.getElementById('importBarangModal'));
        importModal.show();
      @endif
    });
  </script>

  <script>
    function konfirmasiHapus(actionUrl) {
      var form = document.getElementById('formHapusBarang');
      form.action = actionUrl;
      var myModal = new bootstrap.Modal(document.getElementById('modalHapusBarang'));
      myModal.show();
    }

    function editBarang(actionUrl, kode, nama, kategori_id, satuan, deskripsi, harga, image) {
      document.getElementById('edit_kode_barang').value = kode;
      document.getElementById('edit_nama_barang').value = nama;
      document.getElementById('edit_kategori_id').value = kategori_id;
      document.getElementById('edit_satuan').value = satuan;
      if (document.getElementById('edit_deskripsi')) document.getElementById('edit_deskripsi').value = deskripsi || '';
      const hiddenHarga = document.getElementById('edit_harga');
      const formatHarga = document.getElementById('edit_harga_format');

      hiddenHarga.value = harga || 0;
      formatHarga.value = formatRupiah((harga || 0).toString());

      var editPreview = document.getElementById('edit_image_preview');
      if (editPreview) {
        if (image) {
          editPreview.src = '/storage/' + image;
          editPreview.style.display = 'inline-block';
        } else {
          editPreview.src = '';
          editPreview.style.display = 'none';
        }
      }

      document.getElementById('formEditBarang').action = actionUrl;

      var myModal = new bootstrap.Modal(document.getElementById('modalEditBarang'));
      myModal.show();
    }

    function editBarangFromElement(el) {
      const d = el.dataset;
      const harga = parseInt(d.harga) || 0;

      formEdit.action = d.action;

      document.getElementById('edit_kode_barang').value = d.kode;
      document.getElementById('edit_nama_barang').value = d.nama;
      document.getElementById('edit_deskripsi').value = d.deskripsi || '';
      document.getElementById('edit_kategori_id').value = d.kategori;
      document.getElementById('edit_satuan').value = d.satuan;

      editHargaHidden.value = harga;
      editHargaFormat.value = formatRupiah(harga.toString());

      const img = document.getElementById('edit_image_preview');
      if (d.image) {
        img.src = '/storage/' + d.image;
        img.style.display = 'block';
      } else {
        img.style.display = 'none';
      }

      new bootstrap.Modal(document.getElementById('modalEditBarang')).show();
    }

    function readURL(input, previewId) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          var img = document.getElementById(previewId);
          img.src = e.target.result;
          img.style.display = 'inline-block';
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    document.addEventListener('change', function(e) {
      if (e.target && e.target.id === 'create_image') {
        readURL(e.target, 'create_image_preview');
      }
      if (e.target && e.target.id === 'edit_image') {
        readURL(e.target, 'edit_image_preview');
      }
    });
  </script>
@endsection
