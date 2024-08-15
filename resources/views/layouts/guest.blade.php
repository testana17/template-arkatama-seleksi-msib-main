<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--  Title -->
    <title>E-RPL Link</title>
    <!--  Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{asset("landing/images/logos/favicon.ico")}}">
    <!--  Aos -->
    <link rel="stylesheet" href="{{asset("landing/libs/aos/dist/aos.css")}}">
    <link rel="stylesheet" href="{{asset("landing/libs/owl.carousel/dist/assets/owl.carousel.min.css")}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" />

    <link id="themeColors" rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{asset("landing/css/style.css")}}">
    <link rel="stylesheet" href="{{asset("landing/css/app.css")}}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    @stack('vendor-css')
    @stack('css')
</head>

<body>
    <div class="page-wrapper p-0 overflow-hidden">
        @include('layouts.partials.landing-header')
        @yield('content')
    </div>
    @include('layouts.partials.landing-footer')

    
    <script src="{{asset("landing/libs/jquery/dist/jquery.min.js")}}"></script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/datatables.bundle.js') }}"></script>

    @stack('vendor-scripts')
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.init.js') }}"></script>
    <script src="{{asset("landing/libs/aos/dist/aos.js")}}"></script>
    <script src="{{asset("landing/libs/owl.carousel/dist/owl.carousel.min.js")}}"></script>
    <script src="{{ asset('assets/js/dashboard.js')}}"></script>
    <script src="{{asset("landing/js/custom.js")}}"></script>
    <script src="{{asset("assets/js/custom.js")}}"></script>
    
    @stack('scripts')
    <script>
        AOS.init();
    </script>
</body>

</html>
