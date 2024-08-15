@can($globalModule['create'])
    <x-mollecules.modal id="slideshowitem_modal" action="{{ route('cms.slideshow-item.store', ['slideshow' => $slideshow]) }}"
        tableId="slideshowitem-table">
        <x-slot:title>Tambah Slide Show Item</x-slot:title>
        <div class="mb-3">
            <x-atoms.form-label required>Judul Item</x-atoms.form-label>
            <x-atoms.input id="title" name="title" type="text" class="form-control" placeholder="Masukkan Judul" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Caption Item</x-atoms.form-label>
            <x-atoms.textarea name="caption" rows="3" id="caption"
                placeholder="Masukkan Caption"></x-atoms.textarea>
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Gambar Item</x-atoms.form-label>
            <x-atoms.dropzone id="image1" name="image" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Order Item</x-atoms.form-label>
            <x-atoms.input id="order" name="order" type="number" min="0" placeholder="Masukkan Order"
                class="form-control" />
        </div>
        <x-slot:footer>
            <button type="submit" class="btn btn-primary">Tambahkan Item</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
@can($globalModule['update'])
    <x-mollecules.modal id="edit-slideshowitem_modal"
        action="{{ route('cms.slideshow-item.index', ['slideshow' => $slideshow]) }}/{id}" method="PUT"
        tableId="slideshowitem-table">
        <x-slot:title>Edit Slide Show</x-slot:title>
        <div class="mb-3">
            <x-atoms.form-label required>Judul Item</x-atoms.form-label>
            <x-atoms.input id="title_2" name="title" type="text" class="form-control" placeholder="Masukkan Judul" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Caption Item</x-atoms.form-label>
            <x-atoms.textarea name="caption" rows="3" id="caption_2"
                placeholder="Masukkan Caption"></x-atoms.textarea>
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Gambar Item</x-atoms.form-label>
            <x-atoms.dropzone id="image" name="image" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Order Item</x-atoms.form-label>
            <x-atoms.input id="order" name="order" type="number" min="0" placeholder="Masukkan Order"
                class="form-control" />
        </div>
        <x-slot:footer>
            <button type="submit" class="btn btn-primary">Simpan Item</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
@can($globalModule['read'])
    <x-mollecules.modal id="preview-modal" size="md" hasCloseBtn="true" action="#">
        <x-slot name="title">
            Pratinjau Gambar
        </x-slot>
        <x-slot name="footer">
        </x-slot>
        <div class="preview-container-modal" class="mb-3">
            <img src="{{ asset('assets/media/illustrations/img-preview.png') }}" alt="Default Image"
                class="img-fluid rounded mx-auto d-block" style="max-width: 400px; max-height: 300px;">
        </div>
    </x-mollecules.modal>
@endcan
