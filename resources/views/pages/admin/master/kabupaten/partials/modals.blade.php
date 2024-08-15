@can($globalModule['create'])
    <x-mollecules.modal id="add-kabupatenkota_modal" action="{{ route('master.kabupaten-kota.store') }}" method="POST"
        data-table-id="kabupatenkota-table" tableId="kabupatenkota-table" hasCloseBtn="true">
        <x-slot:title>Tambah Kabupaten</x-slot:title>
        <x-slot:footer>
            <button class="btn-primary btn" type="submit" data-text="Save" data-text-loading="Saving">Tambahkan
                Kabupaten</button>
        </x-slot:footer>
        <div>
            <div class="mb-6">
                <x-atoms.form-label required>Nama Provinsi</x-atoms.form-label>
                <x-atoms.select2 placeholder="Pilih Provinsi" id="provinsi_id" name="provinsi_id" class="form-select">
                    <option value="">Pilih Provinsi</option>
                    @foreach ($semua_provinsi as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->nama }}</option>
                    @endforeach
                </x-atoms.select2>
            </div>
            <div class="mb-6">
                <x-atoms.form-label required>Kode Kabupaten/Kota</x-atoms.form-label>
                <x-atoms.input id="kode" name="kode" type="text" class="form-control"
                    placeholder="Masukkan Kode Kabupaten/Kota" />
            </div>
            <div class="mb-6">
                <x-atoms.form-label required>Nama Kabupaten/Kota</x-atoms.form-label>
                <x-atoms.input id="nama" name="nama" type="text" class="form-control"
                    placeholder="Masukkan Nama Kabupaten/Kota" />
            </div>
        </div>
    </x-mollecules.modal>
@endcan
@can($globalModule['update'])
    <x-mollecules.modal id="edit-kabupatenkota_modal" action="/master/kabupaten/{id}" method="PUT"
        tableId="kabupatenkota-table" hasCloseBtn="true">
        <x-slot:title>Edit Kabupaten</x-slot:title>
        <x-slot:footer>
            <button class="btn-primary btn" type="submit" data-text="Save" data-text-loading="Saving">Simpan
                Kabupaten</button>
        </x-slot:footer>
        <div>
            <div class="mb-6">
                <input type="hidden" id="id" name="id" />
                <x-atoms.form-label required>Nama Provinsi</x-atoms.form-label>
                <x-atoms.select2 placeholder="Pilih Provinsi" id="provinsi_id_2" name="provinsi_id" class="form-select">
                    <option value="">Pilih Provinsi</option>
                    @foreach ($semua_provinsi as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->nama }}</option>
                    @endforeach
                </x-atoms.select2>
            </div>
            <div class="mb-6">
                <x-atoms.form-label required>Kode Kabupaten/Kota</x-atoms.form-label>
                <x-atoms.input id="kode_2" name="kode" type="text" class="form-control"
                    placeholder="Masukkan Kode Kabupaten/Kota" />
            </div>
            <div class="mb-6">
                <x-atoms.form-label required>Nama Kabupaten/Kota</x-atoms.form-label>
                <x-atoms.input id="nama_2" name="nama" type="text" class="form-control"
                    placeholder="Masukkan Nama Kabupaten/Kota" />
            </div>
        </div>
    </x-mollecules.modal>
@endcan
