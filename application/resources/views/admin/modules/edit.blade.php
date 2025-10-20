@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body">
                <form action="{{ route('admin.modules.update', $module->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Module Title') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" value="{{ old('title', $module->title) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Course') <span class="text-danger">*</span></label>
                                <select name="course_id" class="form-control" required>
                                    <option value="">@lang('Select Course')</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id', $module->course_id) == $course->id ? 'selected' : '' }}>
                                            {{ $course->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Sort Order')</label>
                                <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', $module->sort_order) }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Status') <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="1" {{ old('status', $module->status) == '1' ? 'selected' : '' }}>@lang('Active')</option>
                                    <option value="0" {{ old('status', $module->status) == '0' ? 'selected' : '' }}>@lang('Inactive')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection