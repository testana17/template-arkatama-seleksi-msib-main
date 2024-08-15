@extends('layouts.app')

@section('content')
    <div class="card-body py-4">
        <div class="d-flex flex-md-row flex-column gap-4  align-items-center justify-content-between">
            <div class="search-box">
                <label class="position-absolute " for="searchBox">
                    <i class="fal fa-search fs-3"></i>
                </label>
                <input type="text" data-table-id="bobot-nilai-table" id="searchBox" data-action="search"
                    class="form-control form-control-solid w-250px ps-13" placeholder="Cari Bobot Nilai" />
            </div>
            <div class="d-flex  flex-row justify-content-center justify-content-md-end w-100 gap-1">
                @if (request()->routeIs('master.bobot-nilai.histori'))
                    <a href="{{ route('master.bobot-nilai.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left fs-3"></i>
                        <span class="ms-2">
                            Kembali
                        </span>
                    </a>
                @else
                    @can($globalModule['create'])
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-bobot-nilai_modal"
                            data-action="add" data-url="">
                            <i class="fal fa-plus fs-3"></i>
                            <span class="ms-2">
                                Tambah
                            </span>
                        </button>
                    @endcan
                    @can($globalModule['read'])
                        <a href="{{ route('master.bobot-nilai.histori') }}" class="btn btn-warning">
                            <i class="fas fa-trash-restore fs-3"></i>
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


    @include('pages.admin.master.bobot-nilai.partials.modals')

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            const tableId = 'bobot-nilai-table';

            $(document).ready(function() {
                $('[data-kt-user-table-filter="search"]').on('input', function() {
                    window.LaravelDataTables[`${tableId}`].search($(this).val()).draw();
                });
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#nilai_min').on('input', function() {
                    validateMaxValue();
                });

                $('#nilai_max').on('input', function() {
                    validateMaxValue();
                });

                function validateMaxValue() {
                    var minValue = parseFloat($('#nilai_min').val());
                    var maxValue = parseFloat($('#nilai_max').val());

                    if (maxValue < minValue) {
                        $('#nilai_max').val(minValue);
                    }
                }

                $('#nilai_min_2').on('input', function() {
                    validateMaxValueEdit();
                });

                $('#nilai_max_2').on('input', function() {
                    validateMaxValueEdit();
                });

                function validateMaxValueEdit() {
                    var minValue = parseFloat($('#nilai_min_2').val());
                    var maxValue = parseFloat($('#nilai_max_2').val());

                    if (maxValue < minValue) {
                        $('#nilai_max_2').val(minValue);
                    }
                }

                let inputNilaiHuruf = document.getElementById('nilai_huruf');
                let inputNilaiHuruf2 = document.getElementById('nilai_huruf_2');

                inputNilaiHuruf.addEventListener('input', function(event) {
                    event.target.value = event.target.value.replace(/[^a-zA-Z]/g, "");
                });

                inputNilaiHuruf2.addEventListener('input', function(event) {
                    event.target.value = event.target.value.replace(/[^a-zA-Z]/g, "");
                });

                $('.nilai-input').on('input', function() {
                    if ($(this).val() > 100) {
                        $(this).val(100);
                    }
                });
            });
        </script>
    @endpush
@endsection
