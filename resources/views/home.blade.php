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
        .dashboard-top {
            background:
                radial-gradient(circle at top left, rgba(13, 110, 253, 0.15), transparent 26%),
                linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
        }

        .menu-card {
            cursor: pointer;
            border-radius: 20px;
            transition: transform 0.22s ease, box-shadow 0.22s ease;
        }

        .menu-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 32px -24px rgba(13, 110, 253, 0.55);
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

        .summary-card {
            border: 1px solid #e2ebf8;
            border-radius: 22px;
            background: #ffffff;
            box-shadow: 0 18px 40px -34px rgba(13, 110, 253, 0.45);
            height: 100%;
        }

        .summary-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0d6efd 0%, #4a96ff 100%);
            color: #ffffff;
            font-size: 1.15rem;
            box-shadow: 0 16px 28px -20px rgba(13, 110, 253, 0.7);
        }

        .summary-value {
            font-size: 1.9rem;
            font-weight: 700;
            line-height: 1;
            color: #12356c;
        }

        .activity-card {
            border: 1px solid #e2ebf8;
            border-radius: 24px;
            background: #ffffff;
            box-shadow: 0 18px 42px -34px rgba(18, 52, 102, 0.35);
        }

        .activity-item {
            border: 1px solid #edf2fa;
            border-radius: 18px;
            padding: 0.95rem 1rem;
            background: linear-gradient(180deg, #ffffff 0%, #f9fbff 100%);
        }

        .activity-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        @media (max-width: 767.98px) {
            .warehouse-media {
                height: 200px;
            }

            .warehouse-content {
                padding: 1.1rem;
            }
        }
    </style>
    <div class="container-fluid dashboard-top py-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-12">
                    <div class="activity-card p-4 h-100">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2 mb-3">
                            <div>
                                <h4 class="mb-1 text-dark">{{ $dashboardTitle ?? 'Ringkasan Dashboard' }}</h4>
                                <p class="text-muted mb-0">Statistik dan aktivitas ditampilkan sesuai role akun yang sedang login.</p>
                            </div>
                            <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">Dashboard</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-1">
                @foreach(($dashboardStats ?? []) as $stat)
                <div class="col-md-6 col-xl-3">
                    <div class="summary-card p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted small mb-2">{{ $stat['label'] }}</div>
                                <div class="summary-value">{{ $stat['value'] }}</div>
                            </div>
                            <div class="summary-icon">
                                <i class="fa {{ $stat['icon'] }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="row g-4 mt-1">
                <div class="col-12">
                    <div class="activity-card p-4 h-100">
                        <h4 class="mb-1 text-dark">Aktivitas Terbaru</h4>
                        <p class="text-muted mb-3">Daftar aktivitas yang relevan dengan role Anda.</p>
                        <div class="d-flex flex-column gap-3">
                            @forelse($recentActivities ?? [] as $activity)
                                <div class="activity-item d-flex gap-3 align-items-start">
                                    <div class="activity-icon bg-{{ $activity['color'] }}-subtle text-{{ $activity['color'] }}">
                                        <i class="fa {{ $activity['icon'] }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-dark">{{ $activity['title'] }}</div>
                                        <div class="small text-muted">{{ $activity['subtitle'] }}</div>
                                        <div class="small mt-1 text-secondary">
                                            {{ $activity['meta'] }}
                                            @if(!empty($activity['date']))
                                                • {{ \Illuminate\Support\Carbon::parse($activity['date'])->translatedFormat('d M Y') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted small">Belum ada aktivitas terbaru.</div>
                            @endforelse
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
