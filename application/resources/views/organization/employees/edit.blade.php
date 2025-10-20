@extends('organization.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body">
                <form action="{{ route('organization.employees.update', $employee->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('First Name') <span class="text--danger">*</span></label>
                                <input type="text" class="form-control" name="firstname" value="{{ old('firstname', $employee->firstname) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Last Name') <span class="text--danger">*</span></label>
                                <input type="text" class="form-control" name="lastname" value="{{ old('lastname', $employee->lastname) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Email') <span class="text--danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ old('email', $employee->email) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Mobile Number') <span class="text--danger">*</span></label>
                                <div class="input-group">
                                    <select name="country_code" class="form-select country-code" required>
                                        @foreach($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->dial_code }}" @selected(old('country_code', $employee->country_code) == $country->dial_code)>{{ __($country->country) }} ({{ $country->dial_code }})</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="mobile" value="{{ old('mobile', $employee->mobile) }}" class="form-control checkUser" placeholder="@lang('Your Phone Number')" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Employee Role')</label>
                                <select name="role" class="form-select">
                                    <option value="employee" @selected(old('role', $employee->pivot->role) == 'employee')>@lang('Employee')</option>
                                    <option value="manager" @selected(old('role', $employee->pivot->role) == 'manager')>@lang('Manager')</option>
                                    <option value="supervisor" @selected(old('role', $employee->pivot->role) == 'supervisor')>@lang('Supervisor')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Status')</label>
                                <select name="status" class="form-select">
                                    <option value="1" @selected(old('status', $employee->pivot->status) == '1')>@lang('Active')</option>
                                    <option value="0" @selected(old('status', $employee->pivot->status) == '0')>@lang('Inactive')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Department')</label>
                                <input type="text" class="form-control" name="department" value="{{ old('department', $employee->pivot->department) }}" placeholder="@lang('e.g., IT, HR, Sales')">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Job Title')</label>
                                <input type="text" class="form-control" name="job_title" value="{{ old('job_title', $employee->pivot->job_title) }}" placeholder="@lang('e.g., Software Developer, HR Manager')">
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="mb-3">@lang('Change Password') <small class="text-muted">(@lang('Leave blank to keep current password'))</small></h5>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('New Password')</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" placeholder="@lang('Enter new password')">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="las la-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">@lang('Minimum 6 characters required')</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Confirm New Password')</label>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="@lang('Confirm new password')">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Course Enrollments')</label>
                                <small class="text-muted d-block mb-2">@lang('Select courses to enroll this employee')</small>
                                @if($courses->count() > 0)
                                    <div class="row">
                                        @foreach($courses as $course)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="courses[]" value="{{ $course->id }}" id="course_{{ $course->id }}" @checked(in_array($course->id, old('courses', $currentEnrollments)))>
                                                    <label class="form-check-label" for="course_{{ $course->id }}">
                                                        {{ $course->name }}
                                                        <small class="text-muted d-block">@lang('Price'): {{ showAmount($course->pivot->assigned_price ?? $course->price) }} {{ __($general->cur_text) }}</small>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">@lang('No courses available for enrollment')</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Update Employee')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
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
                                    <th>@lang('Enrolled Date')</th>
                                    <th>@lang('Progress')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->enrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->course->title }}</td>
                                    <td>{{ showDateTime($enrollment->created_at, 'd M Y') }}</td>
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
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline--danger removeEnrollmentBtn" data-id="{{ $enrollment->id }}" data-course="{{ $enrollment->course->title }}">
                                            <i class="las la-trash"></i> @lang('Remove')
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">@lang('No course enrollments found')</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Remove Enrollment Modal --}}
<div id="removeEnrollmentModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Remove Enrollment')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>@lang('Are you sure you want to remove this employee from the course'): <strong class="course-name"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn--danger">@lang('Remove')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<div class="d-flex flex-wrap gap-2">
    <a href="{{ route('organization.employees.index') }}" class="btn btn-sm btn--secondary"><i class="las la-list"></i> @lang('All Employees')</a>
    <a href="{{ route('organization.employees.show', $employee->id) }}" class="btn btn-sm btn--info"><i class="las la-eye"></i> @lang('View Details')</a>
</div>
@endpush

@push('script')
<script>
    (function($) {
        'use strict';
        
        // Toggle password visibility
        $('#togglePassword').on('click', function() {
            const passwordField = $('input[name="password"]');
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            $(this).find('i').toggleClass('la-eye la-eye-slash');
        });
        
        // Remove enrollment
        $('.removeEnrollmentBtn').on('click', function() {
            var modal = $('#removeEnrollmentModal');
            var enrollmentId = $(this).data('id');
            var courseName = $(this).data('course');
            
            modal.find('.course-name').text(courseName);
            modal.find('form').attr('action', `{{ route('organization.employees.enrollment.destroy', ['employee' => $employee->id, 'enrollment' => '__ID__']) }}`.replace('__ID__', enrollmentId));
            modal.modal('show');
        });
        
        // Check if user exists
        $('.checkUser').on('focusout', function(e) {
            var url = '{{ route('user.checkUser') }}';
            var value = e.target.value;
            var token = '{{ csrf_token() }}';
            var currentUserId = '{{ $employee->id }}';
            
            if ($(this).attr('name') == 'mobile') {
                var mobile = `${$('.country-code').val()}${value}`;
                var data = {mobile: mobile, _token: token, exclude_id: currentUserId}
            }
            if ($(this).attr('name') == 'email') {
                var data = {email: value, _token: token, exclude_id: currentUserId}
            }
            
            $.post(url, data, function(response) {
                if (response.data != false) {
                    $(`.${response.type}Exist`).text(`${response.field} already exist`);
                } else {
                    $(`.${response.type}Exist`).text('');
                }
            });
        });
        
    })(jQuery);
</script>
@endpush