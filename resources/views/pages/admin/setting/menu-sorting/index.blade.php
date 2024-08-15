@extends('layouts.app')


@push('css')
    <style>
        #menu-sorting .list-group {
            display: none;
        }

        .outer-menu [data-action="toggle"] {
            position: relative;
            padding-right: 2em;
        }

        .list-group {
            cursor: pointer;
        }

        .list-group-item{
            border: 1px solid #000000 !important;
            margin-bottom: .2em;
        }

        #menu-sorting .list-group.list-group--expanded {
            display: block;
        }

        .outer-menu>[data-action="toggle"]::after {
            content: "\f078";
            font-family: "Font Awesome 5 Pro";
            position: absolute;
            right: 2em;
            top: 50%;
            transform: translateY(-50%);
        }

        .outer-menu[aria-expanded='true']>[data-action="toggle"]::after {
            transform: rotate(180deg) translateY(50%);
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="mb-4 d-flex justify-content-between" >
                <h5>Sudut pandang role : "{{ $current_role?->name ?? 'Semua' }}"</h5>
                <div class="w-fit-content">
                    <x-atoms.select :lists="c_option($role_ref,'name','name')" value="{{$current_role->name}}" id="role_field" placeholder="Pilih Sudut Pandang"></x-atoms.select>
                </div>
            </div>

            <form action="{{ route('setting.menu-sorting.store') }}" custom-action id="menu-sorting_form">
                <div class="list-group nested-sortable" id="menu-sorting">
                    @foreach ($ref_menu as $menu)
                        @include('pages.admin.setting.menu-sorting.menu-item', ['menu' => $menu])
                    @endforeach
                </div>
                <div class="d-flex justify-content-end position-sticky position-relative mt-3" style="bottom: 2em;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-3"></i>
                        Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('vendor-scripts')
    <script src="{{ asset('assets/libs/jquery-sortablejs/sortable.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-sortablejs/jquery-sortable.js') }}"></script>
@endpush

@push('scripts')
    <script>
        $(function() {
            $(".nested-sortable").each(function() {
                $(this).sortable({
                    group: 'nested',
                    handle: 'div',
                    animation: 150,
                    swapThreshold: 0.5,
                    onAdd: function(evt) {
                        const itemEl = evt.item;
                        const parentId = evt.to.getAttribute('data-parent-id');
                        const itemId = itemEl.getAttribute('data-id');
                        if (parentId) {
                            itemEl.setAttribute('data-id', itemId + '_' + parentId);
                        } else {
                            // console.log(itemId);
                            const oldParentId = evt.from.getAttribute('data-parent-id');
                            itemEl.setAttribute('data-id', itemId.replace('_' + oldParentId,
                                ''));
                        }
                    }
                });

            })

            $("#role_field").on("change", function(){
                window.location.href = "{{ route('setting.menu-sorting.index') }}?role=" + $(this).val();
            })

            $(".outer-menu").on("click", "[data-action='toggle']", function() {
                $(this).next(".list-group").toggleClass("list-group--expanded");
                $(this).parent().attr("aria-expanded", $(this).next(".list-group").hasClass(
                    "list-group--expanded") ? "true" : "false");
            })


            $("#menu-sorting_form").on("submit", function(ev) {
                ev.preventDefault();
                const data = [];
                $('.nested-sortable').each(function() {
                    data.push($(this).sortable('toArray', {
                        attribute: ['data-id', 'data-parent-id']
                    }))
                });

                parsedData = data.map((item, i) => {
                    return {
                        parentId: item[0].includes("_") ? item[0].split('_')?.pop() : null,
                        items: item.map((item, i) => {
                            return item.split('_')[0]
                        })
                    }
                })

                const hideButtonLoader = showButtonLoader($(this).find('button[type="submit"]'));

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    complete: function() {
                        hideButtonLoader();
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        data: parsedData
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.message.title,
                            text: response.message.body,
                        })
                        window.location.reload();
                    },
                    error: function(xhr) {
                        handleAjaxError(xhr);
                    }
                });
            })
        })
    </script>
@endpush
