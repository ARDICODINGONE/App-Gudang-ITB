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
              <th>Deskripsi</th>
              <th>Harga</th>
              <th>Gambar</th>
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
                <td>{{ $item->deskripsi ? \Illuminate\Support\Str::limit($item->deskripsi, 60) : '-' }}</td>
                <td>{{ isset($item->harga) ? number_format($item->harga, 2, ',', '.') : '-' }}</td>
                <td>
                  @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="image" style="max-height:48px; max-width:48px; object-fit:cover;" />
                  @else
                    -
                  @endif
                </td>
                <td>{{ $item->kategori ? $item->kategori->kategori : '-' }}</td>
                <td>{{ $item->satuan }}</td>
                <td>
                  <div class="d-flex">
                    <a class="btn btn-sm btn-outline-primary me-1" href="javascript:void(0);"
                      onclick="editBarangFromElement(this)"
                      data-action="{{ route('barang.update', $item->kode_barang) }}"
                      data-kode="{{ $item->kode_barang }}"
                      data-nama="{{ $item->nama_barang }}"
                      data-kategori="{{ $item->kategori ? $item->kategori->id : '' }}"
                      data-satuan="{{ $item->satuan }}"
                      data-deskripsi="{{ $item->deskripsi }}"
                      data-harga="{{ $item->harga }}"
                      data-image="{{ $item->image }}">
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
                <td colspan="9" class="text-center">Belum ada data barang.</td>
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

      // image preview for edit modal
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


    // Preview handlers for create and edit file inputs
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
