@extends('layouts.app')
@can($globalModule['create'])
    @section('content')
        <div class="mt-2">
            <form action="{{ route('setting.menus.store') }}" method="POST" id="create-menu_form">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-atoms.form-label for="name_field" required>Nama Menu</x-atoms.form-label>
                        <x-atoms.input name="name" id="name_field" placeholder="Masukkan Nama Menu" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-atoms.form-label for="slug_field" required>Slug</x-atoms.form-label>
                        <x-atoms.input name="slug" id="slug_field" readonly />
                    </div>
                    <input type="hidden" name="is_active" value="1">
                </div>

                <div class="mb-3">
                    <x-atoms.form-label for="module_field" required>Nama Modul ( role.nama-menu )</x-atoms.form-label>
                    <x-atoms.input name="module" id="module_field" readonly />
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-atoms.form-label for="type_field" required>Tipe Menu</x-atoms.form-label>
                        <x-atoms.select placeholder="Pilih Tipe" id="type_field" name="type" value="menu"
                            :lists="[
                                'menu' => 'Menu',
                                'group' => 'Group',
                                'divider' => 'Divider',
                            ]"></x-atoms.select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-atoms.form-label for="url_field">Url</x-atoms.form-label>
                        <x-atoms.input name="url" id="url_field" placeholder="Masukkan URL" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-atoms.form-label for="icon_field">Icon</x-atoms.form-label>
                        <x-atoms.select2 name="icon" id="icon_field" source="{{ route('reference.icon') }}"
                            placeholder="Pilih Icon" custom>
                        </x-atoms.select2>
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-atoms.form-label for="parent_id_field">Parent</x-atoms.form-label>
                        <x-atoms.select2 id="parent_id_field" name="parent_id" :lists="c_option($menuRef)">
                        </x-atoms.select2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-atoms.form-label for="target_field" required>Target</x-atoms.form-label>
                        <x-atoms.select placeholder="Pilih Target" id="target_field" name="target" value="_self"
                            :lists="[
                                '_self' => 'Self',
                                '_blank' => 'Blank',
                            ]"></x-atoms.select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-atoms.form-label for="order_field" required>Order</x-atoms.form-label>
                        <x-atoms.input name="order" id="order_field" placeholder="Masukkan Order" type="number"
                            min="0" value="0" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-atoms.form-label for="location_field" required>Lokasi</x-atoms.form-label>
                        <x-atoms.radio-group name="location" value="sidebar" :lists="[
                            'sidebar' => 'Sidebar',
                            'topbar' => 'Topbar',
                        ]"></x-atoms.radio-group>
                    </div>

                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('setting.menus.index') }}" class="btn btn-light me-3">
                        <i class="fas fa-arrow-left me-3"></i>
                        Kembali</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-3"></i>
                        Tambah Menu</button>
                </div>
            </form>
        </div>

        @push('scripts')
            <script>
                $(function() {
                    $('#name_field').on('input', function() {
                        if ($(this).val() == '') {
                            $("#slug_field").val('');
                            $("#module_field").val('');
                            return;
                        }
                        const parent = $("#parent_id_field").find('option:selected').text().toLowerCase()
                            .replace(/[^a-zA-Z0-9]+/g, '-')
                            .replace(/^-+|-+$/g, '')
                            .trim();

                        const name = $(this).val()
                            .toLowerCase()
                            .replace(/[^a-zA-Z0-9]+/g, '-')
                            .replace(/^-+|-+$/g, '')
                            .trim();

                        if (parent) {
                            $("#slug_field").val(`${parent}-${name}`);
                            $("#module_field").val(`${parent}.${name}`);
                            return;
                        }
                        $("#slug_field").val(name);
                        $("#module_field").val(name);
                    });

                    $("#parent_id_field").change(function() {
                        ;
                        const parent = $("#parent_id_field").find('option:selected').text().toLowerCase()
                            .replace(/[^a-zA-Z0-9]+/g, '-')
                            .replace(/^-+|-+$/g, '')
                            .trim();

                        const slug = $("#slug_field").val()

                        if (parent) {
                            $("#slug_field").val(`${parent}-${slug}`);
                            $("#module_field").val(`${parent}.${slug}`);
                            return;
                        }
                        $("#slug_field").val(slug);
                        $("#module_field").val(slug);

                    })





                    function formatIcon(state) {
                        if (!state.id) {
                            return state.text;
                        }
                        const icon = $(`<span><i class="${state.id} fs-5 me-3"></i> ${state.text}</span>`);
                        return icon;
                    }
                    $(document).on("form-submitted:create-menu_form", function(ev) {
                        window.location.href = "{{ route('setting.menus.index') }}";
                    });
                    $("#icon_field").select2({
                        placeholder: "Select Icon",
                        allowClear: true,
                        templateResult: formatIcon,
                        templateSelection: formatIcon,
                        ajax: {
                            url: "{{ route('reference.icon') }}",
                            dataType: 'json',
                            delay: 250,

                            processResults: function(data) {
                                return {
                                    results: data.data
                                };
                            },
                        }
                    });
                });
            </script>
        @endpush
    @endsection
@endcan
