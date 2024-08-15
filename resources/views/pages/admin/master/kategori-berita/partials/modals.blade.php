@can($globalModule['create'])
    <x-mollecules.modal id="add-kategori_berita_modal" action="{{ route('master.kategori-berita.store') }}" method="POST"
        data-table-id="kategori_berita-table" tableId="kategori_berita-table" hasCloseBtn="true">
        <x-slot:title>Tambah Kategori</x-slot:title>
        <x-slot:footer>
            <button class="btn-primary btn" type="submit" data-text="Save" data-text-loading="Saving">Tambahkan
                Kategori</button>
        </x-slot:footer>
        <div>
            <div class="mb-6">
                <x-atoms.form-label required>Nama Kategori</x-atoms.form-label>
                <x-atoms.input id="name" name="name" type="text" class="form-control"
                    placeholder="Masukkan Nama Kategori" />
            </div>
            <div class="mb-6">
                <x-atoms.form-label required>Deskripsi Kategori</x-atoms.form-label>
                <x-atoms.textarea id="description" name="description" class="form-control"
                    placeholder="Masukkan Deskripsi Kategori"></x-atoms.textarea>
            </div>
        </div>
    </x-mollecules.modal>
@endcan
@can($globalModule['update'])
    <x-mollecules.modal id="edit-kategori_berita_modal" action="/master/kategori-berita/{id}" method="PUT"
        tableId="kategori_berita-table" hasCloseBtn="true">
        <x-slot:title>Edit Kategori</x-slot:title>
        <x-slot:footer>
            <button class="btn-primary btn" type="submit" data-text="Save" data-text-loading="Saving">Simpan
                Kategori</button>
        </x-slot:footer>
        <div>
            <div class="mb-6">
                <input type="hidden" name="id" id="id_2" value="" />
                <x-atoms.form-label required>Nama Kategori</x-atoms.form-label>
                <x-atoms.input id="name_2" name="name" type="text" class="form-control"
                    placeholder="Masukkan Nama Kategori" />
            </div>
            <div class="mb-6">
                <x-atoms.form-label required>Deskripsi Kategori</x-atoms.form-label>
                <x-atoms.textarea id="description_2" name="description" class="form-control"
                    placeholder="Masukkan Deskripsi Kategori"></x-atoms.textarea>
            </div>
        </div>
    </x-mollecules.modal>
@endcan
