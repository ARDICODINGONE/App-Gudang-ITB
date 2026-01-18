<!-- Footer -->

<style>
    /* Custom styles for footer */
.footer-section {
    background: linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.9)), 
                url('https://images.unsplash.com/photo-1553413077-190dd305871c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.footer-section .btn-social {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 40px;
    transition: all 0.3s;
}

.footer-section .btn-social:hover {
    background-color: #0d6efd;
    border-color: #0d6efd;
    transform: translateY(-3px);
}

.footer-section a.text-white-50:hover {
    color: #fff !important;
    padding-left: 5px;
    transition: all 0.3s;
}

.footer-section .bg-primary {
    background-color: #0d6efd !important;
}

/* Hover effects for contact items */
.footer-section .d-flex.mb-3:hover .bg-primary {
    background-color: #0b5ed7 !important;
    transform: scale(1.1);
    transition: all 0.3s;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .footer-section .text-center.text-md-start,
    .footer-section .text-center.text-md-end {
        text-align: center !important;
    }
    
    .footer-section .d-flex.justify-content-md-end {
        justify-content: center !important;
    }
}
</style>
<div class="container-fluid footer-section bg-dark py-5 wow fadeIn" data-wow-delay="0.2s">
    <div class="container py-5">
        <!-- Quick Contact Section -->
        <div class="row g-5 mb-5">
            <div class="col-lg-4">
                <div class="mb-4">
                    <a href="{{ url('/') }}" class="navbar-brand d-inline-block mb-3">
                        <h2 class="display-5 text-white m-0"><span class="text-primary">Gudit</span></h2>
                        <p class="text-white-50 m-0">Gudang Digital</p>
                    </a>
                    <p class="text-white-50 mb-4">
                        Platform digital terpercaya untuk kebutuhan gudang dan logistik Anda. 
                        Solusi penyimpanan modern dengan teknologi terkini.
                    </p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social me-2" href="#"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social me-2" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social me-2" href="#"><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6">
                <h5 class="text-white mb-4">Tautan Cepat</h5>
                <div class="d-flex flex-column">
                    <a class="text-white-50 mb-2" href="{{ url('/') }}"><i class="fa fa-angle-right me-2"></i>Beranda</a>
                    <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Tentang Kami</a>
                    <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Layanan</a>
                    <a class="text-white-50 mb-2" href="{{ url('/cart') }}"><i class="fa fa-angle-right me-2"></i>Keranjang</a>
                    <a class="text-white-50" href="#"><i class="fa fa-angle-right me-2"></i>Kontak</a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6">
                <h5 class="text-white mb-4">Gudang Kami</h5>
                <div class="d-flex flex-column">
                    <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Ganesha</a>
                    <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Jatinangor</a>
                    <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Cirebon</a>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-12">
                <h5 class="text-white mb-4">Kontak Kami</h5>
                <div class="d-flex mb-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="fas fa-map-marker-alt text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-white mb-0">Alamat</h6>
                        <p class="text-white-50 mb-0">Jl. Ganesha No. 10, Bandung, Indonesia</p>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="fas fa-envelope text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-white mb-0">Email</h6>
                        <p class="text-white-50 mb-0">info@gudit.com</p>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="fa fa-phone-alt text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-white mb-0">Telepon</h6>
                        <p class="text-white-50 mb-0">(+62) 22 1234 5678</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Divider -->
        <div class="border-top border-secondary pt-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="text-white-50 mb-0">
                        &copy; <a class="text-primary" href="{{ url('/') }}">Gudit - Gudang Digital</a>. 
                        All Rights Reserved.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="d-flex justify-content-md-end justify-content-center">
                        <a class="text-white-50 me-4" href="#">Kebijakan Privasi</a>
                        <a class="text-white-50" href="#">Syarat & Ketentuan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>