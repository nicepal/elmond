<div class="sidebar">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{ route('organization.employees.index') }}" class="sidebar__main-logo"><img
                    src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="@lang('image')"></a>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar__menu-header">@lang('Employee Management')</li>
                <li class="sidebar-menu-item {{ menuActive('organization.employees.*') }}">
                    <a href="{{ route('organization.employees.index') }}" class="nav-link ">
                        <i class="menu-icon las la-users"></i>
                        <span class="menu-title">@lang('Manage Employees')</span>
                    </a>
                </li>
                
                <li class="sidebar-menu-item {{ menuActive('organization.employees.create') }}">
                    <a href="{{ route('organization.employees.create') }}" class="nav-link ">
                        <i class="menu-icon las la-user-plus"></i>
                        <span class="menu-title">@lang('Add Employee')</span>
                    </a>
                </li>
                
                <li class="sidebar__menu-header">@lang('Organization Settings')</li>
                <li class="sidebar-menu-item {{ menuActive('organization.profile') }}">
                    <a href="{{ route('organization.profile') }}" class="nav-link ">
                        <i class="menu-icon las la-user-cog"></i>
                        <span class="menu-title">@lang('Profile Settings')</span>
                    </a>
                </li>
                
                <li class="sidebar-menu-item">
                    <a href="{{ route('organization.logout') }}" class="nav-link ">
                        <i class="menu-icon las la-sign-out-alt"></i>
                        <span class="menu-title">@lang('Logout')</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>