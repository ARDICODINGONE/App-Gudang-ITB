<div class="modal fade" id="modalEditBarangKeluar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Barang Keluar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="formEditBarangKeluar" action="" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <input type="hidden" id="edit_id_bk" name="id">

          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Barang</label>
              <select id="edit_id_barang" name="id_barang" class="form-control" required>
                <option value="">-- Pilih Barang --</option>
                @foreach(
                  \App\Models\Barang::orderBy('nama_barang')->get() as $b
                )
                  <option value="{{ $b->id }}">{{ $b->nama_barang }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Gudang</label>
              <select id="edit_kode_gudang" name="kode_gudang" class="form-control" required>
                <option value="">-- Pilih Gudang --</option>
                @foreach(\App\Models\Gudang::orderBy('kode_gudang')->get() as $g)
                  <option value="{{ $g->kode_gudang }}">{{ $g->kode_gudang }} - {{ $g->nama_gudang }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Jumlah</label>
              <input type="number" id="edit_jumlah" name="jumlah" class="form-control" min="1" required>
            </div>
          </div>

          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Tanggal</label>
              <input type="date" id="edit_tanggal" name="tanggal" class="form-control" required>
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
