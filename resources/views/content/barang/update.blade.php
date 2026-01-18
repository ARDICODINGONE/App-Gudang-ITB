<div class="modal fade" id="modalEditBarang" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Data Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="formEditBarang" action="" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <div class="modal-body">
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Kode Barang</label>
              <input type="text" id="edit_kode_barang" name="kode_barang" class="form-control" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Nama Barang</label>
              <input type="text" id="edit_nama_barang" name="nama_barang" class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea id="edit_deskripsi" name="deskripsi" class="form-control" rows="3"></textarea>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Harga</label>
              <input type="text" id="edit_harga_format" class="form-control" placeholder="Rp 0">
              <input type="hidden" id="edit_harga" name="harga">
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Kategori</label>
              <select id="edit_kategori_id" name="kategori_id" class="form-select" required>
                <option value="">Pilih Kategori</option>
                @foreach ($kategoris as $kategori)
                  <option value="{{ $kategori->id }}">{{ $kategori->kategori }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Satuan</label>
              <input type="text" id="edit_satuan" name="satuan" class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Gambar</label>
              <input type="file" id="edit_image" name="image" accept="image/*" class="form-control">
              <img id="edit_image_preview" src="" alt="preview" style="display:none; margin-top:8px; max-height:120px; object-fit:cover;" />
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

<script>
  document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function () {

        const harga = this.dataset.harga || 0;

        // set action form
        formEdit.action = `/barang/${this.dataset.id}`;

        // isi field
        document.getElementById('edit_kode_barang').value = this.dataset.kode;
        document.getElementById('edit_nama_barang').value = this.dataset.nama;
        document.getElementById('edit_deskripsi').value = this.dataset.deskripsi;
        document.getElementById('edit_kategori_id').value = this.dataset.kategori;
        document.getElementById('edit_satuan').value = this.dataset.satuan;

        // 🔥 HARGA (INI KUNCI)
        editHargaHidden.value = harga;
        editHargaFormat.value = formatRupiah(harga.toString());

        // preview gambar
        const img = document.getElementById('edit_image_preview');
        if (this.dataset.image) {
            img.src = this.dataset.image;
            img.style.display = 'block';
        } else {
            img.style.display = 'none';
        }
    });
});

const formEdit = document.getElementById('formEditBarang');

const editHargaFormat = document.getElementById('edit_harga_format');
const editHargaHidden = document.getElementById('edit_harga');

const editFields = {
    nama_barang: document.getElementById('edit_nama_barang'),
    deskripsi: document.getElementById('edit_deskripsi'),
    harga_format: editHargaFormat,
    kategori_id: document.getElementById('edit_kategori_id'),
    satuan: document.getElementById('edit_satuan'),
};

function formatRupiah(angka) {
    angka = angka.replace(/[^0-9]/g, '');
    return 'Rp ' + angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// FORMAT RUPIAH
editHargaFormat.addEventListener('input', function () {
    let angka = this.value.replace(/\D/g, '');
    this.value = formatRupiah(angka);
    editHargaHidden.value = angka;
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

// VALIDASI SUBMIT
formEdit.addEventListener('submit', function (e) {
    let firstError = null;

    Object.values(editFields).forEach(f => clearError(f));

    if (!editFields.nama_barang.value.trim()) {
        showError(editFields.nama_barang, 'Nama barang wajib diisi');
        firstError ??= editFields.nama_barang;
    }

    if (!editFields.deskripsi.value.trim()) {
        showError(editFields.deskripsi, 'Deskripsi wajib diisi');
        firstError ??= editFields.deskripsi;
    }

    if (!editFields.kategori_id.value) {
        showError(editFields.kategori_id, 'Kategori wajib dipilih');
        firstError ??= editFields.kategori_id;
    }

    if (!editFields.satuan.value.trim()) {
        showError(editFields.satuan, 'Satuan wajib diisi');
        firstError ??= editFields.satuan;
    }

    if (!editHargaHidden.value || editHargaHidden.value <= 0) {
        showError(editFields.harga_format, 'Harga wajib diisi');
        firstError ??= editFields.harga_format;
    }

    if (firstError) {
        e.preventDefault(); // ⛔ STOP SUBMIT
        firstError.focus();
    }
});
</script>
