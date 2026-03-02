@extends('layouts.app')

@section('title', 'Tambah Barang Masuk')

@section('content')
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Tambah Barang Masuk - Gudang {{ $gudangKode }}</div>

          <div class="card-body">
            <form action="{{ route('barang-masuk.store') }}" method="POST">
              @csrf

              <div class="mb-3">
                <label class="form-label">Daftar Barang</label>
                <div id="barangMasukPageItems" class="d-flex flex-column gap-2">
                  <div class="row g-2 align-items-end barang-item-row">
                    <div class="col-7">
                      <select name="id_barang[]" class="form-control" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barangs as $b)
                          <option value="{{ $b->id }}" {{ (isset($selectedBarang) && $selectedBarang == $b->id) ? 'selected' : '' }}>{{ $b->nama_barang }} ({{ $b->kode_barang ?? '' }})</option>
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
                <button type="button" id="btnTambahBarisBarangMasukPage" class="btn btn-sm btn-outline-primary mt-2">
                  + Tambah Barang
                </button>
              </div>

              <div class="mb-3">
                <label class="form-label">Gudang</label>
                <input type="hidden" name="kode_gudang" value="{{ $gudangKode }}">
                <div class="form-control" style="background:#f8f9fa;">{{ $gudangKode }}</div>
              </div>

              <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" required value="{{ old('tanggal', \Carbon\Carbon::now()->format('Y-m-d')) }}">
              </div>

              <div class="d-flex justify-content-end">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var container = document.getElementById('barangMasukPageItems');
  var addBtn = document.getElementById('btnTambahBarisBarangMasukPage');
  if (!container || !addBtn) return;

  function updateRemoveButtonsState() {
    var rows = container.querySelectorAll('.barang-item-row');
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
        <select name="id_barang[]" class="form-control" required>
          <option value="">-- Pilih Barang --</option>
          @foreach($barangs as $b)
            <option value="{{ $b->id }}">{{ $b->nama_barang }} ({{ $b->kode_barang ?? '' }})</option>
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
    container.appendChild(row);
    updateRemoveButtonsState();
  }

  addBtn.addEventListener('click', function () {
    createRow();
  });

  container.addEventListener('click', function (e) {
    var removeBtn = e.target.closest('.btn-remove-row');
    if (!removeBtn) return;
    var rows = container.querySelectorAll('.barang-item-row');
    if (rows.length === 1) return;
    removeBtn.closest('.barang-item-row').remove();
    updateRemoveButtonsState();
  });

  updateRemoveButtonsState();
});
</script>
@endsection
