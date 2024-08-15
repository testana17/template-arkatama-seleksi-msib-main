@extends('layouts.app')

@section('content')
    <div class="card-body py-4">
        <div class="d-flex flex-md-row flex-column gap-4  align-items-center justify-content-between">
            <div class="search-box">
                <label class="position-absolute " for="searchBox">
                    <i class="fal fa-search fs-3"></i>
                </label>
                <input type="text" data-table-id="news-table" id="searchBox" data-action="search"
                    class="form-control form-control-solid w-250px ps-13" placeholder="Cari Berita" />
            </div>
            @can($globalModule['create'])
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-news_modal">
                    <i class="fal fa-plus fs-3"></i>
                    <span class="ms-2">
                        Tambah
                    </span>
                </button>
            @endcan
        </div>
        <div class="table-responsive">
            {{ $dataTable->table() }}
        </div>
    </div>


    @include('pages.admin.cms.news.modal')

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script></script>
    @endpush
@endsection
