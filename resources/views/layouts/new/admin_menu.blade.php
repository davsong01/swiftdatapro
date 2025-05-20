<nav class="main-menu-container nav nav-pills flex-column sub-open">
    <ul class="main-menu">
        <!-- Start::slide -->
        {{-- @if (in_array('Dashboard', allowedPermissions())) --}}
        <li class="slide">
            <a href="{{ route('admin.dashboard') }}" class="side-menu__item {{Route::is('admin.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" height="24"
                            viewBox="0 -960 960 960" fill="#fff" width="24">
                            <path
                                d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Zm80-400h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z" />
                        </svg>
                <span class="side-menu__label">&nbsp;Dashboard</span>
            </a>
        </li>
        {{-- @endif --}}
        
        @if (in_array('Catalogues', allowedPermissions()))
        <li class="slide has-sub {{ Route::is('api.*')  ||  Route::is('paymentgateway.*') ||  Route::is('category.*') || Route::is('product.*') ? 'open' : '' }}">
            <a href="javascript:void(0);" class="side-menu__item">
                <svg xmlns="http://www.w3.org/2000/svg" height="24"
                            viewBox="0 -960 960 960" width="24">
                            <path
                                d="M280-600v-80h560v80H280Zm0 160v-80h560v80H280Zm0 160v-80h560v80H280ZM160-600q-17 0-28.5-11.5T120-640q0-17 11.5-28.5T160-680q17 0 28.5 11.5T200-640q0 17-11.5 28.5T160-600Zm0 160q-17 0-28.5-11.5T120-480q0-17 11.5-28.5T160-520q17 0 28.5 11.5T200-480q0 17-11.5 28.5T160-440Zm0 160q-17 0-28.5-11.5T120-320q0-17 11.5-28.5T160-360q17 0 28.5 11.5T200-320q0 17-11.5 28.5T160-280Z"
                                fill="#fff" />
                        </svg>
                <span class="side-menu__label">&nbsp;Catalogues</span>
                <i class="fe fe-chevron-right side-menu__angle"></i>
            </a>
            <ul class="slide-menu child1">
                @if (in_array('api.index', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('api.index') }}" class="side-menu__item {{Route::is('api.*') ? 'active' : '' }}">API Providers</a>
                </li>
                @endif
                @if (in_array('paymentgateway.index', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('paymentgateway.index') }}" class="side-menu__item {{Route::is('paymentgateway.*') ? 'active' : '' }}">Payment Gateways</a>
                </li>
                @endif
                @if (in_array('category.index', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('category.index') }}" class="side-menu__item {{Route::is('category.*') ? 'active' : '' }}">Categories</a>
                </li>
                @endif
                @if (in_array('product.index', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('product.index') }}" class="side-menu__item {{Route::is('product.*') ? 'active' : '' }}">Products</a>
                </li>
                @endif
                @if (in_array('airtime2cash.index', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('airtime2cash.index') }}" class="side-menu__item {{Route::is('airtime2cash.*') ? 'active' : '' }}">Exchange Airtime</a>
                </li>
                @endif
                
            </ul>
        </li>
        @endif
        <li class="slide has-sub">
            <a href="javascript:void(0);" class="side-menu__item">
                <svg fill="#fff" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                <path d="M400-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM80-160v-112q0-33 17-62t47-44q51-26 115-44t141-18h14q6 0 12 2-8 18-13.5 37.5T404-360h-4q-71 0-127.5 18T180-306q-9 5-14.5 14t-5.5 20v32h252q6 21 16 41.5t22 38.5H80Zm560 40-12-60q-12-5-22.5-10.5T584-204l-58 18-40-68 46-40q-2-14-2-26t2-26l-46-40 40-68 58 18q11-8 21.5-13.5T628-460l12-60h80l12 60q12 5 22.5 11t21.5 15l58-20 40 70-46 40q2 12 2 25t-2 25l46 40-40 68-58-18q-11 8-21.5 13.5T732-180l-12 60h-80Zm40-120q33 0 56.5-23.5T760-320q0-33-23.5-56.5T680-400q-33 0-56.5 23.5T600-320q0 33 23.5 56.5T680-240ZM400-560q33 0 56.5-23.5T480-640q0-33-23.5-56.5T400-720q-33 0-56.5 23.5T320-640q0 33 23.5 56.5T400-560Zm0-80Zm12 400Z" />
                </svg>
                <span class="side-menu__label">&nbsp;Staff</span>
                <i class="fe fe-chevron-right side-menu__angle"></i>
            </a>
            <ul class="slide-menu child1">
                @if (in_array('All Staff', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('staff.index') }}" class="side-menu__item {{Route::is('staff.index') ? 'active' : '' }}">
                        <span class="side-menu__label">All Staff</span>
                    </a>
                </li>
                @endif
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
                <svg xmlns="http://www.w3.org/2000/svg" fill="#fff"
                            height="24" viewBox="0 -960 960 960" width="24">
                            <path
                                d="M660-570q-25 0-42.5-17.5T600-630q0-25 17.5-42.5T660-690q25 0 42.5 17.5T720-630q0 25-17.5 42.5T660-570Zm-360 0q-25 0-42.5-17.5T240-630q0-25 17.5-42.5T300-690q25 0 42.5 17.5T360-630q0 25-17.5 42.5T300-570Zm180 110q-25 0-42.5-17.5T420-520q0-25 17.5-42.5T480-580q25 0 42.5 17.5T540-520q0 25-17.5 42.5T480-460Zm0-220q-25 0-42.5-17.5T420-740q0-25 17.5-42.5T480-800q25 0 42.5 17.5T540-740q0 25-17.5 42.5T480-680Zm0 520q-20 0-40.5-3t-39.5-8v-143q0-35 23.5-60.5T480-400q33 0 56.5 25.5T560-314v143q-19 5-39.5 8t-40.5 3Zm-140-32q-20-8-38.5-18T266-232q-28-20-44.5-52T205-352q0-26-5.5-48.5T180-443q-10-13-37.5-39.5T92-532q-11-11-11-28t11-28q11-11 28-11t28 11l153 145q20 18 29.5 42.5T340-350v158Zm280 0v-158q0-26 10-51t29-42l153-145q12-11 28.5-11t27.5 11q11 11 11 28t-11 28q-23 23-50.5 49T780-443q-14 20-19.5 42.5T755-352q0 36-16.5 68.5T693-231q-16 11-34.5 21T620-192Z" />
                        </svg>
                <span class="side-menu__label">&nbsp;Customers</span>
                <i class="fe fe-chevron-right side-menu__angle"></i>
            </a>
            <ul class="slide-menu child1">
                @if (in_array('All Customers', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('customers.index') }}" class="side-menu__item {{Route::is('customers.index') ? 'active' : '' }}">
                        <span class="side-menu__label">All Customers</span>
                    </a>
                </li>
                @endif
                @if (in_array('Active Customers', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('customers.active') }}" class="side-menu__item {{Route::is('customers.active') ? 'active' : '' }}">
                        <span class="side-menu__label">Active Customers</span>
                    </a>
                </li>
                @endif
                @if (in_array('In Active Customers', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('customers.inactive') }}" class="side-menu__item {{Route::is('customers.inactive') ? 'active' : '' }}">
                        <span class="side-menu__label">In Active Customers</span>
                    </a>
                </li>
                @endif
                @if (in_array('Deleted Customers', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('customers.deleted') }}" class="side-menu__item {{Route::is('customers.deleted') ? 'active' : '' }}">
                        <span class="side-menu__label">Deleted Customers</span>
                    </a>
                </li>
                @endif
                
                @if (in_array('Customer Levels', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('customerlevel.index') }}" class="side-menu__item {{Route::is('customerlevel.*') ? 'active' : '' }}">
                        <span class="side-menu__label">Customer Levels</span>
                    </a>
                </li>
                @endif
                @if (in_array('Reserved Accounts', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('accounts.index') }}" class="side-menu__item {{Route::is('accounts.index') ? 'active' : '' }}">
                        <span class="side-menu__label">Reserved Accounts</span>
                    </a>
                </li>
                @endif
                @if (in_array('Reserved Accounts', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('pending.kyc') }}" class="side-menu__item {{Route::is('pending.kyc') ? 'active' : '' }}">
                        <span class="side-menu__label">Pending KYC</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>

        
        @if (in_array('Finance', allowedPermissions()))
        <li class="slide has-sub {{ Route::is('admin.credit.customer') ||  Route::is('admin.debit.customer') }}">
            <a href="javascript:void(0);" class="side-menu__item">
                <svg fill="#fff" xmlns="http://www.w3.org/2000/svg"
                            height="24" viewBox="0 -960 960 960" width="24">
                            <path
                                d="M80-200v-80h400v80H80Zm0-200v-80h200v80H80Zm0-200v-80h200v80H80Zm744 400L670-354q-24 17-52.5 25.5T560-320q-83 0-141.5-58.5T360-520q0-83 58.5-141.5T560-720q83 0 141.5 58.5T760-520q0 29-8.5 57.5T726-410l154 154-56 56ZM560-400q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Z" />
                        </svg>
                <span class="side-menu__label">&nbsp;Finance</span>
                <i class="fe fe-chevron-right side-menu__angle"></i>
            </a>
            <ul class="slide-menu child1">
                @if (in_array('admin.transactions', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('admin.transactions') }}" class="side-menu__item {{Route::is('admin.debit.customer') ? 'active' : '' }}">Transactions</a>
                </li>
                @endif
                @if (in_array('admin.credit.customer', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('admin.credit.customer') }}" class="side-menu__item {{Route::is('admin.credit.customer') ? 'active' : '' }}">Credit Customer</a>
                </li>
                @endif
                @if (in_array('admin.debit.customer', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('admin.debit.customer') }}" class="side-menu__item {{Route::is('admin.debit.customer') ? 'active' : '' }}">Debit Customer</a>
                </li>
                @endif
                @if (in_array('Deposit Transactions', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('admin.deposit.transactions') }}" class="side-menu__item {{Route::is('admin.deposit.transactions') ? 'active' : '' }}">Deposit Transactions</a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        @if (in_array('Notifications', allowedPermissions()))
        <li class="slide">
            <a href="{{ route('notification.index') }}" class="side-menu__item {{Route::is('notification.index') ? 'active' : '' }}">
                <i class="bx bx-medal side-menu__icon"></i>
                <span class="side-menu__label">Notifications</span>
            </a>
        </li>
        @endif
        @if (in_array('Settings', allowedPermissions()))
        <li class="slide has-sub">
            <a href="javascript:void(0);" class="side-menu__item">
                <svg xmlns="http://www.w3.org/2000/svg"
                            height="24" viewBox="0 -960 960 960" width="24">
                            <path
                                d="m480-560-56-56 63-64H320v-80h167l-64-64 57-56 160 160-160 160ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z"
                                fill="#fff" />
                        </svg>
                <span class="side-menu__label">&nbsp;Settings</span>
                <i class="fe fe-chevron-right side-menu__angle"></i>
            </a>
            <ul class="slide-menu child1">
                @if (in_array('settings.edit', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('settings.edit') }}" class="side-menu__item {{Route::is('settings.edit') ? 'active' : '' }}">General Settings</a>
                </li>
                @endif
                @if (in_array('blacklist.index', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('blacklist.index') }}" class="side-menu__item">Blacklist Biller</a>
                </li>
                @endif
                @if (in_array('ordernumbers.index', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('ordernumbers.index') }}" class="side-menu__item">Order Number(s)</a>
                </li>
                @endif
                @if (in_array('bank.index', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('bank.index') }}" class="side-menu__item {{Route::is('bank.index') ? 'active' : '' }}">Manual Bank Details</a>
                </li>
                @endif
                @if (in_array('kyc.admin.settings', allowedPermissions()))
                <li class="slide">
                    <a href="{{ route('kyc.admin.settings') }}" class="side-menu__item {{Route::is('kyc.admin.settings') ? 'active' : '' }}">Kyc Settings</a>
                </li>
                @endif
            </ul>
        </li>
        @endif
    </ul>
    {{-- <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
        height="24" viewBox="0 0 24 24">
        <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
    </svg></div> --}}
</nav>