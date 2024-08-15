@extends('layouts.auth')

@section('content')
    <div class="py-9">
        <div class="d-flex align-items-center justify-content-center w-100">
            <div class="row justify-content-center w-100">
                <div class="d-none d-lg-flex col-6 col justify-content-center login-bg">
                    <div class="d-flex flex-column align-items-center justify-content-center py-5 h-100 w-50">
                        <h1 class=" d-block fs-2qx fw-bolder text-center mx-auto">Selamat Datang di Sistem E-RPL</h1>
                        <div class=" d-block fs-base text-center text-black">Pendaftaran Mahasiswa Baru Jalur Rekognisi
                            Pembelajaran Lampau</div>
                    </div>
                </div>
                <div class="col-md-8 col-lg-4">
                    <div class="card mb-0 login-card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-6 text-center">
                                    <h3 class="fw-bolder">Login</h3>
                                </div>
                            </div>
                            <form method="POST" action="{{ URL::to('login') }}" custom-action>
                                @csrf
                                <div class="mb-3">
                                    <x-atoms.form-label for="email_field">Email</x-atoms.form-label>
                                    <x-atoms.input type="email" name="email" value="{{ old('email') }}"
                                        id="email_field" placeholder="Enter Email Address" ssr />
                                </div>
                                <div class="mb-4">
                                    <x-atoms.form-label for="password_field">Password</x-atoms.form-label>
                                    <x-atoms.input type="password" name="password" id="password_field"
                                        placeholder="Enter Password" ssr />
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input primary" type="checkbox" value=""
                                            id="flexCheckChecked" checked>
                                        <label class="form-check-label text-dark" for="flexCheckChecked">
                                            Keep me sign in
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 login-btn mb-4 rounded-2">Login</button>
                                <div class="d-flex align-items-center justify-content-center">
                                    <a class="text-primary fw-medium ms-2" href="/password/reset">Forgot your password?</a>
                                </div>
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
                <li class="nav-item">
                    <div class="">
                        <ul class="navbar-nav">
                            <li class="nav-item ">
                                <a class="btn fs-3 w-100 rounded py-2" href="/login">Login</a>
                            </li>
                            <li class="nav-item ms-2">
                                <a class="btn btn-primary fs-3 w-100 rounded btn-hover-shadow py-2"
                                    href="#">Mendaftar</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    @push('scripts')
        <script>
            @if (session('success'))
                swal("Sukses!", "{{ session('success') }}", "success");
            @endif

            @if (session('error'))
                swal("Gagal!", "{{ session('error') }}", "error");
            @endif
        </script>
    @endpush
@endsection
