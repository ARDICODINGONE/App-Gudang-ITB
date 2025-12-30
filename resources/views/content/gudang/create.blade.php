<div class="modal fade" id="modalTambahGudang" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data Gudang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('gudang.store') }}" method="POST" enctype="multipart/form-data">
        @csrf <div class="modal-body">
          <div class="row">
            <div class="col mb-3">
              <label for="kode_gudang" class="form-label">Kode Gudang</label>
              <input type="text" id="kode_gudang" name="kode_gudang" class="form-control"
                placeholder="Contoh: GD-001" value="{{ $nextKode ?? old('kode_gudang') }}" readonly required>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="nama_gudang" class="form-label">Nama Gudang</label>
              <input type="text" id="nama_gudang" name="nama_gudang" class="form-control"
                placeholder="Contoh: Gudang Elektronik" required>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="lokasi" class="form-label">Lokasi</label>
              <input type="text" id="lokasi" name="lokasi" class="form-control"
                placeholder="Contoh: Jakarta Selatan" required>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="images" class="form-label">Gambar (opsional)</label>
              <input type="file" id="images" name="images" class="form-control" accept="image/*" onchange="previewCreateImage(this)">
              <div class="mt-2">
                <img id="create_image_preview" src="" alt="Preview" style="max-height:120px; display:none; object-fit:cover;" class="rounded">
              </div>
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
