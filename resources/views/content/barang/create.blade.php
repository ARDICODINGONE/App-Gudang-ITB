@php
  $oldItems = old('items');
  $formItems = is_array($oldItems) && count($oldItems)
      ? array_values($oldItems)
      : [[
          'kode_barang' => old('kode_barang', isset($nextKode) ? $nextKode : ''),
          'nama_barang' => old('nama_barang', ''),
          'kategori_id' => old('kategori_id', ''),
          'satuan' => old('satuan', ''),
          'deskripsi' => old('deskripsi', ''),
          'harga' => old('harga', ''),
      ]];
@endphp

<style>
  #createBarangModal .modal-dialog {
    max-width: min(1140px, calc(100vw - 2rem));
    margin: 1rem auto;
  }

  #createBarangModal .modal-content {
    overflow: hidden;
  }

  #createBarangModal .modal-body {
    max-height: calc(100vh - 220px);
    overflow-y: auto;
    overflow-x: hidden;
    padding: 1.25rem;
  }

  #barangItemsWrapper {
    min-width: 0;
  }

  #barangItemsWrapper .barang-item {
    width: 100%;
    box-shadow: 0 10px 25px -20px rgba(13, 110, 253, 0.55);
  }

  #barangItemsWrapper .card-body {
    overflow: visible;
  }

  #barangItemsWrapper .form-control,
  #barangItemsWrapper .form-select {
    min-width: 0;
  }

  @media (max-width: 767.98px) {
    #createBarangModal .modal-dialog {
      max-width: calc(100vw - 1rem);
      margin: 0.5rem auto;
    }

    #createBarangModal .modal-body {
      max-height: calc(100vh - 170px);
      padding: 1rem;
    }
  }
</style>

<div class="modal fade" id="createBarangModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <h5 class="modal-title mb-1">Tambah Barang</h5>
          <small class="text-muted">Satu submit bisa menyimpan beberapa barang sekaligus.</small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="formBarang" action="{{ route('barang-store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($gudangKode) && $gudangKode)
          <input type="hidden" name="gudang" value="{{ $gudangKode }}">
        @endif

        <div class="modal-body">
          @error('items')
            <div class="alert alert-danger">{{ $message }}</div>
          @enderror

          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <div class="fw-semibold">Daftar Barang</div>
              <small class="text-muted">Klik tambah baris jika ingin memasukkan barang lain.</small>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" id="addBarangRow">
              <i class="ri-add-line me-1"></i>Tambah Baris
            </button>
          </div>

          <div id="barangItemsWrapper" class="d-flex flex-column gap-3">
            @foreach ($formItems as $index => $item)
              <div class="card border barang-item" data-index="{{ $index }}">
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                  <span class="fw-semibold">Barang <span class="barang-number">{{ $index + 1 }}</span></span>
                  <button type="button" class="btn btn-outline-danger btn-sm remove-barang-row" {{ count($formItems) === 1 ? 'style=display:none;' : '' }}>
                    Hapus
                  </button>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">Kode Barang</label>
                      <input type="text" name="items[{{ $index }}][kode_barang]" value="{{ $item['kode_barang'] ?? '' }}"
                        class="form-control @error("items.$index.kode_barang") is-invalid @enderror barang-kode" readonly>
                      @error("items.$index.kode_barang")
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="col-md-8">
                      <label class="form-label">Nama Barang</label>
                      <input type="text" name="items[{{ $index }}][nama_barang]" value="{{ $item['nama_barang'] ?? '' }}"
                        class="form-control @error("items.$index.nama_barang") is-invalid @enderror barang-nama">
                      @error("items.$index.nama_barang")
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="col-md-4">
                      <label class="form-label">Kategori</label>
                      <select name="items[{{ $index }}][kategori_id]" class="form-select @error("items.$index.kategori_id") is-invalid @enderror barang-kategori">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $k)
                          <option value="{{ $k->id }}" {{ (string) ($item['kategori_id'] ?? '') === (string) $k->id ? 'selected' : '' }}>
                            {{ $k->kategori }}
                          </option>
                        @endforeach
                      </select>
                      @error("items.$index.kategori_id")
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="col-md-4">
                      <label class="form-label">Satuan</label>
                      <input type="text" name="items[{{ $index }}][satuan]" value="{{ $item['satuan'] ?? '' }}"
                        class="form-control @error("items.$index.satuan") is-invalid @enderror barang-satuan">
                      @error("items.$index.satuan")
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="col-md-4">
                      <label class="form-label">Harga</label>
                      <input type="text" class="form-control barang-harga-format" placeholder="Rp 0"
                        value="{{ !empty($item['harga']) ? 'Rp ' . number_format((int) $item['harga'], 0, ',', '.') : '' }}">
                      <input type="hidden" name="items[{{ $index }}][harga]" value="{{ $item['harga'] ?? '' }}" class="barang-harga">
                      @error("items.$index.harga")
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="col-md-8">
                      <label class="form-label">Deskripsi</label>
                      <textarea name="items[{{ $index }}][deskripsi]" class="form-control @error("items.$index.deskripsi") is-invalid @enderror barang-deskripsi" rows="3">{{ $item['deskripsi'] ?? '' }}</textarea>
                      @error("items.$index.deskripsi")
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="col-md-4">
                      <label class="form-label">Gambar</label>
                      <input type="file" name="items[{{ $index }}][image]" accept="image/*"
                        class="form-control @error("items.$index.image") is-invalid @enderror barang-image">
                      @error("items.$index.image")
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                      <img src="" alt="preview" class="barang-image-preview mt-2" style="display:none; max-height:120px; object-fit:cover;" />
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Semua</button>
        </div>
      </form>
    </div>
  </div>
