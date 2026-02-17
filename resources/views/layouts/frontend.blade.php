<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Restoran - Bootstrap Restaurant Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('restoran/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('restoran/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('restoran/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('restoran/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('restoran/css/style.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar & Hero Start -->
        <div class="container-xxl position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0">
                <a href="" class="navbar-brand p-0">
                    <h1 class="text-primary m-0">
                        @if(!empty($app->logo))
                        <img src="{{ asset('storage/'.$app->logo) }}" alt="Logo" style="height:60px;">
                        @endif
                        {{ $app->nama_aplikasi }}
                    </h1>
                </a>

                <div class="collapse navbar-collapse" id="navbarCollapse">

                    <a href="{{ route('cart.index') }}" class="btn btn-primary ms-auto mt-3 mb-3 position-relative">
                        <i class="bi bi-cart"></i>
                        @if(session('cart'))
                            <span id="cart-badge"
                                  class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ collect(session('cart'))->sum('qty') }}
                            </span>
                        @endif
                    </a>    

                </div>

            </nav>
        </div>

        <!-- Navbar & Hero End -->

        <main>
            @yield('content')
        </main>

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Description</h4>
                        <p>{{ $app->deskripsi }}</p>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Contact</h4>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>{{ $app->alamat }}</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>{{ $app->telepon }}</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>{{ $app->email }}</p>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Opening</h4>
                        <h5 class="text-light fw-normal">{{ $app->weekday }}</h5>
                        <p>{{ $app->jam_weekday }}</p>
                        <h5 class="text-light fw-normal">{{ $app->weekend }}</h5>
                        <p>{{ $app->jam_weekend }}</p>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Thanks For Your
                            Visit</h4>
                        <p>Dolor amet sit justo amet elitr clita ipsum elitr est.</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('restoran/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('restoran/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('restoran/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('restoran/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('restoran/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('restoran/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('restoran/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('restoran/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('restoran/js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>
