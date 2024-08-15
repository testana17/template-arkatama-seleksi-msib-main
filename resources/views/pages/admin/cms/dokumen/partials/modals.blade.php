@can($globalModule['create'])
    <x-mollecules.modal id="add-dokumen-modal" action="{{ route('cms.document.store') }}" data-table-id="dokumen-table"
        tableId="dokumen-table">
        <x-slot:title>Tambah Dokumen</x-slot:title>
        <div class="mb-3">
            <x-atoms.form-label required>File</x-atoms.form-label>
            <x-atoms.dropzone id="tes" name="file" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Nama Dokumen</x-atoms.form-label>
            <x-atoms.input name="nama" id="nama_field" placeholder="Masukkan Nama"></x-atoms.input>
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Keterangan Dokumen</x-atoms.form-label>
            <x-atoms.textarea name="keterangan" rows="3" id="keterangan_field"
                placeholder="Masukkan Keterangan"></x-atoms.textarea>
        </div>
        <x-slot:footer>
            <button type="submit" class="btn btn-primary">Tambahkan Dokumen</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
@can($globalModule['update'])
    <x-mollecules.modal id="edit-dokumen-modal" action="{{ route('cms.document.index') }}/{id}" method="PUT"
        tableId="dokumen-table">
        <x-slot:title>Edit Dokumen</x-slot:title>
        <div class="mb-3">
            <x-atoms.form-label required>File</x-atoms.form-label>
            <x-atoms.dropzone id="tes2" name="file" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Nama Dokumen</x-atoms.form-label>
            <x-atoms.input name="nama" id="nama_field" placeholder="Masukkan Nama"></x-atoms.input>
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Keterangan Dokumen</x-atoms.form-label>
            <x-atoms.textarea name="keterangan" rows="3" id="keterangan_field2"
                placeholder="Masukkan Keterangan"></x-atoms.textarea>
        </div>
        <x-slot:footer>
            <button type="submit" class="btn btn-primary">Simpan Dokumen</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
@can($globalModule['read'])
    <x-mollecules.modal id="preview-modal" size="md" hasCloseBtn="true" action="#">
        <x-slot name="title">
            Pratinjau Dokumen
        </x-slot>
        <x-slot name="footer">
        </x-slot>
        <div class="preview-container-modal" class="mb-3">
            <img src="{{ asset('assets/media/illustrations/img-preview.png') }}" alt="Default Image"
                class="img-fluid rounded mx-auto d-block" style="max-width: 400px; max-height: 300px;">
        </div>
    </x-mollecules.modal>
@endcan
