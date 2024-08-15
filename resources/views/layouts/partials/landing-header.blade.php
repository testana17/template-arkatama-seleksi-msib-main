<header class="header">
    <nav class="navbar navbar-expand-lg py-0">
        <div class="container">
            <a class="navbar-brand me-0 py-0" href="/">
                <img src="{{ asset('landing/images/logos/logo-erpl.svg') }}" alt="img-fluid">
            </a>
            <button class="navbar-toggler d-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="ti ti-menu-2 fs-9"></i>
            </button>
            <button class="navbar-toggler border-0 p-0 shadow-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <i class="ti ti-menu-2 fs-9"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav align-items-center mb-2 mb-lg-0 ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('home') }}#alur">Alur
                            Pendaftaran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('home') }}#jadwal">Jadwal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('home') }}#biaya">Biaya</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('unduh') }}">Unduh</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('news') }}">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('home') }}#faq">FAQ</a>
                    </li>
                    <li class="nav-item ms-5">
                        <div class="">
                            <ul class="navbar-nav">
                                @auth
                                    <li class="nav-item ">
                                        <a class="btn btn-outline-primary fs-3 rounded px-3 py-2"
                                            href="{{ route('dashboard') }}">Dashboard</a>
                                    </li>
                                @endauth
                                @guest
                                    <li class="nav-item ">
                                        <a class="btn btn-light fs-3 rounded px-3 py-2"
                                            href="{{ route('login') }}">Login</a>
                                    </li>
                                    <li class="nav-item ms-2">
                                        <a class="btn btn-primary fs-3 rounded btn-hover-shadow px-3 py-2"
                                            href="{{ route('register') }}">Mendaftar</a>
                                    </li>
                                @endguest
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="offcanvas offcanvas-start modernize-lp-offcanvas" tabindex="-1" id="offcanvasNavbar"
    aria-labelledby="offcanvasNavbarLabel">
    <div class="offcanvas-header p-4">
        <img src="{{ asset('landing/images/logos/logo-erpl.svg') }}" alt="img-fluid" width="150">
    </div>
    <div class="offcanvas-body p-4">
        <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{ route('home') }}#alur">Alur Pendaftaran</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{ route('home') }}#jadwal">Jadwal</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{ route('home') }}#biaya">Biaya</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{ route('unduh') }}">Unduh</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{ route('news') }}">Berita</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{ route('home') }}#faq">FAQ</a>
            </li>
            <li class="nav-item">
                <div class="">
                    <ul class="navbar-nav">

                        @auth
                            <li class="nav-item ">
                                <a class="btn btn-outline-primary fs-3 rounded px-3 py-2"
                                    href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                        @endauth
                        @guest
                            <li class="nav-item ">
                                <a class="btn fs-3 rounded px-3 py-2" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item ms-2">
                                <a class="btn btn-primary fs-3 rounded btn-hover-shadow px-3 py-2"
                                    href="{{ route('register') }}">Mendaftar</a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
