@extends('layouts.blank')

@section('content')
    <div>
        <div class=" position-relative mb-4"
            style="background: rgb(9,79,183); background: linear-gradient(97deg, rgba(9,79,183,1) 0%, rgba(53,96,160,1) 87%);">
            <img src="{{ asset('assets/images/svgs/acc-reg-2.svg') }}" alt=""
                class=" position-absolute end-0  d-none d-lg-block z-index-0" style="top: 25%">
            <img src="{{ asset('assets/images/svgs/acc-reg-1.svg') }}" alt=""
                class=" position-absolute d-none d-lg-block  z-index-0" style="bottom:0; ">
            <div class="container d-flex flex-column justify-content-center align-items-center text-center py-5">
                <h1 class="fw-bolder text-white" style="font-size: 56px">Reset Password</h1>
            </div>
        </div>

        <div class="position-relative overflow-hidden radial-gradient d-flex align-items-center justify-content-center">
            <div class="container ">
                <div class="row justify-content-center my-5">
                    <div class="col-md-8">
                        <div class="card mb-0">
                            <div class="card-body my-3">
                                <form method="POST" action="{{ route('password.update') }}">
                                    @csrf

                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="row mb-3">
                                        <label for="email"
                                            class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ $email ?? old('email') }}" required autocomplete="email"
                                                autofocus>

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                required autocomplete="new-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="password-confirm"
                                            class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control"
                                                name="password_confirmation" required autocomplete="new-password">
                                        </div>
                                    </div>

                                    <div class="row mb-0">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Reset Password') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
