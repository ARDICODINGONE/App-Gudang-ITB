<div class="modal fade" id="modalEditBarang" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Data Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="formEditBarang" action="" method="POST">
        @csrf
        @method('PUT') <div class="modal-body">
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Kode Barang</label>
              <input type="text" id="edit_kode_barang" name="kode_barang" class="form-control" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Nama Barang</label>
              <input type="text" id="edit_nama_barang" name="nama_barang" class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Kategori</label>
              <select id="edit_kategori_id" name="kategori_id" class="form-select" required>
                <option value="">Pilih Kategori</option>
                @foreach ($kategoris as $kategori)
                  <option value="{{ $kategori->id }}">{{ $kategori->kategori }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Satuan</label>
              <input type="text" id="edit_satuan" name="satuan" class="form-control" required>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
