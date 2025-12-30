<div class="modal fade" id="createBarangModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Barang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('barang-store') }}" method="POST">
          @csrf
          @if(isset($gudangKode) && $gudangKode)
            <input type="hidden" name="gudang" value="{{ $gudangKode }}">
          @endif
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Kode Barang</label>
                <input type="text" name="kode_barang" value="{{ old('kode_barang') }}"
                  class="form-control @error('kode_barang') is-invalid @enderror">
                @error('kode_barang')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                  class="form-control @error('nama_barang') is-invalid @enderror">
                @error('nama_barang')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
                  <option value="">-- Pilih Kategori --</option>
                  @foreach ($kategoris as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                      {{ $k->kategori }}</option>
                  @endforeach
                </select>
                @error('kategori_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label">Satuan</label>
                <input type="text" name="satuan" value="{{ old('satuan') }}"
                  class="form-control @error('satuan') is-invalid @enderror">
                @error('satuan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- Optional initial stock when creating directly from a gudang view --}}
              @if(isset($gudangKode) && $gudangKode)
                <div class="col-md-6">
                  <label class="form-label">Stok Awal</label>
                  <input type="number" name="initial_stock" min="0" value="{{ old('initial_stock', 0) }}" class="form-control">
                </div>
              @endif
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