@extends('layouts.app')

@section('content')
    <div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
        <h2 class="fw-semibold">Menu Access</h2>
        <div class="d-flex justify-content-end mt-3 pe-5">

            <div class="search-box">
                <label class="position-absolute " for="searchBox">
                    <i class="fal fa-search fs-3"></i>
                </label>
                <input type="text" id="searchBox" class="form-control form-control-solid w-250px ps-13"
                    placeholder="Search User" />
            </div>
        </div>

        @can($globalModule['update'])
            <form action="{{ route('users.role.permissions', $role->id) }}" method="post" id="edit-permission_form">
                @csrf
                @method('PUT')
                <div class="col-xxl-12 row" id="menu-card_container">
                    @foreach ($menuRef as $i => $menu)
                        <div class="col-12 col-sm-6 col-xl-4 col-xxl-3 menu-card">
                            <div class="card bg-danger mb-xl-8">
                                <div class="card-body">
                                    <i class="{{ $menu->icon }} text-white fs-10"></i>
                                    <p class="text-white fw-semibold mb-1 mt-3 fs-6" data-role="name">{{ $menu->name }}</p>
                                    <p class="fw-bold text-white mb-2">{{ $menu->url ?? 'EMPTY URL' }}</p>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input switch-checkbox" data-parent-id="{{ $menu->parent_id }}"
                                            type="checkbox" role="switch" id="switch-{{ $menu->id }}"
                                            data-id="{{ $menu->id }}" name="{{ $menu->module }}"
                                            @if (in_array($menu->module, $permissions)) checked @endif>
                                    </div>
                                    @php
                                        $access = \App\Models\Setting\Access::where('menus_id', '=', $menu->id)->get();
                                    @endphp
                                    @foreach ($access as $item)
                                        <div class="pt-3 form-check">
                                            <input type="checkbox" class="form-check-input related-checkbox"
                                                name="{{ $item->module }}" data-parent="{{ $menu->id }}"
                                                id="checkbox-{{ $item->module }}"
                                                @if (in_array($item->module, $permissions)) checked @endif>
                                            <label class="form-check-label" style="color: white"
                                                for="{{ $item->module }}">{{ $item->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-between position-sticky" style="bottom: 2em; right:2em;">
                    <button class="btn btn-warning" id="reset_btn" type="button">
                        <i class="fal fa-sync"></i>
                        <span class="ms-2">Reset</span>
                    </button>
                    <div class="d-flex">
                        <a href="{{ route('users.role.index') }}" class="btn btn-light" cancel-btn>
                            <i class="fas fa-arrow-left me-3"></i>
                            Kembali</a>
                        <button class="btn btn-primary ms-3" type="submit">
                            <i class="fas fa-save me-3"></i>
                            Simpan</button>
                    </div>
                </div>
            </form>
        @endcan
    </div>



    @push('css')
        <style>
            .form-check-input:checked {
                background-color: #FAC213;

                border-color: #FAC213;

            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.switch-checkbox').change(function() {
                    const id = $(this).data('id');



                    if ($(this).is(':checked')) {
                        $('.switch-checkbox[data-parent-id="' + id + '"]').prop('checked', true).trigger(
                            'change');
                        $('.related-checkbox[data-parent="' + id + '"]').prop('checked', true);
                    } else {
                        $('.switch-checkbox[data-parent-id="' + id + '"]').prop('checked', false).trigger(
                            'change');
                        $('.related-checkbox[data-parent="' + id + '"]').prop('checked', false);
                    }
                });

                $('#reset_btn').on('click', function() {
                    $('.switch-checkbox').prop('checked', false).trigger('change');
                });

                $(document).on("form-submitted:edit-permission_form", function() {
                    window.location.href = "{{ route('users.role.index') }}";
                })

                $("#searchBox").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $(".menu-card").each(function() {
                        var name = $(this).find('[data-role="name"]').text().toLowerCase();
                        if (name.indexOf(value) > -1) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    })
                });
            });
        </script>
    @endpush
@endsection
