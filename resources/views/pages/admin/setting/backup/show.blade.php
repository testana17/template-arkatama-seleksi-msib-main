@extends('layouts.app')

@section('title', 'Backup List')

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div class="app-toolbar py-3 py-lg-6">
            <div class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">

                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Backup {{ $backupSchedule->name }}
                    </h1>

                </div>
            </div>
        </div>
        <div class="app-container container-xxl">
            <div class="card">
                <div class="card-body py-4">
                    <form action="{{ route('setting.backup.update', ['backupSchedule' => $backupSchedule]) }}" method="post"
                        id="update-backup-form">
                        @method('PUT')
                        @csrf
                        <div class="mb-3">
                            <x-atoms.form-label for="name_field" required>Nama Backup</x-atoms.form-label>
                            <x-atoms.input name="name" id="name_field" placeholder="Masukkan Nama Backup"
                                :value="$backupSchedule->name" readonly />
                        </div>
                        <div class="mb-3">
                            <x-atoms.form-label for="frequency_field" required>Frekuensi Backup</x-atoms.form-label>
                            <x-atoms.input name="frequenc" id="frequency_field" placeholder="" readonly :value="$backupSchedule->frequency" />
                        </div>
                        <div class="mb-3">
                            <x-atoms.form-label for="time_field" required>Waktu Backup</x-atoms.form-label>
                            <x-atoms.input name="time" id="time_field" type="time" placeholder="Tentukan Waktu Backup"
                                :value="$backupSchedule->time" readonly />
                        </div>
                        <div class="mb-3">
                            <label for="tables" class="form-label required">Tabel</label>
                            <x-mollecules.checkbox-group :lists="$ref_tables" :values="$curr_tables" name="tables"
                                childClass="col-sm-12 col-md-6 col-lg-4" disabled />
                        </div>

                    </form>
                    <div class="d-flex justify-content-end g-2 mb-10">
                        <form id="delete-form"
                            action="{{ route('setting.backup.destroy', ['backupSchedule' => $backupSchedule]) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mx-2">
                                <i class="ki-outline ki-trash">
                                </i>
                                Hapus
                            </button>
                        </form>
                        <a href="{{ route('setting.backup.edit', ['backupSchedule' => $backupSchedule]) }}">
                            <button class="btn btn-warning mx-2">
                                <i class="ki-outline ki-message-edit">

                                </i>
                                Edit
                            </button>
                        </a>
                        <form id="do-backup-form"
                            action="{{ route('setting.backup.run', ['backupSchedule' => $backupSchedule]) }}"
                            method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info mx-2">
                                <i class="ki-outline ki-arrows-loop">
                                </i>
                                Backup Sekarang
                            </button>
                        </form>

                    </div>
                </div>
            </div>
            <div class="card my-2">
                <div class="card-body">
                    <h2 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Histori Backup
                    </h2>
                    <input type="text" data-table-id="backuphistory-table" data-kt-user-table-filter="search"
                        class="form-control form-control-solid w-250px ps-13 my-2" placeholder="Search Backup"
                        id="backup-table-search" />
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{ $dataTable->scripts() }}

    <script>
        function submitUpdateForm() {
            document.getElementById('update-backup-form').submit();
        }
        $(document).on("form-submitted:do-backup-form", function(ev) {
            window.location.reload()
        });
        $(document).ready(function() {
            $('#backup-table-search').on('input', function() {
                const tableId = $(this).data('table-id');
                window.LaravelDataTables[`${tableId}`].search($(this).val()).draw();

            });

        });
    </script>
@endpush
