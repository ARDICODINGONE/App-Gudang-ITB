@extends('layouts.app')

@section('title', 'Cart Page - Electro')

@section('content')
    <!-- Spinner Start -->
    <div id="spinner" class="bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Cart Page</h1>
        <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="breadcrumb-item active text-white">Cart Page</li>
        </ol>
    </div>
    <!-- Single Page Header End -->

    <!-- Cart Page Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Nama Barang</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Gudang</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Total</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="cart-item">
                            <!-- NAME + IMAGE -->
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="https://via.placeholder.com/70" class="rounded">
                                    <strong>Apple iPad Mini</strong>
                                </div>
                            </td>

                            <!-- MODEL -->
                            <td class="text-muted">G2356</td>

                            <!-- PRICE -->
                            <td class="price">$2.99</td>

                            <!-- QUANTITY -->
                            <td>
                                <div class="qty-box">
                                    <button type="button" class="qty-btn minus">-</button>
                                    <input type="text" class="qty-input" value="1">
                                    <button type="button" class="qty-btn plus">+</button>
                                </div>
                            </td>

                            <!-- TOTAL -->
                            <td class="line-total">$2.99</td>

                            <!-- HANDLE -->
                            <td>
                                <button class="btn btn-outline-danger btn-sm btn-remove rounded-circle">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
            <div class="row g-4 justify-content-end">
                <div class="col-8"></div>
                <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                    <div class="cart-summary p-4">
    <h4 class="mb-4">Order Summary</h4>

    <div class="d-flex justify-content-between mb-3">
        <span>Subtotal</span>
        <strong id="cart-subtotal">$96.00</strong>
    </div>

    <div class="d-flex justify-content-between mb-3">
        <span>Shipping</span>
        <strong id="cart-shipping">$3.00</strong>
    </div>

    <hr>

    <div class="d-flex justify-content-between fs-5 mb-4">
        <span>Total</span>
        <strong id="cart-total">$99.00</strong>
    </div>

    <button class="btn btn-primary w-100 rounded-pill py-3">
        <i class="fa fa-credit-card me-2"></i> Checkout
    </button>
</div>

                </div>
            </div>
        </div>
    </div>
    <!-- Cart Page End -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    window.addEventListener('load', function () {
        document.getElementById('spinner')?.classList.add('d-none');
    });

    document.querySelectorAll('.cart-item').forEach(row => {
        const price = parseFloat(row.querySelector('.price').textContent.replace('$',''));
        const input = row.querySelector('.qty-input');

        row.querySelector('.plus').addEventListener('click', () => {
            let qty = parseInt(input.value);
            qty++;
            update(row, qty);
        });

        row.querySelector('.minus').addEventListener('click', () => {
            let qty = parseInt(input.value);
            if (qty > 1) qty--;
            update(row, qty);
        });

        function update(row, qty) {
            input.value = qty;
            row.querySelector('.line-total').textContent = '$' + (price * qty).toFixed(2);
            updateCartTotals();
        }
    });

    function updateCartTotals() {
        let subtotal = 0;
        document.querySelectorAll('.line-total').forEach(el => {
            subtotal += parseFloat(el.textContent.replace('$',''));
        });

        const shipping = 3;
        document.getElementById('cart-subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('cart-total').textContent = '$' + (subtotal + shipping).toFixed(2);
    }

    updateCartTotals();
});
</script>
@endpush



@push('styles')
<style>
.cart-item td {
    vertical-align: middle;
}

.qty-box {
    display: inline-flex;
    align-items: center;
    border: 1px solid #0d6efd;
    border-radius: 6px;
    overflow: hidden;
}

.qty-btn {
    width: 22px;
    height: 22px;
    border: none;
    background: #0d6efd;
    color: #fff;
    font-size: 12px;
    line-height: 1;
    cursor: pointer;
}

.qty-input {
    width: 30px;
    height: 22px;
    border: none;
    text-align: center;
    font-size: 12px;
    padding: 0;
}

.price, .line-total {
    font-weight: 600;
    color: #0d6efd;
}

/* MOBILE */
@media (max-width: 768px) {
    table thead {
        display: none;
    }

    table tbody tr {
        display: block;
        margin-bottom: 20px;
        border-bottom: 1px solid #ddd;
    }

    table tbody td {
        display: flex;
        justify-content: space-between;
        padding: 10px;
    }
}
</style>
@endpush
