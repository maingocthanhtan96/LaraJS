<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <div class="nav-link d-flex">
                <div class="profile-image">
                    <img src="" alt="image">
                </div>
                <div class="profile-name">
                    <p class="name">
                        Edwin Harring
                    </p>
                    <p class="designation">
                        Manager
                    </p>
                </div>
            </div>
        </li>
        <li class="nav-item {{setActive(['*/contact/*'])}}">
            <a class="nav-link" href="{{route('contact')}}">
                <i class="mdi mdi-account-box menu-icon"></i>
                <span class="menu-title">Contact</span>
            </a>
        </li>
        <li class="nav-item {{setActive(['*/group/*'])}}">
            <a class="nav-link" href="{{route('group')}}">
                <i class="mdi mdi-group menu-icon"></i>
                <span class="menu-title">Group</span>
            </a>
        </li>
        <li class="nav-item {{setActive(['*/enquiry/*'])}}">
            <a class="nav-link" href="{{route('enquiry.index')}}">
                <i class="mdi mdi-archive menu-icon"></i>
                <span class="menu-title">Enquiry</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fa fa-user menu-icon"></i>
                <span class="menu-title">User</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-advanced" aria-expanded="true" aria-controls="ui-advanced">
                <i class="mdi mdi-palette menu-icon"></i>
                <span class="menu-title">Advanced UI</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-advanced" style="">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/ui-features/dragula.html">Dragula</a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/ui-features/clipboard.html">Clipboard</a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/ui-features/context-menu.html">Context menu</a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/ui-features/slider.html">Sliders</a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/ui-features/carousel.html">Carousel</a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/ui-features/colcade.html">Colcade</a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/ui-features/loaders.html">Loaders</a></li>
                </ul>
            </div>
        </li>
    </ul>
</nav>
<!-- partial -->
