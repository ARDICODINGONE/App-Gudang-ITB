@extends('layouts/app')

@section('title', 'Gudang - Index')

@section('content')
  <style>
    .gudang-page {
      max-width: 1280px;
      margin: 1.5rem auto;
      padding: 1rem;
      border-radius: 20px;
      background: linear-gradient(180deg, #f6faff 0%, #ffffff 100%);
      box-shadow: 0 20px 44px -34px rgba(19, 54, 112, 0.42);
    }

    .gudang-shell {
      border: 1px solid #e1ebfb;
      border-radius: 16px;
      overflow: hidden;
      background: #fff;
    }

    .gudang-header {
      padding: 1rem 1.25rem;
      background: linear-gradient(100deg, #0d6efd 0%, #2f84ff 72%, #5ca0ff 100%);
      color: #fff;
    }

    .gudang-subtitle {
      margin: .25rem 0 0;
      font-size: .9rem;
      opacity: .92;
    }

    .gudang-card {
      border: 1px solid #e7eef9;
      border-radius: 14px;
      background: #fff;
      box-shadow: 0 12px 28px -30px rgba(19, 54, 112, 0.5);
      transition: transform .2s ease, box-shadow .2s ease;
    }

    .gudang-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 22px 36px -30px rgba(19, 54, 112, 0.65);
    }

    .gudang-cover {
      width: 100%;
      height: 165px;
      object-fit: cover;
      border-radius: 10px;
      border: 1px solid #dbe7fb;
      background: #f4f7fc;
    }

    .gudang-cover-placeholder {
      height: 165px;
      border-radius: 10px;
      border: 1px dashed #cddbf6;
      background: linear-gradient(135deg, #f7faff 0%, #eef4ff 100%);
      color: #93a7cb;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
    }

    .kode-pill {
      display: inline-block;
      border-radius: 999px;
      padding: .24rem .58rem;
      background: #ebf2ff;
      color: #0d6efd;
      border: 1px solid #d4e3ff;
      font-size: .75rem;
      font-weight: 700;
    }

    .lokasi-text {
      color: #637695;
      font-size: .9rem;
      min-height: 1.2rem;
    }

    .gudang-actions {
      display: flex;
      gap: .4rem;
      flex-wrap: wrap;
    }

    .empty-state {
      border: 1px dashed #d3e0f8;
      border-radius: 12px;
      padding: 2rem 1rem;
      text-align: center;
      color: #7c90b2;
    }

    @media (max-width: 767.98px) {
      .gudang-page {
        margin: 1rem .75rem;
        padding: .75rem;
      }

      .gudang-header {
        padding: 1rem;
      }
    }
  </style>

  <div class="gudang-page">
    <div class="gudang-shell">
      <div class="gudang-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
          <h5 class="mb-0 text-white">Daftar Gudang</h5>
          <p class="gudang-subtitle">Kelola lokasi penyimpanan barang secara terstruktur.</p>
        </div>
        @auth
        @if(auth()->user() && in_array(auth()->user()->role, ['admin', 'petugas']))
        <div>
          <button type="button" class="btn btn-sm btn-light text-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambahGudang">
            <i class="bi bi-plus-lg me-1"></i>Tambah Gudang
          </button>
        </div>
        @endif
        @endauth
      </div>

      <div class="card-body p-3 p-md-4">
        @if (session('success'))
          <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
          </div>
        @endif

        <div class="row g-4">
          @forelse ($gudangs as $gudang)
            <div class="col-md-6 col-lg-4">
              <div class="gudang-card p-3 h-100 d-flex flex-column">
                @if($gudang->images)
                  <img src="{{ asset('storage/' . $gudang->images) }}" alt="{{ $gudang->nama_gudang }}" class="gudang-cover mb-3">
                @else
                  <div class="gudang-cover-placeholder mb-3">
                    <i class="bi bi-building"></i>
                  </div>
                @endif

                <div class="mb-2">
                  <h5 class="mb-1">{{ $gudang->nama_gudang }}</h5>
                  <div class="lokasi-text">{{ $gudang->lokasi ?: '-' }}</div>
                </div>

                <div class="mb-3">
                  <span class="kode-pill">Kode: {{ $gudang->kode_gudang }}</span>
                </div>

                <div class="mt-auto">
                  @auth
                    @if(auth()->user() && in_array(auth()->user()->role, ['admin', 'petugas']))
                      <div class="gudang-actions mb-2">
                        <a class="btn btn-sm btn-outline-primary" href="javascript:void(0);"
                          data-route="{{ route('gudang.update', $gudang->kode_gudang) }}"
                          data-kode="{{ $gudang->kode_gudang }}"
                          data-nama="{{ $gudang->nama_gudang }}"
                          data-lokasi="{{ $gudang->lokasi }}"
                          data-image="{{ $gudang->images ? asset('storage/' . $gudang->images) : '' }}"
                          onclick="openEditFromData(this)">
                          <i class="bi bi-pencil-square me-1"></i>Edit
                        </a>
                        <a class="btn btn-sm btn-outline-danger" href="javascript:void(0);"
                          onclick="konfirmasiHapus('{{ route('gudang.destroy', $gudang->kode_gudang) }}')">
                          <i class="bi bi-trash me-1"></i>Hapus
                        </a>
                      </div>
                    @endif
                  @endauth

                  <button type="button" class="btn btn-primary btn-sm w-100 fw-semibold"
                    onclick="window.location.href='{{ url('/gudang') }}?kode={{ $gudang->kode_gudang }}'">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                  </button>
                </div>
              </div>
            </div>
          @empty
            <div class="col-12">
              <div class="empty-state">
                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                Belum ada data gudang.
              </div>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  @include('content.gudang.create')
  @include('content.gudang.delete')
  @include('content.gudang.update')

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      @if ($errors->any())
        var myModal = new bootstrap.Modal(document.getElementById('modalTambahGudang'));
        myModal.show();
      @endif
    });

    function konfirmasiHapus(actionUrl) {
      var form = document.getElementById('formHapusGudang');
      form.action = actionUrl;
      var myModal = new bootstrap.Modal(document.getElementById('modalHapusGudang'));
      myModal.show();
    }

    function openEditFromData(elem) {
      editGudang(elem.dataset.route, elem.dataset.kode, elem.dataset.nama, elem.dataset.lokasi, elem.dataset.image || null);
    }

    function editGudang(actionUrl, kode, nama, lokasi, imageUrl) {
      document.getElementById('edit_kode_gudang').value = kode;
      document.getElementById('edit_nama_gudang').value = nama;
      document.getElementById('edit_lokasi').value = lokasi;

      var preview = document.getElementById('edit_image_preview');
      if (imageUrl) {
        preview.src = imageUrl;
        preview.style.display = 'block';
      } else {
        preview.src = '';
        preview.style.display = 'none';
      }

      document.getElementById('formEditGudang').action = actionUrl;
      var editFileInput = document.getElementById('edit_images');
      if (editFileInput) editFileInput.value = '';

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
