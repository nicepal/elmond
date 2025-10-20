@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive p-4">
                        <form action="{{ route('admin.organizations.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Company Name') <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="company_name" value="{{ old('company_name') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Contact Person Name') <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="contact_person_name" value="{{ old('contact_person_name') }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Email') <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Designation')</label>
                                            <input type="text" class="form-control" name="designation" value="{{ old('designation') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Password -->
                                <div class="col-md-6">
                                    <div class="mb-4 form-group">
                                        <label>@lang('Password') <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password" placeholder="Password *" required>
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <div class="mb-4 form-group">
                                        <label>@lang('Confirm Password') <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="confirmed" placeholder="Confirm Password *" required>
                                    </div>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Country') <span class="text-danger">*</span></label>
                                            <select name="country_code" class="form-control" required>
                                                <option value="">@lang('Select One')</option>
                                                @foreach($countries as $key => $country)
                                                    <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->dial_code }}" data-code="{{ $key }}">{{ __($country->country) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Mobile') <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text mobile-code"></span>
                                                <input type="number" name="mobile" value="{{ old('mobile') }}" class="form-control checkUser" required>
                                            </div>
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
                                
                                <!-- Course Assignment Section -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('Assign Courses')</label>
                                            <div class="row">
                                                @foreach($courses as $course)
                                                    <div class="col-md-6 mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="courses[]" value="{{ $course->id }}" id="course_{{ $course->id }}">
                                                            <label class="form-check-label" for="course_{{ $course->id }}">
                                                {{ $course->name }} 
                                                <span class="text-muted">({{ $general->cur_sym }}{{ showAmount($course->price) }})</span>
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
                                <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
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
            $('select[name=country_code]').change(function(){
                $('input[name=mobile_code]').val($('select[name=country_code] :selected').data('mobile_code'));
                $('input[name=country]').val($('select[name=country_code] :selected').data('code'));
                $('.mobile-code').text('+'+$('select[name=country_code] :selected').data('mobile_code'));
            });
            $('select[name=country_code]').val('{{ old('country_code') }}');
            var phoneCode = $('select[name=country_code] :selected').data('mobile_code');
            var countryCode = $('select[name=country_code] :selected').data('code');
            $('input[name=mobile_code]').val(phoneCode);
            $('input[name=country]').val(countryCode);
            $('.mobile-code').text('+'+phoneCode);
        @endif
    })(jQuery);
</script>
@endpush