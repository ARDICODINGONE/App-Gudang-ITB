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
                        <i class="fa fa-bars fa-2x text-primary"></i>
                        <div class="ms-4">
                            <a href="{{ url('/kategori')}}">
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
                            <a href="{{ url('/supplier')}}">
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
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-5 text-center position-relative">
                    <div class="position-relative z-1">
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
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="border bg-white rounded p-4 h-100">
                        <div class="mb-4">
                            <div class="rounded overflow-hidden" style="height: 200px;">
                                <img src="{{ asset('img/KampusGanesha1.png') }}" class="img-fluid h-100 w-100"
                                    style="object-fit: cover;" alt="Kampus Ganesha">
                            </div>
                        </div>
                        <div>
                            <h3 class="text-primary mb-3">Kampus Ganesha</h3>
                            <div class="text-muted mb-4 d-grid" style="grid-template-columns: auto 1fr; gap: 0 10px;">
                                <span class="text-primary fw-bold">Location:</span>
                                <div>
                                    Jl. Ganesha 10 <br>
                                    Coblong, Kota Bandung, Jawa Barat <br>
                                    Indonesia 40132 <br>
                                </div>
                            </div>
                            <button type="button" class="btn-modern px-4 w-100 d-block mx-auto"
                                onclick="window.location.href='{{ url('/shop') }}'">
                                Masuk
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="border bg-white rounded p-4 h-100">
                        <div class="mb-4">
                            <div class="rounded overflow-hidden" style="height: 200px;">
                                <img src="{{ asset('img/KampusJatinangor.png') }}" class="img-fluid h-100 w-100"
                                    style="object-fit: cover;" alt="Kampus Janitanagor">
                            </div>
                        </div>
                        <div>
                            <h3 class="text-primary mb-3">Kampus Jatinangor</h3>
                            <div class="text-muted mb-4 d-grid" style="grid-template-columns: auto 1fr; gap: 0 10px;">
                                <span class="text-primary fw-bold">Location:</span>
                                <div>
                                    Jalan Let. Jen. Purn. Dr. (HC). Mashudi No. 1 <br>
                                    Jatinangor, Kab. Sumedang <br>
                                    Jawa Barat, Indonesia 45363 <br>
                                </div>
                            </div>
                            <button type="button" class="btn-modern px-4 w-100 d-block mx-auto"
                                onclick="window.location.href='{{ url('/') }}'">
                                Masuk
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="border bg-white rounded p-4 h-100">
                        <div class="mb-4">
                            <div class="rounded overflow-hidden" style="height: 200px;">
                                <img src="{{ asset('img/KampusCirebon1.png') }}" class="img-fluid h-100 w-100"
                                    style="object-fit: cover;" alt="Kampus Cirebon">
                            </div>
                        </div>
                        <div>
                            <h3 class="text-primary mb-3">Kampus Cirebon</h3>
                            <div class="text-muted mb-4 d-grid" style="grid-template-columns: auto 1fr; gap: 0 10px;">
                                <span class="text-primary fw-bold">Location:</span>
                                <div>
                                    Jl. Kebonturi, Arjawinangun <br>
                                    Kab. Cirebon <br>
                                    Jawa Barat, Indonesia 45162 <br>
                                </div>
                            </div>
                            <button type="button" class="btn-modern px-4 w-100 d-block mx-auto mt-auto"
                                onclick="window.location.href='{{ url('/') }}'">
                                Masuk
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="border bg-white rounded p-4 h-100">
                        <div class="mb-4">
                            <div class="rounded overflow-hidden" style="height: 200px;">
                                <img src="{{ asset('img/KampusJakarta1.png') }}" class="img-fluid h-100 w-100"
                                    style="object-fit: cover;" alt="Kampus Jakarta">
                            </div>
                        </div>
                        <div>
                            <h3 class="text-primary mb-3">Kampus Jakarta</h3>
                            <div class="text-muted mb-4 d-grid" style="grid-template-columns: auto 1fr; gap: 0 10px;">
                                <span class="text-primary fw-bold">Location:</span>
                                <div>
                                    Gedung Graha Irama (Indorama) Lt. 10 & 12 <br>
                                    Jl. H. R. Rasuna Said Kav. 1 Setiabudi <br>
                                    Kota Jakarta Selatan, DKI Jakarta, Indonesia 12950 <br>
                                </div>
                            </div>
                            <button type="button" class="btn-modern px-4 w-100 d-block mx-auto"
                                onclick="window.location.href='{{ url('/') }}'">
                                Masuk
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid product py-5">
        <div class="container py-5">
            <div class="tab-class">
                <div class="row g-4">
                    <div class="col-lg-4 text-start wow fadeInLeft" data-wow-delay="0.1s">
                        <h1>Our Products</h1>
                    </div>
                    <div class="col-lg-8 text-end wow fadeInRight" data-wow-delay="0.1s">
                        <ul class="nav nav-pills d-inline-flex text-center mb-5">
                            <li class="nav-item mb-4">
                                <a class="d-flex mx-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill"
                                    href="#tab-1">
                                    <span class="text-dark" style="width: 130px;">All Products</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-4">
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="product-item rounded wow fadeInUp" data-wow-delay="0.1s">
                                    <div class="product-item-inner border rounded">
                                        <div class="product-item-inner-item">
                                            <img src="{{ asset('img/product-3.png') }}"
                                                class="img-fluid w-100 rounded-top" alt="">
                                            <div class="product-new">New</div>
                                            <div class="product-details">
                                                <a href="#"><i class="fa fa-eye fa-1x"></i></a>
                                            </div>
                                        </div>
                                        <div class="text-center rounded-bottom p-4">
                                            <a href="#" class="d-block mb-2">SmartPhone</a>
                                            <a href="#" class="d-block h4">Apple iPad Mini <br> G2356</a>
                                            <del class="me-2 fs-5">$1,250.00</del>
                                            <span class="text-primary fs-5">$1,050.00</span>
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
@endsection
