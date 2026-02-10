@extends('layouts.app')

@section('content')
    @auth
    @if(auth()->user()->role === 'atasan' || auth()->user()->role === 'admin')
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
                    <a href="{{ url('/shop') }}" class="text-decoration-none">
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
                    <a href="{{ url('/shop') }}" class="text-decoration-none">
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
        }
    </style>
    <div class="container-fluid px-0">
        <div class="row justify-content-center mb-5 mx-0">
            <div class="col-12 col-lg-10 px-0">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-body p-3 p-md-4 p-lg-5 text-center">
                        <h1 class="display-5 fw-bold text-primary mb-3">
                            Selamat Datang
                        </h1>
                        <p class="fs-5 text-muted mb-3 mx-auto" style="max-width: 720px;">
                            Silakan pilih gudang yang tersedia terlebih dahulu
                            <br class="d-none d-md-block">
                            untuk melanjutkan pengajuan barang.
                        </p>
                        <p class="small text-secondary mb-4 mx-auto" style="max-width: 680px;">
                            Pilihan gudang akan menentukan barang dan stok yang tersedia.
                        </p>
                        <div class="d-flex justify-content-center mb-4">
                            <span class="bg-primary rounded-pill" style="width: 60px; height: 4px; opacity: 0.25;"></span>
                        </div>
                        <p class="small text-muted mb-0">
                            Gudang dapat diganti kembali melalui menu pengaturan.
                        </p>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div class="row g-4">
                @php
                    use App\Models\Gudang;
                    $allGudang = Gudang::all();
                @endphp

                @forelse($allGudang as $g)
                    <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                        <div class="border bg-white rounded p-4 h-100">
                            <div class="mb-4">
                                <div class="rounded overflow-hidden" style="height: 200px;">
                                    @if($g->images)
                                        <img src="{{ asset('storage/' . $g->images) }}" class="img-fluid h-100 w-100" style="object-fit: cover;" alt="{{ $g->nama_gudang }}">
                                    @else
                                        <img src="{{ asset('img/default-gudang.png') }}" class="img-fluid h-100 w-100" style="object-fit: cover;" alt="{{ $g->nama_gudang }}">
                                    @endif
                                </div>
                            </div>
                            <div>
                                <h3 class="text-primary mb-3">{{ $g->nama_gudang }}</h3>
                                <div class="text-muted mb-4 d-grid" style="grid-template-columns: auto 1fr; gap: 0 10px;">
                                    <span class="text-primary fw-bold">Location:</span>
                                    <div>{!! nl2br(e($g->lokasi)) !!}</div>
                                </div>
                                <button type="button" class="btn-modern px-4 w-100 d-block mx-auto" onclick="window.location.href='{{ url('/gudang') }}?kode={{ $g->kode_gudang }}'">Masuk</button>
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
@endsection