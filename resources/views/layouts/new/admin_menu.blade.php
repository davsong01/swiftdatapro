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
                <span class="side-menu__label">Staff</span>
                <i class="fe fe-chevron-right side-menu__angle"></i>
            </a>
            <ul class="slide-menu child1">
                @if (in_array('Staff Roles', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('role.index') }}" class="side-menu__item {{Route::is('role.index') ? 'active' : '' }}">
                        <span class="side-menu__label">Staff Roles</span>
                    </a>
                </li>
                @endif
                @if (in_array('Staff Permissions', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('permission.index') }}" class="side-menu__item {{Route::is('permission.index') ? 'active' : '' }}">
                        <span class="side-menu__label">Staff Permissions</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>

        <li class="slide has-sub">
            <a href="javascript:void(0);" class="side-menu__item">
                <i class="bx bx-home side-menu__icon"></i>
                <span class="side-menu__label">Customers</span>
                <i class="fe fe-chevron-right side-menu__angle"></i>
            </a>
            <ul class="slide-menu child1">
                @if (in_array('Staff Roles', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('role.index') }}" class="side-menu__item {{Route::is('role.index') ? 'active' : '' }}">
                        <span class="side-menu__label">All Customers</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="{{ route('role.index') }}" class="side-menu__item {{Route::is('role.index') ? 'active' : '' }}">
                        <span class="side-menu__label">Active</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="{{ route('role.index') }}" class="side-menu__item {{Route::is('role.index') ? 'active' : '' }}">
                        <span class="side-menu__label">Suspended</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="{{ route('role.index') }}" class="side-menu__item {{Route::is('role.index') ? 'active' : '' }}">
                        <span class="side-menu__label">Blacklisted</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="{{ route('role.index') }}" class="side-menu__item {{Route::is('role.index') ? 'active' : '' }}">
                        <span class="side-menu__label">Customer Levels</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>

        <li class="slide has-sub">
            <a href="javascript:void(0);" class="side-menu__item">
                <i class="bx bx-home side-menu__icon"></i>
                <span class="side-menu__label">Settings</span>
                <i class="fe fe-chevron-right side-menu__angle"></i>
            </a>
            <ul class="slide-menu child1">
                <li class="slide">
                    <a href="{{ route('settings.edit') }}" class="side-menu__item {{Route::is('settings.index') ? 'active' : '' }}" >Contact Settings</a>
                </li>
                <li class="slide">
                    <a href="index-1.html" class="side-menu__item">My Site APIKeys</a>
                </li>
                <li class="slide">
                    <a href="index-2.html" class="side-menu__item">Blacklist Number</a>
                </li>
                <li class="slide">
                    <a href="index-3.html" class="side-menu__item">Order Number(s)</a>
                </li>
            </ul>
        </li>

        <li class="slide has-sub {{ Route::is('api.*')  ||  Route::is('paymentgateway.*') ||  Route::is('category.*') || Route::is('product.*') ? 'open' : '' }}">
            <a href="javascript:void(0);" class="side-menu__item">
                <i class="bx bx-home side-menu__icon"></i>
                <span class="side-menu__label">Catalogues</span>
                <i class="fe fe-chevron-right side-menu__angle"></i>
            </a>
            <ul class="slide-menu child1">
                <li class="slide">
                    <a href="{{ route('api.index') }}" class="side-menu__item {{Route::is('api.*') ? 'active' : '' }}"  >API Providers</a>
                </li>
                <li class="slide">
                    <a href="{{ route('paymentgateway.index') }}" class="side-menu__item {{Route::is('paymentgateway.*') ? 'active' : '' }}">Payment Gateways</a>
                </li>
                <li class="slide">
                    <a href="{{ route('category.index') }}" class="side-menu__item {{Route::is('category.*') ? 'active' : '' }}">Categories</a>
                </li>
                <li class="slide">
                    <a href="{{ route('product.index') }}" class="side-menu__item {{Route::is('product.*') ? 'active' : '' }}">Products</a>
                </li>
            
                
            </ul>
        </li>
        
        
    </ul>
    <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
            height="24" viewBox="0 0 24 24">
            <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
        </svg></div>
</nav>