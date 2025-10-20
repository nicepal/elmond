@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--md table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Code')</th>
                                <th>@lang('Discount')</th>
                                <th>@lang('Usage / Limit')</th>
                                <th>@lang('Validity')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coupons as $coupon)
                            <tr>
                                <td data-label="@lang('Name')">
                                    <span class="fw-bold">{{ $coupon->name }}</span>
                                    @if($coupon->is_first_purchase_only)
                                    <span class="badge badge--primary">First Purchase</span>
                                    @endif
                                    @if($coupon->is_registration_bonus)
                                    <span class="badge badge--success">Registration Bonus</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Code')"><span class="badge badge--dark">{{ $coupon->code }}</span></td>
                                <td data-label="@lang('Discount')">
                                    @if($coupon->discount_type == 'percentage')
                                    {{ $coupon->discount_amount }}%
                                    @else
                                    ₹{{ number_format($coupon->discount_amount, 2) }}
                                    @endif
                                    @if($coupon->minimum_purchase > 0)
                                    <small class="d-block">Min: ₹{{ number_format($coupon->minimum_purchase, 2) }}</small>
                                    @endif
                                </td>
                                <td data-label="@lang('Usage / Limit')">
                                    {{ $coupon->usages()->count() }} / {{ $coupon->usage_limit ?: '∞' }}
                                    @if($coupon->usage_limit_per_user)
                                    <small class="d-block">{{ $coupon->usage_limit_per_user }} per user</small>
                                    @endif
                                </td>
                                <td data-label="@lang('Validity')">
                                    @if($coupon->starts_at)
                                    <span>From: {{ $coupon->starts_at->format('M d, Y') }}</span><br>
                                    @endif
                                    @if($coupon->expires_at)
                                    <span>Until: {{ $coupon->expires_at->format('M d, Y') }}</span>
                                    @else
                                    <span class="text-muted">No expiry</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Status')">
                                    @if($coupon->active)
                                    <span class="badge badge--success">@lang('Active')</span>
                                    @else
                                    <span class="badge badge--danger">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-outline--primary">
                                        <i class="la la-pencil"></i> @lang('Edit')
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                        data-action="{{ route('admin.coupons.destroy', $coupon->id) }}"
                                        data-question="@lang('Are you sure to delete this coupon?')">
                                        <i class="la la-trash"></i> @lang('Delete')
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($coupons->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($coupons) }}
            </div>
            @endif
        </div>
    </div>
</div>

<div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="question"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<a href="{{ route('admin.coupons.create') }}" class="btn btn-sm btn--primary box--shadow1 text--small">
    <i class="la la-plus"></i> @lang('Add New')
</a>
@endpush

@push('script')
<script>
    (function($) {
        "use strict";
        
        $('.confirmationBtn').on('click', function() {
            var modal = $('#confirmationModal');
            modal.find('form').attr('action', $(this).data('action'));
            modal.find('.question').text($(this).data('question'));
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush