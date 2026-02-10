@extends('layouts.app')

@section('title', 'Keranjang - Electro')

@section('content')
<div class="container-fluid page-header py-5 mb-5 bg-primary">
    <h1 class="text-center text-white display-6">Keranjang Belanja</h1>
</div>

<div class="container py-3 mb-5">
    <div class="row g-4" id="main-cart-row">
        
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-4 border-bottom">
                        <h5 class="mb-0 fw-bold">Daftar Barang</h5>
                        <a href="{{ url('/shop') }}" id="btn-lanjut-belanja" class="btn btn-outline-primary btn-sm rounded-pill">
                            <i class="fa fa-arrow-left me-2"></i>Lanjut Belanja
                        </a>
                    </div>

                    <div id="cart-empty" class="text-center py-5" style="display:none;">
                        <div class="empty-cart-icon mb-4">
                            <i class="fa fa-shopping-cart fa-3x text-muted"></i>
                        </div>
                        <h4 class="text-muted">Keranjang Anda Kosong</h4>
                        <p class="text-muted mb-4">Sepertinya Anda belum memilih produk apapun.</p>
                        <a href="{{ url('/shop') }}" id="btn-mulai-belanja" class="btn btn-primary px-4 py-2 rounded-pill">Mulai Belanja</a>
                    </div>

                    <div id="table-wrapper" style="display:none;">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light text-secondary">
                                    <tr>
                                        <th class="border-0 py-3 ps-4" style="font-size: 13px; text-transform: uppercase;">Barang</th>
                                        <th class="border-0 py-3" style="font-size: 13px; text-transform: uppercase;">Harga</th>
                                        <th class="border-0 py-3 text-center" style="font-size: 13px; text-transform: uppercase;">Jumlah</th>
                                        <th class="border-0 py-3 text-end pe-4" style="font-size: 13px; text-transform: uppercase;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-items">
                                    </tbody>
                            </table>
                        </div>
                        <div class="p-3 bg-light border-top">
                             <button id="btn-clear-cart" class="btn btn-sm btn-link text-danger text-decoration-none">
                                <i class="fa fa-trash me-1"></i> Bersihkan Keranjang
                             </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4" id="cart-summary-col" style="display:none;">
            <div class="card border-0 shadow-sm rounded-3 sticky-top" style="top: 20px;">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">Ringkasan Pesanan</h5>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold mb-2">Catatan Pesanan</label>
                        <textarea class="form-control" id="order-note" rows="3" placeholder="Contoh: Warna cadangan, instruksi kirim..."></textarea>
                    </div>

                    <hr class="text-muted">

                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Total</span>
                        <span class="fw-bold fs-5 text-primary" id="cart-total">Rp 0</span>
                    </div>

                    <form id="form-ajukan" action="{{ route('pengajuan.fromCart') }}" method="POST">
                        @csrf
                        <input type="hidden" name="note" id="form-note" value="">
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm hover-top">
                            Ajukan <i class="fa fa-chevron-right ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Determine the return URL based on referrer or previous page stored in localStorage
    function getReturnUrl() {
        // Check document.referrer first (more reliable)
        if (document.referrer) {
            const referrerUrl = new URL(document.referrer);
            const baseUrl = new URL(window.location.origin);
            
            // If referrer is from same domain, use it
            if (referrerUrl.origin === baseUrl.origin) {
                return document.referrer;
            }
        }
        
        // Fallback to localStorage
        const storedUrl = localStorage.getItem('cartReturnUrl');
        if (storedUrl) {
            return storedUrl;
        }
        
        // Default to shop page
        return '/shop';
    }

    // Set return URL for buttons
    function setReturnUrlButtons() {
        const returnUrl = getReturnUrl();
        const lanjutBelanja = document.getElementById('btn-lanjut-belanja');
        const mulaiBelanja = document.getElementById('btn-mulai-belanja');
        
        if (lanjutBelanja) {
            lanjutBelanja.href = returnUrl;
        }
        if (mulaiBelanja) {
            mulaiBelanja.href = returnUrl;
        }
    }

    // Save current return URL when leaving cart
    function saveReturnUrl() {
        // Save the current page as potential return URL for future cart visits
        // This runs on pages that might lead to cart
        if (window.location.pathname !== '/cart') {
            localStorage.setItem('cartReturnUrl', window.location.pathname + window.location.search);
        }
    }

    function formatRupiah(num) {
        return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
    }

    async function loadCart() {
        const tableWrapper = document.getElementById('table-wrapper');
        const emptyState = document.getElementById('cart-empty');
        const summaryCol = document.getElementById('cart-summary-col');
        const list = document.getElementById('cart-items');

        try {
            const res = await fetch('/cart/items');
            const payload = await res.json();

            // payload may be an array (legacy) or an object { items, note }
            const cart = Array.isArray(payload) ? payload : (payload.items || []);

            if (!cart || cart.length === 0) {
                tableWrapper.style.display = 'none';
                summaryCol.style.display = 'none';
                emptyState.style.display = 'block';
                // if payload contains note, clear the textarea
                if (payload && payload.note !== undefined) {
                    document.getElementById('order-note').value = payload.note || '';
                }
                return;
            }

            emptyState.style.display = 'none';
            tableWrapper.style.display = 'block';
            summaryCol.style.display = 'block';
            
            list.innerHTML = '';
            let total = 0;

            cart.forEach((item) => {
                const subtotal = (parseFloat(item.price) || 0) * (parseInt(item.qty) || 0);
                total += subtotal;

                const tr = document.createElement('tr');
                tr.className = 'cart-row';
                tr.innerHTML = `
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center">
                            <img src="${item.image || '/img/product-1.png'}" class="cart-img me-3">
                            <div class="w-100">
                                <div class="product-name">${item.name}</div>
                                <div class="product-gudang mt-1">
                                    <i class="fa fa-warehouse me-1"></i>Gudang: <strong>${item.gudang_list}</strong>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="fw-semibold text-secondary">
                        ${formatRupiah(item.price)}
                    </td>
                    <td>
                            <div class="qty-container mx-auto" title="Max: ${item.max_qty}">
                            <button class="qty-btn btn-decr" data-id="${item.id}"><i class="fa fa-minus fa-xs"></i></button>
                            <input type="number" min="1" max="${item.max_qty}" class="qty-input" data-id="${item.id}" value="${item.qty}" data-prev="${item.qty}" data-max="${item.max_qty}" title="Max tersedia: ${item.max_qty}" />
                            <button class="qty-btn btn-incr" data-id="${item.id}"><i class="fa fa-plus fa-xs"></i></button>
                        </div>
                    </td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-outline-danger border-0 rounded-circle btn-remove" data-id="${item.id}">
                            <i class="fa fa-trash-alt"></i>
                        </button>
                    </td>
                `;
                list.appendChild(tr);
            });

            document.getElementById('cart-total').textContent = formatRupiah(total);

            // populate note if provided by server
            if (payload && payload.note !== undefined) {
                const noteEl = document.getElementById('order-note');
                if (noteEl) noteEl.value = payload.note || '';
            }

            // Re-bind events
            attachEvents();

        } catch (err) {
            console.error("Gagal memuat keranjang:", err);
        }
    }

    function attachEvents() {
        document.querySelectorAll('.btn-incr').forEach(b => b.onclick = (e) => changeQtyOptimistic(e.currentTarget.dataset.id, 1));
        document.querySelectorAll('.btn-decr').forEach(b => b.onclick = (e) => changeQtyOptimistic(e.currentTarget.dataset.id, -1));
        document.querySelectorAll('.btn-remove').forEach(b => b.onclick = (e) => removeItemOptimistic(e.currentTarget.dataset.id));

        // bind direct-input handlers (debounced)
        document.querySelectorAll('.qty-input').forEach(input => {
            const id = input.dataset.id;
            const onInput = debounce((e) => {
                const val = e.target.value;
                changeQtyFromInput(id, parseInt(val) || 1, e.target);
            }, 700);
            input.addEventListener('input', onInput);
            input.addEventListener('blur', (e) => {
                // ensure at least 1 and not exceeding max, trigger immediate update on blur
                const maxQty = parseInt(e.target.dataset.max) || Infinity;
                const v = Math.max(1, Math.min(parseInt(e.target.value) || 1, maxQty));
                e.target.value = v;
                changeQtyFromInput(id, v, e.target);
            });
        });
    }

    // debounce helper
    function debounce(fn, wait) {
        let t;
        return function(...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), wait);
        };
    }

    // save note to server
    const saveNoteServer = debounce(async function() {
        const note = document.getElementById('order-note')?.value || '';
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        try {
            await fetch('/cart/note', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ note })
            });
        } catch (err) { console.error('Gagal menyimpan catatan:', err); }
    }, 700);

    // bind note input
    const noteEl = document.getElementById('order-note');
    if (noteEl) noteEl.addEventListener('input', saveNoteServer);

    // copy note into hidden form input before submitting pengajuan
    const formAjukan = document.getElementById('form-ajukan');
    if (formAjukan) {
        formAjukan.addEventListener('submit', async function (e) {
            e.preventDefault();
            
            const note = document.getElementById('order-note')?.value || '';
            const hidden = document.getElementById('form-note');
            if (hidden) hidden.value = note;
            
            // submit form via fetch to capture response
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const formData = new FormData(formAjukan);
            
            try {
                const res = await fetch(formAjukan.action, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf },
                    body: formData
                });
                
                if (res.ok) {
                    // clear catatan from textarea after successful submission
                    document.getElementById('order-note').value = '';
                    // also clear note from cart via API
                    await fetch('/cart/note', {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: JSON.stringify({ note: '' })
                    }).catch(() => {});
                    // redirect to the pengajuan list page
                    window.location.href = '/pengajuan/list';
                } else {
                    alert('Gagal membuat pengajuan. Silakan coba lagi.');
                }
            } catch (err) {
                console.error('Gagal submit pengajuan:', err);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    }

    // parse formatted Rupiah (e.g. "Rp 1.234") -> number
    function parseRupiah(str) {
        if (!str) return 0;
        const digits = String(str).replace(/\D+/g, '');
        return Number(digits) || 0;
    }

    function updateTotalBy(amount) {
        const el = document.getElementById('cart-total');
        const cur = parseRupiah(el.textContent);
        el.textContent = formatRupiah(Math.max(0, cur + amount));
    }

    async function changeQtyOptimistic(itemId, delta) {
        const btnIncr = document.querySelector(`.btn-incr[data-id="${itemId}"]`);
        if (!btnIncr) return;
        const row = btnIncr.closest('tr');
        const qtyEl = row.querySelector('.qty-input');
        const curQty = parseInt(qtyEl.value ?? qtyEl.textContent) || 0;
        const maxQty = parseInt(qtyEl.dataset.max) || Infinity;
        const newQty = Math.max(1, Math.min(curQty + delta, maxQty));
        if (newQty === curQty) return;

        // read price from 2nd column
        const priceText = row.querySelector('td:nth-child(2)').textContent.trim();
        const price = parseRupiah(priceText);

        // optimistic UI update
        if ('value' in qtyEl) { qtyEl.value = newQty; qtyEl.dataset.prev = newQty; } else { qtyEl.textContent = newQty; }
        updateTotalBy((newQty - curQty) * price);

        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        try {
            const res = await fetch(`/cart/items/${itemId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ quantity: newQty })
            });
            if (!res.ok) throw new Error('Update gagal');
        } catch (err) {
            console.error('Gagal update kuantitas, reload cart:', err);
            loadCart();
        }
    }

    async function removeItemOptimistic(itemId) {
        if (!confirm('Hapus item ini dari keranjang?')) return;
        const btn = document.querySelector(`.btn-remove[data-id="${itemId}"]`);
        if (!btn) return;
        const row = btn.closest('tr');
        const qtyEl = row.querySelector('.qty-input');
        const qty = parseInt(qtyEl.value ?? qtyEl.textContent) || 0;
        const price = parseRupiah(row.querySelector('td:nth-child(2)').textContent || '');

        // optimistic removal
        row.remove();
        updateTotalBy(-(qty * price));

        // if no items left, show empty state
        if (!document.querySelector('.cart-row')) {
            document.getElementById('table-wrapper').style.display = 'none';
            document.getElementById('cart-summary-col').style.display = 'none';
            document.getElementById('cart-empty').style.display = 'block';
        }

        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        try {
            const res = await fetch(`/cart/items/${itemId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrf }
            });
            if (!res.ok) throw new Error('Delete failed');
        } catch (err) {
            console.error('Gagal menghapus item, reload cart:', err);
            loadCart();
        }
    }

    // handle updates triggered by typing into the number input
    async function changeQtyFromInput(itemId, newQty, inputEl) {
        const prevQty = parseInt(inputEl.dataset.prev) || 0;
        const maxQty = parseInt(inputEl.dataset.max) || Infinity;
        newQty = Math.max(1, Math.min(parseInt(newQty) || 1, maxQty));
        if (newQty === prevQty) { inputEl.value = prevQty; return; }

        const row = inputEl.closest('tr');
        const price = parseRupiah(row.querySelector('td:nth-child(2)').textContent || '');

        // optimistic
        inputEl.dataset.prev = newQty;
        updateTotalBy((newQty - prevQty) * price);

        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        try {
            const res = await fetch(`/cart/items/${itemId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ quantity: newQty })
            });
            if (!res.ok) throw new Error('Update gagal');
        } catch (err) {
            console.error('Gagal update kuantitas dari input, reload cart:', err);
            loadCart();
        }
    }

    document.getElementById('btn-clear-cart').onclick = async function() {
        if (!confirm('Kosongkan semua isi keranjang?')) return;

        // optimistic clear
        document.getElementById('cart-items').innerHTML = '';
        document.getElementById('table-wrapper').style.display = 'none';
        document.getElementById('cart-summary-col').style.display = 'none';
        document.getElementById('cart-empty').style.display = 'block';
        document.getElementById('cart-total').textContent = formatRupiah(0);

        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        try {
            const res = await fetch('/cart/clear', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf } });
            if (!res.ok) throw new Error('Clear failed');
        } catch (err) {
            console.error('Gagal membersihkan keranjang, reload cart:', err);
            loadCart();
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        setReturnUrlButtons();
        loadCart();
    });
</script>

<style>
    /* Desain Gambar Produk */
    .cart-img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 10px;
        background: #f8f9fa;
        border: 1px solid #eee;
    }

    /* Nama Produk & Spek */
    .product-name {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin-bottom: 2px;
    }
    .product-spec {
        font-size: 11px;
        color: #999;
        text-transform: uppercase;
        background: #f1f1f1;
        padding: 1px 6px;
        border-radius: 4px;
    }
    .product-gudang {
        font-size: 10px;
        color: #555;
        font-weight: 500;
        background: linear-gradient(135deg, #e7f3ff 0%, #d0e8ff 100%);
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
        border-left: 2px solid #0d6efd;
    }

    /* Kontrol Quantity */
    .qty-container {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 8px;
        width: fit-content;
        overflow: hidden;
        background: #fff;
    }
    .qty-container:hover {
        border-color: #0d6efd;
    }
    .qty-btn {
        padding: 5px 10px;
        background: #f8f9fa;
        border: none;
        transition: 0.2s;
    }
    .qty-btn:hover { background: #e9ecef; color: #0d6efd; }

    /* style for number input to blend with container (no box, no arrows) */
    .qty-input {
        width: 48px;
        text-align: center;
        font-weight: bold;
        font-size: 14px;
        border: none;
        background: transparent;
        outline: none;
        padding: 6px 0;
        -webkit-appearance: textfield;
        -moz-appearance: textfield;
        appearance: textfield;
    }

    /* remove webkit spinner */
    .qty-input[type="number"]::-webkit-outer-spin-button,
    .qty-input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* firefox: remove inner padding */
    input.qty-input::-moz-focus-inner { border: 0; padding: 0; }

    /* Efek Card & Button */
    .hover-top { transition: all 0.3s ease; }
    .hover-top:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(0,0,0,0.1); }

    .empty-cart-icon {
        width: 100px;
        height: 100px;
        background: #f8f9fa;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #dee2e6;
    }

    /* Responsive Mobile */
    @media (max-width: 768px) {
        .cart-img { width: 50px; height: 50px; }
        .product-name { font-size: 13px; }
    }
</style>
@endsection