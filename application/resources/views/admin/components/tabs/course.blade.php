<div class="row">
    <div class="col">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.course.index') ? 'active' : '' }}"
                    href="{{route('admin.course.index')}}">@lang('My courses')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.course.new.launches') ? 'active' : '' }}"
                    href="{{route('admin.course.new.launches')}}">@lang('New Launches')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.course.upcoming') ? 'active' : '' }}"
                    href="{{route('admin.course.upcoming')}}">@lang('Upcoming Courses')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.course.featured') ? 'active' : '' }}"
                    href="{{route('admin.course.featured')}}">@lang('Featured Courses')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.course.instructor') ? 'active' : '' }}"
                    href="{{route('admin.course.instructor')}}">@lang('Instructor Course')
                </a>
            </li>
        </ul>
    </div>
</div>
