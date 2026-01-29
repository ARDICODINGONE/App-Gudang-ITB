@extends('layouts.app')

@section('title', 'Electro - Single Product')

@section('content')
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5 mb-4 bg-primary">
        <h1 class="text-center text-white display-6">Shop</h1>
    </div>
    <!-- Single Page Header End -->

    <!-- Single Products Start -->
    <div class="container-fluid shop py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Product Details Card (Left Side) -->
                <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                    <!-- Letakkan di atas card produk, tepat sebelum <div class="card border-0 shadow-sm"> -->
                    <div class="mb-3">
                        <button onclick="history.back()" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="fa fa-arrow-left me-2"></i> Kembali
                        </button>
                    </div>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- Product Images -->
                                <div class="col-md-6">
                                    <div class="single-carousel owl-carousel">
                                        @if (isset($product->images) && count($product->images) > 0)
                                            @foreach ($product->images as $image)
                                                <div class="single-item"
                                                    data-dot="<img class='img-fluid' src='{{ asset('storage/' . $image) }}' alt='{{ $product->name }}'>">
                                                    <div class="single-inner bg-light rounded">
                                                        <img src="{{ asset('storage/' . $image) }}"
                                                            class="img-fluid rounded" alt="{{ $product->name }}"
                                                            style="height: 350px; object-fit: contain; width: 100%;">
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <!-- Fallback image if no product images -->
                                            <div class="single-item"
                                                data-dot="<img class='img-fluid' src='{{ asset('img/product-4.png') }}' alt='{{ $product->name ?? 'Product' }}'>">
                                                <div class="single-inner bg-light rounded">
                                                    <img src="{{ asset('img/product-4.png') }}" class="img-fluid rounded"
                                                        alt="{{ $product->name ?? 'Product' }}"
                                                        style="height: 350px; object-fit: contain; width: 100%;">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Product Details -->
                                <div class="col-md-6">
                                    <h4 class="fw-bold mb-3">{{ $product->name ?? 'Smart Camera' }}</h4>
                                    <p class="mb-3 text-muted">
                                        Category:
                                        @if (isset($product->category))
                                            <a href="{{ route('category.show', $product->category->slug) }}"
                                                class="text-decoration-none">
                                                {{ $product->category->name }}
                                            </a>
                                        @else
                                            Electronics
                                        @endif
                                    </p>

                                    <!-- Price Display -->
                                    <h5 class="fw-bold mb-3">
                                        @if (isset($product->discount_price) && $product->discount_price > 0)
                                            <span
                                                class="text-danger">${{ number_format($product->discount_price, 2) }}</span>
                                            <small
                                                class="text-muted text-decoration-line-through ms-2">${{ number_format($product->price, 2) }}</small>
                                            <span
                                                class="badge bg-danger ms-2">-{{ number_format((($product->price - $product->discount_price) / $product->price) * 100, 0) }}%</span>
                                        @else
                                            ${{ number_format($product->price ?? 3.35, 2) }}
                                        @endif
                                    </h5>

                                    <!-- Stock Status -->
                                    <div class="d-flex flex-column mb-3">
                                        @if (isset($product->stock) && $product->stock > 0)
                                            @if ($product->stock <= 10)
                                                <small class="text-warning">Hurry! Only
                                                    <strong>{{ $product->stock }}</strong> items left in stock</small>
                                            @else
                                                <small>Available: <span class="text-success">{{ $product->stock }} items in
                                                        stock</span></small>
                                            @endif
                                        @else
                                            <small class="text-danger">Out of Stock</small>
                                        @endif
                                    </div>

                                    <!-- Product Description -->
                                    <p class="mb-4">
                                        {{ $product->short_description ?? 'The generated Lorem Ipsum is therefore always free from repetition injected humour, or non-characteristic words etc.' }}
                                    </p>

                                    <!-- Additional Details -->
                                    @if (isset($product->features) && count($product->features) > 0)
                                        <div class="mb-4">
                                            <h6 class="fw-bold mb-2">Key Features:</h6>
                                            <ul class="list-unstyled small">
                                                @foreach ($product->features as $feature)
                                                    <li><i class="fa fa-check text-success me-2"></i>{{ $feature }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <!-- Quantity Selector -->
                                    <div class="input-group quantity mb-4" style="width: 120px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-minus rounded-0 bg-light border">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text"
                                            class="form-control form-control-sm text-center border border-start-0 border-end-0"
                                            value="1" id="quantity" name="quantity" min="1"
                                            max="{{ $product->stock ?? 20 }}">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-plus rounded-0 bg-light border">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    @auth
                                        <div class="d-flex gap-2 mb-4">
                                            @if (isset($product->stock) && $product->stock > 0)
                                                <button type="button"
                                                    class="btn btn-outline-primary btn-sm rounded-0 px-4 py-2 add-to-cart"
                                                    data-product-id="{{ $product->id ?? 1 }}"
                                                    data-product-name="{{ $product->name ?? 'Smart Camera' }}"
                                                    data-product-price="{{ $product->discount_price ?? ($product->price ?? 3.35) }}">
                                                    <i class="fa fa-shopping-cart me-2"></i> Add to cart
                                                </button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary btn-sm rounded-0 px-3 py-2">
                                                    <i class="fa fa-heart"></i>
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="btn btn-outline-secondary btn-sm rounded-0 px-4 py-2" disabled>
                                                    <i class="fa fa-shopping-cart me-2"></i> Out of Stock
                                                </button>
                                            @endif
                                        </div>
                                    @endauth

                                    <!-- Product Meta -->
                                    <div class="border-top pt-3">
                                        <div class="row small">
                                            @if (isset($product->sku))
                                                <div class="col-6">
                                                    <span class="text-muted">SKU:</span>
                                                    <strong>{{ $product->sku }}</strong>
                                                </div>
                                            @endif
                                            @if (isset($product->brand))
                                                <div class="col-6">
                                                    <span class="text-muted">Brand:</span>
                                                    <strong>{{ $product->brand }}</strong>
                                                </div>
                                            @endif
                                            @if (isset($product->weight))
                                                <div class="col-6 mt-2">
                                                    <span class="text-muted">Weight:</span> <strong>{{ $product->weight }}
                                                        kg</strong>
                                                </div>
                                            @endif
                                            @if (isset($product->dimensions))
                                                <div class="col-6 mt-2">
                                                    <span class="text-muted">Dimensions:</span>
                                                    <strong>{{ $product->dimensions }}</strong>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Description Section -->
                                <div class="col-12 mt-4">
                                    <nav>
                                        <div class="nav nav-tabs border-bottom mb-3">
                                            <button class="nav-link active border-0 text-dark bg-transparent" type="button"
                                                role="tab" id="nav-description-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-description" aria-controls="nav-description"
                                                aria-selected="true">Description</button>

                                            @if (isset($product->specifications) && count($product->specifications) > 0)
                                                <button class="nav-link border-0 text-dark bg-transparent" type="button"
                                                    role="tab" id="nav-specs-tab" data-bs-toggle="tab"
                                                    data-bs-target="#nav-specs" aria-controls="nav-specs"
                                                    aria-selected="false">Specifications</button>
                                            @endif

                                            @if (isset($product->reviews) && count($product->reviews) > 0)
                                                <button class="nav-link border-0 text-dark bg-transparent" type="button"
                                                    role="tab" id="nav-reviews-tab" data-bs-toggle="tab"
                                                    data-bs-target="#nav-reviews" aria-controls="nav-reviews"
                                                    aria-selected="false">
                                                    Reviews <span
                                                        class="badge bg-secondary ms-1">{{ count($product->reviews) }}</span>
                                                </button>
                                            @endif
                                        </div>
                                    </nav>

                                    <div class="tab-content">
                                        <!-- Description Tab -->
                                        <div class="tab-pane fade show active" id="nav-description" role="tabpanel"
                                            aria-labelledby="nav-description-tab">
                                            {!! $product->description ??
                                                '<p>Our new <b class="fw-bold">HPB12 / A12 battery</b> is rated at 2000mAh and designed to power up Black and Decker / FireStorm line of 12V tools allowing users to run multiple devices off the same battery pack.</p>' !!}
                                        </div>

                                        <!-- Specifications Tab -->
                                        @if (isset($product->specifications) && count($product->specifications) > 0)
                                            <div class="tab-pane fade" id="nav-specs" role="tabpanel"
                                                aria-labelledby="nav-specs-tab">
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <tbody>
                                                            @foreach ($product->specifications as $key => $value)
                                                                <tr>
                                                                    <th class="text-muted" style="width: 200px;">
                                                                        {{ $key }}</th>
                                                                    <td>{{ $value }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Reviews Tab -->
                                        @if (isset($product->reviews) && count($product->reviews) > 0)
                                            <div class="tab-pane fade" id="nav-reviews" role="tabpanel"
                                                aria-labelledby="nav-reviews-tab">
                                                @foreach ($product->reviews as $review)
                                                    <div class="border-bottom pb-3 mb-3">
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <strong>{{ $review->user_name }}</strong>
                                                            <small
                                                                class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                                        </div>
                                                        <div class="mb-2">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i
                                                                    class="fa fa-star{{ $i <= $review->rating ? ' text-warning' : ' text-muted' }}"></i>
                                                            @endfor
                                                        </div>
                                                        <p class="mb-0">{{ $review->comment }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recommended Products (Right Side) -->
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <!-- Recommended Products Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h5 class="fw-bold mb-0">Recommended Products</h5>
                        </div>
                        <div class="card-body p-3">
                            @if (isset($recommendedProducts) && count($recommendedProducts) > 0)
                                @foreach ($recommendedProducts as $recommended)
                                    <div class="d-flex mb-3 pb-3 border-bottom">
                                        <a href="{{ route('products.show', $recommended->slug) }}" class="flex-shrink-0">
                                            <img src="{{ $recommended->image ? asset('storage/' . $recommended->image) : asset('img/product-1.png') }}"
                                                alt="{{ $recommended->name }}" class="rounded"
                                                style="width: 80px; height: 80px; object-fit: cover;">
                                        </a>
                                        <div class="flex-grow-1 ms-3">
                                            <a href="{{ route('products.show', $recommended->slug) }}"
                                                class="text-decoration-none">
                                                <h6 class="fw-bold mb-1 text-dark">
                                                    {{ Str::limit($recommended->name, 40) }}</h6>
                                            </a>
                                            <div class="d-flex align-items-center mb-1">
                                                @if ($recommended->discount_price > 0)
                                                    <span
                                                        class="fw-bold text-danger">${{ number_format($recommended->discount_price, 2) }}</span>
                                                    <small
                                                        class="text-muted text-decoration-line-through ms-2">${{ number_format($recommended->price, 2) }}</small>
                                                @else
                                                    <span
                                                        class="fw-bold">${{ number_format($recommended->price, 2) }}</span>
                                                @endif
                                            </div>
                                            <div class="small">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i
                                                        class="fa fa-star{{ $i <= ($recommended->rating ?? 4) ? ' text-warning' : ' text-muted' }}"></i>
                                                @endfor
                                                <span
                                                    class="text-muted ms-1">({{ $recommended->review_count ?? 12 }})</span>
                                            </div>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary mt-2 add-to-cart-mini"
                                                data-product-id="{{ $recommended->id }}"
                                                data-product-name="{{ $recommended->name }}"
                                                data-product-price="{{ $recommended->discount_price ?? $recommended->price }}">
                                                <i class="fa fa-shopping-cart"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                @foreach ([['id' => 2, 'name' => 'Wireless Headphones', 'price' => 89.99, 'discount_price' => 0, 'rating' => 4.5, 'review_count' => 24], ['id' => 3, 'name' => 'Smart Watch Series 5', 'price' => 299.99, 'discount_price' => 0, 'rating' => 4.8, 'review_count' => 42], ['id' => 4, 'name' => 'Bluetooth Speaker', 'price' => 49.99, 'discount_price' => 0, 'rating' => 4.2, 'review_count' => 18], ['id' => 5, 'name' => 'Gaming Keyboard', 'price' => 79.99, 'discount_price' => 0, 'rating' => 4.7, 'review_count' => 31]] as $recommended)
                                    <div class="d-flex mb-3 pb-3 border-bottom">
                                        <a href="#" class="flex-shrink-0">
                                            <img src="{{ asset('img/product-' . $recommended['id'] . '.png') }}"
                                                alt="{{ $recommended['name'] }}" class="rounded"
                                                style="width: 80px; height: 80px; object-fit: cover;">
                                        </a>
                                        <div class="flex-grow-1 ms-3">
                                            <a href="#" class="text-decoration-none">
                                                <h6 class="fw-bold mb-1 text-dark">{{ $recommended['name'] }}</h6>
                                            </a>
                                            <div class="d-flex align-items-center mb-1">
                                                @if ($recommended['discount_price'] > 0)
                                                    <span
                                                        class="fw-bold text-danger">${{ number_format($recommended['discount_price'], 2) }}</span>
                                                    <small
                                                        class="text-muted text-decoration-line-through ms-2">${{ number_format($recommended['price'], 2) }}</small>
                                                @else
                                                    <span
                                                        class="fw-bold">${{ number_format($recommended['price'], 2) }}</span>
                                                @endif
                                            </div>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary mt-2 add-to-cart-mini"
                                                data-product-id="{{ $recommended['id'] }}"
                                                data-product-name="{{ $recommended['name'] }}"
                                                data-product-price="{{ $recommended['price'] }}">
                                                <i class="fa fa-shopping-cart"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Single Products End -->
@endsection

@push('styles')
    <style>
        /* Adjust width and layout */
        .container-fluid.shop {
            max-width: 1400px;
            margin: 0 auto;
        }

        .container {
            max-width: 1200px;
        }

        /* Card styling */
        .card {
            border-radius: 8px;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        /* Subtle button styling */
        .btn-outline-primary {
            border-color: #6c757d;
            color: #6c757d;
            background-color: transparent;
            transition: all 0.2s ease;
        }

        .btn-outline-primary:hover {
            border-color: #0d6efd;
            color: #0d6efd;
            background-color: #f8f9fa;
        }

        /* Clean tab styling */
        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 400;
            padding: 0.5rem 0;
            margin-right: 2rem;
        }

        .nav-tabs .nav-link.active {
            color: #212529;
            font-weight: 500;
            border-bottom: 2px solid #212529;
            background: transparent;
        }

        /* Product image container */
        .single-inner {
            background-color: #f8f9fa !important;
            border: 1px solid #dee2e6;
        }

        /* Quantity input styling */
        .input-group.quantity {
            border: none;
        }

        .input-group.quantity .btn {
            border: 1px solid #dee2e6;
            color: #6c757d;
        }

        .input-group.quantity input {
            border: 1px solid #dee2e6;
            border-left: none;
            border-right: none;
            background-color: #fff;
        }

        /* Recommended products */
        .add-to-cart-mini {
            padding: 2px 8px;
            font-size: 12px;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {

            .col-lg-8,
            .col-lg-4 {
                width: 100%;
            }

            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
        }

        @media (max-width: 768px) {
            .col-md-6 {
                width: 100%;
            }

            .card-body {
                padding: 1rem !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Wow.js
            if (typeof WOW === 'function') {
                new WOW({
                    boxClass: 'wow',
                    animateClass: 'animated',
                    offset: 50,
                    mobile: true,
                    live: true
                }).init();
            }

            // Initialize Owl Carousel
            if (typeof $.fn.owlCarousel !== 'undefined') {
                $('.single-carousel').owlCarousel({
                    loop: true,
                    margin: 0,
                    nav: true,
                    dots: true,
                    navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 1
                        },
                        1000: {
                            items: 1
                        }
                    }
                });
            }

            // Quantity selector functionality
            const quantityInput = document.getElementById('quantity');
            const minusBtn = document.querySelector('.btn-minus');
            const plusBtn = document.querySelector('.btn-plus');

            if (minusBtn && plusBtn && quantityInput) {
                minusBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    let currentValue = parseInt(quantityInput.value);
                    if (currentValue > 1) {
                        quantityInput.value = currentValue - 1;
                    }
                });

                plusBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    let currentValue = parseInt(quantityInput.value);
                    const maxStock = parseInt(quantityInput.max);
                    if (currentValue < maxStock) {
                        quantityInput.value = currentValue + 1;
                    }
                });

                // Validate input
                quantityInput.addEventListener('change', function() {
                    let value = parseInt(this.value);
                    const maxStock = parseInt(this.max);

                    if (isNaN(value) || value < 1) {
                        this.value = 1;
                    } else if (value > maxStock) {
                        this.value = maxStock;
                    }
                });
            }

            // Add to cart functionality
            function addToCart(productId, productName, price, quantity = 1) {
                // You can implement your cart logic here
                // Example: AJAX request to add item to cart
                console.log('Adding to cart:', {
                    productId: productId,
                    productName: productName,
                    price: price,
                    quantity: quantity
                });

                // Show notification
                showNotification(`${productName} added to cart!`);
            }

            // Notification function
            function showNotification(message) {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = 'position-fixed top-0 end-0 p-3';
                notification.style.zIndex = '1050';
                notification.innerHTML = `
                <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fa fa-check-circle me-2"></i> ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;

                document.body.appendChild(notification);

                // Initialize and show toast
                const toastElement = notification.querySelector('.toast');
                const toast = new bootstrap.Toast(toastElement, {
                    delay: 3000
                });
                toast.show();

                // Remove element after hide
                toastElement.addEventListener('hidden.bs.toast', function() {
                    notification.remove();
                });
            }

            // Main add to cart button
            const addToCartBtn = document.querySelector('.add-to-cart');
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    const productName = this.getAttribute('data-product-name');
                    const price = this.getAttribute('data-product-price');
                    const quantity = quantityInput ? quantityInput.value : 1;

                    addToCart(productId, productName, price, quantity);
                });
            }

            // Mini add to cart buttons
            const miniCartBtns = document.querySelectorAll('.add-to-cart-mini');
            miniCartBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    const productName = this.getAttribute('data-product-name');
                    const price = this.getAttribute('data-product-price');

                    addToCart(productId, productName, price, 1);
                });
            });

            // Tab switching
            const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active');
                    });
                    // Add active class to clicked button
                    this.classList.add('active');
                });
            });
        });
    </script>
@endpush
