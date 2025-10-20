@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-3 col-lg-5 col-md-5 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Organization Information')</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Company Name')
                            <span class="fw-bold">{{ $organization->company_name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Contact Person')
                            <span>{{ $organization->contact_person_name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Email')
                            <span>{{ $organization->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Mobile')
                            <span>{{ $organization->country_code }}{{ $organization->mobile }}</span>
                        </li>
                        @if($organization->designation)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Designation')
                            <span>{{ $organization->designation }}</span>
                        </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @php echo $organization->statusBadge @endphp
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Joined')
                            <span>{{ showDateTime($organization->created_at) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card b-radius--10 overflow-hidden mt-30">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Address Information')</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Address')
                            <span>{{ $organization->address }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('City')
                            <span>{{ $organization->city }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('State')
                            <span>{{ $organization->state }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Zip Code')
                            <span>{{ $organization->zip }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-xl-9 col-lg-7 col-md-7 mb-30">
            <div class="row mb-none-30">
                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--info">
                        <div class="widget-two__icon">
                            <i class="las la-users f-size--56"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{ $totalEmployees }}</h3>
                            <p class="text-white">@lang('Total Employees')</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--success">
                        <div class="widget-two__icon">
                            <i class="las la-user-check f-size--56"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{ $activeEmployees }}</h3>
                            <p class="text-white">@lang('Active Employees')</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--primary">
                        <div class="widget-two__icon">
                            <i class="las la-graduation-cap f-size--56"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{ $totalCourses }}</h3>
                            <p class="text-white">@lang('Total Courses')</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--warning">
                        <div class="widget-two__icon">
                            <i class="las la-book-open f-size--56"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{ $activeCourses }}</h3>
                            <p class="text-white">@lang('Active Courses')</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Employees Section -->
            <div class="card mt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Employees') ({{ $totalEmployees }})</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Employee')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Department')</th>
                                    <th>@lang('Position')</th>
                                    <th>@lang('Joined')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($organization->users as $user)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $user->fullname }}</span><br>
                                            <span class="small text-muted">{{ '@'.$user->username }}</span>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->pivot->department ?? 'N/A' }}</td>
                                        <td>{{ $user->pivot->position ?? 'N/A' }}</td>
                                        <td>{{ showDateTime($user->pivot->joined_at ?? $user->pivot->created_at) }}</td>
                                        <td>
                                            @if($user->pivot->status == 1)
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Inactive')</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">@lang('No employees found')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Courses Section -->
            <div class="card mt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Assigned Courses') ({{ $totalCourses }})</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Course')</th>
                                    <th>@lang('Original Price')</th>
                                    <th>@lang('Assigned Price')</th>
                                    <th>@lang('Assigned Date')</th>
                                    <th>@lang('Expires')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($organization->courses as $course)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $course->title }}</span><br>
                                            <span class="small text-muted">{{ $course->category->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>{{ $general->cur_sym }}{{ showAmount($course->price) }}</td>
                                        <td>{{ $general->cur_sym }}{{ showAmount($course->pivot->assigned_price) }}</td>
                                        <td>{{ showDateTime($course->pivot->assigned_at) }}</td>
                                        <td>
                                            @if($course->pivot->expires_at)
                                                {{ showDateTime($course->pivot->expires_at) }}
                                            @else
                                                @lang('No Expiry')
                                            @endif
                                        </td>
                                        <td>
                                            @if($course->pivot->status == 1)
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Inactive')</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">@lang('No courses assigned')</td>
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
    <a href="{{ route('admin.organizations.edit', $organization->id) }}" class="btn btn-sm btn-outline--primary">
        <i class="las la-pen"></i> @lang('Edit Organization')
    </a>
    <a href="{{ route('admin.organizations.index') }}" class="btn btn-sm btn-outline--dark">
        <i class="las la-list"></i> @lang('All Organizations')
    </a>
@endpush