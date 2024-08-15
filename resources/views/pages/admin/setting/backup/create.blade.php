@extends('layouts.app')

@can($globalModule['create'])
@section('title', 'Create Backup')

    @section('content')
        <div class="py-4">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('setting.backup.store') }}" method="post" id="add-backup_form">
                        @csrf
                        <div class="mb-3">
                            <x-atoms.form-label for="name_field" required>Nama Backup</x-atoms.form-label>
                            <x-atoms.input name="name" id="name_field" placeholder="Masukkan Nama Backup" />
                        </div>
                        <div class="mb-3">
                            <x-atoms.form-label for="frequency_field" required>Frekuensi Backup</x-atoms.form-label>
                            <x-atoms.select name="frequency" id="frequency_field" placeholder="Pilih Frekuensi"
                                :lists="[
                                    'daily' => 'Daily',
                                    'weekly' => 'Weekly',
                                    'monthly' => 'Monthly',
                                ]" />
                        </div>
                        <div class="mb-3">
                            <x-atoms.form-label for="time_field" required>Waktu Backup</x-atoms.form-label>
                            <x-atoms.input name="time" id="time_field" type="time" placeholder="Tentukan Waktu Backup" />
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <label for="tables" class="form-label required">Tabel</label>
                                <div class="form-check ms-2">
                                    <input class="form-check-input" type="checkbox" value="all" id="flexCheckDefault"
                                        onchange="toggleTables()" />
                                    <label class="form-check-label" for="flexCheckDefault">Pilih Semua</label>
                                </div>
                            </div>
                            <x-mollecules.checkbox-group name="tables" class="gy-2" childClass="col-sm-12 col-md-6 col-lg-4"
                                :lists="$parsedTables">
                            </x-mollecules.checkbox-group>
                        </div>
                        <div class="d-flex justify-content-end mb-10">
                            <a href="{{ route('setting.backup.index') }}" class="btn btn-light me-3">
                                <i class="fas fa-arrow-left me-3"></i>
                                Kembali</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-3"></i>
                                Tambahkan Backup</button>
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
                $(document).on(`form-submitted:add-backup_form`, function() {
                    window.location.href = `{{ route('setting.backup.index') }}`
                })
            });
        </script>
    @endpush
@endcan
