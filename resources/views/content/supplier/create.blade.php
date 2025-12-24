<div class="modal fade" id="modalTambahSupplier" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data Supplier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('supplier.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col mb-3">
              <label for="nama_supplier" class="form-label">Nama Supplier</label>
              <input type="text" id="nama_supplier" name="nama_supplier" class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="alamat" class="form-label">Alamat</label>
              <input type="text" id="alamat" name="alamat" class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="no_telp" class="form-label">No. Telp</label>
              <input type="text" id="no_telp" name="no_telp" class="form-control" required>
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
