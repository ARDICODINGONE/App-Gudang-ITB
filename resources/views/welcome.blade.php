@extends('layouts.app')

@section('content')
    <div class="container-fluid px-0">
        <div class="row g-0">
            <div class="col-6 col-md-4 col-lg-2 border-start border-end wow fadeInUp" data-wow-delay="0.1s">
                <div class="p-4">
                    <div class="d-inline-flex align-items-center">
                        <i class="fa fa-warehouse fa-2x text-primary"></i>
                        <div class="ms-4">
                            <a href="{{ url('/gudang') }}">
                                <h6 class="text-uppercase mb-2">Gudang</h6>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-start border-end wow fadeInUp" data-wow-delay="0.1s">
                <div class="p-4">
                    <div class="d-inline-flex align-items-center">
                        <i class="fa fa-boxes fa-2x text-primary"></i>
                        <div class="ms-4">
                            <a href="{{ url('/barang') }}">
                                <h6 class="text-uppercase mb-2">Barang</h6>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-start border-end wow fadeInUp" data-wow-delay="0.1s">
                <div class="p-4">
                    <div class="d-inline-flex align-items-center">
                        <i class="fa fa-download fa-2x text-primary"></i>
                        <div class="ms-4">
                            <a href="{{ url('/barang-masuk') }}">
                                <h6 class="text-uppercase mb-2">Barang Masuk</h6>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-start border-end wow fadeInUp" data-wow-delay="0.1s">
                <div class="p-4">
                    <div class="d-inline-flex align-items-center">
                        <i class="fa fa-bars fa-2x text-primary"></i>
                        <div class="ms-4">
                            <a href="{{ url('/kategori') }}">
                                <h6 class="text-uppercase mb-2">Kategori</h6>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-start border-end wow fadeInUp" data-wow-delay="0.1s">
                <div class="p-4">
                    <div class="d-inline-flex align-items-center">
                        <i class="fa fa-user-plus fa-2x text-primary"></i>
                        <div class="ms-4">
                            <a href="{{ url('/supplier') }}">
                                <h6 class="text-uppercase mb-2">Supplier</h6>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-start border-end wow fadeInUp" data-wow-delay="0.1s">
                <div class="p-4">
                    <div class="d-inline-flex align-items-center">
                        <i class="fa fa-users fa-2x text-primary"></i>
                        <div class="ms-4">
                            <a href="{{ url('/user') }}">
                                <h6 class="text-uppercase mb-2">User</h6>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-start border-end wow fadeInUp" data-wow-delay="0.1s">
                <div class="p-4">
                    <div class="d-inline-flex align-items-center">
                        <i class="fa fa-file-alt fa-2x text-primary"></i>
                        <div class="ms-4">
                            <a href="{{ url('/shop') }}">
                                <h6 class="text-uppercase mb-2">Laporan</h6>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                    use App\Models\gudang;
                    $allGudang = gudang::all();
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