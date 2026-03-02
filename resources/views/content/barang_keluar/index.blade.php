@extends('layouts/app')

@section('title', 'Barang Keluar - Index')

@section('content')
  <style>
    .bk-page {
      background: linear-gradient(180deg, #fff9f7 0%, #ffffff 100%);
      border-radius: 18px;
      padding: 1.25rem;
      margin: 1.5rem auto;
      max-width: 1280px;
      box-shadow: 0 18px 40px -30px rgba(220, 53, 69, 0.35);
    }

    .bk-card {
      border: 1px solid #f3e4e7;
      border-radius: 14px;
      overflow: hidden;
      background: #fff;
    }

    .bk-header {
      padding: 1rem 1.25rem;
      background: linear-gradient(90deg, #dc3545 0%, #e15260 70%, #ea6c79 100%);
      color: #fff;
    }

    .bk-subtitle {
      margin: 0;
      font-size: 0.88rem;
      opacity: 0.92;
    }

    .bk-table thead th {
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      font-weight: 700;
      color: #6e5b65;
      border-bottom-width: 1px;
      background: #fff8f9;
      white-space: nowrap;
    }

    .bk-table tbody td {
      vertical-align: middle;
    }

    .bk-no {
      display: inline-block;
      min-width: 30px;
      text-align: center;
      padding: 0.24rem 0.48rem;
      border-radius: 999px;
      background: #ffe4e7;
      color: #dc3545;
      font-weight: 700;
      font-size: 0.76rem;
    }

    .bk-jumlah {
      font-weight: 700;
      color: #a3172b;
    }

    .bk-tanggal {
      color: #745964;
      font-size: 0.88rem;
      white-space: nowrap;
    }

    .bk-aksi {
      display: flex;
      gap: 0.4rem;
      flex-wrap: wrap;
      justify-content: center;
    }

    .bk-empty {
      padding: 2rem 1rem;
      text-align: center;
      color: #91717c;
    }

    .bk-empty i {
      font-size: 2rem;
      color: #bd97a3;
      margin-bottom: 0.5rem;
      display: inline-block;
    }

    @media (max-width: 767.98px) {
      .bk-page {
        margin: 1rem 0.75rem;
        padding: 0.75rem;
      }

      .bk-header {
        padding: 1rem;
      }
    }
  </style>

  <div class="bk-page">
    <div class="bk-card">
      <div class="bk-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
          <h5 class="mb-1 text-white">Daftar Barang Keluar</h5>
          <p class="bk-subtitle">Kontrol arus keluar barang agar stok gudang tetap akurat.</p>
        </div>
      </div>

      <div class="card-body p-3 p-md-4">
        @if (session('success'))
          <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i>
            <div>{{ session('success') }}</div>
          </div>
        @endif

        <div class="table-responsive">
          <table class="table table-hover bk-table align-middle mb-0">
            <thead>
              <tr>
                <th>No</th>
                <th>Barang</th>
                <th>Gudang</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse ($items as $item)
                <tr>
                  <td>
                    <span class="bk-no">{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</span>
                  </td>
                  <td>
                    <div class="fw-semibold">{{ optional($item->barang)->nama_barang ?? '-' }}</div>
                    <small class="text-muted">
                      {{ optional($item->barang)->deskripsi ? \Illuminate\Support\Str::limit(optional($item->barang)->deskripsi, 80) : '-' }}
                    </small>
                  </td>
                  <td>{{ optional($item->gudang)->nama_gudang ?? '-' }}</td>
                  <td class="bk-jumlah">{{ $item->jumlah }}</td>
                  <td class="bk-tanggal">{{ $item->tanggal }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="6">
                    <div class="bk-empty">
                      <i class="ri-inbox-archive-line"></i>
                      <div class="fw-semibold">Belum ada data barang keluar</div>
                      <small>Tambahkan transaksi barang keluar untuk memonitor pengurangan stok.</small>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
          {{ $items->render('pagination::bootstrap-4') }}
        </div>
      </div>
    </div>
  </div>

  <script>
    function konfirmasiHapus(actionUrl) {
      var form = document.getElementById('formHapusBarangKeluar');
      form.action = actionUrl;
      var myModal = new bootstrap.Modal(document.getElementById('modalHapusBarangKeluar'));
      myModal.show();
    }

    function editBarangKeluar(actionUrl, id, id_barang, kode_gudang, jumlah, tanggal) {
      document.getElementById('edit_id_bk').value = id;
      document.getElementById('edit_id_barang').value = id_barang;
      document.getElementById('edit_kode_gudang').value = kode_gudang;
      document.getElementById('edit_jumlah').value = jumlah;
      document.getElementById('edit_tanggal').value = tanggal;
      document.getElementById('formEditBarangKeluar').action = actionUrl;
      var myModal = new bootstrap.Modal(document.getElementById('modalEditBarangKeluar'));
      myModal.show();
    }
  </script>
@endsection
