@extends('layouts.app')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Electro - Electronics Website Template')</title>
    <meta name="description" content="@yield('description', 'Electronics Website Template')">
    <meta name="keywords" content="@yield('keywords', 'electronics, ecommerce, shop')">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body style="overflow-x: hidden;">

    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    @include('layouts.navbar')

    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Shop Page</h1>
        <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="breadcrumb-item active text-white">Shop</li>
        </ol>
    </div>
    <!-- Single Page Header End -->

    <!-- Services Start -->
    <div class="container-fluid px-0" style="overflow-x: hidden;">
        <div class="row g-0">
            <div class="col-6 col-md-4 col-lg-2 border-start border-end wow fadeInUp" data-wow-delay="0.1s">
                <div class="p-4">
                    <div class="d-inline-flex align-items-center">
                        <i class="fa fa-sync-alt fa-2x text-primary"></i>
                        <div class="ms-4">
                            <h6 class="text-uppercase mb-2">Free Return</h6>
                            <p class="mb-0">30 days money back guarantee!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.2s">
                <div class="p-4">
                    <div class="d-flex align-items-center">
                        <i class="fab fa-telegram-plane fa-2x text-primary"></i>
                        <div class="ms-4">
                            <h6 class="text-uppercase mb-2">Free Shipping</h6>
                            <p class="mb-0">Free shipping on all order</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.3s">
                <div class="p-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-life-ring fa-2x text-primary"></i>
                        <div class="ms-4">
                            <h6 class="text-uppercase mb-2">Support 24/7</h6>
                            <p class="mb-0">We support online 24 hrs a day</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.4s">
                <div class="p-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-credit-card fa-2x text-primary"></i>
                        <div class="ms-4">
                            <h6 class="text-uppercase mb-2">Receive Gift Card</h6>
                            <p class="mb-0">Receive gift all over order $50</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.5s">
                <div class="p-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-lock fa-2x text-primary"></i>
                        <div class="ms-4">
                            <h6 class="text-uppercase mb-2">Secure Payment</h6>
                            <p class="mb-0">We Value Your Security</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.6s">
                <div class="p-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-blog fa-2x text-primary"></i>
                        <div class="ms-4">
                            <h6 class="text-uppercase mb-2">Online Service</h6>
                            <p class="mb-0">Free return products in 30 days</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Services End -->

    <!-- Products Offer Start -->
    <div class="container-fluid bg-light py-5" style="overflow-x: hidden;">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                    <a href="#" class="d-flex align-items-center justify-content-between border bg-white rounded p-4">
                        <div>
                            <p class="text-muted mb-3">Find The Best Camera for You!</p>
                            <h3 class="text-primary">Smart Camera</h3>
                            <h1 class="display-3 text-secondary mb-0">40% <span
                                    class="text-primary fw-normal">Off</span></h1>
                        </div>
                        <img src="{{ asset('img/product-1.png') }}" class="img-fluid" alt="Smart Camera">
                    </a>
                </div>
                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                    <a href="#" class="d-flex align-items-center justify-content-between border bg-white rounded p-4">
                        <div>
                            <p class="text-muted mb-3">Find The Best Watches for You!</p>
                            <h3 class="text-primary">Smart Watch</h3>
                            <h1 class="display-3 text-secondary mb-0">20% <span
                                    class="text-primary fw-normal">Off</span></h1>
                        </div>
                        <img src="{{ asset('img/product-2.png') }}" class="img-fluid" alt="Smart Watch">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Products Offer End -->

    <!-- Shop Page Start -->
    <div class="container-fluid shop py-5" style="overflow-x: hidden;">
        <div class="container py-5">
            <div class="row g-4">
                <!-- Sidebar -->
                <div class="col-lg-3 wow fadeInUp" data-wow-delay="0.1s">
                    <!-- Product Categories -->
                    <div class="product-categories mb-4">
                        <h4>Products Categories</h4>
                        <ul class="list-unstyled">
                            @foreach(['Accessories' => 3, 'Electronics & Computer' => 5, 'Laptops & Desktops' => 2, 'Mobiles & Tablets' => 8, 'SmartPhone & Smart TV' => 5] as $category => $count)
                            <li>
                                <div class="categories-item">
                                    <a href="#" class="text-dark"><i class="fas fa-apple-alt text-secondary me-2"></i>
                                        {{ $category }}</a>
                                    <span>({{ $count }})</span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Price Filter -->
                    <div class="price mb-4">
                        <h4 class="mb-2">Price</h4>
                        <input type="range" class="form-range w-100" id="rangeInput" name="rangeInput" min="0" max="500"
                            value="0" oninput="amount.value=rangeInput.value">
                        <output id="amount" name="amount" min-value="0" max-value="500" for="rangeInput">0</output>
                    </div>

                    <!-- Color Filter -->
                    <div class="product-color mb-3">
                        <h4>Select By Color</h4>
                        <ul class="list-unstyled">
                            @foreach(['Gold' => 1, 'Green' => 1, 'White' => 1] as $color => $count)
                            <li>
                                <div class="product-color-item">
                                    <a href="#" class="text-dark"><i class="fas fa-apple-alt text-secondary me-2"></i>
                                        {{ $color }}</a>
                                    <span>({{ $count }})</span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Additional Products -->
                    <div class="additional-product mb-4">
                        <h4>Additional Products</h4>
                        @foreach(['Accessories', 'Electronics & Computer', 'Laptops & Desktops', 'Mobiles & Tablets', 'SmartPhone & Smart TV'] as $index => $category)
                        <div class="additional-product-item">
                            <input type="radio" class="me-2" id="Categories-{{ $index+1 }}" name="Categories-1" value="{{ $category }}">
                            <label for="Categories-{{ $index+1 }}" class="text-dark">{{ $category }}</label>
                        </div>
                        @endforeach
                    </div>

                    <!-- Featured Products -->
                    <div class="featured-product mb-4">
                        <h4 class="mb-3">Featured products</h4>
                        @foreach([
                            ['img' => 'product-3.png', 'name' => 'SmartPhone', 'price' => '2.99', 'old_price' => '4.11'],
                            ['img' => 'product-4.png', 'name' => 'Smart Camera', 'price' => '2.99', 'old_price' => '4.11'],
                            ['img' => 'product-5.png', 'name' => 'Camera Lens', 'price' => '2.99', 'old_price' => '4.11']
                        ] as $product)
                        <div class="featured-product-item mb-3">
                            <div class="rounded me-4" style="width: 100px; height: 100px;">
                                <img src="{{ asset('img/' . $product['img']) }}" class="img-fluid rounded" alt="{{ $product['name'] }}">
                            </div>
                            <div>
                                <h6 class="mb-2">{{ $product['name'] }}</h6>
                                <div class="d-flex mb-2">
                                    @for($i = 0; $i < 4; $i++)
                                    <i class="fa fa-star text-secondary"></i>
                                    @endfor
                                    <i class="fa fa-star"></i>
                                </div>
                                <div class="d-flex mb-2">
                                    <h5 class="fw-bold me-2">{{ $product['price'] }}$</h5>
                                    <h5 class="text-danger text-decoration-line-through">{{ $product['old_price'] }}$</h5>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div class="d-flex justify-content-center my-4">
                            <a href="#" class="btn btn-primary px-4 py-3 rounded-pill w-100">View More</a>
                        </div>
                    </div>

                    <!-- Sale Banner -->
                    <a href="#">
                        <div class="position-relative">
                            <img src="{{ asset('img/product-banner-2.jpg') }}" class="img-fluid w-100 rounded" alt="Sale Banner">
                            <div class="text-center position-absolute d-flex flex-column align-items-center justify-content-center rounded p-4"
                                style="width: 100%; height: 100%; top: 0; right: 0; background: rgba(242, 139, 0, 0.3);">
                                <h5 class="display-6 text-primary">SALE</h5>
                                <h4 class="text-secondary">Get UP To 50% Off</h4>
                                <a href="#" class="btn btn-primary rounded-pill px-4">Shop Now</a>
                            </div>
                        </div>
                    </a>

                    <!-- Product Tags -->
                    <div class="product-tags py-4">
                        <h4 class="mb-3">PRODUCT TAGS</h4>
                        <div class="product-tags-items bg-light rounded p-3">
                            @foreach(['New', 'brand', 'black', 'white', 'tablets', 'phone', 'camera', 'drone', 'television', 'sales'] as $tag)
                            <a href="#" class="border rounded py-1 px-2 mb-2">{{ $tag }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="col-lg-9 wow fadeInUp" data-wow-delay="0.1s">
                    <!-- Banner -->
                    <div class="rounded mb-4 position-relative">
                        <img src="{{ asset('img/product-banner-3.jpg') }}" class="img-fluid rounded w-100" style="height: 250px;"
                            alt="Banner">
                        <div class="position-absolute rounded d-flex flex-column align-items-center justify-content-center text-center"
                            style="width: 100%; height: 250px; top: 0; left: 0; background: rgba(242, 139, 0, 0.3);">
                            <h4 class="display-5 text-primary">SALE</h4>
                            <h3 class="display-4 text-white mb-4">Get UP To 50% Off</h3>
                            <a href="#" class="btn btn-primary rounded-pill">Shop Now</a>
                        </div>
                    </div>

                    <!-- Filters and Search -->
                    <div class="row g-4 mb-4">
                        <div class="col-xl-7">
                            <div class="input-group w-100 mx-auto d-flex">
                                <input type="search" class="form-control p-3" placeholder="keywords"
                                    aria-describedby="search-icon-1">
                                <span id="search-icon-1" class="input-group-text p-3"><i
                                        class="fa fa-search"></i></span>
                            </div>
                        </div>
                        <div class="col-xl-3 text-end">
                            <div class="bg-light ps-3 py-3 rounded d-flex justify-content-between">
                                <label for="electronics">Sort By:</label>
                                <select id="electronics" name="electronicslist"
                                    class="border-0 form-select-sm bg-light me-3" form="electronicsform">
                                    <option value="default">Default Sorting</option>
                                    <option value="popularity">Popularity</option>
                                    <option value="newness">Newness</option>
                                    <option value="rating">Average Rating</option>
                                    <option value="low-high">Low to high</option>
                                    <option value="high-low">High to low</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xl-2">
                            <ul class="nav nav-pills d-inline-flex text-center py-2 px-2 rounded bg-light mb-0">
                                <li class="nav-item me-4">
                                    <a class="bg-light" data-bs-toggle="pill" href="#tab-grid">
                                        <i class="fas fa-th fa-3x text-primary"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="bg-light" data-bs-toggle="pill" href="#tab-list">
                                        <i class="fas fa-bars fa-3x text-primary"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Product Display -->
                    <div class="tab-content">
                        <!-- Grid View -->
                        <div id="tab-grid" class="tab-pane fade show p-0 active">
                            <div class="row g-4 product">
                                @foreach(range(3, 11) as $i)
                                <div class="col-lg-4 col-md-6">
                                    <div class="product-item rounded wow fadeInUp" data-wow-delay="0.{{ $i%3 }}s">
                                        <div class="product-item-inner border rounded">
                                            <div class="product-item-inner-item position-relative">
                                                <img src="{{ asset('img/product-' . $i . '.png') }}" class="img-fluid w-100" 
                                                    style="height: 250px; object-fit: cover;" alt="Product {{ $i }}">
                                                <div class="product-new position-absolute top-0 end-0 m-2 bg-primary text-white px-2 rounded">New</div>
                                                <div class="product-details position-absolute top-0 start-0 m-2">
                                                    <a href="#"><i class="fa fa-eye fa-1x text-white bg-dark bg-opacity-50 rounded-circle p-2"></i></a>
                                                </div>
                                            </div>
                                            <div class="text-center rounded-bottom p-4">
                                                <a href="#" class="d-block mb-2">SmartPhone</a>
                                                <a href="#" class="d-block h4">Apple iPad Mini <br> G2356</a>
                                                <del class="me-2 fs-5">$1,250.00</del>
                                                <span class="text-primary fs-5">$1,050.00</span>
                                            </div>
                                        </div>
                                        <div class="product-item-add border border-top-0 rounded-bottom text-center p-4 pt-0">
                                            <a href="#"
                                                class="btn btn-primary border-secondary rounded-pill py-2 px-4 mb-4">
                                                <i class="fas fa-shopping-cart me-2"></i> Add To Cart
                                            </a>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex">
                                                    @for($j = 0; $j < 4; $j++)
                                                    <i class="fas fa-star text-primary"></i>
                                                    @endfor
                                                    <i class="fas fa-star"></i>
                                                </div>
                                                <div class="d-flex">
                                                    <a href="#"
                                                        class="text-primary d-flex align-items-center justify-content-center me-3">
                                                        <span class="rounded-circle btn-sm-square border"><i class="fas fa-random"></i></span>
                                                    </a>
                                                    <a href="#"
                                                        class="text-primary d-flex align-items-center justify-content-center me-0">
                                                        <span class="rounded-circle btn-sm-square border"><i class="fas fa-heart"></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                <!-- Pagination -->
                                <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
                                    <div class="pagination d-flex justify-content-center mt-5">
                                        <a href="#" class="rounded">&laquo;</a>
                                        @for($i = 1; $i <= 6; $i++)
                                        <a href="#" class="{{ $i == 1 ? 'active' : '' }} rounded">{{ $i }}</a>
                                        @endfor
                                        <a href="#" class="rounded">&raquo;</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- List View -->
                        <div id="tab-list" class="products tab-pane fade show p-0">
                            <div class="row g-4 products-mini">
                                @foreach(range(3, 16) as $i)
                                <div class="col-lg-6">
                                    <div class="products-mini-item border">
                                        <div class="row g-0">
                                            <div class="col-5">
                                                <div class="products-mini-img border-end h-100">
                                                    <img src="{{ asset('img/product-' . $i . '.png') }}" class="img-fluid w-100 h-100"
                                                        style="height: 200px; object-fit: cover;" alt="Product {{ $i }}">
                                                    <div class="products-mini-icon rounded-circle bg-primary position-absolute top-50 start-50 translate-middle">
                                                        <a href="#"><i class="fa fa-eye fa-1x text-white p-2"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-7">
                                                <div class="products-mini-content p-3">
                                                    <a href="#" class="d-block mb-2">SmartPhone</a>
                                                    <a href="#" class="d-block h4">Apple iPad Mini <br> G2356</a>
                                                    <del class="me-2 fs-5">$1,250.00</del>
                                                    <span class="text-primary fs-5">$1,050.00</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="products-mini-add border p-3">
                                            <a href="#"
                                                class="btn btn-primary border-secondary rounded-pill py-2 px-4">
                                                <i class="fas fa-shopping-cart me-2"></i> Add To Cart
                                            </a>
                                            <div class="d-flex mt-2">
                                                <a href="#"
                                                    class="text-primary d-flex align-items-center justify-content-center me-3">
                                                    <span class="rounded-circle btn-sm-square border"><i class="fas fa-random"></i></span>
                                                </a>
                                                <a href="#"
                                                    class="text-primary d-flex align-items-center justify-content-center me-0">
                                                    <span class="rounded-circle btn-sm-square border"><i class="fas fa-heart"></i></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                <!-- Pagination -->
                                <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
                                    <div class="pagination d-flex justify-content-center mt-5">
                                        <a href="#" class="rounded">&laquo;</a>
                                        @for($i = 1; $i <= 6; $i++)
                                        <a href="#" class="{{ $i == 1 ? 'active' : '' }} rounded">{{ $i }}</a>
                                        @endfor
                                        <a href="#" class="rounded">&raquo;</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Page End -->

    <!-- Product Banner Start -->
    <div class="container-fluid py-5" style="overflow-x: hidden;">
        <div class="container pb-5">
            <div class="row g-4">
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                    <a href="#">
                        <div class="bg-primary rounded position-relative">
                            <img src="{{ asset('img/product-banner.jpg') }}" class="img-fluid w-100 rounded" alt="Camera Banner">
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center rounded p-4"
                                style="background: rgba(255, 255, 255, 0.5);">
                                <h3 class="display-5 text-primary">EOS Rebel <br> <span>T7i Kit</span></h3>
                                <p class="fs-4 text-muted">$899.99</p>
                                <a href="#" class="btn btn-primary rounded-pill align-self-start py-2 px-4">Shop Now</a>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.2s">
                    <a href="#">
                        <div class="text-center bg-primary rounded position-relative">
                            <img src="{{ asset('img/product-banner-2.jpg') }}" class="img-fluid w-100" alt="Sale Banner">
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center rounded p-4"
                                style="background: rgba(242, 139, 0, 0.5);">
                                <h2 class="display-2 text-secondary">SALE</h2>
                                <h4 class="display-5 text-white mb-4">Get UP To 50% Off</h4>
                                <a href="#" class="btn btn-secondary rounded-pill align-self-center py-2 px-4">Shop Now</a>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Product Banner End -->

    @include('layouts.footer')

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>