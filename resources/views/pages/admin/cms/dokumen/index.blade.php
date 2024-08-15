@extends('layouts.app')

@section('content')
    @include('pages.admin.cms.dokumen.partials.modals')

    <div class="app-container">
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
        <div class="py-4">
            <div class="d-flex flex-md-row flex-column gap-4  align-items-center justify-content-between">
                <div class="search-box">
                    <label class="position-absolute " for="searchBox">
                        <i class="fal fa-search fs-3"></i>
                    </label>
                    <input type="text" data-table-id="dokumen-table" id="searchBox" data-action="search"
                        class="form-control form-control-solid w-250px ps-13" placeholder="Cari Dokumen" />
                </div>
                <div class="d-flex flex-row justify-content-center justify-content-md-end w-100 gap-1">
                    @if (request()->routeIs('cms.document.histori'))
                        <a href="{{ route('cms.document.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left fs-3"></i>
                            <span class="ms-2">
                                Kembali
                            </span>
                        </a>
                    @else
                        @can($globalModule['create'])
                            <button data-bs-toggle="modal" data-bs-target="#add-dokumen-modal" class="btn btn-primary">
                                <i class="fal fa-plus fa-sm fs-4"></i>
                                <span class="ms-2">Tambah</span>
                            </button>
                        @endcan
                        @can($globalModule['read'])
                            <a class="btn btn-warning" href="{{ route('cms.document.histori') }}">
                                <i class="fas fa-trash-restore fs-4"></i>
                                <span class="ms-2">Riwayat Terhapus</span>
                            </a>
                        @endcan
                    @endif
                </div>
            </div>
            <div class="table-relative">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush
@endsection
