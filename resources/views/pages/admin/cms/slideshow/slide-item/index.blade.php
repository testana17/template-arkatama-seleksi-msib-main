@extends('layouts.app')

@section('content')
    @include('pages.admin.cms.slideshow.slide-item.partials.modals')

    <div class="app-container">
        <div class="py-4">
            @if (!request()->routeIs('cms.slideshow-item.histori'))
                <h5 class="mb-4">
                    <b>
                        Slide Item {{ $slideshow->name }}
                    </b>
                </h5>
            @endif
            <div class="d-flex align-items-center justify-content-between position-relative mb-3">
                <div class="search-box">
                    <label class="position-absolute " for="searchBox">
                        <i class="fal fa-search fs-3"></i>
                    </label>
                    <input type="text" data-table-id="slideshowitem-table" id="searchBox" data-action="search"
                        class="form-control form-control-solid w-250px ps-13" placeholder="Cari Slide Item" />
                </div>
                <div class="d-flex gap-2">
                    @if (request()->routeIs('cms.slideshow-item.histori'))
                        <a href="{{ route('cms.slideshow-item.index', ['slideshow' => $slideshow]) }}"
                            class="btn btn-primary">
                            <i class="fas fa-arrow-left fs-3"></i>
                            <span class="ms-2">
                                Kembali
                            </span>
                        </a>
                    @else
                        <a href="{{ route('cms.slideshow.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left fs-3"></i>
                            <span class="ms-2">
                                Kembali ke Slide Show
                            </span>
                        </a>
                        @can($globalModule['create'])
                            <button data-bs-toggle="modal" data-bs-target="#slideshowitem_modal" class="btn btn-primary">
                                <i class="fas fa-plus fs-3"></i>
                                <span class="ms-2">Tambah</span>
                            </button>
                        @endcan
                        @can($globalModule['read'])
                            <a href="{{ route('cms.slideshow-item.histori', ['slideshow' => $slideshow]) }}"
                                class="btn btn-warning">
                                <i class="fas fa-trash-restore fs-3"></i>
                                <span class="ms-2">
                                    Riwayat Terhapus
                                </span>
                            </a>
                        @endcan
                    @endif
                </div>
            </div>

        </div>

        <div class="table-relative">
            {{ $dataTable->table() }}
        </div>

    </div>


    @push('scripts')
        {{ $dataTable->scripts() }}

        <script>
            let inputNama = document.getElementById('title');
            let inputNama2 = document.getElementById('title_2');
            let inputDeskripsi = document.getElementById('caption');
            let inputDeskripsi2 = document.getElementById('caption_2');

            inputNama.addEventListener('input', function(event) {
                validateInputNameJenjang(event.target);
            });

            inputDeskripsi.addEventListener('input', function(event) {
                validateInputNameJenjang(event.target);
            });

            inputNama2.addEventListener('input', function(event) {
                validateInputNameJenjang(event.target);
            });

            inputDeskripsi2.addEventListener('input', function(event) {
                validateInputNameJenjang(event.target);
            });
        </script>
    @endpush
@endsection
