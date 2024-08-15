    @extends('layouts.guest')
    @section('content')
        <div class="body-wrapper overflow-hidden">
            <section class="hero-section position-relative d-flex overflow-hidden mb-0 mb-lg-11">
                @if ($slideitems)
                    <button class="d-none d-xl-block btn btn-lg owl-navigation owl-prev ms-3"><i
                            class="ti ti-chevron-left text-primary fs-10"></i></button>
                @endif
                <div class="container pt-3">
                    @if ($slideitems)
                        <div class="owl-carousel counter-carousel owl-theme">
                            <div class="item">
                    @endif
                    <div class="row align-items-center">
                        <div class="col-xl-6">
                            <div class="hero-content my-11 my-xl-0">
                                <div class=" d-grid gap-1 mb-2">
                                    <h1 class="fw-bolder fs-13" data-aos="fade-up" data-aos-delay="400"
                                        data-aos-duration="1000">
                                        Penerimaan Mahasiswa Baru</h1>
                                    <p class="fs-5 text-dark fw-normal" data-aos="fade-up" data-aos-delay="600"
                                        data-aos-duration="1000">Rekognisi Pembelajaran Lampau (RPL) Tahun Ajaran 2022/2023
                                    </p>
                                </div>
                                <div class="d-sm-flex align-items-center gap-3 z-index-1" data-aos="fade-up"
                                    data-aos-delay="800" data-aos-duration="1000">
                                    <a class="btn btn-primary btn-hover-shadow d-flex mb-3 mb-sm-0"
                                        href="{{ route('register') }}"><img
                                            src="{{ asset('landing/images/svgs/ph_sign-in-bold.svg') }}" width="24"
                                            height="24" class="w-20px h-20px me-2" alt="">Mendaftar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 d-none d-xl-block" data-aos="fade-left" data-aos-duration="1000">
                            <div class="image-container">
                                <img src="{{ asset('landing/images/hero-img/hero-image.png') }}" alt=""
                                    class="image">
                                <div class="overlay-top"></div>
                                <div class="overlay-right"></div>
                                <div class="overlay-bottom"></div>
                                <div class="overlay-left"></div>
                            </div>
                        </div>
                    </div>
                    @if ($slideitems)
                </div>
                @endif
                @foreach ($slideitems as $item)
                    <div class="item">
                        <div class="row align-items-center">
                            <div class="col-xl-6">
                                <div class="hero-content my-11 my-md-0">
                                    <div class="d-grid gap-1 mb-2 overflow-auto">
                                        <h1 class="fw-bolder fs-13" data-aos="fade-up" data-aos-delay="400"
                                            data-aos-duration="1000">{{ $item['title'] }}</h1>
                                        <p class="fs-5 text-dark fw-normal" data-aos="fade-up" data-aos-delay="600"
                                            data-aos-duration="1000">{{ $item['caption'] }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 d-none d-xl-block" data-aos="fade-left" data-aos-duration="1000">
                                <div class="image-container">
                                    <img src="{{ getFileInfo($item['image'])['preview'] }}" alt="" class="image">
                                    <div class="overlay-top"></div>
                                    <div class="overlay-right"></div>
                                    <div class="overlay-bottom"></div>
                                    <div class="overlay-left"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if ($slideitems)
        </div>
        @endif
        </div>
        @if ($slideitems)
            <button class="d-none d-xl-block btn btn-lg owl-navigation owl-next"><i
                    class="ti ti-chevron-right text-primary fs-10"></i></button>
        @endif
        </section>

        <section class="my-12" id="faq">
            <div class="container d-flex flex-column theFAQ " data-aos="fade" data-aos-duration="1000">
                <h1 class="fw-semibold fs-12 text-center">FaQ PMB Jalur RPL</h1>
                <div class="col-md-8 align-self-center pt-3 mx-2 mx-md-0">
                    @if ($faqs->count() > 0)
                        <div class="accordion accordion-flush" id="accordionExample">
                            @foreach ($faqs as $index => $faq)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $index }}">
                                        <button class="accordion-button collapsed accFAQ" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}"
                                            aria-expanded="false" aria-controls="collapse{{ $index }}">
                                            {{ $faq->question }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $index }}" class="accordion-collapse collapse accFAQdes"
                                        aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            {{ $faq->answer }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center">
                            Belum ada pertanyaan yang tersedia
                        </p>
                    @endif
                </div>
            </div>
        </section>

        </div>
        <footer class="footer-part pt-8 pb-5">
            <div class="container">

            </div>
        </footer>
        <div class="offcanvas offcanvas-start modernize-lp-offcanvas" tabindex="-1" id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header p-4">
                <img src="{{ asset('landing/images/logos/logo-erpl.svg') }}" alt="img-fluid" width="150">
            </div>
            <div class="offcanvas-body p-4">
                <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#" target="_blank">Alur
                            Pendaftaran</a>
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
    @endsection

    @push('scripts')
        <script>
            const accordionButtons = document.querySelectorAll('.accordion-button');

            accordionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const currentCollapse = document.getElementById(this.dataset.bsTarget);

                    this.classList.toggle('active');

                    if (currentCollapse.classList.contains('show')) {
                        currentCollapse.classList.remove('show');
                    } else {
                        accordionButtons.forEach(otherButton => {
                            if (otherButton !== button) {
                                const otherCollapse = document.getElementById(otherButton.dataset
                                    .bsTarget);
                                if (otherCollapse.classList.contains('show')) {
                                    otherCollapse.classList.remove('show');
                                    otherButton.classList.remove('active');
                                }
                            }
                        });
                        currentCollapse.classList.add('show');
                    }
                });
            });
            $(".owl-next").click(function() {
                $(".owl-carousel").trigger("next.owl.carousel");
            });

            $(".owl-prev").click(function() {
                $(".owl-carousel").trigger("prev.owl.carousel");
            });
        </script>
    @endpush
