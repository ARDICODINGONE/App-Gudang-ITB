<div class="modal fade" id="modalEditSupplier" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Data Supplier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="formEditSupplier" action="" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <input type="hidden" id="edit_id_supplier" name="id">
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Nama Supplier</label>
              <input type="text" id="edit_nama_supplier" name="nama_supplier" class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Alamat</label>
              <input type="text" id="edit_alamat" name="alamat" class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">No. Telp</label>
              <input type="text" id="edit_no_telp" name="no_telp" class="form-control" required>
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
