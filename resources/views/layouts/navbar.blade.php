<div class="container-fluid px-5 d-none border-bottom d-lg-block topbar">
    <div class="row gx-0 align-items-center">
        <div class="col-lg-4 text-center text-lg-start mb-lg-0">
            <div class="d-inline-flex align-items-center" style="height: 45px;">
                <a href="#" class="text-muted me-2"> Help</a><small> / </small>
                <a href="#" class="text-muted mx-2"> Support</a><small> / </small>
                <a href="#" class="text-muted ms-2"> Contact</a>
            </div>
        </div>
        <div class="col-lg-4 text-center d-flex align-items-center justify-content-center">
            <small class="text-dark">Call Us:</small>
            <a href="#" class="text-muted">(+012) 1234 567890</a>
        </div>
        <div class="col-lg-4 text-center text-lg-end">
            <div class="d-inline-flex align-items-center" style="height: 45px;">
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle text-muted me-2"
                        data-bs-toggle="dropdown"><small>USD</small></a>
                    <div class="dropdown-menu rounded">
                        <a href="#" class="dropdown-item"> Euro</a>
                        <a href="#" class="dropdown-item"> Dolar</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle text-muted mx-2"
                        data-bs-toggle="dropdown"><small>English</small></a>
                    <div class="dropdown-menu rounded">
                        <a href="#" class="dropdown-item"> English</a>
                        <a href="#" class="dropdown-item"> Turkish</a>
                        <a href="#" class="dropdown-item"> Spanol</a>
                        <a href="#" class="dropdown-item"> Italiano</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle text-muted ms-2" data-bs-toggle="dropdown"><small><i
                                class="fa fa-home me-2"></i> My Dashboard</small></a>
                    <div class="dropdown-menu rounded">
                        <a href="#" class="dropdown-item"> Login</a>
                        <a href="#" class="dropdown-item"> Wishlist</a>
                        <a href="#" class="dropdown-item"> My Card</a>
                        <a href="#" class="dropdown-item"> Notifications</a>
                        <a href="#" class="dropdown-item"> Account Settings</a>
                        <a href="#" class="dropdown-item"> My Account</a>
                        <a href="#" class="dropdown-item"> Log Out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid nav-bar p-0">
    <div class="row gx-0 bg-primary px-5 align-items-center">
        <div class="col-lg-3 d-none d-lg-block">
            <nav class="navbar navbar-light position-relative" style="width: 250px;">
                <button class="navbar-toggler border-0 fs-4 w-100 px-0 text-start" type="button"
                    data-bs-toggle="collapse" data-bs-target="#allCat">
                    <h2 class="m-0"><span class="text-primary">Gudit</span></h2>
                    <h6 class="m-1">Gudang Digital</h6>
                </button>
                <div class="collapse navbar-collapse rounded-bottom" id="allCat">
                    <div class="navbar-nav ms-auto py-0">
                        <ul class="list-unstyled categories-bars">
                            <li>
                                <div class="categories-bars-item"><a href="#">Accessories</a><span>(3)</span>
                                </div>
                            </li>
                            <li>
                                <div class="categories-bars-item"><a href="#">Electronics &
                                        Computer</a><span>(5)</span></div>
                            </li>
                            <li>
                                <div class="categories-bars-item"><a href="#">Laptops &
                                        Desktops</a><span>(2)</span></div>
                            </li>
                            <li>
                                <div class="categories-bars-item"><a href="#">Mobiles &
                                        Tablets</a><span>(8)</span></div>
                            </li>
                            <li>
                                <div class="categories-bars-item"><a href="#">SmartPhone & Smart
                                        TV</a><span>(5)</span></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <div class="col-12 col-lg-9">
            <nav class="navbar navbar-expand-lg navbar-light bg-primary ">
                <a href="{{ url('/') }}" class="navbar-brand d-block d-lg-none">
                    <h1 class="display-5 text-secondary m-0"><i class="fas fa-shopping-bag text-white me-2"></i>Gudit
                    </h1>
                </a>
                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars fa-1x"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="{{ url('/') }}" class="nav-item nav-link active">Beranda</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link" data-bs-toggle="dropdown">Gudang</a>
                            <div class="dropdown-menu m-0">
                                <a href="bestseller.html" class="dropdown-item">Ganesha</a>
                                <a href="cart.html" class="dropdown-item">Jatinangor</a>
                                <a href="cheackout.html" class="dropdown-item">Cirebon</a>
                                <a href="404.html" class="dropdown-item">Jakarta</a>
                            </div>
                        </div>
                        <a href="{{ url('/cart') }}" class="nav-item nav-link">Keranjang</a>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="btn btn-primary rounded-pill py-2 px-4 px-lg-3 mb-3 mb-md-3 mb-lg-0"
                            data-bs-toggle="dropdown">
                            <small><i class="fa fa-user me-2"></i>
                                @auth
                                    {{ auth()->user()->nama ?? auth()->user()->nama }}
                                @else
                                    Pengguna
                                @endauth
                            </small>
                        </a>
                        <div class="dropdown-menu rounded">
                            @guest
                                <a href="{{ route('login.show') }}" class="dropdown-item">Login</a>
                            @endguest

                            @auth
                                <a href="#" class="dropdown-item">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Log Out</button>
                                </form>
                            @endauth
                        </div>
                    </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
