@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.lesson.update', @$lesson->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group mb-3">
                                    <label class=" mb-2">@lang('Title') </label>
                                    <input class="form-control" name="title"
                                        value="{{ __(@$lesson->title ?? old('title')) }}" placeholder="Enter a title"
                                        required>
                                </div>
                            </div>

                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group mb-3">
                                    <label class=" mb-2">@lang('Course') </label>
                                    <select class="form--control form-select" name="course_id" id="course">
                                        <option value="0">@lang('Select One')</option>
                                        @foreach ($course as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $item->id == @$lesson->course_id ? 'selected' : '' }}>
                                                {{ __($item->name) }}
                                            </option>
                                        @endforeach>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group mb-3">
                                    <label class=" mb-2">@lang('Module') </label>
                                    <select class="form--control form-select" name="module_id" id="moduleSelectD">
                                        <option value="">@lang('Select Module')</option>
                                        @foreach ($modules as $module)
                                            <option value="{{ $module->id }}"
                                                {{ $module->id == @$lesson->module_id ? 'selected' : '' }}>
                                                {{ __($module->title) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row">


                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group mb-3">
                                    <label class=" mb-2">@lang('Select Level') </label>
                                    <select class="form--control form-select" name="level" id="level" required>
                                        <option value="">@lang('Select One')</option>
                                        <option value="1" {{ @$lesson->level == 1 ? 'selected' : old('level') }}>
                                            @lang('Beginner')</option>
                                        <option value="2"{{ @$lesson->level == 2 ? 'selected' : old('level') }}>
                                            @lang('intermediate')</option>
                                        <option value="3"{{ @$lesson->level == 3 ? 'selected' : old('level') }}>
                                            @lang('Advance')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group mb-3">
                                    <label class="mb-2">@lang('Select Value') </label>
                                    <select name="value" class="form--control form-select" id="value" required>
                                        <option value="">@lang('Select One')</option>
                                        <option value="0" {{ @$lesson->value == 0 ? 'selected' : old('value') }}>
                                            @lang('Free')</option>
                                        <option value="1" {{ @$lesson->value == 1 ? 'selected' : old('value') }}>
                                            @lang('Premium')
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group mb-3">
                                    <label class="mb-2">@lang('Select Status') </label>
                                    <select class="form--control form-select" name="status" id="status" required>
                                        <option value="">@lang('Select One')</option>
                                        <option value="1" {{ @$lesson->status == 1 ? 'selected' : '' }}>
                                            @lang('Active')</option>
                                        <option value="0"{{ @$lesson->status == 0 ? 'selected' : '' }}>
                                            @lang('Pending')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label class="mb-2">@lang('Preview Video')</label>
                                    <select class="form--control form-select" name="preview_video" id="preview_video"
                                        required>
                                        <option value="1" {{ @$lesson->preview_video == 1 ? 'selected' : '' }}>
                                            @lang('Upload')</option>
                                        <option value="2" {{ @$lesson->preview_video == 2 ? 'selected' : '' }}>
                                            @lang('Video Url')
                                        </option>
                                        <option value="4" {{ @$lesson->preview_video == 4 ? 'selected' : '' }}>
                                            @lang('Documents')
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-6 col-xl-6 documents_input">
                                <div class="form-group mb-3">
                                    <div>
                                        <label class="mb-2">@lang('Upload Documents')
                                            <span class="text-danger">(pdf, ppt, doc, docx, png, jpg, jpeg, zip, xls, xlsx,
                                                csv, pptx, bmp, webp)</span>
                                        </label>
                                        <input class="form-control" type="file" name="documents[]"
                                            accept=".pdf,.ppt,.doc,.docx,.png,.jpg,.jpeg,.zip,.xls,.xlsx,.csv,.pptx,.gif,.bmp,.webp"
                                            multiple>
                                    </div>
                                    
                                    
                                    @if (@$lesson->lessonDocuments->count() > 0)
                                        <div class="card mt-4">
                                            <div class="card-body">
                                                <ul class="list-group list-group-flush">
                                                    @php
                                                        $fileIcons = [
                                                            'jpg' => 'jpg.png',
                                                            'jpeg' => 'jpeg.png',
                                                            'pdf' => 'pdf.png',
                                                            'ppt' => 'ppt.png',
                                                            'pptx' => 'pptx.png',
                                                            'zip' => 'zip.png',
                                                            'csv' => 'csv.png',
                                                            'webp' => 'webp.png',
                                                            'bmp' => 'bmp.png',
                                                            'doc' => 'doc.png',
                                                            'docx' => 'docx.png',
                                                            'png' => 'png.png',
                                                        ];
                                                    @endphp

                                                    @foreach (@$lesson->lessonDocuments as $item)
                                                        @php
                                                            $extension = strtolower(
                                                                pathinfo($item->file, PATHINFO_EXTENSION),
                                                            );
                                                            $icon = $fileIcons[$extension] ?? 'txt.png'; // Default to 'txt.png' if extension not found
                                                        @endphp
                                                        <li
                                                            class="list-group-item d-flex flex-wrap gap-3 justify-content-between align-items-center">
                                                            <div class="thumb--wrap">
                                                                <img src="{{ asset(getFilePath('lesson_file_ext') . '/' . $icon) }}"
                                                                    alt="{{ $extension }}">
                                                            </div>
                                                            <div class="title">

                                                                {{ $item->file }}
                                                            </div>
                                                            <div class="btn--wrap">
                                                                <button type="button"
                                                                    class="btn btn--danger btn--sm documentDelete align-items-center"
                                                                    data-id="{{ $item->id }}">x</button>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                    
                                </div>
                            </div>

                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group mb-3">
                                    <div>
                                        <label class="mb-2">@lang('Upload Video')
                                            <span class="text-danger">(mp4,mov)</span>
                                        </label>
                                        <input class="form-control" type="file" name="upload_video" id="browseFile">
                                    </div>

                                    <div style="display: none" class="progress mt-3" style="height: 25px">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                            role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                            style="width:75%; height:100%">75%
                                        </div>
                                    </div>
                             
                                    <div class="card mt-4 {{ $lesson->upload_video ? '' : 'd-none' }}">
                                        <div class="card-body">
                                            <div class="video-show position-relative m-0  text-end">
                                                <a href="javascript:void(0)"
                                                    class="uploadVideoDelete btn btn--danger btn--sm position-absolute"
                                                    onclick="modalShow();"><i class="fa-solid fa-circle-xmark"></i></a>
                                                <video id="videoPreview" src="{{ $upload_video }}"
                                                    data-file-name="{{ @$lesson->upload_video }}"
                                                    data-id="{{ @$lesson->id }}" controls
                                                    style="width:100%; height: 50%"></video>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none">
                                <div class="form-group mb-3">
                                    <label class="mb-2">@lang('Video Url')</label>
                                    <input class="form-control" type="text" name="video_url"
                                        value="{{ $lesson->video_url }}">
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="mb-2">@lang('Description')</label>
                                <textarea class="form--control trumEdit" name="description">{{ __($lesson->description) }}</textarea>
                            </div>
                        </div>


                        <div class="row justify-content-end text-end">
                            <div class="col-4 mt-4">
                                <button type="submit" class="btn btn-success">@lang('Update')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="videoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoModalLabel">@lang('Alert')</h5>
                    <button type="button" class="btn-close btn btn--danger" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>@lang('Are you sure? You delete this upload video?')</p>
                    <input type="text" hidden name="fileName" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-modal="0"
                        data-bs-dismiss="modal">@lang('No')</button>
                    <button type="button" class="btn btn--primary" data-bs-dismiss="modal" data-modal="1"
                        onclick="uploadeVideoDelete(this);">@lang('Yes')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/resumable.min.js') }}"></script>
@endpush
@push('style')
    <style>
        .ck.ck-content.ck-editor__editable.ck-rounded-corners.ck-editor__editable_inline {
            height: 200px;
        }
    </style>
@endpush


@push('script')
    {{-- ---------------------- upload video file js code ---------------------- --}}
    <script type="text/javascript">
        let browseFile = $('#browseFile');
        let resumable = new Resumable({
            target: '{{ route('admin.lesson.edit.video.upload') }}',
            query: {
                _token: '{{ csrf_token() }}'
            },
            fileType: ['mp4'],
            chunkSize: 10 * 1024 *
                1024, // default is 1*1024*1024, this should be less than your maximum limit in php.ini
            headers: {
                'Accept': 'application/json'
            },
            testChunks: false,
            throttleProgressCallbacks: 1,
        });

        resumable.assignBrowse(browseFile[0]);

        resumable.on('fileAdded', function(file) { // trigger when file picked
            showProgress();
            resumable.upload() // to actually start uploading.
        });

        resumable.on('fileProgress', function(file) { // trigger when file progress update
            updateProgress(Math.floor(file.progress() * 100));
        });

        resumable.on('fileSuccess', function(file, response) { // trigger when file upload complete
            response = JSON.parse(response)
            $('#videoPreview').attr('src', response.path);
            $('#videoPreview').attr('data-file-name', response.filename);
            $('.video-show').parent().parent().removeClass('d-none');
            $('.video-show').parent().parent().show();
        });

        resumable.on('fileError', function(file, response) { // trigger when there is any error
            alert('file uploading error.')
        });

        let progress = $('.progress');

        function showProgress() {
            progress.find('.progress-bar').css('width', '0%');
            progress.find('.progress-bar').html('0%');
            progress.find('.progress-bar').removeClass('bg-success');
            progress.show();
        }

        function updateProgress(value) {
            progress.find('.progress-bar').css('width', `${value}%`);
            progress.find('.progress-bar').html(`${value}%`);
            if (value == 100) {
                setTimeout(() => {
                    hideProgress();
                }, 1500);
            }
        }

        function hideProgress() {
            progress.hide();
        }
    </script>
    <script>
        function uploadeVideoDelete(object) {
            var ancor = $('.uploadVideoDelete');
            var srcValue = ancor.siblings("video").attr('src');
            var fileName = ancor.siblings("video").data('file-name');
            var id = ancor.siblings("video").data('id');
            uploadeVideoDeleteAjax(ancor, srcValue, fileName, id);
        }

        function uploadeVideoDeleteAjax(ancor, srcValue, fileName, id) {
            $.ajax({
                url: "{{ route('admin.lesson.edit.video.upload.delete') }}",
                type: "POST",
                data: {
                    videoUrl: srcValue,
                    fileName: fileName,
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.status == 'success') {
                        ancor.parents('.card').hide();
                        hideProgress();
                        var videoPreview = $('#videoPreview');
                        videoPreview.attr('src', '');
                        Toast.fire({
                            icon: response.status,
                            title: response.message
                        });


                    } else {
                        Toast.fire({
                            icon: response.status,
                            title: response.message
                        });
                    }
                    setTimeout(() => {
                        window.location.reload(true);
                    }, 1500);
                }
            });
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            const selectElements = $('select[name="preview_video"]');
            const inputUploadVideo = $('input[name="upload_video"]');
            const inputVideoUrl = $('input[name="video_url"]');
            const videoPreview = $('#videoPreview');
            const liveClassInputs = $('.live_class_inputs');
            const documentInputs = $('.documents_input input[name="documents[]"]');

            // Bind change event to select elements
            selectElements.on('change', function() {
                handleSelectionChange(inputUploadVideo, inputVideoUrl, videoPreview, liveClassInputs,
                    documentInputs);
            });

            // Initial setup
            handleSelectionChange(inputUploadVideo, inputVideoUrl, videoPreview, liveClassInputs, documentInputs);
        });

        function handleSelectionChange(inputUploadVideo, inputVideoUrl, videoPreview, liveClassInputs, documentInputs) {
            const selectedOption = $('select[name="preview_video"]').val();

            // Hide all elements initially
            hideElement(inputVideoUrl.parent().parent());
            hideElement(inputUploadVideo.closest('.form-group').parent());
            hideElement(liveClassInputs);
            documentInputs.prop('required', false);
            inputUploadVideo.prop('required', false);

            switch (parseInt(selectedOption)) {
                case 1:
                    // Upload video selected
                    showElement(inputUploadVideo.closest('.form-group').parent());
                    inputUploadVideo.prop('required', false);
                    break;

                case 2:
                    // Video URL selected
                    if (videoPreview.attr('src') === '') {
                        showElement(inputVideoUrl.parent().parent());
                        
                    } else {
                        showVideoModal();
                    }
                    break;

                case 4:
                    // Document upload selected
                    showElement(inputUploadVideo.closest('.form-group').parent());

                    documentInputs.prop('required', true);
                    inputUploadVideo.prop('required', false);
                    break;

                default:
                    // Live class or fallback option
                    showElement(liveClassInputs);
                    if (videoPreview.attr('src') !== '') {
                        showVideoModal();
                    }
                    break;
            }
        }

        function showVideoModal() {
            const videoModal = $('#videoModal');
            const fileName = $('.uploadVideoDelete').siblings("video").data('file-name');
            videoModal.find('form input[name="fileName"]').val(fileName);
            videoModal.modal('show');
        }

        function hideElement(element) {
            element.addClass('d-none').find('input').removeAttr('required');
        }

        function showElement(element) {
            element.removeClass('d-none').find('input').attr('required', true);
        }

        // Modal Show Function
        function modalShow() {
            $('#videoModal').modal('show');
        }
    </script>
    <script type="text/javascript">
        $('.documentDelete').on('click', function() {
            const id = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.lesson.document.delete', ':id') }}".replace(':id', id),
                type: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.status == 'success') {
                        Toast.fire({
                            icon: response.status,
                            title: response.message
                        });
                    } else {
                        Toast.fire({
                            icon: response.status,
                            title: response.message
                        });
                    }
                    setTimeout(() => {
                        window.location.reload(true);
                    }, 1500);
                }
            });
        });
    </script>

    <script type="text/javascript">

        $('form').on('submit', function(e) {
            const previewVideoValue = $('#preview_video').val();
            const uploadVideoInput = $('input[name="upload_video"]');
            const videoUrlInput = $('input[name="video_url"]');
            const documentsInput = $('input[name="documents[]"]');
            const descriptionField = $('textarea[name="description"]');
            
            let isValid = true;
            let errorMessage = '';
            
            // Remove any existing error alerts
            $('.validation-error-alert').remove();
            
            // Check if description is empty
            if (!descriptionField.val() || descriptionField.val().trim() === '') {
                isValid = false;
                errorMessage = 'Please enter a description before updating the lesson.';
            }
            // Only check other validations if description is valid
            else if (previewVideoValue == '2') {
                // Video URL selected - check if URL is provided
                if (!videoUrlInput.val() || videoUrlInput.val().trim() === '') {
                    isValid = false;
                    errorMessage = 'Please enter a video URL before updating the lesson.';
                }
            } else if (previewVideoValue == '1' || previewVideoValue == '3') {
                // Upload or Live Class selected - check if video file is uploaded or exists
                const hasNewUpload = uploadVideoInput[0].files && uploadVideoInput[0].files.length > 0;
                const hasExistingVideo = $('#videoPreview').length > 0 && $('#videoPreview').attr('src') && $('#videoPreview').attr('src') !== '';
                
                if (!hasNewUpload && !hasExistingVideo) {
                    isValid = false;
                    errorMessage = 'Please upload a video file before updating the lesson.';
                }
            } else if (previewVideoValue == '4') {
                // Documents selected - check if documents are uploaded
                const hasNewDocuments = documentsInput.length > 0 && documentsInput[0].files && documentsInput[0].files.length > 0;
                const hasExistingDocuments = $('.existing-documents').length > 0;
                
                if (!hasNewDocuments && !hasExistingDocuments) {
                    isValid = false;
                    errorMessage = 'Please upload at least one document before updating the lesson.';
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                
                // Create and show error alert
                const alertHtml = `
                    <div class="alert alert-danger validation-error-alert" role="alert">
                        <strong>Error!</strong> ${errorMessage}
                    </div>
                `;
                
                // Insert alert at the top of the form
                $('form').prepend(alertHtml);
                
                // Scroll to top to show the error
                $('html, body').animate({
                    scrollTop: $('form').offset().top - 100
                }, 500);
                
                return false;
            }
        });
    </script>
@endpush

@push('script')
<script>
$(document).ready(function() {
    $('#course').on('change', function() {
        var courseId = $(this).val();
        var moduleSelect = $('#moduleSelectD');
        
        // Clear module dropdown
        moduleSelect.html('<option value="">@lang("Loading...")</option>');
        
        if (courseId && courseId != '0') {
            $.ajax({
                url: '{{ route("admin.lesson.getModulesByCourse", "") }}/' + courseId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    moduleSelect.html('<option value="">@lang("Select Module")</option>');
                    $.each(data, function(key, module) {
                        moduleSelect.append('<option value="' + module.id + '">' + module.title + '</option>');
                    });
                },
                error: function() {
                    moduleSelect.html('<option value="">@lang("Error loading modules")</option>');
                }
            });
        } else {
            moduleSelect.html('<option value="">@lang("Select Course First")</option>');
        }
    });
});
</script>
@endpush
