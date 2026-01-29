<div class="modal fade" id="createBarangModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Barang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formBarang" action="{{ route('barang-store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @if(isset($gudangKode) && $gudangKode)
            <input type="hidden" name="gudang" value="{{ $gudangKode }}">
          @endif
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Kode Barang</label>
                <input type="text" name="kode_barang" value="{{ old('kode_barang', isset($nextKode) ? $nextKode : '') }}"
                  class="form-control @error('kode_barang') is-invalid @enderror" readonly>
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

              <div class="col-md-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label">Harga</label>
                <input type="text" id="harga_format" class="form-control"placeholder="Rp 0">
                <input type="hidden" name="harga" id="harga" value="{{ old('harga') }}">
                @error('harga')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label">Gambar</label>
                <input type="file" id="create_image" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                @error('image')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <img id="create_image_preview" src="" alt="preview" style="display:none; margin-top:8px; max-height:120px; object-fit:cover;" />
              </div>

              {{-- Stok Awal dihapus sesuai permintaan pengguna --}}
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

 <script>
function formatRupiah(angka) {
    return 'Rp ' + angka
        .replace(/\D/g, '')
        .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

const form = document.getElementById('formBarang');

const fields = {
    kode_barang: document.querySelector('[name="kode_barang"]'),
    nama_barang: document.querySelector('[name="nama_barang"]'),
    kategori_id: document.querySelector('[name="kategori_id"]'),
    satuan: document.querySelector('[name="satuan"]'),
    deskripsi: document.querySelector('[name="deskripsi"]'),
    harga_format: document.getElementById('harga_format'),
    harga: document.getElementById('harga'),
};

// FORMAT HARGA
fields.harga_format.addEventListener('input', function () {
    let angka = this.value.replace(/\D/g, '');
    this.value = formatRupiah(angka);
    fields.harga.value = angka;
});

// HAPUS ERROR
function clearError(field) {
    field.classList.remove('is-invalid');
    const err = field.parentNode.querySelector('.invalid-feedback.js-error');
    if (err) err.remove();
}

// TAMPILKAN ERROR
function showError(field, message) {
    field.classList.add('is-invalid');

    if (!field.parentNode.querySelector('.invalid-feedback.js-error')) {
        const div = document.createElement('div');
        div.className = 'invalid-feedback d-block js-error';
        div.innerText = message;
        field.parentNode.appendChild(div);
    }
}

// VALIDASI SAAT SUBMIT
form.addEventListener('submit', function (e) {
    let firstError = null;

    Object.values(fields).forEach(f => clearError(f));

    if (!fields.kode_barang.value.trim()) {
        showError(fields.kode_barang, 'Kode barang wajib diisi');
        firstError ??= fields.kode_barang;
    }

    if (!fields.nama_barang.value.trim()) {
        showError(fields.nama_barang, 'Nama barang wajib diisi');
        firstError ??= fields.nama_barang;
    }

    if (!fields.kategori_id.value) {
        showError(fields.kategori_id, 'Kategori wajib dipilih');
        firstError ??= fields.kategori_id;
    }

    if (!fields.satuan.value.trim()) {
        showError(fields.satuan, 'Satuan wajib diisi');
        firstError ??= fields.satuan;
    }

    if (!fields.deskripsi.value.trim()) {
        showError(fields.deskripsi, 'Deskripsi wajib diisi');
        firstError ??= fields.deskripsi;
    }

    if (!fields.harga.value || fields.harga.value === '0') {
        showError(fields.harga_format, 'Harga wajib diisi');
        firstError ??= fields.harga_format;
    }

    // Stok awal dihapus — validasi terkait dihilangkan


    if (firstError) {
        e.preventDefault(); // ⛔ STOP SUBMIT
        firstError.focus();
    }
});
</script>

<script>
// Auto-fill kode_barang immediately on page load (also retry on modal open)
document.addEventListener('DOMContentLoaded', function () {
  const kodeInput = document.querySelector('[name="kode_barang"]');
  if (!kodeInput) return;

  function fetchKode() {
    if (!kodeInput.value.trim()) {
      fetch("{{ route('barang.nextKode') }}")
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(data => {
          if (data && data.kode) kodeInput.value = data.kode;
        })
        .catch(() => {
          if (!kodeInput.value.trim()) kodeInput.value = 'BR001';
        });
    }
  }

  // Fetch immediately so kode appears without waiting for modal
  fetchKode();

  // Also try again when modal opens (safe no-op if already filled)
  const modal = document.getElementById('createBarangModal');
  if (modal) {
    modal.addEventListener('show.bs.modal', fetchKode);
  }
});
</script>


<script>
// Show loading spinner on submit (disable button) similar to barang_masuk
document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('formBarang');
  if (!form) return;
  var submitBtn = form.querySelector('button[type="submit"]');
  form.addEventListener('submit', function (e) {
    // If another handler already prevented submit (validation/duplicate), don't show spinner
    if (e.defaultPrevented) return;
    if (!submitBtn) return;
    var originalHtml = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';

    // Safety restore if something goes wrong (e.g., network error or JS prevented reload)
    var restore = function () {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalHtml;
    };
    setTimeout(restore, 10000);
  });
});
</script>
