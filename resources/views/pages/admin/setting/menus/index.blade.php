@extends('layouts.app')

@section('content')
    <div class="mb-2 mt-3">
        <div class="d-flex flex-md-row flex-column gap-4  align-items-center justify-content-between">
            <div class="search-box">
                <label class="position-absolute " for="searchBox">
                    <i class="fal fa-search fs-3"></i>
                </label>
                <input type="text" data-table-id="menus-table" id="searchBox" data-action="search"
                    class="form-control form-control-solid w-250px ps-13" placeholder="Cari Menu" />
            </div>
            <div class="d-flex flex-row justify-content-center justify-content-md-end w-100 gap-1">
                @can($globalModule['read'])
                    <form action="{{ route('setting.menus.exportjson') }}" method="post" custom-action>
                        @csrf
                        <button type="submit" class="btn btn-success me-3">
                            <i class="fas fa-file"></i>
                            <span class="ms-2">Export Menus as json</span>
                        </button>
                    </form>
                @endcan

                @can($globalModule['create'])
                    <a href="{{ route('setting.menus.create') }}" class="ms-2">
                        <button type="button" class="btn btn-primary">
                            <i class="fas fa-plus fs-3"></i>
                            <span class="ms-2">Tambah</span>
                        </button>
                    </a>
                @endcan
            </div>
        </div>

    </div>

    <div class="card-body py-4">
        <div class="table-responsive">
            {{ $dataTable->table() }}
        </div>
    </div>


    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush
@endsection
