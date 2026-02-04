<div class="modal fade" id="importBarangModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Import Barang (XLSX/CSV)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('barang.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <p>Unggah file XLSX atau CSV dengan kolom: <strong>kode_barang,nama_barang,kategori,satuan,deskripsi,harga,image</strong> (kolom <strong>image</strong> opsional). Untuk kolom image, Anda dapat mengisi dengan:</p>
          <ul>
            <li><strong>URL lengkap:</strong> <code>https://example.com/image.jpg</code> — Sistem akan mengunduh dan menyimpan secara otomatis</li>
            <li><strong>Path lokal:</strong> <code>images/barang/file.jpg</code> — Pastikan file sudah ada di folder <code>public/storage/</code></li>
            <li><strong>Kosongkan:</strong> Jika tidak ingin menambah gambar untuk barang tertentu</li>
          </ul>
          <p>Pastikan file disimpan dalam format yang benar:</p>
          <ul>
            <li><strong>XLSX:</strong> Format Excel (Microsoft Excel 2007+)</li>
            <li><strong>CSV:</strong> Format CSV (UTF-8) dengan koma sebagai pemisah (jika Excel Anda menggunakan titik koma, pilih format CSV dengan koma)</li>
          </ul>
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
            <label class="form-label">Pilih file XLSX atau CSV</label>
            <input type="file" name="file" accept=".xlsx,.csv,text/csv" class="form-control">
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
