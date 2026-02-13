@extends('layouts.app')

@section('title', 'Shop - Electro')

@section('content')
    <div class="container-fluid page-header py-5 mb-4 bg-primary">
        <h1 class="text-center text-white display-6">Shop</h1>
    </div>

    <div class="container mb-5">
        <div class="mx-auto text-center pb-5" style="max-width: 700px;">
            <h4 class="text-primary mb-4 border-bottom border-primary border-2 d-inline-block p-2 title-border-radius wow fadeInUp"
                data-wow-delay="0.1s">Gudang</h4>
            @php
                $gudangName = 'Ganesha';
                if (isset($gudangKode)) {
                    if (isset($gudangs) && is_iterable($gudangs)) {
                        $g = $gudangs->firstWhere('kode_gudang', $gudangKode);
                        if ($g && isset($g->nama_gudang)) $gudangName = $g->nama_gudang;
                    } else {
                        $g = \App\Models\Gudang::where('kode_gudang', $gudangKode)->first();
                        if ($g && isset($g->nama_gudang)) $gudangName = $g->nama_gudang;
                    }
                }
            @endphp

            <p class="wow fadeInUp" data-wow-delay="0.2s">selamat datang di halaman gudang {{ $gudangName }}, silakan pilih barangnya</p>
        </div>
        
        <div class="category-filter-container mb-4">
            <div class="category-chips">
                <button class="category-chip active" data-category="all">
                    <i class="fa fa-th-large"></i> Semua
                </button>
                <div id="dynamic-categories"></div>
            </div>
        </div>

        <div class="input-group mb-4 shadow-sm">
            <input type="search" id="main-search" class="form-control p-3 border-0" placeholder="Cari nama atau kode produk...">
            <button class="btn btn-primary px-4" id="btn-search">
                <i class="fa fa-search"></i>
            </button>
            @if (auth()->check() && (auth()->user()->role === 'atasan' || auth()->user()->role === 'admin' || auth()->user()->role === 'petugas'))
                {{-- Admin controls removed: add/remove buttons --}}
            @endif
        </div>

        <div id="selected-list" class="mb-4" style="min-height:40px;"></div>

        {{-- Product picker removed --}}

        <div class="product-grid" id="product-grid">
            <div class="loading-placeholder">Memuat produk...</div>
        </div>

        <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
            <div class="pagination d-flex justify-content-center mt-5">
                <!-- Pagination akan di-generate secara dinamis -->
            </div>
        </div>
    </div>

    <script>
        // State & Config
        let _shopProducts = [];
        let _displayedProducts = [];
        let currentGudang = "{{ isset($gudangKode) ? $gudangKode : '' }}";
        let currentGudangCode = currentGudang;
        let currentPage = 1;
        const ITEMS_PER_PAGE = 30;

        // --- FUNGSI LOAD KATEGORI ---
        async function loadCategories() {
            try {
                const res = await fetch('/shop/categories');
                if (!res.ok) throw new Error('Network error');
                const categories = await res.json();
                const categoriesContainer = document.getElementById('dynamic-categories');
                categoriesContainer.innerHTML = '';
                
                categories.forEach(cat => {
                    const chip = document.createElement('button');
                    chip.className = 'category-chip';
                    chip.dataset.category = (cat.slug || cat.name).toString().toLowerCase();
                    chip.innerHTML = `<i class="fa fa-tag"></i> ${cat.name}`;
                    
                    chip.addEventListener('click', function() {
                        filterByCategory(this.dataset.category);
                        document.querySelectorAll('.category-chip').forEach(c => c.classList.remove('active'));
                        this.classList.add('active');
                    });
                    
                    categoriesContainer.appendChild(chip);
                });
            } catch (err) {
                console.error('Gagal memuat kategori:', err);
            }
        }

        // --- FUNGSI PENCARIAN & FILTER ---
        function runMainFilter() {
            const searchTerm = document.getElementById('main-search').value.toLowerCase().trim();
            _displayedProducts = _shopProducts.filter(p => {
                const name = (p.name || p.nama_barang || '').toLowerCase();
                const kode = (p.kode || p.kode_barang || '').toLowerCase();
                return name.includes(searchTerm) || kode.includes(searchTerm);
            });
            
            currentPage = 1;
            displayProductsPage();
        }

        function filterByCategory(categoryName) {
            if (!categoryName) return;
            if (categoryName === 'all' || categoryName === 'All') {
                _displayedProducts = _shopProducts;
            } else {
                const target = categoryName.toString().toLowerCase().trim();
                _displayedProducts = _shopProducts.filter(p => {
                    const slug = (p.kategori_slug || (p.kategori || '')).toString().toLowerCase().trim();
                    return slug === target;
                });
            }

            currentPage = 1;
            displayProductsPage();
        }

        // --- FUNGSI LOAD DATA ---
        async function loadProductsFromDB(openPickerAfter = true, gudang = null) {
            try {
                let url = '/shop/products';
            const gudangToUse = gudang !== null ? gudang : currentGudang;
            if (!openPickerAfter && gudangToUse) url += '?gudang=' + encodeURIComponent(gudangToUse);
                
                const res = await fetch(url);
                if (!res.ok) throw new Error('Network error');
                const products = await res.json();
                _shopProducts = products;
                _displayedProducts = products;
                currentPage = 1;

                displayProductsPage();
            } catch (err) {
                console.error('Gagal memuat produk:', err);
                alert('Gagal memuat produk dari server.');
            }
        }

        // picker UI/functions removed

        // picker removed: renderPickerList no longer available

        function createProductCard(p) {
            const card = document.createElement('div');
            card.className = 'product-card';
            card.dataset.id = p.id || '';
            card.dataset.kode = p.kode || p.kode_barang || '';
            card.dataset.name = p.name || p.nama_barang || '';
            card.dataset.image = p.image || '';
            card.dataset.price = p.price || p.harga || '';
            card.dataset.category = p.kategori || p.category || '';
            
            const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
            
            card.innerHTML = `
                <div class="product-image">
                    <img src="${p.image}" alt="${p.name}">
                </div>
                <div class="product-body">
                    <div class="product-title">${p.name}</div>
                    <div class="product-price">Rp. ${Number(p.price).toLocaleString()}</div>
                    <div class="product-stock">Stok: ${p.stok !== undefined && p.stok !== null ? p.stok : '-'}</div>
                    <div class="product-actions">
                        <button class="btn-specs">Spesifikasi</button>
                        ${isAuthenticated ? `
                            <button class="btn-cart">+Keranjang</button>
                            <span class="cart-float">+1</span>
                        ` : ''}
                        <div class="spec-popup">
                            <div><span class="spec-label">Kode:</span> ${p.kode || ''}</div>
                            <div><span class="spec-label">Satuan:</span> ${p.satuan || ''}</div>
                            <div class="mt-1"><span class="spec-label">Spesifikasi:</span><br>${p.deskripsi || ''}</div>
                        </div>
                    </div>
                </div>
            `;

            // Event Listeners for dynamic cards
            card.querySelector('.btn-specs').addEventListener('click', (e) => {
                e.stopPropagation();
                const popup = card.querySelector('.spec-popup');
                document.querySelectorAll('.spec-popup').forEach(el => el.classList.remove('show'));
                popup.classList.toggle('show');
            });

            const cartBtn = card.querySelector('.btn-cart');
            if (cartBtn) {
                cartBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    addToCart(card.querySelector('.btn-cart'));
                });
            }

            return card;
        }

        function appendProductsToGrid() {
            const grid = document.querySelector('.product-grid');
            grid.innerHTML = '';
            
            const totalPages = Math.ceil(_displayedProducts.length / ITEMS_PER_PAGE);
            const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
            const endIndex = startIndex + ITEMS_PER_PAGE;
            const paginatedProducts = _displayedProducts.slice(startIndex, endIndex);
            
            paginatedProducts.forEach(p => grid.appendChild(createProductCard(p)));
            renderPagination(totalPages);
        }

        function displayProductsPage() {
            const grid = document.querySelector('.product-grid');
            grid.innerHTML = '';
            
            const totalPages = Math.ceil(_displayedProducts.length / ITEMS_PER_PAGE);
            const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
            const endIndex = startIndex + ITEMS_PER_PAGE;
            const paginatedProducts = _displayedProducts.slice(startIndex, endIndex);
            
            if (paginatedProducts.length === 0) {
                grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #999;">Tidak ada produk yang sesuai</div>';
            } else {
                paginatedProducts.forEach(p => grid.appendChild(createProductCard(p)));
            }
            
            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            const paginationContainer = document.querySelector('.pagination');
            paginationContainer.innerHTML = '';
            
            if (totalPages <= 1) return;
            
            // Previous button
            const prevBtn = document.createElement('a');
            prevBtn.href = '#';
            prevBtn.className = 'rounded';
            prevBtn.innerHTML = '&laquo;';
            prevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (currentPage > 1) {
                    currentPage--;
                    appendProductsToGrid();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
            paginationContainer.appendChild(prevBtn);
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageLink = document.createElement('a');
                pageLink.href = '#';
                pageLink.className = `rounded ${i === currentPage ? 'active' : ''}`;
                pageLink.textContent = i;
                pageLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    currentPage = i;
                    appendProductsToGrid();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
                paginationContainer.appendChild(pageLink);
            }
            
            // Next button
            const nextBtn = document.createElement('a');
            nextBtn.href = '#';
            nextBtn.className = 'rounded';
            nextBtn.innerHTML = '&raquo;';
            nextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (currentPage < totalPages) {
                    currentPage++;
                    appendProductsToGrid();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
            paginationContainer.appendChild(nextBtn);
        }

        // --- KERANJANG (LOCAL STORAGE) ---
        function showAddFeedback(btn, message = 'Ditambahkan ke keranjang') {
            const float = btn.closest('.product-actions')?.querySelector('.cart-float');
            if (float) {
                // Restart the keyframe animation so repeated clicks replay smoothly
                float.style.animation = 'none';
                // force reflow to reset animation
                void float.offsetWidth;
                float.style.animation = '';
                float.classList.add('show');

                if (float.__timeout) clearTimeout(float.__timeout);
                float.__timeout = setTimeout(() => {
                    float.classList.remove('show');
                    float.style.animation = '';
                    float.__timeout = null;
                }, 800);
            }

            const selectedList = document.getElementById('selected-list');
            if (!selectedList) return;

            // Reuse existing feedback element instead of creating many
            let el = selectedList.querySelector('.added-feedback');
            if (el) {
                el.textContent = message;
                el.style.opacity = '1';
                if (el.__fadeTimeout) clearTimeout(el.__fadeTimeout);
                if (el.__removeTimeout) clearTimeout(el.__removeTimeout);
                el.__fadeTimeout = setTimeout(() => {
                    el.style.transition = 'opacity 0.3s';
                    el.style.opacity = '0';
                    el.__removeTimeout = setTimeout(() => el.remove(), 400);
                }, 1200);
                return;
            }

            el = document.createElement('div');
            el.className = 'added-feedback';
            el.style.padding = '6px 10px';
            el.style.background = '#e9f7ef';
            el.style.border = '1px solid #c7efd9';
            el.style.borderRadius = '6px';
            el.style.display = 'inline-block';
            el.style.marginRight = '6px';
            el.textContent = message;
            selectedList.prepend(el);
            el.__fadeTimeout = setTimeout(() => {
                el.style.transition = 'opacity 0.3s';
                el.style.opacity = '0';
                el.__removeTimeout = setTimeout(() => el.remove(), 400);
            }, 1200);
        }

        function addToCart(btn) {
            if (!currentGudangCode) {
                alert("Silakan pilih gudang terlebih dahulu.");
                window.location.href = "{{ url('/') }}";
                return;
            }

            const card = btn.closest('.product-card');
            const productId = card.dataset.id;

            // Immediately show optimistic feedback so user sees instant response
            showAddFeedback(btn);

            // Try server-side add (will use session or auth user)
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            fetch('/cart/items', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ barang_id: productId, quantity: 1 })
            }).then(res => {
                if (res.ok) {
                    // success on server: optionally update badge later
                    return;
                }
                throw new Error('server');
            }).catch(() => {
                // Fallback to localStorage-based cart when server unavailable
                const product = {
                    id: card.dataset.id,
                    kode: card.dataset.kode,
                    name: card.dataset.name,
                    price: parseFloat(card.dataset.price) || 0,
                    image: card.dataset.image,
                    qty: 1,
                    gudang_kode: currentGudangCode
                };

                let cart = JSON.parse(localStorage.getItem('electro_cart')) || [];

                if (cart.length > 0 && cart[0].gudang_kode !== currentGudangCode) {
                    if (confirm(`Hapus keranjang dari gudang sebelumnya?`)) {
                        cart = [];
                    } else {
                        return;
                    }
                }

                const existingIndex = cart.findIndex(item => item.id === product.id);
                if (existingIndex > -1) cart[existingIndex].qty += 1;
                else cart.push(product);

                localStorage.setItem('electro_cart', JSON.stringify(cart));

                // show an additional short message indicating fallback saved locally
                showAddFeedback(btn, 'Disimpan lokal (server tidak merespon)');
            });
        }

        // --- EVENT INITIALIZATION ---
        document.addEventListener('DOMContentLoaded', function() {
            // Load categories from API
            loadCategories();

            // Search & Category Events
            document.getElementById('main-search').addEventListener('input', runMainFilter);
            document.getElementById('btn-search').addEventListener('click', runMainFilter);

            // Event listener untuk tombol "Semua"
            const allBtn = document.querySelector('[data-category="all"]');
            if (allBtn) {
                allBtn.addEventListener('click', function() {
                    filterByCategory('all');
                    document.querySelectorAll('.category-chip').forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                });
            }

            // picker and add-product controls removed

            // bulk-delete feature removed

            // Initial load: prefer `gudang` query param if present, otherwise load all products via API
            const params = new URLSearchParams(window.location.search);
            const urlGudang = params.get('gudang');
            if (urlGudang) {
                currentGudang = urlGudang;
                currentGudangCode = urlGudang;
                loadProductsFromDB(false, urlGudang);
            } else {
                loadProductsFromDB(false);
            }

            // Spec popups
            document.querySelectorAll('.btn-specs').forEach(btn => {
                btn.onclick = (e) => {
                    e.stopPropagation();
                    const card = btn.closest('.product-card');
                    const popup = card.querySelector('.spec-popup');
                    document.querySelectorAll('.spec-popup').forEach(p => p.classList.remove('show'));
                    popup.classList.toggle('show');
                };
            });

            window.onclick = (e) => {
                if (!e.target.closest('.spec-popup') && !e.target.closest('.btn-specs')) {
                    document.querySelectorAll('.spec-popup').forEach(p => p.classList.remove('show'));
                }
            };
        });
    </script>

    <style>
        /* Category Filter Styles */
        /* Compact horizontal category chips (minimal vertical space) */
        .category-filter-container {
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 8px 0 6px 0;
        }

        .category-chips {
            display: flex;
            gap: 8px;
            align-items: center;
            overflow-x: auto;
            padding: 6px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none; /* Firefox */
            max-width: 100%;
            white-space: nowrap;
        }

        /* hide scrollbar (webkit) */
        .category-chips::-webkit-scrollbar { height: 6px; }
        .category-chips::-webkit-scrollbar-track { background: transparent; }
        .category-chips::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.08); border-radius: 10px; }

        .category-chip {
            padding: 6px 12px;
            border: 1px solid #e3e3e3;
            background-color: white;
            border-radius: 999px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            color: #555;
            transition: all 0.18s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            min-width: 48px;
            height: 36px;
        }

        .category-chip:hover {
            border-color: #bcdcff;
            color: #0d6efd;
            background-color: #f8fbff;
            transform: translateY(-1px);
        }

        .category-chip.active {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
            box-shadow: 0 6px 18px rgba(13,110,253,0.12);
        }

        .category-chip i { font-size: 12px; }

        /* On very small screens reduce padding */
        @media (max-width: 420px) {
            .category-chips { gap: 6px; padding: 4px; }
            .category-chip { padding: 5px 10px; font-size: 12px; height: 32px; }
        }

        .product-card { transition: transform 0.2s; margin-top: -8px; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .spec-popup { 
            display: none; position: absolute; bottom: 100%; left: 0; 
            background: white; border: 1px solid #ddd; padding: 10px; 
            z-index: 100; width: 100%; box-shadow: 0 -5px 15px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .spec-popup.show { display: block; }
        .cart-float { 
            display: none; position: absolute; top: -20px; right: 10px; 
            color: #198754; font-weight: bold; animation: fadeUp 0.8s;
        }
        .cart-float.show { display: block; }
        .product-stock { font-size: 0.75rem; color: #6c757d; margin-top: -6px; margin-bottom:6px; line-height:1; }
        @keyframes fadeUp { 
            0% { opacity: 1; transform: translateY(0); } 
            100% { opacity: 0; transform: translateY(-20px); } 
        }
        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 3px;
            border: 1px solid #ddd;
            background-color: white;
            color: #0d6efd;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .pagination a:hover {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
        .pagination a.active {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
            font-weight: bold;
        }
    </style>
@endsection