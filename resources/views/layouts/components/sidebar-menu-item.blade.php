@if ($menuData->childrens->count() > 0)
    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
        <span class="menu-link">
            <span class="menu-icon">
                <i class="{{ $menuData->icon }}"></i>
            </span>
            <span class="menu-title">{{ $menuData->name }}</span>
            <span class="menu-arrow"></span>
        </span>
        <div class="menu-sub menu-sub-accordion">
            @foreach ($menuData->childrens as $menu)
                
                    @include('layouts.components.sidebar-menu-item', ['menuData' => $menu])
                
            @endforeach
        </div>
    </div>
@else
    <div class="menu-item">
        <a class="menu-link" href="{{ $menuData->url }}">
            <span class="menu-icon">
                <i class="{{ $menuData->icon }}"></i>
            </span>
            <span class="menu-title">{{ $menuData->name }}</span>
        </a>
    </div>
@endif
