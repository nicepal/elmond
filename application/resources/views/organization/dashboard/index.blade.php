@extends('organization.layouts.app')

@section('panel')
<div class="row gy-4">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <div class="card prod-p-card background-pattern">
            <div class="card-body">
                <div class="row align-items-center m-b-0">
                    <div class="col">
                        <h6 class="m-b-5">@lang('Total Employees')</h6>
                        <h3 class="m-b-0">{{ $totalEmployees }}</h3>
                    </div>
                    <div class="col-auto">
                        <i class="dashboard-widget__icon las la-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <div class="card prod-p-card background-pattern">
            <div class="card-body">
                <div class="row align-items-center m-b-0">
                    <div class="col">
                        <h6 class="m-b-5">@lang('Active Employees')</h6>
                        <h3 class="m-b-0">{{ $activeEmployees }}</h3>
                    </div>
                    <div class="col-auto">
                        <i class="dashboard-widget__icon las la-user-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <div class="card prod-p-card background-pattern">
            <div class="card-body">
                <div class="row align-items-center m-b-0">
                    <div class="col">
                        <h6 class="m-b-5">@lang('Available Courses')</h6>
                        <h3 class="m-b-0">{{ $totalCourses }}</h3>
                    </div>
                    <div class="col-auto">
                        <i class="dashboard-widget__icon las la-book"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <div class="card prod-p-card background-pattern">
            <div class="card-body">
                <div class="row align-items-center m-b-0">
                    <div class="col">
                        <h6 class="m-b-5">@lang('Total Enrollments')</h6>
                        <h3 class="m-b-0">{{ $totalEnrollments }}</h3>
                    </div>
                    <div class="col-auto">
                        <i class="dashboard-widget__icon las la-graduation-cap"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row gy-4 mt-2">
    <!-- Recent Employees -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">@lang('Recent Employees')</h5>
                <a href="{{ route('organization.employees.index') }}" class="btn btn-sm btn--primary">@lang('View All')</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Employee')</th>
                                <th>@lang('Email')</th>
                                <th>@lang('Joined')</th>
                                <th>@lang('Status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEmployees as $employee)
                            <tr>
                                <td>
                                    <div class="user">
                                        <div class="thumb">
                                            <img src="{{ getImage(getFilePath('userProfile').'/'.@$employee->image, getFileSize('userProfile')) }}" alt="@lang('image')">
                                        </div>
                                        <span class="name">{{ $employee->fullname }}</span>
                                    </div>
                                </td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ showDateTime($employee->pivot->joined_at, 'd M Y') }}</td>
                                <td>
                                    @if($employee->status && $employee->pivot->status)
                                        <span class="badge badge--success">@lang('Active')</span>
                                    @else
                                        <span class="badge badge--warning">@lang('Inactive')</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Course Progress -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">@lang('Course Progress Overview')</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Course')</th>
                                <th>@lang('Enrolled')</th>
                                <th>@lang('Completed')</th>
                                <th>@lang('Progress')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($courseProgress as $course)
                            <tr>
                                <td>
                                    <div class="user">
                                        <div class="thumb">
                                            <img src="{{ getImage(getFilePath('course').'/'.@$course->image, getFileSize('course')) }}" alt="@lang('image')">
                                        </div>
                                        <span class="name">{{ strLimit($course->title, 30) }}</span>
                                    </div>
                                </td>
                                <td>{{ $course->enrollments_count }}</td>
                                <td>{{ $course->completed_count ?? 0 }}</td>
                                <td>
                                    @php
                                        $progress = $course->enrollments_count > 0 ? round(($course->completed_count ?? 0) / $course->enrollments_count * 100) : 0;
                                    @endphp
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">{{ $progress }}%</div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">@lang('No course data available')</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<a href="{{ route('organization.employees.create') }}" class="btn btn-sm btn--primary"><i class="las la-plus"></i>@lang('Add Employee')</a>
@endpush