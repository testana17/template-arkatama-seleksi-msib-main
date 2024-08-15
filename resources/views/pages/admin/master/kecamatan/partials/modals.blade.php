@can($globalModule['create'])
    <x-mollecules.modal id="add-kecamatan_modal" action="{{ route('master.kecamatan.store') }}" method="POST"
        data-table-id="kecamatan-table" tableId="kecamatan-table" hasCloseBtn="true">
        <x-slot:title>Tambah Kecamatan</x-slot:title>
        <x-slot:footer>
            <button class="btn-primary btn" type="submit" data-text="Save" data-text-loading="Saving">Tambahkan
                Kecamatan</button>
        </x-slot:footer>
        <div>
            <div class="mb-6">
                <x-atoms.form-label required>Nama Kabupaten/Kota</x-atoms.form-label>
                <x-atoms.select2 placeholder="Pilih Kabupaten/Kota" id="kabupaten_kota_id" name="kabupaten_kota_id"
                    class="form-select">
                    <option value="">Pilih Kabupaten/Kota</option>
                    @foreach ($semua_kabupaten_kota as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->nama }}</option>
                    @endforeach
                </x-atoms.select2>
            </div>
            <div class="mb-6">
                <x-atoms.form-label required>Kode Kecamatan</x-atoms.form-label>
                <x-atoms.input id="kode" name="kode" type="text" class="form-control"
                    placeholder="Masukkan Kode Kecamatan" />
            </div>
            <div class="mb-6">
                <x-atoms.form-label required>Nama Kecamatan</x-atoms.form-label>
                <x-atoms.input id="nama" name="nama" type="text" class="form-control"
                    placeholder="Masukkan Nama Kecamatan" />
            </div>
        </div>
    </x-mollecules.modal>
@endcan
@can($globalModule['update'])
    <x-mollecules.modal id="edit-kecamatan_modal" action="/master/kecamatan/{id}" method="PUT" tableId="kecamatan-table"
        hasCloseBtn="true">
        <x-slot:title>Edit Kecamatan</x-slot:title>
        <x-slot:footer>
            <button class="btn-primary btn" type="submit" data-text="Save" data-text-loading="Saving">Simpan
                Kecamatan</button>
        </x-slot:footer>
        <div>
            <div class="mb-6">
                <input type="hidden" id="id" name="id" />
                <x-atoms.form-label required>Nama Kabupaten/Kota</x-atoms.form-label>
                <x-atoms.select2 placeholder="Pilih Kabupaten/Kota" id="kabupaten_kota_id_2" name="kabupaten_kota_id"
                    class="form-select">
                    <option value="">Pilih Kabupaten/Kota</option>
                    @foreach ($semua_kabupaten_kota as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->nama }}</option>
                    @endforeach
                </x-atoms.select2>
            </div>
            <div class="mb-6">
                <x-atoms.form-label required>Kode Kecamatan</x-atoms.form-label>
                <x-atoms.input id="kode_2" name="kode" type="text" class="form-control"
                    placeholder="Masukkan Kode Kecamatan" />
            </div>
            <div class="mb-6">
                <x-atoms.form-label required>Nama Kecamatan</x-atoms.form-label>
                <x-atoms.input id="nama_2" name="nama" type="text" class="form-control"
                    placeholder="Masukkan Nama Kecamatan" />
            </div>
        </div>
    </x-mollecules.modal>
@endcan