</div>

<template id="barangRowTemplate">
  <div class="card border barang-item" data-index="__INDEX__">
    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
      <span class="fw-semibold">Barang <span class="barang-number">__NUMBER__</span></span>
      <button type="button" class="btn btn-outline-danger btn-sm remove-barang-row">Hapus</button>
    </div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Kode Barang</label>
          <input type="text" name="items[__INDEX__][kode_barang]" class="form-control barang-kode" readonly>
        </div>

        <div class="col-md-8">
          <label class="form-label">Nama Barang</label>
          <input type="text" name="items[__INDEX__][nama_barang]" class="form-control barang-nama">
        </div>

        <div class="col-md-4">
          <label class="form-label">Kategori</label>
          <select name="items[__INDEX__][kategori_id]" class="form-select barang-kategori">
            <option value="">-- Pilih Kategori --</option>
            @foreach ($kategoris as $k)
              <option value="{{ $k->id }}">{{ $k->kategori }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Satuan</label>
          <input type="text" name="items[__INDEX__][satuan]" class="form-control barang-satuan">
        </div>

        <div class="col-md-4">
          <label class="form-label">Harga</label>
          <input type="text" class="form-control barang-harga-format" placeholder="Rp 0">
          <input type="hidden" name="items[__INDEX__][harga]" class="barang-harga">
        </div>

        <div class="col-md-8">
          <label class="form-label">Deskripsi</label>
          <textarea name="items[__INDEX__][deskripsi]" class="form-control barang-deskripsi" rows="3"></textarea>
        </div>

        <div class="col-md-4">
          <label class="form-label">Gambar</label>
          <input type="file" name="items[__INDEX__][image]" accept="image/*" class="form-control barang-image">
          <img src="" alt="preview" class="barang-image-preview mt-2" style="display:none; max-height:120px; object-fit:cover;" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
function formatRupiah(angka) {
  return 'Rp ' + angka.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('formBarang');
  const wrapper = document.getElementById('barangItemsWrapper');
  const template = document.getElementById('barangRowTemplate');
  const addButton = document.getElementById('addBarangRow');
  const modal = document.getElementById('createBarangModal');
  let baseCodeNumber = parseInt(@json((int) preg_replace('/\D/', '', $nextKode ?? '1')), 10) || 1;

  if (!form || !wrapper || !template) return;

  function clearError(field) {
    field.classList.remove('is-invalid');
    const parent = field.closest('.col-md-4, .col-md-8, .col-md-12') || field.parentNode;
    const err = parent ? parent.querySelector('.invalid-feedback.js-error') : null;
    if (err) err.remove();
  }

  function showError(field, message) {
    field.classList.add('is-invalid');
    const parent = field.closest('.col-md-4, .col-md-8, .col-md-12') || field.parentNode;
    if (!parent) return;

    if (!parent.querySelector('.invalid-feedback.js-error')) {
      const div = document.createElement('div');
      div.className = 'invalid-feedback d-block js-error';
      div.innerText = message;
      parent.appendChild(div);
    }
  }

  function updateRemoveButtons() {
    const rows = wrapper.querySelectorAll('.barang-item');
    rows.forEach(function (row) {
      const button = row.querySelector('.remove-barang-row');
      if (button) {
        button.style.display = rows.length > 1 ? '' : 'none';
      }
    });
  }

  function updateKodeBarang() {
    wrapper.querySelectorAll('.barang-item').forEach(function (row, index) {
      const kodeInput = row.querySelector('.barang-kode');
      if (kodeInput) {
        kodeInput.value = 'BR' + String(baseCodeNumber + index).padStart(3, '0');
      }
    });
  }

  function updateRowIndexes() {
    wrapper.querySelectorAll('.barang-item').forEach(function (row, index) {
      row.dataset.index = index;
      const numberEl = row.querySelector('.barang-number');
      if (numberEl) numberEl.textContent = index + 1;

      row.querySelectorAll('[name]').forEach(function (field) {
        field.name = field.name.replace(/items\[\d+\]/, 'items[' + index + ']');
      });
    });

    updateKodeBarang();
    updateRemoveButtons();
  }

  function addBarangRow() {
    const index = wrapper.querySelectorAll('.barang-item').length;
    const html = template.innerHTML
      .replaceAll('__INDEX__', index)
      .replaceAll('__NUMBER__', index + 1);

    wrapper.insertAdjacentHTML('beforeend', html);
    updateRowIndexes();

    const newRow = wrapper.querySelector('.barang-item:last-child');
    if (newRow) {
      newRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      const firstInput = newRow.querySelector('.barang-nama');
      if (firstInput) firstInput.focus();
    }
  }

  function fetchKode() {
    fetch("{{ route('barang.nextKode') }}")
      .then(function (res) {
        return res.ok ? res.json() : Promise.reject(res);
      })
      .then(function (data) {
        if (data && data.kode) {
          const parsed = parseInt(String(data.kode).replace(/\D/g, ''), 10);
          if (!Number.isNaN(parsed)) {
            baseCodeNumber = parsed;
            updateKodeBarang();
          }
        }
      })
      .catch(function () {
        updateKodeBarang();
      });
  }

  addButton.addEventListener('click', addBarangRow);

  wrapper.addEventListener('click', function (event) {
    const removeButton = event.target.closest('.remove-barang-row');
    if (!removeButton) return;

    removeButton.closest('.barang-item').remove();
    updateRowIndexes();
  });

  wrapper.addEventListener('input', function (event) {
    if (event.target.classList.contains('barang-harga-format')) {
      const angka = event.target.value.replace(/\D/g, '');
      event.target.value = angka ? formatRupiah(angka) : '';
      const hidden = event.target.parentNode.querySelector('.barang-harga');
      if (hidden) hidden.value = angka;
    }
  });

  wrapper.addEventListener('change', function (event) {
    if (event.target.classList.contains('barang-image')) {
      const input = event.target;
      const preview = input.parentNode.querySelector('.barang-image-preview');
      const file = input.files && input.files[0] ? input.files[0] : null;

      if (!preview) return;

      if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
      } else {
        preview.src = '';
        preview.style.display = 'none';
      }
    }
  });

  form.addEventListener('submit', function (e) {
    let firstError = null;

    form.querySelectorAll('.barang-item').forEach(function (row) {
      const fields = [
        row.querySelector('.barang-kode'),
        row.querySelector('.barang-nama'),
        row.querySelector('.barang-kategori'),
        row.querySelector('.barang-satuan'),
        row.querySelector('.barang-deskripsi'),
        row.querySelector('.barang-harga-format')
      ];

      fields.forEach(function (field) {
        if (field) clearError(field);
      });

      const kode = row.querySelector('.barang-kode');
      const nama = row.querySelector('.barang-nama');
      const kategori = row.querySelector('.barang-kategori');
      const satuan = row.querySelector('.barang-satuan');
      const deskripsi = row.querySelector('.barang-deskripsi');
      const hargaFormat = row.querySelector('.barang-harga-format');
      const harga = row.querySelector('.barang-harga');

      if (kode && !kode.value.trim()) {
        showError(kode, 'Kode barang wajib diisi');
        firstError ??= kode;
      }

      if (nama && !nama.value.trim()) {
        showError(nama, 'Nama barang wajib diisi');
        firstError ??= nama;
      }

      if (kategori && !kategori.value) {
        showError(kategori, 'Kategori wajib dipilih');
        firstError ??= kategori;
      }

      if (satuan && !satuan.value.trim()) {
        showError(satuan, 'Satuan wajib diisi');
        firstError ??= satuan;
      }

      if (deskripsi && !deskripsi.value.trim()) {
        showError(deskripsi, 'Deskripsi wajib diisi');
        firstError ??= deskripsi;
      }

      if (hargaFormat && harga && (!harga.value || harga.value === '0')) {
        showError(hargaFormat, 'Harga wajib diisi');
        firstError ??= hargaFormat;
      }
    });

    if (firstError) {
      e.preventDefault();
      firstError.focus();
      return;
    }

    const submitBtn = form.querySelector('button[type="submit"]');
    if (!submitBtn) return;

    const originalHtml = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';

    setTimeout(function () {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalHtml;
    }, 10000);
  });

  wrapper.querySelectorAll('.barang-harga-format').forEach(function (input) {
    const angka = input.value.replace(/\D/g, '');
    if (angka) {
      input.value = formatRupiah(angka);
      const hidden = input.parentNode.querySelector('.barang-harga');
      if (hidden) hidden.value = angka;
    }
  });

  updateRowIndexes();
  fetchKode();

  if (modal) {
    modal.addEventListener('show.bs.modal', fetchKode);
  }
});
</script>
