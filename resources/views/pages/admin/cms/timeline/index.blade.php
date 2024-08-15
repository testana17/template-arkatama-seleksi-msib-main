@extends('layouts.app')

@section('content')
    @include('pages.admin.cms.timeline.partials.modals')

    <div class="app-container">
        <div class="py-4">
            <div class="d-flex flex-md-row flex-column gap-4  align-items-center justify-content-between">
                <div class="search-box">
                    <label class="position-absolute " for="searchBox">
                        <i class="fal fa-search fs-3"></i>
                    </label>
                    <input type="text" data-table-id="timeline-table" id="searchBox" data-action="search"
                        class="form-control form-control-solid w-250px ps-13" placeholder="Cari Timeline" />
                </div>
                <div class="d-flex flex-row justify-content-center justify-content-md-end w-100 gap-1">
                    @if (request()->routeIs('cms.timeline.histori'))
                        <a href="{{ route('cms.timeline.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left fs-3"></i>
                            <span class="ms-2">
                                Kembali
                            </span>
                        </a>
                    @else
                        @can($globalModule['create'])
                            <button data-bs-toggle="modal" data-bs-target="#timeline_modal" class="btn btn-primary">
                                <i class="fas fa-plus fs-3"></i>
                                <span class="ms-2">Tambah</span>
                            </button>
                        @endcan
                        @can($globalModule['read'])
                            <a href="{{ route('cms.timeline.histori') }}" class="btn btn-warning">
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

        <div class="table-responsive">
            {{ $dataTable->table() }}
        </div>

    </div>


    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush
@endsection
