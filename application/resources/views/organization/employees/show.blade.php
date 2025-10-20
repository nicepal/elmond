@extends('organization.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="user-profile-thumb">
                                <img src="{{ getImage(getFilePath('userProfile').'/'.@$employee->image, getFileSize('userProfile')) }}" alt="@lang('image')" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                            <h4 class="mt-3">{{ $employee->fullname }}</h4>
                            <p class="text-muted">{{ $employee->pivot->role ?? 'Employee' }}</p>
                            @if($employee->status && $employee->pivot->status)
                                <span class="badge badge--success">@lang('Active')</span>
                            @else
                                <span class="badge badge--warning">@lang('Inactive')</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Full Name')</label>
                                    <p class="form-control-plaintext">{{ $employee->fullname }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Email')</label>
                                    <p class="form-control-plaintext">{{ $employee->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Mobile')</label>
                                    <p class="form-control-plaintext">+{{ $employee->country_code }}{{ $employee->mobile }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Country')</label>
                                    <p class="form-control-plaintext">{{ @$employee->country_name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Role')</label>
                                    <p class="form-control-plaintext">{{ ucfirst($employee->pivot->role ?? 'employee') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Department')</label>
                                    <p class="form-control-plaintext">{{ $employee->pivot->department ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Job Title')</label>
                                    <p class="form-control-plaintext">{{ $employee->pivot->job_title ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Joined Date')</label>
                                    <p class="form-control-plaintext">{{ showDateTime($employee->pivot->joined_at, 'd M Y') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Registration Date')</label>
                                    <p class="form-control-plaintext">{{ showDateTime($employee->created_at, 'd M Y') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Last Login')</label>
                                    <p class="form-control-plaintext">
                                        @if($employee->login_at)
                                            {{ showDateTime($employee->login_at, 'd M Y h:i A') }}
                                        @else
                                            @lang('Never')
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-6">
        <div class="card b-radius--10">
            <div class="card-header">
                <h5 class="card-title mb-0">@lang('Course Enrollments')</h5>
            </div>
            <div class="card-body">
                @if($employee->enrollments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table--light">
                            <thead>
                                <tr>
                                    <th>@lang('Course')</th>
                                    <th>@lang('Progress')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->enrollments->take(5) as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->course->title }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $enrollment->progress }}%" aria-valuenow="{{ $enrollment->progress }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ $enrollment->progress }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($enrollment->status == 1)
                                            <span class="badge badge--success">@lang('Active')</span>
                                        @else
                                            <span class="badge badge--warning">@lang('Inactive')</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($employee->enrollments->count() > 5)
                        <div class="text-center mt-3">
                            <small class="text-muted">@lang('Showing 5 of') {{ $employee->enrollments->count() }} @lang('enrollments')</small>
                        </div>
                    @endif
                @else
                    <p class="text-muted text-center">@lang('No course enrollments found')</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card b-radius--10">
            <div class="card-header">
                <h5 class="card-title mb-0">@lang('Statistics')</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h3 class="text--primary">{{ $employee->enrollments->count() }}</h3>
                            <p class="text-muted mb-0">@lang('Total Enrollments')</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <h3 class="text--success">{{ $employee->enrollments->where('status', 1)->count() }}</h3>
                        <p class="text-muted mb-0">@lang('Active Enrollments')</p>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h3 class="text--info">{{ number_format($employee->enrollments->avg('progress'), 1) }}%</h3>
                            <p class="text-muted mb-0">@lang('Avg Progress')</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <h3 class="text--warning">{{ $employee->enrollments->where('progress', 100)->count() }}</h3>
                        <p class="text-muted mb-0">@lang('Completed')</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<div class="d-flex flex-wrap gap-2">
    <a href="{{ route('organization.employees.index') }}" class="btn btn-sm btn--secondary"><i class="las la-list"></i> @lang('All Employees')</a>
    <a href="{{ route('organization.employees.edit', $employee->id) }}" class="btn btn-sm btn--primary"><i class="las la-pen"></i> @lang('Edit Employee')</a>
</div>
@endpush