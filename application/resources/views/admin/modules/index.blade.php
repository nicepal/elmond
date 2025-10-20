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
                                <th>@lang('S.N.')</th>
                                <th>@lang('Title')</th>
                                <th>@lang('Course')</th>
                                <th>@lang('Sort Order')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($modules as $module)
                            <tr>
                                <td>{{ $modules->firstItem() + $loop->index }}</td>
                                <td>{{ $module->title }}</td>
                                <td>{{ $module->course->name ?? 'N/A' }}</td>
                                <td>{{ $module->sort_order }}</td>
                                <td>
                                    @if($module->status == 1)
                                        <span class="badge badge--success">@lang('Active')</span>
                                    @else
                                        <span class="badge badge--warning">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.modules.edit', $module->id) }}" class="btn btn-sm btn-outline--primary">
                                        <i class="la la-pencil"></i> @lang('Edit')
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.modules.delete', $module->id) }}" data-question="@lang('Are you sure to delete this module?')">
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
            @if ($modules->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($modules) }}
                </div>
            @endif
        </div>
    </div>
</div>

<div class="d-flex justify-content-end mt-4">
    <a href="{{ route('admin.modules.create') }}" class="btn btn--primary">
        <i class="la la-plus"></i> @lang('Add New Module')
    </a>
</div>

<x-confirmation-modal />
@endsection