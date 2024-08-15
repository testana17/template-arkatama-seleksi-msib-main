@can($globalModule['create'])
    <x-mollecules.modal id="add-jenjang-pendidikan_modal" action="{{ route('master.jenjang-pendidikan.store') }}"
        method="POST" data-table-id="jenjang-pendidikan-table" tableId="jenjang-pendidikan-table" hasCloseBtn="true">
        <x-slot:title>Tambah Jenjang Pendidikan</x-slot:title>
        <x-slot:footer>
            <button class="btn-primary btn" type="submit" data-text="Save" data-text-loading="Saving">Tambahkan
                Jenjang</button>
        </x-slot:footer>
        <div>
            <div class="mb-6">
                <x-atoms.form-label required>Kode Jenjang Pendidikan</x-atoms.form-label>
                <x-atoms.input id="kode" name="kode" type="text" class="form-control"
                    placeholder="Masukkan Kode Jenjang Pendidikan" />
            </div>
            <div class="mb-6">
                <x-atoms.form-label required>Nama Jenjang Pendidikan</x-atoms.form-label>
                <x-atoms.input id="nama" name="nama" type="text" class="form-control"
                    placeholder="Masukkan Nama Jenjang Pendidikan" />
            </div>
        </div>
    </x-mollecules.modal>
@endcan
@can($globalModule['update'])
    <x-mollecules.modal id="edit-jenjang-pendidikan_modal" action="/master/jenjang/{id}" method="PUT"
        tableId="jenjang-pendidikan-table" hasCloseBtn="true">
        <x-slot:title>Edit Jenjang Pendidikan</x-slot:title>
        <x-slot:footer>
            <button class="btn-primary btn" type="submit" data-text="Save" data-text-loading="Saving">Simpan
                Jenjang</button>
        </x-slot:footer>
        <div>
            <div class="mb-6">
                <input type="hidden" id="id" name="id" />
                <x-atoms.form-label required>Kode Jenjang Pendidikan</x-atoms.form-label>
                <x-atoms.input id="kode_2" name="kode" type="text" class="form-control"
                    placeholder="Masukkan Kode Jenjang Pendidikan" />
            </div>
            <div class="mb-6">
                <x-atoms.form-label required>Nama Jenjang Pendidikan</x-atoms.form-label>
                <x-atoms.input id="nama_2" name="nama" type="text" class="form-control"
                    placeholder="Masukkan Nama Jenjang Pendidikan" />
            </div>
        </div>
    </x-mollecules.modal>
@endcan
