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
          <div class="row">
            <div class="col mb-3">
              <label class="form-label">Barang</label>
              <select name="id_barang" class="form-control" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($barangs as $b)
                  <option value="{{ $b->id }}">{{ $b->nama_barang }} - {{ $b->deskripsi }}</option>
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
              <input type="date" name="tanggal" class="form-control" required value="{{ old('tanggal', \Carbon\Carbon::now()->format('Y-m-d')) }}">
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
  var barangSel = document.querySelector('select[name="id_barang"]');
  var gudangSel = document.querySelector('select[name="kode_gudang"]');
  if (barangSel) new Choices(barangSel, {searchEnabled: true, itemSelectText: ''});
  if (gudangSel) new Choices(gudangSel, {searchEnabled: true, itemSelectText: ''});

  var form = document.getElementById('formTambahBarangMasuk');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var action = form.getAttribute('action');
      var formData = new FormData(form);

      var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

      fetch(action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: formData
      }).then(function (res) {
        if (res.ok) {
          // Close modal and reload current page for a faster UX
          var modalEl = document.getElementById('modalTambahBarangMasuk');
          try {
            var myModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            myModal.hide();
          } catch (err) {
            // ignore
          }
          window.location.reload();
        } else {
          return res.text().then(function (t) { throw new Error(t || 'Request failed'); });
        }
      }).catch(function (err) {
        alert('Gagal menyimpan: ' + (err.message || err));
      });
    });
  }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('formTambahBarangMasuk');
  if (!form) return;
  var submitBtn = form.querySelector('button[type="submit"]');
  form.addEventListener('submit', function (e) {
    // This handler augments the fetch flow above: disable button and show spinner
    if (!submitBtn) return;
    var originalHtml = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';

    // Re-enable and restore after a short delay in case the other handler fails to run
    var restore = function () {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalHtml;
    };
    // Ensure restore runs if something goes wrong after 10s
    setTimeout(restore, 10000);
    // When page reloads on success the button state won't matter.
  });
});
</script>
