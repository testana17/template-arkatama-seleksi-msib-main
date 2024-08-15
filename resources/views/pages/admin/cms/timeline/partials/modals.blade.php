@can($globalModule['create'])
    <x-mollecules.modal id="timeline_modal" action="{{ route('cms.timeline.store') }}" tableId="timeline-table">
        <x-slot:title>Tambah Timeline</x-slot:title>
        {{-- <div class="mb-3">
        <x-atoms.form-label required>Tahun Ajaran</x-atoms.form-label>
        <x-atoms.select2 name="tahun_ajaran_id" id="tahun_ajaran_id" ref="{{ route('reference.tahun_ajaran') }}"
                placeholder="Cari Tahun Ajaran" parent="#timeline_modal" source="{{ route('reference.tahun_ajaran') }}" >
                <option selected value="{{ $th->id }}">{{ $th->tahun_ajaran }}</option>
            </x-atoms.select2>
    </div> --}}
        <div class="mb-3">
            <x-atoms.form-label required>Tanggal Mulai Pendaftaran</x-atoms.form-label>
            <x-atoms.input type="date" name="tanggal_mulai_pendaftaran" id="tanggal_mulai_pendaftaran" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Tanggal Selesai Pendaftaran</x-atoms.form-label>
            <x-atoms.input type="date" name="tanggal_selesai_pendaftaran" id="tanggal_selesai_pendaftaran" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Tanggal Mulai Administrasi</x-atoms.form-label>
            <x-atoms.input type="date" name="tanggal_mulai_administrasi" id="tanggal_mulai_administrasi" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Tanggal Selesai Administrasi</x-atoms.form-label>
            <x-atoms.input type="date" name="tanggal_selesai_administrasi" id="tanggal_selesai_administrasi" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Tanggal Mulai Assesmen</x-atoms.form-label>
            <x-atoms.input type="date" name="tanggal_mulai_assesmen" id="tanggal_mulai_assesmen" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Tanggal Seleksi Evaluasi Diri</x-atoms.form-label>
            <x-atoms.input type="date" name="tanggal_seleksi_evaluasi_diri" id="tanggal_seleksi_evaluasi_diri" />
        </div>
        <x-slot:footer>
            <button type="submit" class="btn btn-primary">Tambahkan Timeline</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
@can($globalModule['update'])
    <x-mollecules.modal id="edit-timeline_modal" action="/cms/timeline/{id}" method="PUT" tableId="timeline-table">
        <x-slot:title>Edit Timeline</x-slot:title>
        <div class="mb-3">
            <input type="hidden" name="id" id="id">
            {{-- <x-atoms.form-label required>Tahun Ajaran</x-atoms.form-label>
        <x-atoms.select2 name="tahun_ajaran_id" id="tahun_ajaran_id2" ref="{{ route('reference.tahun_ajaran') }}"
                placeholder="Cari Tahun Ajaran" parent="#edit-timeline_modal" source="{{ route('reference.tahun_ajaran') }}" /> --}}
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Tanggal Mulai Pendaftaran</x-atoms.form-label>
            <x-atoms.input type="date" name="tanggal_mulai_pendaftaran" id="tanggal_mulai_pendaftaran" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Tanggal Selesai Pendaftaran</x-atoms.form-label>
            <x-atoms.input type="date" name="tanggal_selesai_pendaftaran" id="tanggal_selesai_pendaftaran" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Tanggal Mulai Administrasi</x-atoms.form-label>
            <x-atoms.input type="date" name="tanggal_mulai_administrasi" id="tanggal_mulai_administrasi" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Tanggal Selesai Administrasi</x-atoms.form-label>
            <x-atoms.input type="date" name="tanggal_selesai_administrasi" id="tanggal_selesai_administrasi" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Tanggal Mulai Assesmen</x-atoms.form-label>
            <x-atoms.input type="date" name="tanggal_mulai_assesmen" id="tanggal_mulai_assesmen" />
        </div>
        <div class="mb-3">
            <x-atoms.form-label required>Tanggal Seleksi Evaluasi Diri</x-atoms.form-label>
            <x-atoms.input type="date" name="tanggal_seleksi_evaluasi_diri" id="tanggal_seleksi_evaluasi_diri" />
        </div>
        <x-slot:footer>
            <button type="submit" class="btn btn-primary">Simpan Timeline</button>
        </x-slot:footer>
    </x-mollecules.modal>
@endcan
