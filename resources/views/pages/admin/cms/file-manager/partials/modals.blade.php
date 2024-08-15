@can($globalModule['create'])
    <x-mollecules.modal id="add-file_modal" action="{{ route('cms.file-manager.store') }}" tableId="filemanagement-table">
        <x-slot:title>Tambahkan File</x-slot:title>
        <div class="mb-3">
            <x-atoms.form-label required>File</x-atoms.form-label>
            <x-atoms.dropzone id="tes" name="file" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Keterangan File</x-atoms.form-label>
            <x-atoms.textarea name="keterangan" rows="3" id="keterangan_field"
                placeholder="Masukkan Keterangan"></x-atoms.textarea>
        </div>
        <x-slot:footer>
            <button type="submit" class="btn btn-primary">Tambahkan File</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
@can($globalModule['update'])
    <x-mollecules.modal id="edit-file_modal" action="/cms/file-manager/{id}" method="PUT" tableId="filemanagement-table">
        <x-slot:title>Edit File</x-slot:title>

        <div class="mb-3">
            <x-atoms.form-label required>File</x-atoms.form-label>
            <x-atoms.dropzone id="tes2" name="file" />
        </div>

        <div class="mb-3">
            <x-atoms.form-label required>Keterangan</x-atoms.form-label>
            <x-atoms.textarea name="keterangan" rows="3" id="keterangan_field2"
                placeholder="Masukkan Keterangan"></x-atoms.textarea>
        </div>
        <x-slot:footer>
            <button type="submit" class="btn btn-primary">Simpan File</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
@can($globalModule['read'])
    <x-mollecules.modal id="preview-modal" size="md" hasCloseBtn="true" action="#">
        <x-slot name="title">
            Preview File
        </x-slot>
        <x-slot name="footer">
        </x-slot>
        <div class="preview-container-modal" class="mb-3">
            <img src="{{ asset('assets/media/illustrations/img-preview.png') }}" alt="Default Image"
                class="img-fluid rounded mx-auto d-block" style="max-width: 400px; max-height: 300px;">
        </div>
    </x-mollecules.modal>
@endcan
