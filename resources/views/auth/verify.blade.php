@extends('layouts.blank')

@section('content')
    {{-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        {{ __('If you did not receive the email') }},
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit"
                                class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

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
                            <a class="nav-link active" aria-current="page" href="{{ route('home') }}#faq">FAQ</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="py-9">
        <div class="d-flex align-items-center justify-content-center w-100">
            <div class="row justify-content-center w-100">
                <div class="col-md-8 col-lg-4">
                    <div class="card mb-0 login-card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-6 text-center">
                                    <h3 class="fw-bolder">Verifikasi Email</h3>
                                </div>
                            </div>
                            @if (session('resent'))
                                <div class="alert alert-success text-center mt-3 pt-3" role="alert">
                                    {{ __('Tautan verifikasi baru telah dikirim ke email Anda.') }}
                                </div>
                            @endif

                            <p class="mb-0 mt-3 text-center">
                                Sebelum melanjutkan, silakan periksa email Anda untuk mendapatkan tautan verifikasi. Jika
                                Anda tidak menerima email tersebut, klik tombol di bawah ini.
                            </p>
                            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit"
                                    class="btn btn-primary mt-3 fs-3 w-100 rounded btn-hover-shadow py-2">
                                    Klik di sini untuk verifikasi email</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-start modernize-lp-offcanvas" tabindex="-1" id="offcanvasNavbar"
        aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header p-4">
            <img src="{{ asset('landing/images/logos/logo-erpl.svg') }}" alt="img-fluid" width="150">
        </div>
        <div class="offcanvas-body p-4">
            <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#" target="_blank">Alur Pendaftaran</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#" target="_blank">Jadwal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#" target="_blank">Biaya</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/unduh" target="_blank">Unduh</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#" target="_blank">FAQ</a>
                </li>
            </ul>
        </div>
    </div>
@endsection
