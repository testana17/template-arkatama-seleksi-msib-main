@extends('layouts.app')
@can($globalModule['read'])
    @section('content')
        <div class="d-flex justify-content-between mt-4 mb-2">
            <div class="search-box">
                <label class="position-absolute " for="searchBox">
                    <i class="fal fa-search fs-3"></i>
                </label>
                <input type="text" data-table-id="sitesetting-history-table" id="searchBox" data-action="search"
                    class="form-control form-control-solid w-250px ps-13" placeholder="Cari Setting" />
            </div>
            <a href="{{ route('setting.site-settings.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left fs-3"></i>
                <span class="ms-2">Kembali</span>
            </a>
        </div>
        <div class="table-responsive">
            {{ $dataTable->table() }}
        </div>
    @endsection

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            $(document).ready(function() {
                $(document).on('action-confirmed:restore', function() {
                    window.LaravelDataTables['sitesetting-table'].ajax.reload();
                })
            });
        </script>
    @endpush
@endcan
