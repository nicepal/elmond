@extends($activeTemplate . 'instructor.layouts.master')
@section('content')
    <div class="row justify-content-center mx-lg-0">
        <div class="col-lg-12 justify-content-center">
            <div class="base--card">
                <div class="col-lg-12">
                    <form action="{{ route('instructor.lesson.update', @$lesson->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group mb-3">
                                    <label class=" mb-2">@lang('Title') </label>
                                    <input class="form--control" name="title"
                                        value="{{ __(@$lesson->title ?? old('title')) }}" placeholder="Enter a title"
                                        required>
                                </div>
                            </div>

                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group mb-3">
                                    <label class=" mb-2">@lang('Course') </label>
                                    <select class="form--control form-select" name="course_id" id="course"
                                        onchange="course(this);">
                                        <option value="0">@lang('Select One')</option>
                                        @foreach ($courses as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $item->id == @$lesson->course_id ? 'selected' : '' }}>
                                                {{ __($item->name) }}
                                            </option>
                                        @endforeach>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
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
                                        <input class="form--control" type="file" name="documents[]"
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
                                                                <p>
                                                                    {{ $item->file }}
                                                                </p>
                                                            </div>
                                                            <div class="btn--wrap">
                                                                <button type="button"
                                                                    class="btn btn--danger btn--sm documentDelete"
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
                                        <input class="form--control" type="file" name="upload_video" id="browseFile">
                                    </div>

                                    <div style="display: none" class="progress mt-3" style="height: 25px">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                            role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                            style="width:75%; height:100%">75%
                                        </div>
                                    </div>

                                    <div class="card mt-4 {{ $lesson->upload_video ? '' : 'd-none' }}">
                                        <div class="card-body">
                                            <div class="video-show position-relative text-end">
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
                                    <input class="form--control" type="text" name="video_url"
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
                                <button type="submit" class="btn btn--base">@lang('Update')</button>
                            </div>
                        </div>
                    </form>
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
                        <p>@lang('Are you sure? you want change video URL?')</p>
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
    </div>
@endsection

@push('style')
    <style>
        .image-upload .thumb .profilePicPreview {
            width: 100%;
            height: 210px;
            display: block;
            border-radius: 10px;
            background-size: cover !important;
            background-position: top;
            background-repeat: no-repeat;
            position: relative;
            overflow: hidden;
        }

        @media (max-width:1500px) {
            .image-upload .thumb .profilePicPreview {
                height: 152px;
            }
        }

        .image-upload .thumb .profilePicPreview.logoPicPrev {
            background-size: contain !important;
            background-position: center;
        }

        .image-upload .thumb .profilePicUpload {
            font-size: 0;
            display: none;
        }

        .image-upload .thumb .avatar-edit label {
            text-align: center;
            line-height: 32px;
            font-size: 18px;
            cursor: pointer;
            padding: 2px 25px;
            width: 100%;
            border-radius: 5px;
            box-shadow: 0 5px 10px 0 rgb(0 0 0 / 16%);
            transition: all 0.3s;
            margin-top: 6px;
        }

        .image-upload .thumb .profilePicPreview .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            text-align: center;
            width: 34px;
            height: 34px;
            font-size: 23px;
            border-radius: 50%;
            background-color: hsl(var(--base));
            color: #ffffff;
            display: none;
            opacity: .8;
        }

        .image-upload .thumb .profilePicPreview .remove-image:hover {
            opacity: 1;
        }

        .image-upload .thumb .profilePicPreview.has-image .remove-image {
            display: block;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/common/js/ckeditor.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/resumable.min.js') }}"></script>
@endpush


@push('script')
    {{-- ---------------------- upload video file js code ---------------------- --}}
    <script type="text/javascript">
        let browseFile = $('#browseFile');
        let resumable = new Resumable({
            target: '{{ route('instructor.lesson.edit.video.upload') }}',
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
            $('.video-show').parents('.card').removeClass('d-none');
            $('.video-show').parents('.card').show();


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

    <script type="text/javascript">
        function uploadeVideoDelete(object) {
            var ancor = $('.uploadVideoDelete');
            console.log(ancor);

            var srcValue = ancor.siblings("video").attr('src');
            var fileName = ancor.siblings("video").data('file-name');
            var id = ancor.siblings("video").data('id');
            uploadeVideoDeleteAjax(ancor, srcValue, fileName, id);
        }

        function uploadeVideoDeleteAjax(ancor, srcValue, fileName, id) {
            $.ajax({
                url: "{{ route('instructor.lesson.edit.video.upload.delete') }}",
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
                        inputUploadVideo.removeAttr('required');
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
                url: "{{ route('instructor.lesson.document.delete', ':id') }}".replace(':id', id),
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
@endpush
