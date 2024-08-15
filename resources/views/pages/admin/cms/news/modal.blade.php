@can($globalModule['create'])
    <x-mollecules.modal id="add-news_modal" action="{{ route('cms.news.store') }}" tableId="news-table">
        <x-slot:title>Tambah Berita</x-slot:title>

        <div class="mb-6">
            <x-atoms.form-label required>Judul Berita</x-atoms.form-label>
            <x-atoms.input name="title" id="title_field" placeholder="Masukkan Judul Berita" />
        </div>
        <div class="mb-6">
            <x-atoms.form-label required>Kategori</x-atoms.form-label>
            <x-atoms.select2 name="news_kategori_id" id="news_kategori_id" ref="{{ route('reference.kategori_berita') }}"
                placeholder="Pilih Kategori" source="{{ route('reference.kategori_berita') }}" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Thumbnail</x-atoms.form-label>
            <x-atoms.dropzone id="tes" name="thumbnail" />
        </div>
        <div class="mb-6">
            <x-atoms.form-label required>Deskripsi</x-atoms.form-label>
            <x-atoms.summernote id="description_field" name="description" placeholder="Masukkan Deskripsi Berita"
                tabsize="2" height="300" />
        </div>

        <x-slot:footer>
            <button class="btn-primary btn" type="submit">Tambahkan Berita</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
@can($globalModule['update'])
    <x-mollecules.modal id="edit-news_modal" action="/cms/news/{id}" tableId="news-table" method="PUT">
        <x-slot:title>Edit Berita </x-slot:title>
        <div class="mb-6">
            <x-atoms.form-label required>Judul Berita</x-atoms.form-label>
            <x-atoms.input name="title" id="title_field2" placeholder="Masukkan Judul Berita" />
        </div>
        <div class="mb-6">
            <x-atoms.form-label required>Kategori</x-atoms.form-label>
            <x-atoms.select2 name="news_kategori_id" id="news_kategori_id_2" placeholder="Pilih Kategori">
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->name }}</option>
                @endforeach
            </x-atoms.select2>
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Thumbnail</x-atoms.form-label>
            <x-atoms.dropzone id="tes2" name="thumbnail" />
        </div>
        <div class="mb-6">
            <x-atoms.form-label required>Deskripsi</x-atoms.form-label>
            <x-atoms.summernote id="description_field2" name="description" placeholder="Masukkan Deskripsi Berita"
                tabsize="2" height="300" />
        </div>
        <x-slot:footer>
            <button class="btn-primary btn" type="submit">Simpan Berita</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
