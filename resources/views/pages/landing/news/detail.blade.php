@extends('layouts.guest')
@section('content')
    <section class="px-5 row">

        <article class="col-lg-8 pt-5 pb-5 ps-5 pe-5 container">
            <div class="container row justify-content-center">
                <p class="fs-3">
                    <span><i class="ti fs-5 ti-user px-2"></i>{{ $detailNews->author->name }}</span>
                    <span><i
                            class="ti fs-5 ti-calendar-time px-2"></i>{{ \Carbon\Carbon::parse($detailNews->created_at)->translatedFormat('d F Y') }}</span>
                    <span><i
                            class="ti fs-5 ti-clock px-2"></i>{{ \Carbon\Carbon::parse($detailNews->created_at)->translatedFormat('H:i') }}</span>
                </p>
                <h3 class="fw-semibold fs-10 my-3">{{ $detailNews->title }}</h3>
                <img src="{{ getFileInfo($detailNews->thumbnail)['preview'] }}" class="img-fluid rounded"
                    alt="{{ $detailNews->title }}">
                <div class="fs-4 my-4">{!! $detailNews->description !!}</div>
            </div>
        </article>

        <article class="col-lg-4 pt-5 pb-5 ps-0 pe-5 container">
            <div class="card rounded-0 bg-light-subtle">
                <div class="container p-4">
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
                                        class="img-fluid h-100 rounded-start" alt="{{ $latestNews->title }}">
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
                                        {{-- show kategori --}}
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
