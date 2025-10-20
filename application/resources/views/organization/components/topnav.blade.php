<!-- navbar-wrapper start -->
<nav class="navbar-wrapper">
    <div class="navbar__left">
        <button type="button" class="res-sidebar-open-btn me-3"><i class="las la-bars"></i></button>
        <form class="navbar-search">
            <input type="search" name="#0" class="navbar-search-field" id="searchInput" autocomplete="off"
                placeholder="@lang('Search Options...')">
            <i class="fas fa-search text--primary"></i>
            <ul class="search-list"></ul>
        </form>
    </div>
    <div class="navbar__right">
        <ul class="navbar__action-list">
            <li>
                <a title="@lang('Visit Site')" href="{{ route('home') }}" target="_blank" class="btn btn-sm btn--primary"><i class="fas fa-globe-americas"></i></a>
            </li>
            <li class="dropdown">
                <button type="button" class="primary--layer" data-bs-toggle="dropdown" data-display="static"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="las la-user-circle text--primary"></i>
                </button>
                <div class="dropdown-menu dropdown-menu--sm p-0 border-0 box--shadow1 dropdown-menu-right">
                    <a href="{{ route('organization.profile') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                        <i class="dropdown-menu__icon las la-user-edit"></i>
                        <span class="dropdown-menu__caption">@lang('Profile')</span>
                    </a>
                    
                    <a href="{{ route('organization.logout') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                        <i class="dropdown-menu__icon las la-sign-out-alt"></i>
                        <span class="dropdown-menu__caption">@lang('Logout')</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>
<!-- navbar-wrapper end -->