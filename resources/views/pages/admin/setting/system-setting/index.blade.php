@extends('layouts.app')

@section('content')
    @include('pages.admin.setting.system-setting.modal')

    <div class="d-flex flex-md-row flex-column gap-4  align-items-center justify-content-between">
        <div class="search-box">
            <label class="position-absolute " for="searchBox">
                <i class="fal fa-search fs-3"></i>
            </label>
            <input type="text" data-table-id="filemanagement-table" id="searchBox" data-action="search"
                class="form-control form-control-solid w-250px ps-13" placeholder="Cari Setting" />
        </div>
        <div class="d-flex flex-row justify-content-center justify-content-md-end mb-md-0 mb-2 w-100 gap-1">
            @can($globalModule['create'])
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-setting_modal">
                    <i class="fas fa-plus fs-3"></i>
                    <span class="ms-2">Tambah</span>
                </button>
            @endcan
            @can($globalModule['read'])
                <a href="{{ route('setting.system-setting.histori') }}" class="btn btn-warning">
                    <i class="fas fa-trash-restore fs-3"></i>
                    <span class="ms-2">Riwayat Terhapus</span>
                </a>
            @endcan
        </div>
    </div>
    <div class="table-responsive">
        {{ $dataTable->table() }}
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
