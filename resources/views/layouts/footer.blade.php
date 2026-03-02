<style>
    .gd-footer {
        width: 100%;
        margin-top: 0;
        background: linear-gradient(rgba(15, 23, 42, 0.86), rgba(15, 23, 42, 0.9)),
            url('https://images.unsplash.com/photo-1553413077-190dd305871c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .gd-footer-main {
        padding: 1rem 1.25rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.8rem;
    }

    .gd-brand {
        margin: 0;
        color: #fff;
        font-size: 1.05rem;
        font-weight: 800;
        letter-spacing: 0.04em;
    }

    .gd-tagline {
        margin: 0.1rem 0 0;
        color: #cbd5e1;
        font-size: 0.84rem;
    }

    .gd-links {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem 1rem;
    }

    .gd-links a {
        color: #dbeafe;
        text-decoration: none;
        font-size: 0.84rem;
        font-weight: 600;
    }

    .gd-links a:hover {
        color: #93c5fd;
    }

    .gd-footer-bottom {
        border-top: 1px solid rgba(148, 163, 184, 0.25);
        padding: 0.65rem 1.25rem;
        font-size: 0.78rem;
        color: #cbd5e1;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 0.5rem;
    }

    @media (max-width: 767.98px) {
        .gd-footer-main,
        .gd-footer-bottom {
            padding-left: 1rem;
            padding-right: 1rem;
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<footer class="gd-footer">
    <div class="gd-footer-main">
        <div>
            <p class="gd-brand">GUDIT</p>
            <p class="gd-tagline">Gudang Digital ITB - Institut Teknologi Bandung.</p>
        </div>

        <nav class="gd-links">
            <a href="{{ url('/') }}">Beranda</a>
            <a href="{{ url('/pengajuan/list') }}">Pengajuan</a>
            <a href="{{ url('/gudang') }}">Gudang</a>
            <a href="{{ url('/laporan') }}">Laporan</a>
        </nav>
    </div>

    <div class="gd-footer-bottom">
        <span>&copy; {{ now()->year }} GUDIT</span>
        <span>Inventory Management System</span>
    </div>
</footer>
