@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive p-4">
                        <form action="{{ route('admin.organizations.update', $organization->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Company Name') <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="company_name" value="{{ old('company_name', $organization->company_name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Contact Person Name') <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="contact_person_name" value="{{ old('contact_person_name', $organization->contact_person_name) }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Email') <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" value="{{ old('email', $organization->email) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Designation')</label>
                                            <input type="text" class="form-control" name="designation" value="{{ old('designation', $organization->designation) }}">
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
                                                    <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->dial_code }}" data-code="{{ $key }}" {{ $organization->country_code == $country->dial_code ? 'selected' : '' }}>{{ __($country->country) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Mobile') <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text mobile-code">{{ $organization->country_code }}</span>
                                                <input type="number" name="mobile" value="{{ old('mobile', $organization->mobile) }}" class="form-control checkUser" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('Address') <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="address" value="{{ old('address', $organization->address) }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('City') <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="city" value="{{ old('city', $organization->city) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('State') <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="state" value="{{ old('state', $organization->state) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Zip Code') <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="zip" value="{{ old('zip', $organization->zip) }}" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Status Field -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Status') <span class="text-danger">*</span></label>
                                            <select name="status" class="form-control" required>
                                                <option value="1" {{ old('status', $organization->status) == 1 ? 'selected' : '' }}>@lang('Active')</option>
                                                <option value="0" {{ old('status', $organization->status) == 0 ? 'selected' : '' }}>@lang('Inactive')</option>
                                            </select>
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
                                                            <input class="form-check-input" type="checkbox" name="courses[]" value="{{ $course->id }}" id="course_{{ $course->id }}" {{ in_array($course->id, $assignedCourses) ? 'checked' : '' }}>
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
                                <button type="submit" class="btn btn--primary w-100 h-45">@lang('Update Organization')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.organizations.show', $organization->id) }}" class="btn btn-sm btn-outline--primary">
        <i class="las la-eye"></i> @lang('View Details')
    </a>
    <a href="{{ route('admin.organizations.index') }}" class="btn btn-sm btn-outline--dark">
        <i class="las la-list"></i> @lang('All Organizations')
    </a>
@endpush

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
            
            // Initialize mobile code display
            var phoneCode = $('select[name=country_code] :selected').data('mobile_code');
            var countryCode = $('select[name=country_code] :selected').data('code');
            $('input[name=mobile_code]').val(phoneCode);
            $('input[name=country]').val(countryCode);
            $('.mobile-code').text('+'+phoneCode);
        @endif
    })(jQuery);
</script>
@endpush