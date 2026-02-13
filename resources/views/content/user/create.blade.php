<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('user.store') }}" method="POST">
        @csrf 
        <div class="modal-body">
          <div class="row">
            <div class="col mb-3">
              <label for="nama" class="form-label">Nama</label>
              <input type="text" id="nama" name="nama" class="form-control"
                placeholder="Contoh: Fulan" required>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" id="username" name="username" class="form-control"
                placeholder="Contoh: Fulan1234" required>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                <input type="password" id="password" name="password" class="form-control" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                  <i class="fa fa-eye"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="role" class="form-label">Hak Akses</label>
              <select id="role" name="role" class="form-select">
                <option value="user">User</option>
                <option value="petugas">Petugas</option>
                <option value="approval">Approval</option>
                <option value="supervisor">Supervisor</option>
                <option value="atasan">Atasan</option>
                <option value="admin">Admin</option>
              </select>
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
