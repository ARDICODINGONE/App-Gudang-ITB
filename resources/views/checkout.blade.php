@extends('layouts.app')

@section('title', 'Electro - Checkout')

@section('content')
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Checkout Page</h1>
        <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="breadcrumb-item active text-white">Checkout</li>
        </ol>
    </div>
    <!-- Single Page Header End -->

    <!-- Services Start -->
    <div class="container-fluid px-0" style="overflow-x: hidden;">
        <div class="row g-0 mx-0">
            @foreach([
                ['icon' => 'fa-sync-alt', 'title' => 'Free Return', 'desc' => '30 days money back guarantee!', 'delay' => '0.1s'],
                ['icon' => 'fab fa-telegram-plane', 'title' => 'Free Shipping', 'desc' => 'Free shipping on all order', 'delay' => '0.2s'],
                ['icon' => 'fas fa-life-ring', 'title' => 'Support 24/7', 'desc' => 'We support online 24 hrs a day', 'delay' => '0.3s'],
                ['icon' => 'fas fa-credit-card', 'title' => 'Receive Gift Card', 'desc' => 'Receive gift all over order $50', 'delay' => '0.4s'],
                ['icon' => 'fas fa-lock', 'title' => 'Secure Payment', 'desc' => 'We Value Your Security', 'delay' => '0.5s'],
                ['icon' => 'fas fa-blog', 'title' => 'Online Service', 'desc' => 'Free return products in 30 days', 'delay' => '0.6s']
            ] as $service)
            <div class="col-6 col-md-4 col-lg-2 border-start border-end wow fadeInUp" data-wow-delay="{{ $service['delay'] }}">
                <div class="p-4">
                    <div class="d-flex align-items-center">
                        <i class="{{ $service['icon'] }} fa-2x text-primary"></i>
                        <div class="ms-4">
                            <h6 class="text-uppercase mb-2">{{ $service['title'] }}</h6>
                            <p class="mb-0">{{ $service['desc'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <!-- Services End -->

    <!-- Checkout Page Start -->
    <div class="container-fluid bg-light overflow-hidden py-5">
        <div class="container py-5">
            <h1 class="mb-4 wow fadeInUp" data-wow-delay="0.1s">Billing details</h1>
            <form action="#" method="POST">
                @csrf
                <div class="row g-5">
                    <!-- Billing Form -->
                    <div class="col-md-12 col-lg-6 col-xl-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="form-item w-100">
                                    <label class="form-label my-3">First Name<sup>*</sup></label>
                                    <input type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <div class="form-item w-100">
                                    <label class="form-label my-3">Last Name<sup>*</sup></label>
                                    <input type="text" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-item">
                            <label class="form-label my-3">Company Name<sup>*</sup></label>
                            <input type="text" class="form-control" required>
                        </div>
                        
                        <div class="form-item">
                            <label class="form-label my-3">Address <sup>*</sup></label>
                            <input type="text" class="form-control" placeholder="House Number Street Name" required>
                        </div>
                        
                        <div class="form-item">
                            <label class="form-label my-3">Town/City<sup>*</sup></label>
                            <input type="text" class="form-control" required>
                        </div>
                        
                        <div class="form-item">
                            <label class="form-label my-3">Country<sup>*</sup></label>
                            <input type="text" class="form-control" required>
                        </div>
                        
                        <div class="form-item">
                            <label class="form-label my-3">Postcode/Zip<sup>*</sup></label>
                            <input type="text" class="form-control" required>
                        </div>
                        
                        <div class="form-item">
                            <label class="form-label my-3">Mobile<sup>*</sup></label>
                            <input type="tel" class="form-control" required>
                        </div>
                        
                        <div class="form-item">
                            <label class="form-label my-3">Email Address<sup>*</sup></label>
                            <input type="email" class="form-control" required>
                        </div>
                        
                        <div class="form-check my-3">
                            <input type="checkbox" class="form-check-input" id="Account-1" name="create_account">
                            <label class="form-check-label" for="Account-1">Create an account?</label>
                        </div>
                        
                        <hr>
                        
                        <div class="form-check my-3">
                            <input class="form-check-input" type="checkbox" id="Address-1" name="different_address">
                            <label class="form-check-label" for="Address-1">Ship to a different address?</label>
                        </div>
                        
                        <div class="form-item">
                            <textarea name="order_notes" class="form-control" spellcheck="false" cols="30" rows="11"
                                placeholder="Order Notes (Optional)"></textarea>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-md-12 col-lg-6 col-xl-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th scope="col" class="text-start">Name</th>
                                        <th scope="col">Model</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($i = 1; $i <= 5; $i++)
                                    <tr class="text-center">
                                        <th scope="row" class="text-start py-4">
                                            Apple iPad Mini
                                        </th>
                                        <td class="py-4">G2356</td>
                                        <td class="py-4">$269.00</td>
                                        <td class="py-4 text-center">2</td>
                                        <td class="py-4">$538.00</td>
                                    </tr>
                                    @endfor
                                    
                                    <!-- Subtotal -->
                                    <tr>
                                        <th scope="row"></th>
                                        <td class="py-4"></td>
                                        <td class="py-4"></td>
                                        <td class="py-4">
                                            <p class="mb-0 text-dark py-2">Subtotal</p>
                                        </td>
                                        <td class="py-4">
                                            <div class="py-2 text-center border-bottom border-top">
                                                <p class="mb-0 text-dark">$1,345.00</p>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- Shipping Options -->
                                    <tr>
                                        <th scope="row"></th>
                                        <td class="py-4">
                                            <p class="mb-0 text-dark py-4">Shipping</p>
                                        </td>
                                        <td colspan="3" class="py-4">
                                            @foreach([
                                                ['id' => 'Shipping-1', 'label' => 'Free Shipping', 'value' => 'free'],
                                                ['id' => 'Shipping-2', 'label' => 'Flat rate: $15.00', 'value' => '15'],
                                                ['id' => 'Shipping-3', 'label' => 'Local Pickup: $8.00', 'value' => '8']
                                            ] as $shipping)
                                            <div class="form-check text-start">
                                                <input type="radio" class="form-check-input bg-primary border-0"
                                                    id="{{ $shipping['id'] }}" name="shipping" value="{{ $shipping['value'] }}" {{ $loop->first ? 'checked' : '' }}>
                                                <label class="form-check-label" for="{{ $shipping['id'] }}">{{ $shipping['label'] }}</label>
                                            </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                    
                                    <!-- Total -->
                                    <tr>
                                        <th scope="row"></th>
                                        <td class="py-4">
                                            <p class="mb-0 text-dark text-uppercase py-2">TOTAL</p>
                                        </td>
                                        <td class="py-4"></td>
                                        <td class="py-4"></td>
                                        <td class="py-4">
                                            <div class="py-2 text-center border-bottom border-top">
                                                <p class="mb-0 text-dark">$1,360.00</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Payment Methods -->
                        <div class="payment-methods">
                            @foreach([
                                ['id' => 'Transfer-1', 'label' => 'Direct Bank Transfer', 'name' => 'payment_method'],
                                ['id' => 'Payments-1', 'label' => 'Check Payments', 'name' => 'payment_method'],
                                ['id' => 'Delivery-1', 'label' => 'Cash On Delivery', 'name' => 'payment_method'],
                                ['id' => 'Paypal-1', 'label' => 'PayPal', 'name' => 'payment_method']
                            ] as $index => $payment)
                            <div class="row g-0 text-center align-items-center justify-content-center border-bottom py-2">
                                <div class="col-12">
                                    <div class="form-check text-start my-2">
                                        <input type="radio" class="form-check-input bg-primary border-0" 
                                            id="{{ $payment['id'] }}" name="{{ $payment['name'] }}" value="{{ strtolower(str_replace(' ', '_', $payment['label'])) }}" {{ $loop->first ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $payment['id'] }}">{{ $payment['label'] }}</label>
                                    </div>
                                    @if($index === 0)
                                    <p class="text-start text-dark mb-0">
                                        Make your payment directly into our bank account. Please use your Order ID as the payment reference. 
                                        Your order will not be shipped until the funds have cleared in our account.
                                    </p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Place Order Button -->
                        <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                            <button type="submit" class="btn btn-primary border-secondary py-3 px-4 text-uppercase w-100">
                                Place Order
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Checkout Page End -->
@endsection

@push('styles')
<style>
    /* Fix untuk scroll horizontal */
    .container-fluid.px-0,
    .container-fluid.bg-light {
        overflow-x: hidden;
    }
    
    /* Style untuk form */
    .form-item input,
    .form-item textarea {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }
    
    .form-item input:focus,
    .form-item textarea:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    /* Responsive table */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .table th,
        .table td {
            padding: 0.5rem;
        }
        
        .page-header h1 {
            font-size: 1.8rem;
        }
        
        .page-header .breadcrumb {
            font-size: 0.875rem;
        }
    }
    
    /* Style untuk radio buttons */
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    /* Border radius untuk cards/containers */
    .border-start, .border-end {
        border-color: #dee2e6 !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Wow.js
        if (typeof WOW === 'function') {
            new WOW().init();
        }
        
        // Shipping method selection
        const shippingRadios = document.querySelectorAll('input[name="shipping"]');
        const totalElement = document.querySelector('td.py-4 .text-dark:last-child');
        
        shippingRadios.forEach(radio => {
            radio.addEventListener('change', updateTotal);
        });
        
        function updateTotal() {
            const subtotal = 1345.00;
            let shipping = 0;
            
            shippingRadios.forEach(radio => {
                if (radio.checked) {
                    shipping = radio.value === 'free' ? 0 : parseFloat(radio.value);
                }
            });
            
            const total = subtotal + shipping;
            if (totalElement) {
                totalElement.textContent = '$' + total.toFixed(2);
            }
        }
        
        // Form validation
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let valid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        valid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                if (!valid) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                }
            });
        }
        
        // Toggle different address
        const diffAddressCheckbox = document.getElementById('Address-1');
        if (diffAddressCheckbox) {
            diffAddressCheckbox.addEventListener('change', function() {
                const shippingFields = document.querySelectorAll('.shipping-fields');
                shippingFields.forEach(field => {
                    if (this.checked) {
                        field.style.display = 'block';
                    } else {
                        field.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endpush