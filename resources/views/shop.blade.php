@extends('layouts.app')

@section('title', 'Shop - Electro')

@section('content')

    <style>
        .category-scroller {
            display: flex;
            align-items: center;
            width: 100%;
            padding-bottom: 10px;
            overflow-x: auto;
            justify-content: flex-start;
            gap: 16px;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .category-scroller::-webkit-scrollbar {
            display: none;
        }

        .cat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: inherit;
            transition: opacity 0.2s;
            flex: 0 0 auto;
            min-width: 70px;
        }

        @media (min-width: 992px) {
            .category-scroller {
                overflow-x: visible;
                /* justify-content: space-between;
                        gap: 0; */
                justify-content: center;
                gap: 24px;
            }
        }

        .cat-item:hover {
            opacity: 0.8;
            color: #0d6efd;
        }

        .cat-bubble {
            width: 65px;
            height: 65px;
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            font-size: 24px;
            color: #0d6efd;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .cat-name {
            font-size: 12px;
            font-weight: 500;
            text-align: center;
            white-space: nowrap;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
        }

        @media (max-width: 992px) {
            .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 576px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
        }

        .product-card {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
        }

        @media (max-width: 768px) {
            .product-card:hover {
                transform: none;
                box-shadow: none;
            }
        }

        .product-image {
            width: 100%;
            aspect-ratio: 1 / 1;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .product-image img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }

        .product-body {
            padding: 12px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-category {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 4px;
        }

        .product-title {
            font-size: 14px;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 6px;
            color: #212529;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }


        .product-price {
            font-size: 15px;
            font-weight: 700;
            color: #0d6efd;
            margin-top: auto;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }
    </style>

    <div class="container-fluid page-header py-5 mb-4 bg-primary">
        <h1 class="text-center text-white display-6">Shop</h1>
    </div>

    <div class="container mb-5">
        <div class="mx-auto text-center pb-5" style="max-width: 700px;">
            <h4 class="text-primary mb-4 border-bottom border-primary border-2 d-inline-block p-2 title-border-radius wow fadeInUp"
                data-wow-delay="0.1s">Gudang</h4>
            <p class="wow fadeInUp" data-wow-delay="0.2s">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                Modi, asperiores ducimus sint quos tempore officia similique quia? Libero, pariatur consectetur?</p>
        </div>

        <div class="category-scroller">
            @php
                $categories = [
                    ['icon' => 'fa-laptop', 'name' => 'Laptop'],
                    ['icon' => 'fa-mobile-alt', 'name' => 'Phone'],
                    ['icon' => 'fa-camera', 'name' => 'Camera'],
                    ['icon' => 'fa-headphones', 'name' => 'Audio'],
                    ['icon' => 'fa-gamepad', 'name' => 'Gaming'],
                    ['icon' => 'fa-tv', 'name' => 'TV'],
                    ['icon' => 'fa-clock', 'name' => 'Watch'],
                    ['icon' => 'fa-print', 'name' => 'Printer'],
                ];
            @endphp

            @foreach ($categories as $cat)
                <a href="#" class="cat-item">
                    <div class="cat-bubble">
                        <i class="fa {{ $cat['icon'] }}"></i>
                    </div>
                    <span class="cat-name">{{ $cat['name'] }}</span>
                </a>
            @endforeach
        </div>

        <div class="input-group mb-4 shadow-sm">
            <input type="search" class="form-control p-3 border-0" placeholder="Cari produk...">
            <button class="btn btn-primary px-4">
                <i class="fa fa-search"></i>
            </button>
        </div>

        <div class="product-grid">
            @foreach (range(1, 25) as $i)
                <a href="{{ route('single', $i) }}" class="text-decoration-none text-dark">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="{{ asset('img/' . $i . '.jpg') }}" alt="Product {{ $i }}">
                        </div>

                        <div class="product-body">
                            <div class="product-category">Electronics</div>
                            <div class="product-title">
                                Apple iPhone {{ $i }} Pro Max Ultra
                            </div>
                            <div class="product-price">
                                Rp {{ number_format(10000000 + $i * 50000, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>


        <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
            <div class="pagination d-flex justify-content-center mt-5">
                <a href="#" class="rounded">&laquo;</a>
                @for ($i = 1; $i <= 6; $i++)
                    <a href="#" class="{{ $i == 1 ? 'active' : '' }} rounded">{{ $i }}</a>
                @endfor
                <a href="#" class="rounded">&raquo;</a>
            </div>
        </div>

    </div>

    <a href="#" class="btn btn-primary btn-lg-square back-to-top"
        style="position: fixed; bottom: 30px; right: 30px; z-index: 99;">
        <i class="fa fa-arrow-up"></i>
    </a>

@endsection
