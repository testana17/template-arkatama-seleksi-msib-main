<nav class="sidebar-nav scroll-sidebar" data-simplebar>
    <ul id="sidebarnav">
        @foreach ($menus as $menu)
            @if ($menu->location == 'sidebar')
                <x-atoms.sidebar-menu-item :menuData="$menu" />
            @endif
        @endforeach
    </ul>
</nav>
