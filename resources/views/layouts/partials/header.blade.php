{{-- @php
    $currentUser = auth()->user();
@endphp --}}
<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav ms-2">
            <li class="nav-item d-flex flex-row">
                <a class="nav-link sidebartoggler nav-icon-hover ms-n3" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
                <a class="nav-link  nav-icon-hover ms-n3" href="/">
                    <img src="{{ asset('assets/images/logos/logo erpl.svg') }}" class="" width="180"
                        alt="" />
                </a>
            </li>

        </ul>
        <ul class="navbar-nav quick-links d-none d-lg-flex">
            @foreach ($menus as $menu)
                @if ($menu->position == 'topbar')
                    <x-atoms.topbar-menu-item :menu="$menu" />
                @endif
            @endforeach
        </ul>
        <button class="navbar-toggler p-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="p-2">
                <i class="ti ti-dots fs-7"></i>
            </span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <div class="d-flex align-items-center justify-content-between">
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">

                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link notify-badge nav-icon-hover" href="javascript:void(0)" id="drop2"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-bell-ringing"></i>
                            <span class="badge rounded-pill bg-primary fs-2">5</span>
                        </a>
                        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                            aria-labelledby="drop2">
                            <div class="d-flex align-items-center justify-content-between py-3 px-7">
                                <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
                                <span class="badge bg-primary rounded-4 px-3 py-1 lh-sm">5 new</span>
                            </div>
                            <div class="message-body" data-simplebar>
                                <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                                    <span class="me-3">
                                        <img src="{{ asset('assets/images/profile/user-1.jpg') }}" alt="user"
                                            class="rounded-circle" width="48" height="48" />
                                    </span>
                                    <div class="w-75 d-inline-block v-middle">
                                        <h6 class="mb-1 fw-semibold">Roman Joined the Team!</h6>
                                        <span class="d-block">Congratulate him</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                                    <span class="me-3">
                                        <img src="{{ asset('assets/images/profile/user-2.jpg') }}" alt="user"
                                            class="rounded-circle" width="48" height="48" />
                                    </span>
                                    <div class="w-75 d-inline-block v-middle">
                                        <h6 class="mb-1 fw-semibold">New message</h6>
                                        <span class="d-block">Salma sent you new message</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                                    <span class="me-3">
                                        <img src="{{ asset('assets/images/profile/user-3.jpg') }}" alt="user"
                                            class="rounded-circle" width="48" height="48" />
                                    </span>
                                    <div class="w-75 d-inline-block v-middle">
                                        <h6 class="mb-1 fw-semibold">Bianca sent payment</h6>
                                        <span class="d-block">Check your earnings</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                                    <span class="me-3">
                                        <img src="{{ asset('assets/images/profile/user-4.jpg') }}" alt="user"
                                            class="rounded-circle" width="48" height="48" />
                                    </span>
                                    <div class="w-75 d-inline-block v-middle">
                                        <h6 class="mb-1 fw-semibold">Jolly completed tasks</h6>
                                        <span class="d-block">Assign her new tasks</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                                    <span class="me-3">
                                        <img src="{{ asset('assets/images/profile/user-5.jpg') }}" alt="user"
                                            class="rounded-circle" width="48" height="48" />
                                    </span>
                                    <div class="w-75 d-inline-block v-middle">
                                        <h6 class="mb-1 fw-semibold">John received payment</h6>
                                        <span class="d-block">$230 deducted from account</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                                    <span class="me-3">
                                        <img src="{{ asset('assets/images/profile/user-1.jpg') }}" alt="user"
                                            class="rounded-circle" width="48" height="48" />
                                    </span>
                                    <div class="w-75 d-inline-block v-middle">
                                        <h6 class="mb-1 fw-semibold">Roman Joined the Team!</h6>
                                        <span class="d-block">Congratulate him</span>
                                    </div>
                                </a>
                            </div>
                            <div class="py-6 px-7 mb-1">
                                <button class="btn btn-outline-primary w-100"> See All Notifications </button>
                            </div>
                        </div>
                    </li> --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div class="user-profile-img">
                                    <img src="{{ asset('assets/images/profile/user-1.jpg') }}" class="rounded-circle"
                                        width="35" height="35" alt="" />
                                    {{-- @if (auth()->user()->getRoleNames()->first() == 'asesor' && auth()->user()->asAsesorInstance?->foto_profil)
                                        <img src="{{ getFileInfo(auth()->user()->asAsesorInstance->foto_profil)['preview'] }}"
                                            class="rounded-circle" width="35" height="35" alt="" />
                                    @elseif(in_array(auth()->user()->getRoleNames()->first(), [
                                            'admin',
                                            'admin-prodi',
                                            'admin-fakultas',
                                            'admin-keuangan',
                                            'admin-upt',
                                            'camaba'
                                        ]) && $institusi?->logo)
                                        <img src="{{ getFileInfo($institusi?->logo)['preview'] }}"
                                            class="rounded-circle" width="35" height="35" alt="" />
                                    @else
                                        <img src="{{ asset('assets/images/profile/user-1.jpg') }}"
                                            class="rounded-circle" width="35" height="35" alt="" />
                                    @endif --}}
                                </div>
                                <span class="mx-2 fs-3 text-dark">{{ $currentUser->name }}</span>
                                <i class="ti ti-chevron-down"></i>
                            </div>
                        </a>
                        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                            aria-labelledby="drop1">
                            <div class="profile-dropdown position-relative" data-simplebar>
                                <div class="py-3 px-7 pb-0">
                                    <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                                </div>
                                <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                    <img src="{{ asset('assets/images/profile/user-1.jpg') }}" class="rounded-circle"
                                        width="35" height="35" alt="" />

                                    {{-- @if (auth()->user()->getRoleNames()->first() == 'asesor' && auth()->user()->asAsesorInstance?->foto_profil)
                                        <img src="{{ getFileInfo(auth()->user()->asAsesorInstance->foto_profil)['preview'] }}"
                                            class="rounded-circle" width="80" height="80" alt="" />
                                    @elseif(in_array(auth()->user()->getRoleNames()->first(), [
                                            'admin',
                                            'admin-prodi',
                                            'admin-fakultas',
                                            'admin-keuangan',
                                            'admin-upt',
                                            'camaba'
                                        ]) && $institusi?->logo)
                                        <img src="{{ getFileInfo($institusi?->logo)['preview'] }}"
                                            class="rounded-circle" width="80" height="80" alt="" />
                                    @else
                                        <img src="{{ asset('assets/images/profile/user-1.jpg') }}"
                                            class="rounded-circle" width="80" height="80" alt="" />
                                    @endif --}}
                                    <div class="ms-3">
                                        <h5 class="mb-1 fs-3">{{ $currentUser->name }}</h5>
                                        <span
                                            class="mb-1 d-block text-dark">{{ $currentUser->getRoleNames()->first() }}</span>
                                        <p class="mb-0 d-flex text-dark align-items-center gap-2">
                                            <i class="ti ti-mail fs-4"></i> {{ $currentUser->email }}
                                        </p>
                                    </div>
                                </div>
                                <div class="message-body">
                                    {{-- <a href="{{ route($currentUser->hasRole('asesor') ? 'asesor.profil.index' : ($currentUser->hasRole('admin-upt') ? 'dashboard' : ($currentUser->hasRole('admin-prodi') ? 'dashboard' : ($currentUser->hasRole('camaba') ? 'dashboard' : 'dashboard')))) }}" --}}
                                    @if ($currentUser->hasRole('asesor'))
                                        <a href="{{ route($currentUser->hasRole('asesor') ? 'asesor.profil.index' : 'dashboard') }}"
                                            class="py-8 px-7 mt-8 d-flex align-items-center">
                                            <span
                                                class="d-flex align-items-center justify-content-center bg-light rounded-1 p-6">
                                                <img src="{{ asset('assets/images/svgs/icon-account.svg') }}"
                                                    alt="" width="24" height="24">
                                            </span>
                                            <div class="w-75 d-inline-block v-middle ps-3">
                                                <h6 class="mb-1 bg-hover-primary fw-semibold"> My Profile </h6>
                                                <span class="d-block text-dark">Account Settings</span>
                                            </div>
                                        </a>
                                    @endif
                                    {{-- <a href="./app-email.html" class="py-8 px-7 d-flex align-items-center">
                                        <span
                                            class="d-flex align-items-center justify-content-center bg-light rounded-1 p-6">
                                            <img src="{{ asset('assets/images/svgs/icon-inbox.svg') }}"
                                                alt="" width="24" height="24">
                                        </span>
                                        <div class="w-75 d-inline-block v-middle ps-3">
                                            <h6 class="mb-1 bg-hover-primary fw-semibold">My Inbox</h6>
                                            <span class="d-block text-dark">Messages & Emails</span>
                                        </div>
                                    </a>
                                    <a href="./app-notes.html" class="py-8 px-7 d-flex align-items-center">
                                        <span
                                            class="d-flex align-items-center justify-content-center bg-light rounded-1 p-6">
                                            <img src="{{ asset('assets/images/svgs/icon-tasks.svg') }}"
                                                alt="" width="24" height="24">
                                        </span>
                                        <div class="w-75 d-inline-block v-middle ps-3">
                                            <h6 class="mb-1 bg-hover-primary fw-semibold">My Task</h6>
                                            <span class="d-block text-dark">To-do and Daily Tasks</span>
                                        </div>
                                    </a> --}}
                                </div>
                                <div class="d-grid py-4 px-7 pt-8">
                                    {{-- <div
                                        class="upgrade-plan bg-light-primary position-relative overflow-hidden rounded-4 p-4 mb-9">
                                        <div class="row">
                                            <div class="col-6">
                                                <h5 class="fs-4 mb-3 w-50 fw-semibold text-dark">Unlimited Access</h5>
                                                <button class="btn btn-primary text-white">Upgrade</button>
                                            </div>
                                            <div class="col-6">
                                                <div class="m-n4">
                                                    <img src="{{ asset('assets/images/backgrounds/unlimited-bg.png') }}"
                                                        alt="" class="w-100">
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}

                                    @if (Auth::user()->isImpersonated())
                                        <a href="{{ route('leaveImpersonation') }}" class="btn btn-outline-primary">End
                                            Impersonation</a>
                                    @else
                                        <a href="{{ route('logout') }}" class="btn btn-outline-primary"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log
                                            Out</a>
                                    @endif
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="top-header " style="background: #00468C">
    <div class=" container d-flex flex-row justify-content-between align-content-center">
        @php
            $bcs = BreadCrumbPage::render('dashboard');
        @endphp
        <h1 class="fw-bolder text-white fs-8 m-0">{{ end($bcs)['label'] }}</h1> <!-- Perlu di automasi -->
        <div class="d-flex flex-row gap-3 p-2">
            <img src="{{ asset('assets/images/svgs/calendar-outline.png') }}" alt="">
            <h1 class="text-white fs-2 m-0 align-self-center ">{{ Date('M d, Y') }}</h1>
        </div>
    </div>
</div>
