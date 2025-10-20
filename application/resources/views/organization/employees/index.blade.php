@extends('organization.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--md table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Employee')</th>
                                <th>@lang('Email')</th>
                                <th>@lang('Mobile')</th>
                                <th>@lang('Joined Date')</th>
                                <th>@lang('Enrollments')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                            <tr>
                                <td>
                                    <div class="user">
                                        <div class="thumb">
                                            <img src="{{ getImage(getFilePath('userProfile').'/'.@$employee->image, getFileSize('userProfile')) }}" alt="@lang('image')">
                                        </div>
                                        <span class="name">{{ $employee->fullname }}</span>
                                    </div>
                                </td>
                                <td>{{ $employee->email }}</td>
                                <td>+{{ $employee->country_code }}{{ $employee->mobile }}</td>
                                <td>{{ showDateTime($employee->pivot->joined_at, 'd M Y') }}</td>
                                <td>
                                    <span class="fw-bold">{{ $employee->enrollments_count }}</span>
                                </td>
                                <td>
                                    @if($employee->status && $employee->pivot->status)
                                        <span class="badge badge--success">@lang('Active')</span>
                                    @else
                                        <span class="badge badge--warning">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="button--group">
                                        <a href="{{ route('organization.employees.show', $employee->id) }}" class="btn btn-sm btn-outline--primary">
                                            <i class="las la-eye"></i> @lang('Details')
                                        </a>
                                        <a href="{{ route('organization.employees.edit', $employee->id) }}" class="btn btn-sm btn-outline--primary">
                                            <i class="las la-pen"></i> @lang('Edit')
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('organization.employees.destroy', $employee->id) }}" data-question="@lang('Are you sure to remove this employee from your organization?')">
                                            <i class="las la-trash"></i> @lang('Remove')
                                        </button>
                                    </div>
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
            @if ($employees->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($employees) }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Confirmation Modal --}}
<div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p class="question"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<div class="d-flex flex-wrap gap-2">
    <form action="" method="GET" class="d-flex flex-wrap gap-2">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="@lang('Search by name, email, mobile')" value="{{ request()->search }}">
            <button class="btn btn--primary" type="submit"><i class="las la-search"></i></button>
        </div>
        <select name="status" class="form-select">
            <option value="">@lang('All Status')</option>
            <option value="1" @selected(request()->status == '1')>@lang('Active')</option>
            <option value="0" @selected(request()->status == '0')>@lang('Inactive')</option>
        </select>
        <button class="btn btn--secondary" type="submit"><i class="las la-filter"></i> @lang('Filter')</button>
    </form>
    <a href="{{ route('organization.employees.create') }}" class="btn btn-sm btn--primary"><i class="las la-plus"></i>@lang('Add New')</a>
</div>
@endpush

@push('script')
<script>
    (function($) {
        'use strict';
        $('.confirmationBtn').on('click', function() {
            var modal = $('#confirmationModal');
            var data = $(this).data();
            modal.find('.question').text(`${data.question}`);
            modal.find('form').attr('action', `${data.action}`);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush