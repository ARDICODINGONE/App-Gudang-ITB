<div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Data User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="formEditUser" action="" method="POST">
        @csrf
        @method('PUT') <div class="modal-body">
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Nama</label>
              <input type="text" id="edit_nama" name="nama" class="form-control" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Username</label>
              <input type="text" id="edit_username" name="username" class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Password</label>
              <input type="password" id="edit_password" name="password" class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Hak Akses</label>
              <select id="edit_role" name="role" class="form-select">
                <option value="user">User</option>
                <option value="petugas">Petugas</option>
                <option value="approval">Approval</option>
                <option value="atasan">Atasan</option>
              </select>
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
