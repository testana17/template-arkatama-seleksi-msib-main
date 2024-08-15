@extends('layouts.guest')
@section('content')
    <section class="px-5 row">
        <article class="col-lg-8 pt-3 pb-5 ps-5 pe-5 ">
            @if(request()->has('search'))
                <h1 class="col-md-8 mt-4 fw-semibold fs-12 text-start" style="color: #232933;">Hasil Pencarian: "{{ request()->input('search') }}"</h1>
            @else
                <h1 class="col-md-8 mt-4 fw-semibold fs-12 text-start" style="color: #232933;">Berita Terbaru</h1>
            @endif
            <div class="container row">
                @forelse ($newsList as $news)
                    <div class="card shadow my-4 p-0 rounded" data-aos="fade-up" data-aos-delay="300" data-aos-duration="900">
                        <img src="{{ getFileInfo($news->thumbnail)['preview'] }}"
                            class="card-img-top rounded-top object-fit-cover" alt="{{ $news->title }}"
                            style="height: 24rem">
                        <div class="card-body">
                            <h3 class="card-title fw-semibold py-1"><a class="text-black"
                                    href="{{ route('news.detail', $news->id) }}">{{ $news->title }}</a></h3>
                            <p class="fs-3">
                                <span><i class="fs-5 ti ti-user pe-2"></i>{{ $news->author->name }}</span>
                                <span><i
                                        class="fs-5 ti ti-calendar-time px-2"></i>{{ \Carbon\Carbon::parse($news->created_at)->translatedFormat('d F Y') }}</span>
                                <span><i
                                        class="ti fs-5 ti-clock px-2"></i>{{ \Carbon\Carbon::parse($news->created_at)->translatedFormat('H:i') }}</span>
                            </p>
                            <p class="fs-3">
                                <span>
                                    <i class="ti ti-folder pe-2 text-black fw-bold"></i>
                                    {{ $news->kategori->name }}
                                </span>
                            </p>
                            <p class="card-text ">
                                <a class="text-dark" style ="font-size:14px" <a
                                    href="{{ route('news.detail', $news->id) }}">{!! Str::limit($news->description, 100, '...') !!}</a>
                            </p>
                            <p class="text-end px-md-5 py-0">
                                <a href="{{ route('news.detail', $news->id) }}" class="btn-primary btn px-3 py-2">Baca
                                    Selengkapnya</a>
                            </p>
                        </div>
                    </div>
                @empty
                    <p>Tidak ada berita yang ditemukan.</p>
                @endforelse
            </div>
            <div class="d-flex justify-content-md-center">
                {{ $newsList->links('pages.landing.news.partials.pagination') }}
            </div>

        </article>

        <article class= " col-lg-4 pt-5 pb-5 ps-0 pe-5 container">

            <div class="card rounded-0" style="background-color:var(--bs-gray-100);">
                <div class ="container p-4">
                    <form action="{{ route('news.search') }}" method="GET" id="searchForm" custom-action>
                        <div class="input-group mb-3 py-3">
                            <input type="text" class="border border-dark-subtle opacity-50 form-control"
                                placeholder="Search" name="search" value="{{ old('cari') }}" aria-label="Search"
                                aria-describedby="basic-addon2" id="searchInput">
                            <button type="submit" class="border-0 input-group-text bg-primary" id="basic-addon2"><i
                                    style="font-size: 25px;" class="text-white ti ti-search"></i></button>
                        </div>
                    </form>
                    <h4 class="text-black-60 fw-bold">Kategori</h4>
                    <hr>
                    <div class="card mb-3 bg-transparent my-2 mx-1" style="box-shadow:none; margin-bottom: 10px;">
                        @foreach ($categoryNewsList as $categoryNews)
                            <p class ="py-0 px-2 my-2"><a href="{{ route('news.kategori', $categoryNews->id) }}"
                                    class="fs-4">
                                    <i class="ti ti-folder px-2 text-black fw-bold"></i>
                                    {{ $categoryNews->name }}</a></p>
                        @endforeach
                    </div>
                    <hr>
                    <h4 class ="text-black-60 fw-bold">Trending</h4>
                    <hr>
                    @foreach ($randomNewsList as $latestNews)
                        <div class="card bg-transparent my-4" style="max-width: 540px;">
                            <div class="row g-0">
                                <div class="col-md-5">
                                    <img src="{{ getFileInfo($latestNews->thumbnail)['preview'] }}"
                                        class="img-fluid w-100 rounded-start object-fit-cover"  style="height:6rem;" alt="{{ $latestNews->title }}">
                                </div>
                                <div class="col-md-7">
                                    <div class="m-0 p-2 card-body">
                                        <h5 class="card-title fs-2">
                                            <a class="ms-2" href="{{ route('news.detail', $latestNews->id) }}">
                                                {{ $latestNews->title }}
                                            </a>
                                        </h5>
                                        <p class="m-0">
                                            <span class="fs-1">
                                                <i class="ti ti-user px-2 text-black fw-bold"></i>
                                                {{ $latestNews->author->name }}
                                            </span>
                                            <span class="fs-1">
                                                <i class="ti ti-calendar-time px-2 text-black fw-bold"></i>
                                                {{ \Carbon\Carbon::parse($latestNews->created_at)->translatedFormat('d F Y') }}
                                            </span>
                                        </p>
                                        <p class="m-0">
                                            <span class="fs-1">
                                                <i class="ti ti-folder px-2 text-black fw-bold"></i>
                                                {{ $latestNews->kategori->name }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </article>

    </section>
@endsection
