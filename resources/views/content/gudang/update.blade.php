<div class="modal fade" id="modalEditGudang" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Data Gudang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="formEditGudang" action="" method="POST">
        @csrf
        @method('PUT') <div class="modal-body">
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Kode Gudang</label>
              <input type="text" id="edit_kode_gudang" name="kode_gudang" class="form-control" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Nama Gudang</label>
              <input type="text" id="edit_nama_gudang" name="nama_gudang" class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Lokasi</label>
              <input type="text" id="edit_lokasi" name="lokasi" class="form-control" required>
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
