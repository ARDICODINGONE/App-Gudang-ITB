<div class="modal fade" id="modalTambahBarangMasuk" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Barang Masuk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="formTambahBarangMasuk" action="{{ route('barang-masuk.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Daftar Barang</label>
            <div id="barangMasukItems" class="d-flex flex-column gap-2">
              <div class="row g-2 align-items-end barang-item-row">
                <div class="col-7">
                  <select name="id_barang[]" class="form-control js-barang-select" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach($barangs as $b)
                      <option value="{{ $b->id }}">{{ $b->nama_barang }} - {{ $b->deskripsi }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-3">
                  <input type="number" name="jumlah[]" class="form-control" min="1" placeholder="Jumlah" required>
                </div>
                <div class="col-2 d-grid">
                  <button type="button" class="btn btn-outline-danger btn-remove-row">-</button>
                </div>
              </div>
            </div>
            <button type="button" id="btnTambahBarisBarangMasuk" class="btn btn-sm btn-outline-primary mt-2">
              + Tambah Barang
            </button>
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
              <label class="form-label">Tanggal</label>
              <input type="date" name="tanggal" class="form-control" readonly required value="{{ old('tanggal', \Carbon\Carbon::now()->format('Y-m-d')) }}">
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
<!-- Choices.js for searchable selects -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('formTambahBarangMasuk');
  if (!form) return;

  var gudangSel = form.querySelector('select[name="kode_gudang"]');
  var itemsContainer = document.getElementById('barangMasukItems');
  var addRowBtn = document.getElementById('btnTambahBarisBarangMasuk');
  var submitBtn = form.querySelector('button[type="submit"]');
  var originalSubmitHtml = submitBtn ? submitBtn.innerHTML : '';

  function initChoices(selectEl) {
    if (!selectEl || selectEl.dataset.choicesInit === '1') return;
    new Choices(selectEl, { searchEnabled: true, itemSelectText: '' });
    selectEl.dataset.choicesInit = '1';
  }

  function updateRemoveButtonsState() {
    var rows = itemsContainer.querySelectorAll('.barang-item-row');
    rows.forEach(function (row) {
      var removeBtn = row.querySelector('.btn-remove-row');
      if (removeBtn) removeBtn.disabled = rows.length === 1;
    });
  }

  function createRow() {
    var row = document.createElement('div');
    row.className = 'row g-2 align-items-end barang-item-row';
    row.innerHTML = `
      <div class="col-7">
        <select name="id_barang[]" class="form-control js-barang-select" required>
          <option value="">-- Pilih Barang --</option>
          @foreach($barangs as $b)
            <option value="{{ $b->id }}">{{ $b->nama_barang }} - {{ $b->deskripsi }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-3">
        <input type="number" name="jumlah[]" class="form-control" min="1" placeholder="Jumlah" required>
      </div>
      <div class="col-2 d-grid">
        <button type="button" class="btn btn-outline-danger btn-remove-row">-</button>
      </div>
    `;

    itemsContainer.appendChild(row);
    initChoices(row.querySelector('.js-barang-select'));
    updateRemoveButtonsState();
  }

  initChoices(gudangSel);
  itemsContainer.querySelectorAll('.js-barang-select').forEach(initChoices);
  updateRemoveButtonsState();

  if (addRowBtn) {
    addRowBtn.addEventListener('click', function () {
      createRow();
    });
  }

  itemsContainer.addEventListener('click', function (e) {
    var removeBtn = e.target.closest('.btn-remove-row');
    if (!removeBtn) return;
    var rows = itemsContainer.querySelectorAll('.barang-item-row');
    if (rows.length === 1) return;
    removeBtn.closest('.barang-item-row').remove();
    updateRemoveButtonsState();
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
    }

    var action = form.getAttribute('action');
    var formData = new FormData(form);
    var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch(action, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
      body: formData
    }).then(function (res) {
      if (res.ok) {
        var modalEl = document.getElementById('modalTambahBarangMasuk');
        try {
          var myModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
          myModal.hide();
        } catch (err) {
          // ignore
        }
        window.location.reload();
        return;
      }

      return res.text().then(function (text) {
        var message = 'Request gagal';
        try {
          var json = JSON.parse(text);
          message = json && json.message ? json.message : message;
          if (json && json.errors) {
            var flatErrors = [];
            Object.keys(json.errors).forEach(function (key) {
              flatErrors = flatErrors.concat(json.errors[key]);
            });
            if (flatErrors.length) message = flatErrors.join('\n');
          }
        } catch (err) {
          if (text) message = text;
        }
        throw new Error(message);
      });
    }).catch(function (err) {
      alert('Gagal menyimpan: ' + (err.message || err));
    }).finally(function () {
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalSubmitHtml;
      }
    });
  });
});
</script>
