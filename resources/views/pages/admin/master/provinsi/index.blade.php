@extends('layouts.app')

@section('content')
    <div class="card-body py-4">
        <div class="d-flex flex-md-row flex-column gap-4  align-items-center justify-content-between">
            <div class="search-box">
                <label class="position-absolute " for="searchBox">
                    <i class="fal fa-search fs-3"></i>
                </label>
                <input type="text" data-table-id="provinsi-table" id="searchBox" data-action="search"
                    class="form-control form-control-solid w-250px ps-13" placeholder="Cari Provinsi" />
            </div>
            <div class="d-flex flex-row justify-content-center justify-content-md-end w-100 gap-1">
                @if (request()->routeIs('master.provinsi.histori'))
                    <a href="{{ route('master.provinsi.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left fs-3"></i>
                        <span class="ms-2">
                            Kembali
                        </span>
                    </a>
                @else
                    @can($globalModule['create'])
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-provinsi_modal"
                            data-action="add" data-url="">
                            <i class="fas fa-plus fs-3 me-2"></i>
                            <span class="ms-2">
                                Tambah
                            </span>
                        </button>
                    @endcan
                    @can($globalModule['read'])
                        <a href="{{ route('master.provinsi.histori') }}" class="btn btn-warning">
                            <i class="fas fa-trash-restore fs-3 me-2"></i>
                            <span class="ms-2">
                                Riwayat Terhapus
                            </span>
                        </a>
                    @endcan
                @endif
            </div>
        </div>
        <div class="table-responsive">
            {{ $dataTable->table() }}
        </div>
    </div>


    @include('pages.admin.master.provinsi.partials.modals')

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            const tableId = 'provinsi-table';

            $(document).ready(function() {
                $('[data-kt-user-table-filter="search"]').on('input', function() {
                    window.LaravelDataTables[`${tableId}`].search($(this).val()).draw();
                });
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

            });

            let inputNama = document.getElementById('nama');
            let inputNama2 = document.getElementById('nama_2');
            let inputKode = document.getElementById('kode');
            let inputKode2 = document.getElementById('kode_2');


            inputKode.addEventListener('input', function(event) {
                validateInputKode(event.target);
            });

            inputKode2.addEventListener('input', function(event) {
                validateInputKode(event.target);
            });

            inputNama.addEventListener('input', function(event) {
                validateInputChar(event.target);
            });

            inputNama2.addEventListener('input', function(event) {
                validateInputChar(event.target);
            });
        </script>
    @endpush
@endsection
