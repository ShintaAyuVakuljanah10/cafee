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

                    <a href="#" class="btn btn-primary ms-auto mt-3 mb-3">
                        <i class="bi bi-cart"></i>
                    </a>

                </div>

            </nav>
        </div>

        <!-- Navbar & Hero End -->


        <!-- Menu Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h5 class="section-title ff-secondary text-center text-primary fw-normal">Food & Drink Menu</h5>
                    <h1 class="mb-5">Most Popular Items</h1>
                </div>
                <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s">
                    <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                        @foreach($categories as $key => $category)
                        <li class="nav-item">
                            <a class="d-flex align-items-center text-start mx-3 pb-3 
                                    {{ $key == 0 ? 'active' : '' }}" data-bs-toggle="pill"
                                href="#tab-{{ $category->id }}">

                                <i class="fa fa-utensils fa-2x text-primary"></i>

                                <div class="ps-3">
                                    <small class="text-body">Category</small>
                                    <h6 class="mt-n1 mb-0">{{ $category->name }}</h6>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach($categories as $key => $category)
                        <div id="tab-{{ $category->id }}"
                            class="tab-pane fade show p-0 {{ $key == 0 ? 'active' : '' }}">

                            <div class="row g-4">

                                @forelse($category->makanans as $makanan)
                                <div class="col-lg-6">
                                    @if($makanan->id)
                                    <a href="{{ route('menu.detail', $makanan->id) }}"
                                    class="text-decoration-none text-dark">
                                    @endif
                                        <div class="d-flex align-items-start">

                                            {{-- Gambar --}}
                                            <img class="flex-shrink-0 img-fluid rounded"
                                                src="{{ asset('storage/'.$makanan->gambar) }}"
                                                style="width: 80px; height:80px; object-fit:cover">

                                            <div class="w-100 d-flex flex-column text-start ps-4">

                                                {{-- Nama & Harga --}}
                                                <h5 class="d-flex justify-content-between border-bottom pb-2">
                                                    <span>{{ $makanan->nama }}</span>
                                                    <span class="text-primary">
                                                        Rp {{ number_format($makanan->harga) }}
                                                    </span>
                                                </h5>

                                                {{-- Sub Makanan --}}
                                                @if($makanan->subMakanans->count() > 0)
                                                <small class="text-muted">
                                                    Pilihan:
                                                    @foreach($makanan->subMakanans as $sub)
                                                    {{ $sub->nama }}
                                                    (+Rp {{ number_format($sub->tambahan_harga) }})
                                                    @if(!$loop->last), @endif
                                                    @endforeach
                                                </small>
                                                @else
                                                <small class="fst-italic text-muted">
                                                    Tidak ada varian
                                                </small>
                                                @endif

                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @empty
                                <div class="col-12 text-center">
                                    <p>Belum ada menu di kategori ini</p>
                                </div>
                                @endforelse

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <!-- Menu End -->


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
                        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Thanks For Your Visit</h4>
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
</body>

</html>
