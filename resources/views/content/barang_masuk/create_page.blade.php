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
                <label class="form-label">Barang</label>
                <select name="id_barang" class="form-control" required>
                  <option value="">-- Pilih Barang --</option>
                  @foreach($barangs as $b)
                    <option value="{{ $b->id }}" {{ (isset($selectedBarang) && $selectedBarang == $b->id) ? 'selected' : '' }}>{{ $b->nama_barang }} ({{ $b->kode_barang ?? '' }})</option>
                  @endforeach
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Gudang</label>
                <input type="hidden" name="kode_gudang" value="{{ $gudangKode }}">
                <div class="form-control" style="background:#f8f9fa;">{{ $gudangKode }}</div>
              </div>

              

              <div class="mb-3">
                <label class="form-label">Jumlah</label>
                <input type="number" name="jumlah" class="form-control" min="1" required>
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
@endsection
