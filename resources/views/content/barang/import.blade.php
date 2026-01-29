<div class="modal fade" id="importBarangModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Import Barang (CSV)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('barang.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <p>Unggah file CSV dengan kolom: <strong>kode_barang,nama_barang,kategori,satuan,deskripsi,harga,image</strong> (kolom <strong>image</strong> opsional — isi dengan nama file gambar, path relatif di <em>public/</em> atau <em>storage/app/public/</em>, atau URL).</p>
          <p>Anda dapat mengunggah <strong>CSV saja</strong> atau sebuah <strong>ZIP</strong> yang berisi CSV dan file gambar. Jika ZIP diunggah, gambar akan dicari di dalam ZIP terlebih dahulu.</p>
          <p>Pastikan file CSV disimpan sebagai <strong>CSV (UTF-8)</strong> dan menggunakan <strong>koma</strong> sebagai pemisah (jika Excel Anda menggunakan titik koma, pilih format CSV dengan koma).</p>
          <p>Anda dapat <a href="{{ route('barang.template') }}">mengunduh template CSV</a> sebagai contoh.</p>

          @if(session('import_errors'))
            <div class="alert alert-warning">
              <strong>Beberapa baris gagal diimpor:</strong>
              <ul class="mb-0">
                @foreach(session('import_errors') as $err)
                  <li>{{ $err }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="mb-3">
            <label class="form-label">Pilih file CSV</label>
            <input type="file" name="file" accept=".csv,text/csv" class="form-control">
            @error('file')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Import</button>
        </div>
      </form>
    </div>
  </div>
</div>
