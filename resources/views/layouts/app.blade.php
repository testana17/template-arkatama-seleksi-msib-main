<!DOCTYPE html>
<html lang="en">

<head>
    <title>E-RPL Link</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Mordenize" />
    <meta name="author" content="" />
    <meta name="keywords" content="Mordenize" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/libs/datatables/datatables.bundle.css') }}"/> --}}
    <link id="themeColors" rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{asset("assets/css/app.css")}}">
    @stack('vendor-css')
    @stack('css')
</head>

<body style="overflow: hidden">
    <div class="preloader">
        <img src="{{ asset('assets/images/logos/logo-erpl.png') }}" alt="loader" style="width: 150px;"
            class="lds-ripple img-fluid" />
    </div>
    <div class="transparent-preloader">
        <img src="{{ asset('assets/images/logos/logo-erpl.png') }}" alt="loader" style="width: 150px;"
            class="lds-ripple img-fluid" />
    </div>
    <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        {{-- <x-header /> --}}
        <aside class="left-sidebar pt-4 theSidebar-height">
            <div>
                @include('layouts.partials.sidebar')
            </div>
        </aside>

        <div class="body-wrapper">
            <div class="container-xxl" style="max-width: 1560px; height:100vh;">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dark-transparent sidebartoggler"></div>
    <div class="dark-transparent sidebartoggler"></div>
    </div>

    @include('layouts.partials.header-mobile')


    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/datatables.bundle.js') }}"></script>

    @stack('vendor-scripts')
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.init.js') }}"></script>
    <script src="{{ asset('assets/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>

    <script src="{{asset("landing/libs/aos/dist/aos.js")}}"></script>
    <script src="{{asset("landing/libs/owl.carousel/dist/owl.carousel.min.js")}}"></script>

    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('landing/js/custom.js') }}"></script>
    <script src="{{ asset('assets/libs/fontawesome/js/all.min.js') }}"></script>

    @stack('scripts')
    <script>
        AOS.init();
    </script>
</body>

</html>
