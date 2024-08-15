@can($globalModule['create'])
    <x-mollecules.modal id="add-bobot-nilai_modal" action="{{ route('master.bobot-nilai.store') }}" method="POST"
        data-table-id="bobot-nilai-table" tableId="bobot-nilai-table" hasCloseBtn="true">
        <x-slot:title>Tambah Bobot Nilai</x-slot:title>
        <x-slot:footer>
            <button class="btn-primary btn" type="submit" data-text="Save" data-text-loading="Saving">Tambahkan Bobot
                Nilai</button>
        </x-slot:footer>
        <div class="mb-6">
            <x-atoms.form-label required>Nilai Minimal</x-atoms.form-label>
            <x-atoms.input id="nilai_min" name="nilai_min" min="0" max="100" type="number" class="form-control nilai-input"
                placeholder="Masukkan Nilai Minimal" />
        </div>
        <div class="mb-6">
            <x-atoms.form-label required>Nilai Maximal</x-atoms.form-label>
            <x-atoms.input id="nilai_max" name="nilai_max" min="0" max="100" type="number" class="form-control nilai-input"
                placeholder="Masukkan Nilai Maximal" />
        </div>
        <div class="mb-6">
            <x-atoms.form-label required>Nilai Huruf</x-atoms.form-label>
            <x-atoms.input id="nilai_huruf" name="nilai_huruf" type="text" class="form-control"
                placeholder="Masukkan Nilai Huruf" />
        </div>
        {{-- <div class="mb-6">
        <x-atoms.form-label required>Status Nilai</x-atoms.form-label>
        <x-atoms.radio-group name="is_active" value="1" :lists="[
            '1' => 'Aktif',
            '0' => 'Nonaktif',
        ]"></x-atoms.radio-group>
    </div> --}}
    </x-mollecules.modal>
@endcan
@can($globalModule['update'])
    <x-mollecules.modal id="edit-bobot-nilai_modal" action="/master/bobot/{id}" method="PUT" tableId="bobot-nilai-table"
        hasCloseBtn="true">
        <x-slot:title>Edit Bobot Nilai</x-slot:title>
        <x-slot:footer>
            <button class="btn-primary btn" type="submit" data-text="Save" data-text-loading="Saving">Simpan Bobot
                Nilai</button>
        </x-slot:footer>
        <div class="mb-6">
            <input type="hidden" id="id" name="id" />
            <x-atoms.form-label required>Nilai Minimal</x-atoms.form-label>
            <x-atoms.input id="nilai_min_2" name="nilai_min" min="0" max="100" type="number" class="form-control nilai-input"
                placeholder="Masukkan Nilai Minimal" />
        </div>
        <div class="mb-6">
            <x-atoms.form-label required>Nilai Maximal</x-atoms.form-label>
            <x-atoms.input id="nilai_max_2" name="nilai_max" min="0" max="100" type="number" class="form-control nilai-input"
                placeholder="Masukkan Nilai Maximal" />
        </div>
        <div class="mb-6">
            <x-atoms.form-label required>Nilai Huruf</x-atoms.form-label>
            <x-atoms.input id="nilai_huruf_2" name="nilai_huruf" type="text" class="form-control"
                placeholder="Masukkan Nilai Huruf" />
        </div>
        <div class="mb-6">
            <x-atoms.form-label required>Status Nilai</x-atoms.form-label>
            <x-atoms.radio-group name="is_active" id="is_active_2" value="" :lists="[
                '1' => 'Aktif',
                '0' => 'Nonaktif',
            ]"></x-atoms.radio-group>
        </div>
    </x-mollecules.modal>
@endcan
