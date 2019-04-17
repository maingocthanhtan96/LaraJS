<!-- partial:partials/_navbar.html -->
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-left navbar-brand-wrapper d-flex align-items-center justify-content-between">
        <a class="navbar-brand brand-logo" href="#"><img src="{{asset('images/NEX.png')}}" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="#"><img src="{{asset('images/NEX.png')}}" alt="logo"/></a>
        <button class="navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
        <div><h4 id="headerTitle"></h4></div>
        <ul class="navbar-nav">
            <li class="nav-item  dropdown d-none align-items-center d-lg-flex d-none">
                <a class="nav-profile-name" class="dropdown-toggle btn btn-outline-secondary btn-fw"  href="#" data-toggle="dropdown" id="pagesDropdown">{{\Auth::user()->name}}</a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="pagesDropdown">
{{--                    <a class="dropdown-item">--}}
{{--                        <i class="mdi mdi-settings text-primary"></i>--}}
{{--                        Settings--}}
{{--                    </a>--}}
                    <a class="dropdown-item" href="{{route('logout')}}">
                        <i class="mdi mdi-logout text-primary"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>
<!-- partial -->
