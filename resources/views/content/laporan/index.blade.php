@extends('layouts/app')

@section('title', 'Laporan - Index')

@section('content')
  <style>
    .laporan-page {
      --lp-bg-top: #f4f8ff;
      --lp-bg-bottom: #ffffff;
      --lp-surface: #ffffff;
      --lp-border: #e5ecf8;
      --lp-text: #1f2a44;
      --lp-muted: #5e6c87;
      --lp-shadow: 0 20px 44px -34px rgba(15, 44, 92, 0.45);
      --lp-hover-shadow: 0 28px 56px -34px rgba(15, 44, 92, 0.58);
      max-width: 1280px;
      margin: 1.5rem auto;
      padding: 1rem;
      border-radius: 20px;
      background: linear-gradient(180deg, var(--lp-bg-top) 0%, var(--lp-bg-bottom) 100%);
      box-shadow: var(--lp-shadow);
    }

    .laporan-hero {
      border: 1px solid #dbe7ff;
      border-radius: 16px;
      padding: 1.1rem 1.25rem;
      background: linear-gradient(100deg, #0d6efd 0%, #2e82ff 75%, #5ca0ff 100%);
      color: #fff;
    }

    .laporan-hero h1 {
      font-size: 1.4rem;
      margin: 0 0 0.25rem;
      font-weight: 700;
      letter-spacing: 0.01em;
    }

    .laporan-hero p {
      margin: 0;
      opacity: 0.92;
      font-size: 0.9rem;
    }

    .laporan-grid {
      margin-top: 1rem;
    }

    .laporan-card {
      position: relative;
      border: 1px solid var(--lp-border);
      border-radius: 16px;
      background: var(--lp-surface);
      padding: 1rem;
      height: 100%;
      transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
      box-shadow: 0 14px 30px -28px rgba(20, 38, 76, 0.65);
    }

    .laporan-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--lp-hover-shadow);
      border-color: #c8dafc;
    }

    .laporan-icon {
      width: 52px;
      height: 52px;
      border-radius: 13px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 0.8rem;
    }

    .laporan-icon.blue {
      background: #eaf2ff;
      color: #0d6efd;
    }

    .laporan-icon.green {
      background: #e9f8ee;
      color: #198754;
    }

    .laporan-icon.red {
      background: #ffecef;
      color: #dc3545;
    }

    .laporan-icon.yellow {
      background: #fff8df;
      color: #b98500;
    }

    .laporan-icon.cyan {
      background: #e7f7fc;
      color: #0c8ba6;
    }

    .laporan-card h5 {
      color: var(--lp-text);
      font-size: 1rem;
      margin-bottom: 0.4rem;
      font-weight: 700;
    }

    .laporan-card p {
      color: var(--lp-muted);
      font-size: 0.86rem;
      margin-bottom: 0.9rem;
      min-height: 2.4rem;
    }

    .laporan-link {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      font-weight: 600;
      font-size: 0.84rem;
      text-decoration: none;
    }

    @media (max-width: 767.98px) {
      .laporan-page {
        margin: 1rem 0.75rem;
        padding: 0.75rem;
      }

      .laporan-hero {
        padding: 1rem;
      }

      .laporan-hero h1 {
        font-size: 1.2rem;
      }
    }
  </style>

  <div class="laporan-page">
    <div class="laporan-hero d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
      <div>
        <h1><i class="bi bi-bar-chart-line me-1"></i>Laporan Sistem</h1>
        <p>Kelola dan akses laporan stok, barang masuk, barang keluar, dan pengajuan.</p>
      </div>
      <div>
        <a href="/" class="btn btn-sm btn-light text-primary fw-semibold">
          <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
      </div>
    </div>

    <div class="row g-3 laporan-grid">
      <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="laporan-card">
          <div class="laporan-icon blue"><i class="bi bi-hdd-network"></i></div>
          <h5>Laporan Stok Gudang</h5>
          <p>Lihat detail stok barang di setiap gudang secara aktual.</p>
          <a href="{{ route('laporan.stok-gudang') }}" class="laporan-link text-primary">Buka Laporan <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="laporan-card">
          <div class="laporan-icon green"><i class="bi bi-box-arrow-in-down"></i></div>
          <h5>Laporan Barang Masuk</h5>
          <p>Pantau riwayat barang yang masuk ke gudang dari waktu ke waktu.</p>
          <a href="{{ route('laporan.barang-masuk') }}" class="laporan-link text-success">Buka Laporan <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="laporan-card">
          <div class="laporan-icon red"><i class="bi bi-box-arrow-up-right"></i></div>
          <h5>Laporan Barang Keluar</h5>
          <p>Pantau riwayat barang yang keluar untuk kontrol pengurangan stok.</p>
          <a href="{{ route('laporan.barang-keluar') }}" class="laporan-link text-danger">Buka Laporan <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>

      @if(auth()->user()->role === 'atasan')
        <div class="col-md-6 col-lg-4 col-xl-3">
          <div class="laporan-card">
            <div class="laporan-icon yellow"><i class="bi bi-card-checklist"></i></div>
            <h5>Laporan Pengajuan</h5>
            <p>Lihat status dan detail pengajuan untuk kebutuhan persetujuan.</p>
            <a href="{{ route('laporan.pengajuan') }}" class="laporan-link text-warning">Buka Laporan <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      @endif

      <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="laporan-card">
          <div class="laporan-icon cyan"><i class="bi bi-clock-history"></i></div>
          <h5>Riwayat Pengajuan</h5>
          <p>Lihat histori pengajuan dengan kontrol akses sesuai role pengguna.</p>
          <a href="{{ route('laporan.riwayat-pengajuan') }}" class="laporan-link text-info">Buka Laporan <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
@endsection
