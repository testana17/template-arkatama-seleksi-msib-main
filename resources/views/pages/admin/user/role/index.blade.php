@extends('layouts.app')

@section('content')
    @include('pages.admin.user.role.partials.modals')
    <div class="app-container">
        <div class="py-4">
            <div class="d-flex flex-md-row flex-column gap-4  align-items-center justify-content-between">
                <div class="search-box">
                    <label class="position-absolute " for="searchBox">
                        <i class="fal fa-search fs-3"></i>
                    </label>
                    <input type="text" data-table-id="roles-table" id="searchBox" data-action="search"
                        class="form-control form-control-solid w-250px ps-13" placeholder="Cari Role" />
                </div>
                <div class="d-flex flex-row justify-content-center justify-content-md-end w-100 gap-1">
                    @can($globalModule['read'])
                        <form action="{{ route('users.permission.export') }}" method="post" custom-action>
                            @csrf
                            <button type="submit" class="btn btn-success me-3">
                                <i class="fas fa-file"></i>
                                <span class="ms-2">Export Permissions as json</span>
                            </button>
                        </form>
                    @endcan
                    @can($globalModule['create'])
                        <button type="button" data-bs-toggle="modal" data-bs-target="#add-role_modal" class="btn btn-primary">
                            <i class="fas fa-plus fs-3"></i>
                            <span class="ms-2">
                                Tambah
                            </span>
                        </button>
                    @endcan
                </div>
            </div>
            <div class="table-relative">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush
@endsection
