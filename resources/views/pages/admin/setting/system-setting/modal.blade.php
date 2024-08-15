@can($globalModule['create'])
    <x-mollecules.modal id="add-setting_modal" action="{{ route('setting.system-setting.store') }}"
        tableId="system-setting-table">
        <x-slot:title> Tambah System Setting</x-slot:title>
        <div class="mb-3">
            <x-atoms.form-label for="name_field" required>Nama</x-atoms.label>
                <x-atoms.input type="text" id="name_field" name="name" class="form-control" placeholder="Masukkan Nama" />
                <div class="invalid-feedback"></div>
        </div>
        <div class="mb-3">
            <x-atoms.form-label for="value_field" required>Value</x-atoms.label>
                <x-atoms.input type="text" id="value_field" name="value" class="form-control"
                    placeholder="Masukkan Value" />
                <div class="invalid-feedback"></div>
        </div>
        <div class="mb-3">
            <x-atoms.form-label for="description_field">Deskripsi</x-atoms.label>
                <x-atoms.textarea id="description_field" name="description" placeholder="Masukkan Deskripsi" />
                <div class="invalid-feedback"></div>
        </div>
        <x-slot:footer>
            <button class="btn-primary btn" type="submit" data-text="Save" data-text-loading="Saving..">Tambahkan
                Setting</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
@can($globalModule['update'])
    <x-mollecules.modal id="edit-setting_modal" action="/setting/system-setting/{id}" method="PUT"
        tableId="system-setting-table">
        <x-slot:title>Edit System Setting</x-slot:title>
        <div class="mb-3">
            <x-atoms.form-label for="name_field" required>Nama</x-atoms.label>
                <x-atoms.input type="text" id="name_field" name="name" class="form-control"
                    placeholder="Masukkan Nama" />
                <div class="invalid-feedback"></div>
        </div>
        <div class="mb-3">
            <x-atoms.form-label for="value_field" required>Value</x-atoms.label>
                <x-atoms.input type="text" id="value_field" name="value" class="form-control"
                    placeholder="Masukkan Value" />
                <div class="invalid-feedback"></div>
        </div>
        <div class="mb-3">
            <x-atoms.form-label for="description_field" required>Deskripsi</x-atoms.label>
                <x-atoms.textarea id="description_field" name="description" placeholder="Masukkan Deskripsi" />
                <div class="invalid-feedback"></div>
        </div>
        <x-slot:footer>
            <button class="btn-primary btn" type="submit" data-text="Save" data-text-loading="Saving..">Simpan
                Setting</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
