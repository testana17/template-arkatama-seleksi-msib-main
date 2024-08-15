@extends('layouts.blank')

@section('content')
<div>
    <div class=" position-relative mb-4" style="background: rgb(9,79,183); background: linear-gradient(97deg, rgba(9,79,183,1) 0%, rgba(53,96,160,1) 87%);">
        <img src="{{asset("assets/images/svgs/acc-reg-2.svg")}}" alt="" class=" position-absolute end-0  d-none d-lg-block z-index-0" style="top: 25%">
        <img src="{{asset("assets/images/svgs/acc-reg-1.svg")}}" alt="" class=" position-absolute d-none d-lg-block  z-index-0" style="bottom:0; ">
        <div class="container d-flex flex-column justify-content-center align-items-center text-center py-5">
            <h1 class="fw-bolder text-white" style="font-size: 56px">Confirm Password</h1>
        </div>
    </div>
    <div class="container d-flex align-items-center justify-content-center">
        <div class="row justify-content-center">
            <div class="">
                <div class="card">

                    <div class="card-body">
                        <div class="my-3" style="background: #F5F6FA">
                            <div class="p-3 d-flex flex-column">
                                <p class="fs-4 m-0" style="color:#5A607F">Harap konfirmasi kata sandi Anda sebelum melanjutkan.</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('password.confirm') }}" class="d-flex flex-column justify-content-center">
                            @csrf

                            <div class="row my-3">

                                <div class="">
                                    <x-atoms.input type="password" name="password" id="password" placeholder="Masukan Password Anda" class="py-3" required autocomplete="current-password" value="{{ old('password') }}"/>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit"  class="btn btn-primary mb-3 align-self-center col-8"> {{ __('Confirm Password') }}</button>

                            @if (Route::has('password.request'))
                            <a class="btn text-primary align-self-center col-8" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <script>
    Swal.fire({
        title: 'Delivery Successful',
        text: 'Please check your email to see your reset password instructions',
        icon: 'success',
        confirmButtonColor: '#1E5EFF',
        confirmButtonText: 'Continue',
        customClass: {
            confirmButton: 'text-white px-5',
        }
    })
</script> --}}

@endsection


