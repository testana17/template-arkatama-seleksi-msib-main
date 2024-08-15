@can($globalModule['create'])
    <x-mollecules.modal id="add-role_modal" action="{{ route('users.role.store') }}" tableId="roles-table">
        <x-slot:title>Menambah Role</x-slot:title>
        <div class="mb-3">
            <x-atoms.form-label for="name_field" required>Nama</x-atoms.form-label>
            <x-atoms.input name="name" id="name_field" placeholder="Masukkan Nama" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label for="guard_field" required>Nama Guard</x-atoms.form-label>
            <x-atoms.select :lists="[
                'web' => 'web',
                'api' => 'api',
            ]" value="" name="guard_name" id="guard_field"
                placeholder="Pilih Guard"></x-atoms.select>
        </div>
        <x-slot:footer>
            <button type="submit" class="btn btn-primary">Tambahkan Role</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
@can($globalModule['update'])
    <x-mollecules.modal id="edit-role_modal" action="/users/role/{id}" tableId="roles-table" method="PUT">
        <x-slot:title>Edit Role</x-slot:title>
        <div class="mb-3">
            <x-atoms.form-label for="name_field" required>Nama</x-atoms.form-label>
            <x-atoms.input name="name" id="name_field" placeholder="Masukkan Nama" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label for="guard_field" required>Nama Guard</x-atoms.form-label>
            <x-atoms.select :lists="[
                'web' => 'web',
                'api' => 'api',
            ]" value="" name="guard_name" id="guard_field2"
                placeholder="Pilih Guard"></x-atoms.select>
        </div>
        <x-slot:footer>
            <button type="submit" class="btn btn-primary">Simpan Role</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
