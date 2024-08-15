@extends('layouts.app')

@can($globalModule['update'])
    @section('title', 'Backup List')

@section('content')
    <div class="py-4">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('setting.backup.update', ['backupSchedule' => $backupSchedule]) }}" method="post"
                    id="edit-backup_form">
                    @method('PUT')
                    @csrf
                    <div class="mb-3">
                        <x-atoms.form-label for="name_field" required>Nama Backup</x-atoms.form-label>
                        <x-atoms.input name="name" id="name_field" placeholder="Masukkan Nama Backup" :value="$backupSchedule->name" />
                    </div>
                    <div class="mb-3">
                        <x-atoms.form-label for="frequency_field" required>Frekuensi Backup</x-atoms.form-label>
                        <x-atoms.select name="frequency" id="frequency_field" placeholder="Pilih Frekuensi"
                            :value="$backupSchedule->frequency" :lists="[
                                'daily' => 'Daily',
                                'weekly' => 'Weekly',
                                'monthly' => 'Monthly',
                            ]" />
                    </div>
                    <div class="mb-3">
                        <x-atoms.form-label for="time_field" required>Waktu Backup</x-atoms.form-label>
                        <x-atoms.input name="time" id="time_field" type="time" placeholder="Tentukan Waktu Backup"
                            :value="$backupSchedule->time" />
                    </div>
                    <div class="mb-3">
                        <label for="tables" class="form-label required">Tabel</label>
                        <x-mollecules.checkbox-group :lists="$ref_tables" :values="$curr_tables" name="tables"
                            childClass="col-sm-12 col-md-6 col-lg-4" />
                    </div>
                    <div class="d-flex justify-content-end g-2 mb-10">
                        <a href="{{ route('setting.backup.index') }}" class="btn btn-light me-3" cancel-btn>
                            <i class="fas fa-arrow-left me-3"></i>
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-3"></i>
                            Simpan Backup
                        </button>
                    </div>

                </form>


            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function toggleTables() {
            const checkboxes = document.getElementsByName('tables[]');
            const checkAll = document.getElementById('flexCheckDefault');
            for (let i = 0; i < checkboxes.length; i++) {
                if (checkAll.checked) {
                    checkboxes[i].checked = true;
                } else {
                    checkboxes[i].checked = false;
                }
            }
        };

        $(function() {
            $(document).on(`form-submitted:edit-backup_form`, function() {
                window.location.href = `{{ route('setting.backup.index') }}`
            })
        });
    </script>
@endpush
@endcan
