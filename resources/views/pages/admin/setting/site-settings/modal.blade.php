@can($globalModule['create'])
    <x-mollecules.modal id="add-site-setting_modal" action="{{ route('setting.site-settings.store') }}"
        tableId="sitesetting-table">
        <x-slot:title>Tambah Site Setting</x-slot:title>
        <input type="hidden" name="id" id="id">
        <div class="mb-3">
            <x-atoms.form-label for="name_field" required>Nama</x-atoms.label>
                <x-atoms.input type="text" id="name_field" name="name" class="form-control"
                    placeholder="Masukkan Nama" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label for="type_field" required>Tipe</x-atoms.label>
                <x-atoms.select id="type_field" name="type" :lists="[
                    'site-identity' => 'Site Identity',
                    'hero' => 'Hero',
                    'profile' => 'Profile',
                ]"></x-atoms.select>
        </div>
        <div class="mb-3">
            <x-atoms.form-label for="value_field" required>Value</x-atoms.label>
                <x-atoms.input type="text" id="value_field" name="value" placeholder="Masukkan Value" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label for="description_field">Deskripsi</x-atoms.label>
                <x-atoms.textarea id="description_field" name="description" />
        </div>
        <x-slot:footer>
            <button class="btn-primary btn" type="submit" data-text="Save" data-text-loading="Saving">Tambahkan
                Setting</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
@can($globalModule['update'])
    <x-mollecules.modal id="edit-site-setting_modal" action="/setting/site-settings/{id}" method="PUT"
        tableId="sitesetting-table">
        <x-slot:title>Edit Site Setting</x-slot:title>
        <div class="mb-3">
            <x-atoms.form-label for="name_field2" required>Nama</x-atoms.label>
                <x-atoms.input type="text" id="name_field2" name="name" class="form-control"
                    placeholder="Masukkan Nama" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label for="type_field2" required>Tipe</x-atoms.label>
                <x-atoms.select id="type_field2" name="type" :lists="[
                    'site-identity' => 'Site Identity',
                    'hero' => 'Hero',
                    'profile' => 'Profile',
                ]"></x-atoms.select>
        </div>
        <div class="mb-3">
            <x-atoms.form-label for="value_field2" required>Value</x-atoms.label>
                <x-atoms.input type="text" id="value_field2" name="value" placeholder="Masukkan Value" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label for="description_field2">Deskripsi</x-atoms.label>
                <x-atoms.textarea id="description_field2" name="description" placeholder="Masukkan Deskripsi" />
        </div>
        <x-slot:footer>
            <button class="btn-primary btn" type="submit" data-text="Save" data-text-loading="Saving">Simpan
                Setting</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
