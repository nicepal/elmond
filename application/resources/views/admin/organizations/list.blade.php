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
                                    <th>@lang('Company')</th>
                                    <th>@lang('Contact Person')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Mobile')</th>
                                    <th>@lang('Employees')</th>
                                    <th>@lang('Courses')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Joined At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($organizations as $organization)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $organization->company_name }}</span><br>
                                            <span class="small text-muted">{{ $organization->city }}, {{ $organization->state }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $organization->contact_person_name }}</span><br>
                                            @if($organization->designation)
                                                <span class="small text-muted">{{ $organization->designation }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $organization->email }}</td>
                                        <td>{{ $organization->country_code }}{{ $organization->mobile }}</td>
                                        <td>
                                            <span class="fw-bold">{{ $organization->users()->count() }}</span>
                                            <small class="text-muted d-block">{{ $organization->activeUsers()->count() }} active</small>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $organization->courses()->count() }}</span>
                                            <small class="text-muted d-block">{{ $organization->activeCourses()->count() }} active</small>
                                        </td>
                                        <td>
                                            @php
                                                echo $organization->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            {{ showDateTime($organization->created_at) }}<br>
                                            <span class="text-muted">{{ diffForHumans($organization->created_at) }}</span>
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.organizations.show', $organization->id) }}" class="btn btn-sm btn-outline--primary">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
                                                <a href="{{ route('admin.organizations.edit', $organization->id) }}" class="btn btn-sm btn-outline--primary">
                                                    <i class="las la-pen"></i> @lang('Edit')
                                                </a>
                                                @if($organization->users()->count() == 0)
                                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.organizations.destroy', $organization->id) }}" data-question="@lang('Are you sure to delete this organization?')">
                                                        <i class="las la-trash"></i> @lang('Delete')
                                                    </button>
                                                @endif
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
                @if ($organizations->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($organizations) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search by company name, email, contact person..." />
    <a href="{{ route('admin.organizations.create') }}" class="btn btn-sm btn-outline--primary">
        <i class="las la-plus"></i>@lang('Add New Organization')
    </a>
@endpush

@push('script')
<script>
    (function($){
        'use strict';
        $('.confirmationBtn').on('click', function () {
            var modal = $('#confirmationModal');
            var data  = $(this).data();
            modal.find('.question').text(`${data.question}`);
            modal.find('form').attr('action', `${data.action}`);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush