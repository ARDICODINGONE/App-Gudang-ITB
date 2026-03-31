@extends('layouts.app')

@section('content')
    @auth
    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'petugas'))
    <div class="container-fluid bg-light py-3">
        <div class="container">
            <div class="row g-3 justify-content-center">
                <!-- Gudang -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 wow fadeInUp" data-wow-delay="0.1s">
                    <a href="{{ url('/gudang') }}" class="text-decoration-none">
                        <div class="menu-card p-3 rounded shadow-sm border-0 bg-white transition-all">
                            <div class="text-center">
                                <i class="fa fa-warehouse fa-2x text-primary d-block mb-2"></i>
                                <div class="text-xs fw-bold text-dark">Gudang</div>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Barang -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 wow fadeInUp" data-wow-delay="0.15s">
                    <a href="{{ url('/barang') }}" class="text-decoration-none">
                        <div class="menu-card p-3 rounded shadow-sm border-0 bg-white transition-all">
                            <div class="text-center">
                                <i class="fa fa-boxes fa-2x text-primary d-block mb-2"></i>
                                <div class="text-xs fw-bold text-dark">Barang</div>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Barang Masuk -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 wow fadeInUp" data-wow-delay="0.2s">
                    <a href="{{ url('/barang-masuk') }}" class="text-decoration-none">
                        <div class="menu-card p-3 rounded shadow-sm border-0 bg-white transition-all">
                            <div class="text-center">
                                <i class="fa fa-download fa-2x text-primary d-block mb-2"></i>
                                <div class="text-xs fw-bold text-dark">Barang Masuk</div>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Barang Keluar -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 wow fadeInUp" data-wow-delay="0.25s">
                    <a href="{{ url('/barang-keluar') }}" class="text-decoration-none">
                        <div class="menu-card p-3 rounded shadow-sm border-0 bg-white transition-all">
                            <div class="text-center">
                                <i class="fa fa-upload fa-2x text-primary d-block mb-2"></i>
                                <div class="text-xs fw-bold text-dark">Barang Keluar</div>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Kategori -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 wow fadeInUp" data-wow-delay="0.25s">
                    <a href="{{ url('/kategori') }}" class="text-decoration-none">
                        <div class="menu-card p-3 rounded shadow-sm border-0 bg-white transition-all">
                            <div class="text-center">
                                <i class="fa fa-bars fa-2x text-primary d-block mb-2"></i>
                                <div class="text-xs fw-bold text-dark">Kategori</div>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Laporan -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 wow fadeInUp" data-wow-delay="0.4s">
                    <a href="{{ url('/laporan') }}" class="text-decoration-none">
                        <div class="menu-card p-3 rounded shadow-sm border-0 bg-white transition-all">
                            <div class="text-center">
                                <i class="fa fa-file-alt fa-2x text-primary d-block mb-2"></i>
                                <div class="text-xs fw-bold text-dark">Laporan</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @elseif(auth()->check() && auth()->user()->role === 'approval')
    <div class="container-fluid bg-light py-3">
        <div class="container">
            <div class="row g-3 justify-content-center">
                <!-- Pengajuan -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 wow fadeInUp" data-wow-delay="0.15s">
                    <a href="{{ url('/pengajuan/list') }}" class="text-decoration-none">
                        <div class="menu-card p-3 rounded shadow-sm border-0 bg-white transition-all">
                            <div class="text-center">
                                <i class="fa fa-paper-plane fa-2x text-primary d-block mb-2"></i>
                                <div class="text-xs fw-bold text-dark">Pengajuan</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="container-fluid bg-light py-3">
        <div class="container">
            <div class="row g-3 justify-content-center">
                <!-- Gudang -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 wow fadeInUp" data-wow-delay="0.1s">
                    <a href="{{ url('/gudang') }}" class="text-decoration-none">
                        <div class="menu-card p-3 rounded shadow-sm border-0 bg-white transition-all">
                            <div class="text-center">
                                <i class="fa fa-warehouse fa-2x text-primary d-block mb-2"></i>
                                <div class="text-xs fw-bold text-dark">Gudang</div>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Pengajuan -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 wow fadeInUp" data-wow-delay="0.15s">
                    <a href="{{ url('/pengajuan/list') }}" class="text-decoration-none">
                        <div class="menu-card p-3 rounded shadow-sm border-0 bg-white transition-all">
                            <div class="text-center">
                                <i class="fa fa-paper-plane fa-2x text-primary d-block mb-2"></i>
                                <div class="text-xs fw-bold text-dark">Pengajuan</div>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Laporan -->
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 wow fadeInUp" data-wow-delay="0.2s">
                    <a href="{{ route('laporan.riwayat-pengajuan') }}" class="text-decoration-none">
                        <div class="menu-card p-3 rounded shadow-sm border-0 bg-white transition-all">
                            <div class="text-center">
                                <i class="fa fa-file-alt fa-2x text-primary d-block mb-2"></i>
                                <div class="text-xs fw-bold text-dark">Laporan</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endauth

    <style>
        .menu-card {
            cursor: pointer;
            border-radius: 20px;
            transition: transform 0.22s ease, box-shadow 0.22s ease;
        }

        .menu-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 32px -24px rgba(13, 110, 253, 0.55);
        }

        .welcome-shell {
            background:
                radial-gradient(circle at top left, rgba(13, 110, 253, 0.14), transparent 28%),
                radial-gradient(circle at bottom right, rgba(18, 140, 126, 0.10), transparent 24%),
                linear-gradient(180deg, #f7fbff 0%, #ffffff 100%);
        }

        .welcome-card {
            position: relative;
            border: 1px solid #e1ebf8;
            border-radius: 32px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fbff 55%, #eef6ff 100%);
            box-shadow: 0 28px 55px -42px rgba(13, 110, 253, 0.65);
        }

        .welcome-card::before {
            content: "";
            position: absolute;
            inset: auto 4% 8% auto;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37, 211, 102, 0.14) 0%, rgba(37, 211, 102, 0) 72%);
            pointer-events: none;
        }

        .welcome-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1rem;
            border-radius: 999px;
            background: rgba(13, 110, 253, 0.08);
            color: #0d6efd;
            font-weight: 600;
        }

        .welcome-title-accent {
            color: #0d6efd;
        }

        .welcome-stat {
            border: 1px solid #deebfa;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.86);
            padding: 1rem 1.1rem;
            height: 100%;
        }

        .welcome-stat-value {
            font-size: 1.35rem;
            font-weight: 700;
            color: #0f3d91;
        }

        .warehouse-card {
            border: 1px solid #e2eaf6;
            border-radius: 24px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 22px 46px -34px rgba(18, 52, 102, 0.35);
            transition: transform 0.24s ease, box-shadow 0.24s ease;
        }

        .warehouse-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 28px 54px -34px rgba(13, 110, 253, 0.35);
        }

        .warehouse-media {
            position: relative;
            height: 220px;
            overflow: hidden;
        }

        .warehouse-media img {
            transition: transform 0.35s ease;
        }

        .warehouse-card:hover .warehouse-media img {
            transform: scale(1.04);
        }

        .warehouse-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(7, 26, 58, 0.02) 0%, rgba(7, 26, 58, 0.58) 100%);
        }

        .warehouse-code {
            position: absolute;
            top: 1rem;
            left: 1rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.92);
            color: #0d6efd;
            font-size: 0.78rem;
            font-weight: 700;
            z-index: 1;
        }

        .warehouse-content {
            padding: 1.4rem;
        }

        .warehouse-location {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 0.2rem 0.75rem;
            color: #607089;
            font-size: 0.95rem;
        }

        .warehouse-location .label {
            color: #0d6efd;
            font-weight: 700;
        }

        .warehouse-action {
            border: 0;
            border-radius: 16px;
            background: linear-gradient(135deg, #0d6efd 0%, #3c8bfd 100%);
            color: #ffffff;
            font-weight: 600;
            padding: 0.9rem 1rem;
            box-shadow: 0 16px 30px -20px rgba(13, 110, 253, 0.7);
        }

        .warehouse-action:hover {
            color: #ffffff;
        }

        .help-section {
            background:
                radial-gradient(circle at top right, rgba(13, 110, 253, 0.12), transparent 30%),
                linear-gradient(135deg, #f8fbff 0%, #ffffff 100%);
        }

        .help-card {
            border: 1px solid #dfe9f7;
            border-radius: 22px;
            background: #ffffff;
            box-shadow: 0 18px 45px -34px rgba(13, 110, 253, 0.5);
        }

        .help-step {
            border: 1px solid #e7eef8;
            border-radius: 18px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            height: 100%;
        }

        .help-step-badge {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #0d6efd;
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 10px 22px -12px rgba(13, 110, 253, 0.9);
        }

        .help-shortcut {
            border: 1px dashed #c8daf7;
            border-radius: 16px;
            background: #f8fbff;
        }

        .help-contact {
            border: 1px solid #d6e5fb;
            border-radius: 18px;
            background: linear-gradient(135deg, #128c7e 0%, #25d366 100%);
            color: #ffffff;
            box-shadow: 0 18px 38px -28px rgba(37, 211, 102, 0.85);
        }

        .help-contact .btn {
            border-radius: 999px;
            font-weight: 600;
            color: #128c7e;
        }

        .help-accordion .accordion-item {
            border: 1px solid #e6edf8;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 0.85rem;
        }

        .help-accordion .accordion-button {
            font-weight: 600;
            background: #ffffff;
            box-shadow: none;
        }

        .help-accordion .accordion-button:not(.collapsed) {
            color: #0d6efd;
            background: #eef5ff;
        }

        @media (max-width: 767.98px) {
            .welcome-card {
                border-radius: 24px;
            }

            .warehouse-media {
                height: 200px;
            }

            .warehouse-content {
                padding: 1.1rem;
            }
        }
    </style>
    <div class="container-fluid welcome-shell py-4 py-lg-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-11">
                    <div class="welcome-card overflow-hidden">
                        <div class="card-body p-4 p-md-5 p-xl-5">
                            <div class="row g-4 align-items-center">
                                <div class="col-lg-7">
                                    <span class="welcome-kicker mb-3">
                                        <i class="fa fa-sparkles"></i>
                                        Dashboard Inventaris GUDIT
                                    </span>
                                    <h1 class="display-5 fw-bold text-dark mb-3">
                                        <span class="welcome-title-accent">Selamat Datang</span> di pusat pengelolaan <span class="welcome-title-accent">GUDIT</span>
                                    </h1>
                                    <p class="fs-5 text-muted mb-3" style="max-width: 760px;">
                                        Pilih gudang terlebih dahulu untuk melihat stok, mengelola data barang, dan melanjutkan proses inventaris dengan alur yang lebih cepat dan terstruktur.
                                    </p>
                                    <p class="small text-secondary mb-0" style="max-width: 700px;">
                                        Gudang yang dipilih akan menentukan daftar barang, ketersediaan stok, serta transaksi yang tampil pada halaman berikutnya.
                                    </p>
                                </div>
                                <div class="col-lg-5">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="welcome-stat">
                                                <div class="welcome-stat-value">{{ \App\Models\Gudang::count() }}</div>
                                                <div class="text-muted small mt-1">Gudang tersedia untuk dipilih</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="welcome-stat">
                                                <div class="welcome-stat-value">{{ \App\Models\Barang::count() }}</div>
                                                <div class="text-muted small mt-1">Barang tercatat di sistem</div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="welcome-stat">
                                                <div class="fw-semibold text-dark mb-2">Mulai dari sini</div>
                                                <div class="text-muted small mb-0">
                                                    Buka salah satu gudang di bawah untuk masuk ke tampilan stok dan melanjutkan aktivitas inventaris sesuai peran akun Anda.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5 help-section">
        <div class="container">
            <div class="help-card p-4 p-lg-5">
                <div class="row g-4 align-items-start">
                    <div class="col-lg-5">
                        <span class="badge rounded-pill text-primary bg-primary-subtle px-3 py-2 mb-3">Bantuan Penggunaan</span>
                        <h2 class="fw-bold text-dark mb-3">Panduan singkat memakai aplikasi GUDIT</h2>
                        <p class="text-muted mb-4">
                            Section ini bisa dipakai sebagai pusat bantuan cepat untuk pengguna baru. Alurnya dibuat ringkas supaya user tahu harus mulai dari mana.
                        </p>

                        <div class="help-shortcut p-3 p-md-4">
                            <div class="fw-semibold text-dark mb-2">Alur paling umum</div>
                            <div class="text-muted small mb-0">
                                Pilih gudang terlebih dahulu, cek data barang yang tersedia, lalu lanjutkan proses barang masuk, barang keluar, atau pengajuan sesuai peran pengguna.
                            </div>
                        </div>

                        <div class="help-contact p-3 p-md-4 mt-3">
                            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                                <div>
                                    <div class="fw-bold mb-1">Butuh bantuan lebih lanjut?</div>
                                    <div class="small opacity-75">
                                        Jika ada kendala saat menggunakan aplikasi, hubungi WhatsApp admin di <strong>08****</strong>.
                                    </div>
                                </div>
                                <a href="https://wa.me/6281222780639" target="_blank" rel="noopener noreferrer" class="btn btn-light text-primary px-4">
                                    Hubungi WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="help-step p-3 p-md-4">
                                    <div class="help-step-badge mb-3">1</div>
                                    <h6 class="fw-bold mb-2">Pilih Gudang</h6>
                                    <p class="text-muted small mb-0">
                                        Masuk ke gudang yang sesuai agar stok dan daftar barang tampil sesuai lokasi penyimpanan.
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="help-step p-3 p-md-4">
                                    <div class="help-step-badge mb-3">2</div>
                                    <h6 class="fw-bold mb-2">Kelola Data</h6>
                                    <p class="text-muted small mb-0">
                                        Tambahkan barang, atur kategori, dan lengkapi data supaya transaksi GUDIT tercatat dengan rapi.
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="help-step p-3 p-md-4">
                                    <div class="help-step-badge mb-3">3</div>
                                    <h6 class="fw-bold mb-2">Proses Transaksi</h6>
                                    <p class="text-muted small mb-0">
                                        Gunakan menu barang masuk, barang keluar, atau pengajuan untuk mencatat perpindahan stok.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion help-accordion mt-4" id="helpAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="helpHeadingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#helpCollapseOne" aria-expanded="true" aria-controls="helpCollapseOne">
                                        Bagaimana cara memulai penggunaan aplikasi?
                                    </button>
                                </h2>
                                <div id="helpCollapseOne" class="accordion-collapse collapse show" aria-labelledby="helpHeadingOne" data-bs-parent="#helpAccordion">
                                    <div class="accordion-body text-muted">
                                        Buka halaman utama, pilih gudang yang ingin digunakan, lalu masuk ke menu yang dibutuhkan seperti barang, barang masuk, barang keluar, atau pengajuan.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="helpHeadingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#helpCollapseTwo" aria-expanded="false" aria-controls="helpCollapseTwo">
                                        Menu apa saja yang biasanya dipakai admin atau petugas?
                                    </button>
                                </h2>
                                <div id="helpCollapseTwo" class="accordion-collapse collapse" aria-labelledby="helpHeadingTwo" data-bs-parent="#helpAccordion">
                                    <div class="accordion-body text-muted">
                                        Admin dan petugas biasanya memakai menu gudang, barang, kategori, barang masuk, barang keluar, dan laporan untuk mengelola stok serta riwayat GUDIT.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="helpHeadingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#helpCollapseThree" aria-expanded="false" aria-controls="helpCollapseThree">
                                        Jika hanya ingin mengajukan barang, harus lewat mana?
                                    </button>
                                </h2>
                                <div id="helpCollapseThree" class="accordion-collapse collapse" aria-labelledby="helpHeadingThree" data-bs-parent="#helpAccordion">
                                    <div class="accordion-body text-muted">
                                        Gunakan menu pengajuan setelah memilih gudang. Riwayat atau status permintaan bisa dilihat kembali pada menu laporan atau daftar pengajuan sesuai hak akses.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="helpHeadingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#helpCollapseFour" aria-expanded="false" aria-controls="helpCollapseFour">
                                        Bagaimana kalau stok atau barang tidak muncul?
                                    </button>
                                </h2>
                                <div id="helpCollapseFour" class="accordion-collapse collapse" aria-labelledby="helpHeadingFour" data-bs-parent="#helpAccordion">
                                    <div class="accordion-body text-muted">
                                        Pastikan gudang yang dipilih sudah benar. Jika masih tidak muncul, cek apakah barang sudah ditambahkan ke gudang tersebut dan apakah stok sudah pernah dicatat.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->check() && auth()->user()->role !== 'approval')
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div class="row g-4">
                @php
                    $allGudang = \App\Models\Gudang::all();
                @endphp

                @forelse($allGudang as $g)
                    <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                        <div class="warehouse-card h-100">
                            <div class="warehouse-media">
                                <div class="warehouse-code">{{ $g->kode_gudang }}</div>
                                <div class="warehouse-overlay"></div>
                                    @if($g->images)
                                        <img src="{{ asset('storage/' . $g->images) }}" class="img-fluid h-100 w-100" style="object-fit: cover;" alt="{{ $g->nama_gudang }}">
                                    @else
                                        <img src="{{ asset('img/default-gudang.png') }}" class="img-fluid h-100 w-100" style="object-fit: cover;" alt="{{ $g->nama_gudang }}">
                                    @endif
                            </div>
                            <div class="warehouse-content">
                                <h3 class="text-primary mb-2">{{ $g->nama_gudang }}</h3>
                                <p class="text-muted small mb-3">Pilih gudang ini untuk melihat stok dan transaksi yang sesuai lokasi.</p>
                                <div class="warehouse-location mb-4">
                                    <span class="label">Lokasi</span>
                                    <div>{!! nl2br(e($g->lokasi)) !!}</div>
                                </div>
                                <button type="button" class="warehouse-action w-100 d-block mx-auto" onclick="window.location.href='{{ url('/gudang') }}?kode={{ $g->kode_gudang }}'">
                                    Masuk ke Gudang
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">Belum ada gudang terdaftar.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif
@endsection
