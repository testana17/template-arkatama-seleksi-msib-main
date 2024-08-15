@extends('layouts.blank')

@section('content')
    <div>
        <div class=" position-relative mb-4" style="background: rgb(9,79,183); background: linear-gradient(97deg, rgba(9,79,183,1) 0%, rgba(53,96,160,1) 87%);">
            <img src="{{asset("assets/images/svgs/acc-reg-2.svg")}}" alt="" class=" position-absolute end-0  d-none d-lg-block z-index-0" style="top: 25%">
            <img src="{{asset("assets/images/svgs/acc-reg-1.svg")}}" alt="" class=" position-absolute d-none d-lg-block  z-index-0" style="bottom:0; ">
            <div class="container d-flex flex-column justify-content-center align-items-center text-center py-5">
                <h1 class="fw-bolder text-white" style="font-size: 56px">Forgot Password</h1>
            </div>
        </div>

        <div class="position-relative overflow-hidden radial-gradient d-flex align-items-center justify-content-center">
            <div class="container d-flex align-items-center justify-content-center">
                <div class="row justify-content-center my-5">
                    <div class="col-md-8">
                        <div class="card mb-0">
                            <div class="card-body my-3">
                                <div class="my-3" style="background: #F5F6FA">
                                    <div class="p-3 d-flex flex-column">
                                        <p class="fs-4 m-0" style="color:#5A607F">Lupa Password ? Jangan khawatir, kami akan mengirimkan petunjuk pengaturan ulang kepada Anda.</p>
                                    </div>
                                </div>

                                <form class="d-flex flex-column justify-content-center">
                                    <div class="mb-3">
                                        <x-atoms.input type="email" name="email" id="email_field" placeholder="Masukan Email Anda" class="py-3" value="{{ old('email') }}"/>
                                    </div>

                                    <a href="javascript:void(0)" class="btn btn-primary mb-3 align-self-center col-8">Reset Password</a>
                                    <a href="{{route("login")}}"
                                        class="btn text-primary align-self-center col-8">Kembali Ke Halaman Login</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
