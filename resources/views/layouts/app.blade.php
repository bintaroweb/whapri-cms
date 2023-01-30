<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Inter" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('vendor/bootstrap-5.2.0/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    @yield ('header_styles')
</head>
<body>
    <!-- Page Wrapper -->
    <div id="wrapper">

    @auth
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
                <!-- <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div> -->
                <div class="sidebar-brand-text mx-3">
                    <img src="{{ asset('images/send.png') }}" width="20px"/>
                    WAPRI
                </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider mb-1 mt-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ (request()->is('home')) ? 'text-light fw-bold' : '' }}" href="{{ url('/home') }}" title="Dashboard" >
                    <div class="menu-icon">
                        <i class="fas fa-fw fa-tachometer-alt {{ (request()->is('home')) ? 'text-light' : '' }}"></i>
                    </div>
                    <span>Dashboard</span></a>
            </li>

            <hr class="sidebar-divider mb-0 mt-1">

            <li class="nav-item">
                <a class="nav-link dropdown-toggle collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePesan"
                    aria-expanded="{{ (request()->is('settings/*') || request()->routeIs('outlets.*') || request()->routeIs('payments.*')) ? 'true' : 'false' }}" aria-controls="collapsePengaturan">
                    <div class="menu-icon">
                        <i class="fa-solid fa-address-book"></i>
                    </div>
                    <span>Pesan</span>
                </a>
                <div id="collapsePesan" class="collapse {{ (request()->routeIs('messages.*') || request()->routeIs('broadcasts.*')) ? 'show' : '' }}" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="py-2 collapse-inner rounded">
                        <a class="collapse-item {{ (request()->routeIs('messages.*')) ? 'text-light fw-bold' : '' }}" href="{{ url('messages')}}" data-bs-toggle="tooltip" data-bs-title="Kirim pesan hanya ke satu penerima">Kirim Pesan</a>
                        <a class="collapse-item {{ (request()->routeIs('broadcasts.*')) ? 'text-light fw-bold' : '' }}" href="{{ url('broadcasts')}}" data-bs-toggle="tooltip" data-bs-title="Kirim pesan ke banyak penerima sekaligus dalam satu grup">Kirim Broadcast</a>
                    </div>
                </div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link dropdown-toggle collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePengaturan"
                    aria-expanded="{{ (request()->is('settings/*') || request()->routeIs('outlets.*') || request()->routeIs('payments.*')) ? 'true' : 'false' }}" aria-controls="collapsePengaturan">
                    <div class="menu-icon">
                        <i class="fa-solid fa-address-book"></i>
                    </div>
                    <span>Kontak</span>
                </a>
                <div id="collapsePengaturan" class="collapse {{ (request()->routeIs('contacts.*') || request()->routeIs('groups.*') || request()->routeIs('payments.*')) ? 'show' : '' }}" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="py-2 collapse-inner rounded">
                        <a class="collapse-item {{ (request()->routeIs('contacts.*')) ? 'text-light fw-bold' : '' }}" href="{{ url('contacts')}}">Daftar Kontak</a>
                        <a class="collapse-item {{ (request()->routeIs('groups.*')) ? 'text-light fw-bold' : '' }}" href="{{ url('groups')}}">Group Kontak</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/templates') }}" title="Template">
                    <div class="menu-icon">
                        <i class="fa-solid fa-file"></i>
                    </div>
                    <span>Template</span>
                </a>
            </li>

            <hr class="sidebar-divider mb-1 mt-1">

            <li class="nav-item">
                <a class="nav-link {{ (request()->is('devices*')) ? 'text-light fw-bold' : '' }}" href="{{ url('/devices') }}" title="Perangkat">
                    <div class="menu-icon">
                        <i class="fa-solid fa-mobile-screen {{ (request()->is('devices')) ? 'text-light' : '' }}"></i>
                    </div>
                    <!-- <div>Perangkat<span class="badge badge-danger"><i class="fa-solid fa-circle text-warning"></i></span></div> -->
                    <span>Perangkat</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ (request()->is('users')) ? 'text-info' : '' }}" href="{{ url('/users/'. Auth::user()->uuid .'/edit') }}" title="Pengguna">
                    <div class="menu-icon">
                        <i class="fa-solid fa-user {{ (request()->is('users')) ? 'text-light' : '' }}"></i>
                    </div>
                    <span>Profile</span>
                </a>
            </li>

            <hr class="sidebar-divider mb-1 mt-1">

            <li class="nav-item">
                <a class="nav-link {{ (request()->is('customers*')) ? 'text-info' : '' }}" title="Deposit" href="{{ url('/billings') }}">
                    <div class="menu-icon">
                        <i class="fa-solid fa-wallet {{ (request()->is('customers')) ? 'text-light' : '' }}"></i>
                    </div>
                    <span>Billing</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" title="Panduan" href="{{ url('#') }}">
                    <div class="menu-icon">
                        <i class="fa-solid fa-book"></i>
                    </div>
                    <span>Panduan</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block mt-1">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Sidebar Message -->
            <!-- <div class="sidebar-card d-none d-lg-flex">
                <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
                <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
                <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
            </div> -->

        </ul>
        <!-- End of Sidebar -->
    @endauth

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

            @auth
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <!-- <p>Aktif s/d 30 Januari 2023</p> -->
                    <!-- <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form> -->

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav align-items-center ms-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- <div class="topbar-divider d-none d-sm-block"></div> -->

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
                            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user-circle fa-fw fa-2x"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                                <h6 class="dropdown-header d-flex align-items-center">
                                    <!-- <i class="fas fa-user-circle fa-fw fa-2x"></i> -->
                                    <div class="dropdown-user-details">
                                        <div class="dropdown-user-details-name">{{ Auth::user()->name }}</div>
                                        <div class="dropdown-user-details-email">{{ Auth::user()->email }}</div>
                                    </div>
                                </h6>
                                <div class="dropdown-divider"></div>
                                <!-- <a class="dropdown-item" href="#!">
                                    <div class="dropdown-item-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg></div>
                                    Account
                                </a> -->
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>

                </nav>
                <!-- End of Topbar -->
            @endauth
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white mt-4">
                <div class="container my-auto">
                    <div class="copyright my-auto">
                        <!-- <span>Copyright &copy; 2022 Rekap. All Right Reserved.</span> -->
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Jquery -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="{{ asset('vendor/bootstrap-5.2.0/js/bootstrap.bundle.min.js') }}"></script>

     <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/9386dbefe1.js" crossorigin="anonymous"></script>

    <!-- Scripts -->
    <script src="{{ asset('js/script.min.js') }}" ></script>

    <script>
        // $('[data-bs-toggle="tooltip"]').tooltip()
    </script>

    @stack('scripts')
</body>
</html>
