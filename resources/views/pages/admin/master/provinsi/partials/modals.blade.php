@can($globalModule['create'])
    <x-mollecules.modal id="add-provinsi_modal" action="{{ route('master.provinsi.store') }}" method="POST"
        data-table-id="provinsi-table" tableId="provinsi-table" hasCloseBtn="true">
        <x-slot:title>Tambah Provinsi</x-slot:title>
        <x-slot:footer>
            <button class="btn-primary btn" type="submit" data-text="Save" data-text-loading="Saving">Tambahkan
                Provinsi</button>
        </x-slot:footer>
        <div>
            <div class="mb-6">
                <x-atoms.form-label required>Kode Provinsi</x-atoms.form-label>
                <x-atoms.input id="kode" name="kode" type="text" class="form-control"
                    placeholder="Masukkan Kode Provinsi" />
            </div>
            <div class="mb-6">
                <x-atoms.form-label required>Nama Provinsi</x-atoms.form-label>
                <x-atoms.input id="nama" name="nama" type="text" class="form-control"
                    placeholder="Masukkan Nama Provinsi" />
            </div>
        </div>
    </x-mollecules.modal>
@endcan
@can($globalModule['update'])
    <x-mollecules.modal id="edit-provinsi_modal" action="/master/provinsi/{id}" method="PUT" tableId="provinsi-table"
        hasCloseBtn="true">
        <x-slot:title>Edit Provinsi</x-slot:title>
        <x-slot:footer>
            <button class="btn-primary btn" type="submit" data-text="Save" data-text-loading="Saving">Simpan
                Provinsi</button>
        </x-slot:footer>
        <div>
            <div class="mb-6">
                <input type="hidden" id="id" name="id" />
                <x-atoms.form-label required>Kode Provinsi</x-atoms.form-label>
                <x-atoms.input id="kode_2" name="kode" type="text" class="form-control"
                    placeholder="Masukkan Kode Provinsi" />
            </div>
            <div class="mb-6">
                <x-atoms.form-label required>Nama Provinsi</x-atoms.form-label>
                <x-atoms.input id="nama_2" name="nama" type="text" class="form-control"
                    placeholder="Masukkan Nama Provinsi" />
            </div>
        </div>
    </x-mollecules.modal>
@endcan
