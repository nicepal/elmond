@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Coupon Name')</label>
                                <input type="text" class="form-control" name="name" required value="{{ old('name', $coupon->name) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Coupon Code')</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="code" required value="{{ old('code', $coupon->code) }}">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn--primary generate-code">@lang('Generate')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Description')</label>
                                <textarea class="form-control" name="description" rows="3">{{ old('description', $coupon->description) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Discount Type')</label>
                                <select class="form-control" name="discount_type" required>
                                    <option value="percentage" {{ old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : '' }}>@lang('Percentage')</option>
                                    <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>@lang('Fixed Amount')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Discount Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" class="form-control" name="discount_amount" required value="{{ old('discount_amount', $coupon->discount_amount) }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text discount-type-addon">{{ $coupon->discount_type == 'percentage' ? '%' : '₹' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Minimum Purchase Amount')</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">₹</span>
                                    </div>
                                    <input type="number" step="0.01" min="0" class="form-control" name="minimum_purchase" value="{{ old('minimum_purchase', $coupon->minimum_purchase) }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Start Date')</label>
                                <input type="datetime-local" class="form-control" name="starts_at" value="{{ old('starts_at', $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Expiry Date')</label>
                                <input type="datetime-local" class="form-control" name="expires_at" value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Usage Limit (Total)')</label>
                                <input type="number" min="0" class="form-control" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" placeholder="Unlimited if empty">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Usage Limit Per User')</label>
                                <input type="number" min="0" class="form-control" name="usage_limit_per_user" value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user) }}" placeholder="Unlimited if empty">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Applicable Courses')</label>
                                <select class="form-control select2-multi-select" name="applicable_courses[]" multiple>
                                    <option value="">@lang('All Courses')</option>
                                    @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ (is_array(old('applicable_courses', $coupon->applicable_courses)) && in_array($course->id, old('applicable_courses', $coupon->applicable_courses ?? []))) ? 'selected' : '' }}>
                                        {{ $course->title ?? $course->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">@lang('Leave empty to apply to all courses')</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_first_purchase_only" name="is_first_purchase_only" {{ old('is_first_purchase_only', $coupon->is_first_purchase_only) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_first_purchase_only">@lang('First Purchase Only')</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_registration_bonus" name="is_registration_bonus" {{ old('is_registration_bonus', $coupon->is_registration_bonus) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_registration_bonus">@lang('Registration Bonus')</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="active" name="active" {{ old('active', $coupon->active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="active">@lang('Active')</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn--primary btn-block">@lang('Update Coupon')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small">
    <i class="la la-fw la-backward"></i> @lang('Go Back')
</a>
@endpush

@push('script')
<script>
    (function($) {
        "use strict";
        
        // Update discount type addon
        $('select[name=discount_type]').on('change', function() {
            var type = $(this).val();
            if (type === 'percentage') {
                $('.discount-type-addon').text('%');
            } else {
                $('.discount-type-addon').text('₹');
            }
        });
        
        // Generate random coupon code
        $('.generate-code').on('click', function() {
            $.get('{{ route("admin.coupons.generate-code") }}', function(response) {
                $('input[name=code]').val(response.code);
            });
        });
        
        // Initialize select2
        $('.select2-multi-select').select2();
    })(jQuery);
</script>
@endpush