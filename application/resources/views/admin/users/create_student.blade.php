@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <form action="{{ route('admin.users.create.student.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('First Name') <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Last Name') <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Username') <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="username" value="{{ old('username') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Email') <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Mobile Number') <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text mobile-code"></span>
                                            <input type="number" name="mobile" value="{{ old('mobile') }}" class="form-control checkUser" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Country') <span class="text-danger">*</span></label>
                                        <select name="country" class="form-control" required>
                                            @foreach($countries as $key => $country)
                                                <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}" {{ old('country') == $key ? 'selected' : '' }}>{{ __($country->country) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Password') <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password" required>
                                        <small class="text-muted">@lang('This password will be sent to the student via email')</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Address') <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="address" value="{{ old('address') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('City') <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="city" value="{{ old('city') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('State') <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="state" value="{{ old('state') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('Zip Code') <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="zip" value="{{ old('zip') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Select Courses to Enroll')</label>
                                        <div class="row">
                                            @foreach($courses as $course)
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="courses[]" value="{{ $course->id }}" id="course_{{ $course->id }}">
                                                        <label class="form-check-label" for="course_{{ $course->id }}">
                                                            {{ $course->name }} 
                                                            <small class="text-muted">({{ $general->cur_sym }}{{ showAmount($course->price) }})</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn--primary btn-global">@lang('Create Student')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    "use strict";
    (function ($) {
        @if($countries)
            $('select[name=country]').change(function(){
                $('input[name=mobile]').val('');
                var curText = $('select[name=country] :selected').data('mobile_code');
                $('.mobile-code').text('+'+curText);
            });
            $('select[name=country] :selected').trigger('change');
        @endif
    })(jQuery);
</script>
@endpush