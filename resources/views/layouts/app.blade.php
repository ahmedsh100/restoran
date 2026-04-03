<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Restoran') }} - @yield('title', 'Premium Restaurant')</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link href="{{ asset('assets/img/favicon.ico') }}" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('assets/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/premium.css') }}" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div id="app">
        <div class="container-xxl position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0">
                <a href="{{ url('/') }}" class="navbar-brand p-0">
                    <h1 class="text-primary m-0"><i class="fa fa-utensils me-3"></i>Restoran</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0 pe-4">
                        <a href="{{ route('home.index') }}" class="nav-item nav-link {{ request()->routeIs('home.index') ? 'active' : '' }}">Home</a>
                        <a href="{{ route('about') }}" class="nav-item nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
                        <a href="{{ route('service') }}" class="nav-item nav-link {{ request()->routeIs('service') ? 'active' : '' }}">Service</a>
                        <a href="{{ route('menu') }}" class="nav-item nav-link {{ request()->routeIs('menu') ? 'active' : '' }}">Menu</a>
                        <a href="{{ route('cart.index') }}" class="nav-item nav-link {{ request()->routeIs('cart.*') ? 'active' : '' }} position-relative">
                            <i class="fas fa-cart-shopping"></i> Cart
                            @if($cartCount > 0)
                                <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('contact') }}" class="nav-item nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
                        @auth
                        @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="nav-item nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            <i class="fa fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                        @endif
                        @endauth
                    </div>

                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}"><i class="fa fa-sign-in-alt me-1"></i>{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}"><i class="fa fa-user-plus me-1"></i>{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-user-circle me-1"></i> {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->is_admin)
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fa fa-tachometer-alt me-2"></i>Admin Dashboard
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('my.orders') }}">
                                        <i class="fa fa-list me-2"></i>My Orders
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa fa-sign-out-alt me-2"></i>{{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </nav>
        </div>

        <main class="py-0">
            @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn footer" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Company</h4>
                    <a class="btn btn-link" href="{{ route('about') }}">About Us</a>
                    <a class="btn btn-link" href="{{ route('contact') }}">Contact Us</a>
                    <a class="btn btn-link" href="{{ route('menu') }}">Our Menu</a>
                    <a class="btn btn-link" href="">Privacy Policy</a>
                    <a class="btn btn-link" href="">Terms & Conditions</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Contact</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, New York, USA</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@restoran.com</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-instagram"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Opening Hours</h4>
                    <h5 class="text-light fw-normal">Monday - Saturday</h5>
                    <p>09:00 AM - 09:00 PM</p>
                    <h5 class="text-light fw-normal">Sunday</h5>
                    <p>10:00 AM - 08:00 PM</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Newsletter</h4>
                    <p>Subscribe for exclusive deals and updates.</p>
                    <div class="position-relative mx-auto" style="max-width: 400px;">
                        <input class="form-control border-primary w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                        <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">Subscribe</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">{{ config('app.name', 'Restoran') }}</a>, All Rights Reserved.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-menu">
                            <a href="{{ route('home.index') }}">Home</a>
                            <a href="">Cookies</a>
                            <a href="">Help</a>
                            <a href="">FAQs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('assets/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assets/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/premium.js') }}"></script>
    @stack('scripts')
</body>
</html>
