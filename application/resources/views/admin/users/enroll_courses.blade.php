@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <form action="{{ route('admin.users.enroll.courses.store', $user->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>@lang('Student Information')</h5>
                                    <p><strong>@lang('Name'):</strong> {{ $user->fullname }}</p>
                                    <p><strong>@lang('Email'):</strong> {{ $user->email }}</p>
                                    <p><strong>@lang('Username'):</strong> {{ $user->username }}</p>
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Select Courses to Enroll') <span class="text-danger">*</span></label>
                                        @if($courses->count() > 0)
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
                                        @else
                                            <p class="text-muted">@lang('No available courses to enroll this student in.')</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($courses->count() > 0)
                            <div class="modal-footer">
                                <button type="submit" class="btn btn--primary btn-global">@lang('Enroll in Selected Courses')</button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection