@extends('organization.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <form action="{{ route('organization.profile.update') }}" method="POST">
                        @csrf
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{ __($pageTitle) }}</h5>
                        </div>
                        <div class="card-body">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Email Address') <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" value="{{ old('email', $organization->email) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Designation')</label>
                                        <input type="text" class="form-control" name="designation" value="{{ old('designation', $organization->designation) }}" placeholder="@lang('e.g., CEO, Manager')">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Mobile Number') <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="mobile" value="{{ old('mobile', $organization->mobile) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Address') <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="address" value="{{ old('address', $organization->address) }}" required>
                                    </div>
                                </div>
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
                                        <label>@lang('ZIP Code') <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="zip" value="{{ old('zip', $organization->zip) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Update Profile')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection