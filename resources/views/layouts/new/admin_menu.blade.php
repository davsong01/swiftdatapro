<nav class="main-menu-container nav nav-pills flex-column sub-open">
    
    <ul class="main-menu">
        <!-- Start::slide -->
        @if (in_array('Dashboard', allowedPermissions()))
        <li class="slide">
            <a href="{{ route('admin.dashboard') }}" class="side-menu__item {{Route::is('admin.dashboard') ? 'active' : '' }}">
                <i class="bx bx-store-alt side-menu__icon"></i>
                <span class="side-menu__label">Dashboard</span>
            </a>
        </li>
        @endif
        <li class="slide has-sub">
            <a href="javascript:void(0);" class="side-menu__item">
                <i class="bx bx-home side-menu__icon"></i>
                <span class="side-menu__label">Dashboards</span>
                <i class="fe fe-chevron-right side-menu__angle"></i>
            </a>
            <ul class="slide-menu child1">
                <li class="slide side-menu__label1">
                    <a href="javascript:void(0)">Dashboards</a>
                </li>
                <li class="slide">
                    <a href="index.html" class="side-menu__item">CRM</a>
                </li>
                <li class="slide">
                    <a href="index-1.html" class="side-menu__item">Ecommerce</a>
                </li>
                <li class="slide">
                    <a href="index-2.html" class="side-menu__item">Crypto</a>
                </li>
                <li class="slide">
                    <a href="index-3.html" class="side-menu__item">Jobs</a>
                </li>
                <li class="slide">
                    <a href="index-4.html" class="side-menu__item">NFT</a>
                </li>
                <li class="slide">
                    <a href="index-5.html" class="side-menu__item">Sales</a>
                </li>
                <li class="slide">
                    <a href="index-6.html" class="side-menu__item">Analytics</a>
                </li>
                <li class="slide">
                    <a href="index-7.html" class="side-menu__item">Projects</a>
                </li>
                <li class="slide">
                    <a href="index-8.html" class="side-menu__item">HRM</a>
                </li>
                <li class="slide">
                    <a href="index-9.html" class="side-menu__item">Stocks</a>
                </li>
                <li class="slide">
                    <a href="index-10.html" class="side-menu__item">Courses</a>
                </li>
                <li class="slide">
                    <a href="index-11.html" class="side-menu__item">Personal</a>
                </li>
            </ul>
        </li>
        <!-- End::slide -->
        <li class="slide">
            <a href="icons.html" class="side-menu__item">
                <i class="bx bx-store-alt side-menu__icon"></i>
                <span class="side-menu__label">Icons</span>
            </a>
        </li>
        
    </ul>
    <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
            height="24" viewBox="0 0 24 24">
            <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
        </svg></div>
</nav>