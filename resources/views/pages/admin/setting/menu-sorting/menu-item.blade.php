@props(['menu', 'parent_id' => null])

@if ($menu->childrens->count() > 0)
    <div class="list-group-item outer-menu" data-id="{{ $menu->id.($parent_id ? "_$parent_id" : "")}}" >
        <div class="p-3" data-action="toggle">
            <i class="fs-5 {{ $menu->icon }}"></i>
            <span class="ms-3">{{ $menu->name }}</span>
        </div>
        <div class="list-group nested-sortable" data-parent-id="{{$menu->id}}">
            @foreach ($menu->childrens as $children)
                @include('pages.admin.setting.menu-sorting.menu-item', [
                    'menu' => $children,
                    'parent_id' => $menu->id,
                ])
            @endforeach
        </div>
    </div>
@else
    <div class="list-group-item" data-id="{{ $menu->id.($parent_id ? "_$parent_id" : "") }}" >
        <div class="p-3" data-action="toggle">
            <i class="fs-5 {{ $menu->icon }}"></i>
            <span class="ms-3">{{ $menu->name }}</span>
        </div>
    </div>
@endif
