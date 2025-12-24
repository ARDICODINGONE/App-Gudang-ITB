<div class="modal fade" id="modalTambahBarangKeluar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Barang Keluar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('barang-keluar.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Barang</label>
              <select name="id_barang" class="form-control" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($barangs as $b)
                  <option value="{{ $b->id }}">{{ $b->nama_barang }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Gudang</label>
              <select name="kode_gudang" class="form-control" required>
                <option value="">-- Pilih Gudang --</option>
                @foreach($gudangs as $g)
                  <option value="{{ $g->kode_gudang }}">{{ $g->kode_gudang }} - {{ $g->nama_gudang }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Jumlah</label>
              <input type="number" name="jumlah" class="form-control" min="1" required>
            </div>
          </div>

          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Tanggal</label>
              <input type="date" name="tanggal" class="form-control" required>
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
