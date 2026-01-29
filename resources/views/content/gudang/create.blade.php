<div class="modal fade" id="modalTambahGudang" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data Gudang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('gudang.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="row">
            <div class="col mb-3">
              <label for="kode_gudang" class="form-label">Kode Gudang</label>
              <input type="text" id="kode_gudang" name="kode_gudang" class="form-control @error('kode_gudang') is-invalid @enderror"
                placeholder="Contoh: GD-001" value="{{ $nextKode ?? old('kode_gudang') }}" readonly required>
              @error('kode_gudang')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="nama_gudang" class="form-label">Nama Gudang</label>
              <input type="text" id="nama_gudang" name="nama_gudang" class="form-control @error('nama_gudang') is-invalid @enderror"
                placeholder="Contoh: Gudang Elektronik" value="{{ old('nama_gudang') }}" required>
              @error('nama_gudang')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="lokasi" class="form-label">Lokasi</label>
              <input type="text" id="lokasi" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                placeholder="Contoh: Jakarta Selatan" value="{{ old('lokasi') }}" required>
              @error('lokasi')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="images" class="form-label">Gambar (opsional - Max 2MB, JPG/PNG/GIF)</label>
              <input type="file" id="images" name="images" class="form-control @error('images') is-invalid @enderror" accept="image/*" onchange="previewCreateImage(this)">
              <small class="text-muted d-block mt-1">Format yang didukung: JPG, JPEG, PNG, GIF</small>
              <div class="mt-2">
                <img id="create_image_preview" src="" alt="Preview" style="max-height:120px; display:none; object-fit:cover;" class="rounded">
              </div>
              @error('images')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
