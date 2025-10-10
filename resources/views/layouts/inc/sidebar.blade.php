<div class="kt-aside kt-aside--fixed kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">
    <div class="kt-aside__brand kt-grid__item" id="kt_aside_brand">
        <div class="kt-aside__brand-logo">
            <a href="{{ url('/') }}">
                <img alt="Logo" src="{{ asset('images/logo/logo.png') }}" width="155px" height="60px" />
            </a>
        </div>
        <div class="kt-aside__brand-tools">
            <button class="kt-aside__brand-aside-toggler kt-aside__brand-aside-toggler--left" id="kt_aside_toggler">
                <span></span>
            </button>
        </div>
    </div>

    <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
        <div id="kt_aside_menu" class="kt-aside-menu" data-ktmenu-vertical="1" data-ktmenu-scroll="1"
            data-ktmenu-dropdown-timeout="500">
            <ul class="kt-menu__nav">
                {{-- Dashboard --}}
                <li class="kt-menu__item @if (Request::is('dashboard')) kt-menu__item--here @endif" aria-haspopup="true">
                    <a href="{{ route('dashboard') }}" class="kt-menu__link">
                        <i class="kt-menu__link-icon flaticon2-graphic"></i>
                        <span class="kt-menu__link-text">Dashboard</span>
                    </a>
                </li>

                {{-- Post Management --}}
                <li class="kt-menu__section">
                    <h4 class="kt-menu__section-text">Post Management</h4>
                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                </li>
                <li class="kt-menu__item @if (Request::is('posts')) kt-menu__item--here @endif" aria-haspopup="true">
                    <a href="{{ route('posts.index') }}" class="kt-menu__link">
                        <i class="kt-menu__link-icon flaticon2-edit"></i>
                        <span class="kt-menu__link-text">Create New Post</span>
                    </a>
                </li>

                {{-- Group Management --}}
                <li class="kt-menu__section">
                    <h4 class="kt-menu__section-text">Group Management</h4>
                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                </li>
                <li class="kt-menu__item @if (Request::is('groups/import')) kt-menu__item--here @endif" aria-haspopup="true">
                    <a href="{{ route('groups.import') }}" class="kt-menu__link">
                        <i class="kt-menu__link-icon flaticon2-download-1"></i>
                        <span class="kt-menu__link-text">Import Groups</span>
                    </a>
                </li>
                <li class="kt-menu__item @if (Request::is('groups')) kt-menu__item--here @endif" aria-haspopup="true">
                    <a href="{{ route('groups.index') }}" class="kt-menu__link">
                        <i class="kt-menu__link-icon flaticon2-list"></i>
                        <span class="kt-menu__link-text">Group List</span>
                    </a>
                </li>
                <li class="kt-menu__item @if (Request::is('categories')) kt-menu__item--here @endif" aria-haspopup="true">
                    <a href="{{ route('categories.index') }}" class="kt-menu__link">
                        <i class="kt-menu__link-icon flaticon2-tag"></i>
                        <span class="kt-menu__link-text">Group Categories</span>
                    </a>
                </li>

                {{-- Reports --}}
                <li class="kt-menu__section">
                    <h4 class="kt-menu__section-text">Reports</h4>
                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                </li>
                <li class="kt-menu__item @if (Request::is('reports')) kt-menu__item--here @endif" aria-haspopup="true">
                    <a href="{{ route('reports.index') }}" class="kt-menu__link">
                        <i class="kt-menu__link-icon flaticon2-line-chart"></i>
                        <span class="kt-menu__link-text">Post Reports</span>
                    </a>
                </li>

                {{-- Settings --}}
                <li class="kt-menu__section">
                    <h4 class="kt-menu__section-text">Settings</h4>
                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                </li>
                <li class="kt-menu__item @if (Request::is('settings/facebook')) kt-menu__item--here @endif" aria-haspopup="true">
                    <a href="{{ route('settings.facebook') }}" class="kt-menu__link">
                        <i class="kt-menu__link-icon flaticon2-gear"></i>
                        <span class="kt-menu__link-text">Facebook Connection</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
