@can($globalModule['create'])
    <x-mollecules.modal id="faqs_modal" action="{{ route('cms.faqs.store') }}" tableId="faqs-table">
        <x-slot:title>Tambah FAQ</x-slot:title>
        <div class="mb-3">
            <x-atoms.form-label required>Pertanyaan</x-atoms.form-label>
            <x-atoms.input name="question" id="question" placeholder="Masukkan Pertanyaan" />
            <input type="hidden" name="is_active" id="is_active" value="1">
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Jawaban</x-atoms.form-label>
            <x-atoms.textarea name="answer" rows="8" id="answer"
                placeholder="Masukkan Jawaban"></x-atoms.textarea>
        </div>
        <x-slot:footer>
            <button type="submit" class="btn btn-primary">Tambahkan FAQ</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
@can($globalModule['update'])
    <x-mollecules.modal id="edit-faqs_modal" action="/cms/faqs/{id}" method="PUT" tableId="faqs-table">
        <x-slot:title>Edit FAQ</x-slot:title>
        <div class="mb-3">
            <input type="hidden" name="id" id="id">
            <x-atoms.form-label required>Pertanyaan</x-atoms.form-label>
            <x-atoms.input name="question" id="question" placeholder="Masukkan Pertanyaan" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Jawaban</x-atoms.form-label>
            <x-atoms.textarea name="answer" rows="8" id="answer2"
                placeholder="Masukkan Jawaban"></x-atoms.textarea>
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Status FAQ</x-atoms.form-label>
            <x-atoms.radio-group name="is_active" id="is_active2" value="" :lists="[
                '0' => 'Nonaktif',
                '1' => 'Aktif',
            ]"></x-atoms.radio-group>
        </div>
        <x-slot:footer>
            <button type="submit" class="btn btn-primary">Simpan FAQ</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
@can($globalModule['read'])
    <x-mollecules.modal id="preview-faqs-modal" size="md" hasCloseBtn="true" action="#">
        <x-slot name="title">
            Pratinjau FAQ
        </x-slot>
        <div class="mb-3">
            <x-atoms.form-label>Pertanyaan</x-atoms.form-label>
            <x-atoms.input readonly name="question" id="question" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label>Jawaban</x-atoms.form-label>
            <x-atoms.textarea name="answer" rows="8" id="answer3"></x-atoms.textarea>
        </div>
        <x-slot name="footer">
        </x-slot>
    </x-mollecules.modal>
@endcan
