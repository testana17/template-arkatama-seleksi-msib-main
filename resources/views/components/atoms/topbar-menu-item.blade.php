@props([
    'menu' => [],
])


@if ($menu->childrens->count() > 0)

<li class="nav-item dropdown hover-dd d-none d-lg-block">
    <a class="nav-link" href="javascript:void(0)" data-bs-toggle="dropdown">{{$menu->name}}<span class="mt-1">
        <i class="ti ti-chevron-down"></i></span></a>
    <div class="dropdown-menu dropdown-menu-nav dropdown-menu-animate-up py-0">
        <div class="row">
            <div class="col-8">
                <div class=" ps-7 pt-7">
                    <div class="border-bottom">
                        @foreach ($menu->childrens as $menu )
                            <x-atoms.topbar-menu-item :menu="$menu" />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>
@else
    <li class="nav-item dropdown-hover d-none d-lg-block">
        <a class="nav-link" href="{{$menu->url}}">{{$menu->name}}</a>
    </li>
@endif
