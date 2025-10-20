@extends('organization.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body">
                <form action="{{ route('organization.employees.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('First Name') <span class="text--danger">*</span></label>
                                <input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Last Name') <span class="text--danger">*</span></label>
                                <input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Email') <span class="text--danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Mobile Number') <span class="text--danger">*</span></label>
                                <div class="input-group">
                                    <select name="country_code" class="form-select country-code" required>
                                        @foreach($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->dial_code }}" @selected(old('country_code') == $country->dial_code)>{{ __($country->country) }} ({{ $country->dial_code }})</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="mobile" value="{{ old('mobile') }}" class="form-control checkUser" placeholder="@lang('Your Phone Number')" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Password') <span class="text--danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="las la-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">@lang('Minimum 6 characters required')</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Confirm Password') <span class="text--danger">*</span></label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Employee Role')</label>
                                <select name="role" class="form-select">
                                    <option value="employee" @selected(old('role') == 'employee')>@lang('Employee')</option>
                                    <option value="manager" @selected(old('role') == 'manager')>@lang('Manager')</option>
                                    <option value="supervisor" @selected(old('role') == 'supervisor')>@lang('Supervisor')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Department')</label>
                                <input type="text" class="form-control" name="department" value="{{ old('department') }}" placeholder="@lang('e.g., IT, HR, Sales')">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Job Title')</label>
                                <input type="text" class="form-control" name="job_title" value="{{ old('job_title') }}" placeholder="@lang('e.g., Software Developer, HR Manager')">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Enroll in Courses')</label>
                                <small class="text-muted d-block mb-2">@lang('Select courses to automatically enroll this employee')</small>
                                @if($courses->count() > 0)
                                    <div class="row">
                                        @foreach($courses as $course)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="courses[]" value="{{ $course->id }}" id="course_{{ $course->id }}" @checked(in_array($course->id, old('courses', [])))>
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="send_credentials" id="sendCredentials" value="1" @checked(old('send_credentials'))>
                                    <label class="form-check-label" for="sendCredentials">
                                        @lang('Send login credentials via email')
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Add Employee')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<a href="{{ route('organization.employees.index') }}" class="btn btn-sm btn--primary"><i class="las la-list"></i> @lang('All Employees')</a>
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
        
        // Check if user exists
        $('.checkUser').on('focusout', function(e) {
            var url = '{{ route('user.checkUser') }}';
            var value = e.target.value;
            var token = '{{ csrf_token() }}';
            
            if ($(this).attr('name') == 'mobile') {
                var mobile = `${$('.country-code').val()}${value}`;
                var data = {mobile: mobile, _token: token}
            }
            if ($(this).attr('name') == 'email') {
                var data = {email: value, _token: token}
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