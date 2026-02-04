<div class="modal fade" id="importBarangMasukModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Import Barang Masuk (XLSX/CSV)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('barang-masuk.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <p>Unggah file XLSX atau CSV dengan kolom: <strong>nama_barang,nama_gudang,jumlah,tanggal</strong></p>
          <ul>
            <li><strong>nama_barang:</strong> Nama barang (harus ada di database)</li>
            <li><strong>nama_gudang:</strong> Nama gudang (harus ada di database)</li>
            <li><strong>jumlah:</strong> Jumlah barang masuk (integer positif)</li>
            <li><strong>tanggal:</strong> Tanggal masuk (format: YYYY-MM-DD)</li>
          </ul>
          <p>Pastikan file disimpan dalam format yang benar:</p>
          <ul>
            <li><strong>XLSX:</strong> Format Excel (Microsoft Excel 2007+) — Rekomendasi ✓</li>
            <li><strong>CSV:</strong> Format CSV (UTF-8) dengan koma sebagai pemisah</li>
          </ul>
          <p><a href="{{ route('barang-masuk.template') }}">📥 Unduh template XLSX</a> untuk melihat contoh format file yang benar.</p>

          <div class="mb-3">
            <label class="form-label">Pilih file XLSX atau CSV</label>
            <input type="file" name="file" accept=".xlsx,.csv,text/csv" class="form-control" required>
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
