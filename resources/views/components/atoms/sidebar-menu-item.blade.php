@props([
    'menuData' => [],
])

@if ($menuData->type == 'group')
    <li class="nav-small-cap">
        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
        <span class="hide-menu">{{ $menuData->name }}</span>
    </li>
@else
    @if ($menuData->childrens->count() > 0)
        @can($menuData->module)
            <li class="sidebar-item ">
                <a class="sidebar-link has-arrow " href="javascript:void(0)" aria-expanded="false">
                    <span class="d-flex">
                        <i class="{{ $menuData->icon }} fs-5"></i>
                    </span>
                    <span class="hide-menu">{{ $menuData->name }}</span>
                </a>
                <ul aria-expanded="false" class="collapse inner-level ">
                    @foreach ($menuData->childrens as $menu)
                        @can($menu->module)
                            <x-atoms.sidebar-menu-item :menuData="$menu" />
                        @endcan
                    @endforeach
                </ul>
            </li>
        @endcan
    @else
        @can($menuData->module)
            <li class="sidebar-item ">
                <a class="sidebar-link" href="{{ $menuData->url }}" aria-expanded="false">
                    <span>
                        <i class="{{ $menuData->icon }} fs-5"></i>
                    </span>
                    <span class="hide-menu">{{ $menuData->name }}</span>
                </a>
            </li>
        @endcan
    @endif

@endif
