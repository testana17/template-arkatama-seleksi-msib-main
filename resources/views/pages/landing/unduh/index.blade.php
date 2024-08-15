@extends('layouts.guest')
@section('content')
@include('layouts.partials.hero', ['title' => "Unduh Dokumen <br/> PMB Jalur RPL"])
            <section class="row d-flex justify-content-center">
              <div class="col-8">
                <div class="table-responsive">
                  {{ $dataTable->table() }}
                </div>
              </div>
            </section>
            <div class="offcanvas offcanvas-start modernize-lp-offcanvas" tabindex="-1" id="offcanvasNavbar"
    aria-labelledby="offcanvasNavbarLabel">
    <div class="offcanvas-header p-4">
        <img src="{{asset("landing/images/logos/logo-erpl.svg")}}" alt="img-fluid" width="150">
    </div>
    <div class="offcanvas-body p-4">
        <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#"
                    target="_blank">Alur Pendaftaran</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#"
                    target="_blank">Jadwal</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#"
                    target="_blank">Biaya</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/unduh"
                    target="_blank">Unduh</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#"
                    target="_blank">FAQ</a>
            </li>
            <li class="nav-item">
                <div class="">
                    <ul class="navbar-nav">
                        <li class="nav-item ">
                            <a class="btn fs-3 w-100 rounded py-2"
                    href="/login">Login</a>
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
  {{ $dataTable->scripts() }}
@endpush

@endsection