
<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e(route('admin.lesson.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group mb-3">
                                    <label class=" mb-2"><?php echo app('translator')->get('Title'); ?> </label>
                                    <input class="form-control" name="title"
                                        value="<?php echo e(__(@$lesson->title ?? old('title'))); ?>" placeholder="Enter a title"
                                        required>
                                </div>
                            </div>

                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group mb-3">
                                    <label class=" mb-2"><?php echo app('translator')->get('Course'); ?> </label>
                                    <select class="form--control form-select" name="course_id" id="course">
                                        <option value="0"><?php echo app('translator')->get('Select One'); ?></option>
                                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->id); ?>"
                                                <?php echo e($item->id == $lessonId ? 'selected' : ''); ?>>
                                                <?php echo e(__($item->name)); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group mb-3">
                                    <label class=" mb-2"><?php echo app('translator')->get('Module'); ?> </label>
                                    <select class="form--control form-select" name="module_id" id="moduleSelect">
                                        <option value=""><?php echo app('translator')->get('Select Course First'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group mb-3">
                                    <label class=" mb-2"><?php echo app('translator')->get('Select Status'); ?> </label>
                                    <select class="form--control form-select" name="status" id="status" required>
                                        <option value=""><?php echo app('translator')->get('Select One'); ?></option>
                                        <option value="1">
                                            <?php echo app('translator')->get('Active'); ?></option>
                                        <option value="0">
                                            <?php echo app('translator')->get('Pending'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group mb-3">
                                    <label class=" mb-2"><?php echo app('translator')->get('Selece Level'); ?> </label>
                                    <select class="form--control form-select" name="level" id="level" required>
                                        <option value=""><?php echo app('translator')->get('Select One'); ?></option>
                                        <option value="1" <?php echo e(@$lesson->level == 1 ? 'selected' : old('level')); ?>>
                                            <?php echo app('translator')->get('Beginner'); ?></option>
                                        <option value="2"<?php echo e(@$lesson->level == 2 ? 'selected' : old('level')); ?>>
                                            <?php echo app('translator')->get('intermediate'); ?></option>
                                        <option value="3"<?php echo e(@$lesson->level == 3 ? 'selected' : old('level')); ?>>
                                            <?php echo app('translator')->get('Advance'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group mb-3">
                                    <label class="mb-2"><?php echo app('translator')->get('Select Value'); ?> </label>
                                    <select name="value" class="form--control form-select" id="value" required>
                                        <option value=""><?php echo app('translator')->get('Select One'); ?></option>
                                        <option value="0" <?php echo e(@$lesson->value == 0 ? 'selected' : old('value')); ?>>
                                            <?php echo app('translator')->get('Free'); ?></option>
                                        <option value="1" <?php echo e(@$lesson->value == 1 ? 'selected' : old('value')); ?>>
                                            <?php echo app('translator')->get('Premium'); ?>
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group mb-3">
                                    <label class="mb-2"><?php echo app('translator')->get('Preview Video'); ?></label>
                                    <select class="form--control form-select" name="preview_video" id="preview_video"
                                        required>
                                        <option value="1"><?php echo app('translator')->get('Upload'); ?></option>
                                        <option value="2"><?php echo app('translator')->get('Video Url'); ?></option>
                                        <option value="3"><?php echo app('translator')->get('Live Class'); ?></option>
                                        </option>
                                        <option value="4"><?php echo app('translator')->get('Documents'); ?></option>
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-xl-6 documents_input">
                                <div class="form-group mb-3">
                                    <div>
                                        <label class="mb-2"><?php echo app('translator')->get('Upload Documents'); ?>
                                            <span class="text-danger">(pdf, ppt, doc, docx, png, jpg, jpeg, zip, xls, xlsx,
                                                csv, pptx, bmp, webp)</span>
                                        </label>
                                        <input class="form-control" type="file" name="documents[]"
                                            accept=".pdf,.ppt,.txt,.doc,.docx,.png,.jpg,.jpeg" multiple>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-xl-6">
                                <div class="form-group mb-3">
                                    <div>
                                        <label class="mb-2"><?php echo app('translator')->get('Upload Video'); ?></label>
                                        <input class="form-control" type="file" name="upload_video" id="browseFile">
                                    </div>

                                    <div style="display: none" class="progress mt-3" style="height: 25px">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                            role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                            style="width:75%; height:100%">75%
                                        </div>
                                    </div>

                                    <div class="card mt-4 d-none">
                                        <div class="card-body">
                                            <div class="video-show text-end position-relative">
                                                <a href="javascript:void(0)"
                                                    class="uploadVideoDelete btn btn--danger btn--sm uploadvideo-icon position-absolute"
                                                    onclick="modalShow();">
                                                    <i class="fa-solid fa-circle-xmark"></i>
                                                </a>
                                                <video id="videoPreview" src="" controls
                                                    style="width:100%; height: auto"></video>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-xl-6 d-none">
                                <div class="form-group mb-3">
                                    <label class="mb-2"><?php echo app('translator')->get('Video Url'); ?></label>
                                    <input class="form-control" type="text" name="video_url">
                                </div>
                            </div>
                        </div>

                        <div class="row d-none live_class_inputs">
                            <blockquote class="blockquote">
                                <h4><?php echo app('translator')->get('Zoom Instruction'); ?></h4>
                                <p><?php echo app('translator')->get('You must be add zoom scopes:[meeting:write:meeting, meeting:write:meeting:admin]'); ?></p>
                            </blockquote>
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label class="mb-2"><?php echo app('translator')->get('Agenda'); ?></label>
                                    <input class="form-control" type="text" name="agenda" placeholder="Agenda name"
                                        value=<?php echo e(@$zoomData->agenda); ?>>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label class="mb-2"><?php echo app('translator')->get('Class Topic'); ?></label>
                                    <input class="form-control" type="text" name="class_topic"
                                        placeholder="Class name of topic" value=<?php echo e(@$zoomData->class_topic); ?>>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label class="mb-2"><?php echo app('translator')->get('Zoom email'); ?></label>
                                    <input class="form-control" type="email" name="email" placeholder="Zoom email"
                                        value=<?php echo e(@$zoomData->email); ?>>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label class="mb-2"><?php echo app('translator')->get('Approximate Time'); ?></label>
                                    <input class="form-control" type="number" name="approximate_time"
                                        placeholder="Approximate meeting duration (minute)"
                                        value=<?php echo e(@$zoomData->agenda); ?>>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label class="mb-2"><?php echo app('translator')->get('Approval Type'); ?></label>
                                    <select class="form--control form-select" name="approval_type" id="approval_type"
                                        required>
                                        <option value="0"><?php echo app('translator')->get('Automatic'); ?></option>
                                        <option value="1"><?php echo app('translator')->get('Manually'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label class="mb-2"><?php echo app('translator')->get('Type'); ?></label>
                                    <select class="form--control form-select" name="type" id="type" required>
                                        <option value="1"><?php echo app('translator')->get('Instant'); ?></option>
                                        <option value="2"><?php echo app('translator')->get('Scheduled'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label class="mb-2"><?php echo app('translator')->get('Starting Time'); ?></label>
                                    <input class="form-control" type="datetime-local" name="start_time"
                                        value=<?php echo e(@$zoomData->agenda); ?>>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label class="mb-2"><?php echo app('translator')->get('Password'); ?></label>
                                    <input class="form-control" type="text" name="password"
                                        placeholder="Zoom meeting password" value=<?php echo e(@$zoomData->agenda); ?>>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="mb-2"><?php echo app('translator')->get('Description'); ?></label>
                                <textarea class="form-control trumEdit" name="description"></textarea>
                            </div>
                        </div>

                        <div class="row justify-content-end text-end">
                            <div class="col-4 mt-4">
                                <button type="submit" class="btn btn-success"><?php echo app('translator')->get('Save'); ?></button>
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
                    <h5 class="modal-title" id="videoModalLabel"><?php echo app('translator')->get('Alert'); ?></h5>
                    <button type="button" class="btn-close btn btn--danger" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><?php echo app('translator')->get('Are you sure? you change video URL?'); ?></p>
                    <input type="text" hidden name="fileName" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-modal="0"
                        data-bs-dismiss="modal"><?php echo app('translator')->get('No'); ?></button>
                    <button type="button" class="btn btn--primary" data-bs-dismiss="modal" data-modal="1"
                        onclick="uploadeVideoDelete(this);"><?php echo app('translator')->get('Yes'); ?></button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-lib'); ?>
    <script src="<?php echo e(asset($activeTemplateTrue . 'js/resumable.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .ck.ck-content.ck-editor__editable.ck-rounded-corners.ck-editor__editable_inline {
            height: 200px;
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script'); ?>
    <script type="text/javascript">
        let browseFile = $('#browseFile');
        let resumable = new Resumable({
            target: '<?php echo e(route('admin.lesson.video.upload')); ?>',
            query: {
                _token: '<?php echo e(csrf_token()); ?>'
            },
            fileType: ['mp4'],
            chunkSize: 10 * 1024 *
                1024, // 10 MB
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
            $('.video-show').show();
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
            uploadeVideoDeleteAjax(ancor, srcValue, fileName);
        }

        function uploadeVideoDeleteAjax(ancor, srcValue, fileName) {
            $.ajax({
                url: "<?php echo e(route('admin.lesson.video.upload.delete')); ?>",
                type: "POST",
                data: {
                    videoUrl: srcValue,
                    fileName: fileName,
                    _token: "<?php echo e(csrf_token()); ?>"
                },
                success: function(response) {
                    if (response.status == 'success') {
                        ancor.closest('.card').hide();
       
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
                    }, 1500)
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            const selectElements = $('select[name="preview_video"]');
            const inputUploadVideo = $('input[name="upload_video"]');
            const inputVideoUrl = $('input[name="video_url"]');
            const videoPreview = $('#videoPreview');
            const liveClassInputs = $('.live_class_inputs');
            const documentInputs = $('.documents_input input[name="documents[]"]');
            const videoModal = $('#videoModal');

            // Bind change event to the select element
            selectElements.on('change', function() {
                handleSelectChange($(this).val(), inputUploadVideo, inputVideoUrl, videoPreview,
                    liveClassInputs, documentInputs, videoModal);
            });

            // Initialize on page load
            handleSelectChange(selectElements.val(), inputUploadVideo, inputVideoUrl, videoPreview, liveClassInputs,
                documentInputs, videoModal);
        });

        function handleSelectChange(selectedOption, inputUploadVideo, inputVideoUrl, videoPreview, liveClassInputs,
            documentInputs, videoModal) {
            resetInputs(inputUploadVideo, inputVideoUrl, liveClassInputs, documentInputs);

            switch (parseInt(selectedOption)) {
                case 1: // Upload video
                    showElement(inputUploadVideo.closest('.form-group').parent());
                   
                    break;

                case 2: // Video URL
                    if (isVideoPreviewEmpty(videoPreview)) {
                        showElement(inputVideoUrl.parent().parent());
                    } else {
                        showVideoModal(videoModal);
                    }
                    break;

                case 3: // Live class
                    if (isVideoPreviewEmpty(videoPreview)) {
                        showElement(liveClassInputs);
                        showElement(inputUploadVideo.closest('.form-group').parent());
                    } else {
                        showVideoModal(videoModal);
                    }
                    break;

                default: // Other options
                    showElement(inputUploadVideo.parents('.col-lg-6'));
                    documentInputs.prop('required', true);
                    break;
            }
        }

        function resetInputs(inputUploadVideo, inputVideoUrl, liveClassInputs, documentInputs) {
            hideElement(inputUploadVideo.closest('.form-group').parent());
            hideElement(inputVideoUrl.parent().parent());
            hideElement(liveClassInputs);

            inputUploadVideo.removeAttr('required');
            inputVideoUrl.removeAttr('required');
            documentInputs.prop('required', false);
        }

        function showVideoModal(videoModal) {
            const fileName = $('.uploadVideoDelete').siblings("video").data('file-name');
            videoModal.find('form input[name=fileName]').val(fileName);
            videoModal.modal('show');
        }

        function isVideoPreviewEmpty(videoPreview) {
            return videoPreview.attr('src') === '';
        }

        function hideElement(element) {
            element.addClass('d-none');
        }

        function showElement(element) {
            element.removeClass('d-none');
        }
         // Modal Show Function
         function modalShow() {
            $('#videoModal').modal('show');
        }
    </script>

    <script>

        $('form').on('submit', function(e) {
            const previewVideoValue = $('#preview_video').val();
            const uploadVideoInput = $('input[name="upload_video"]');
            const videoUrlInput = $('input[name="video_url"]');
            const documentsInput = $('input[name="documents[]"]');
            const descriptionField = $('.trumEdit');
            
            let isValid = true;
            let errorMessage = '';
            
            // Remove any existing error alerts
            $('.validation-error-alert').remove();
            
            // Check if description is empty
            if (!descriptionField.val() || descriptionField.val().trim() === '') {
                isValid = false;
                errorMessage = 'Please enter a description before saving the lesson.';
            } else if (previewVideoValue == '2') {
                // Video URL selected - check if URL is provided
                if (!videoUrlInput.val() || videoUrlInput.val().trim() === '') {
                    isValid = false;
                    errorMessage = 'Please enter a video URL before saving the lesson.';
                }
            } else if (previewVideoValue == '1' || previewVideoValue == '3') {
                // Upload or Live Class selected - check if video file is uploaded
                if (!uploadVideoInput[0].files || uploadVideoInput[0].files.length === 0) {
                    // Also check if there's an existing video preview
                    const videoPreview = $('#videoPreview');
                    if (!videoPreview.attr('src') || videoPreview.attr('src') === '') {
                        isValid = false;
                        errorMessage = 'Please upload a video file before saving the lesson.';
                    }
                }
            } else if (previewVideoValue == '4') {
                // Documents selected - check if video file is uploaded (as per the form logic)
                if (!uploadVideoInput[0].files || uploadVideoInput[0].files.length === 0) {
                    const videoPreview = $('#videoPreview');
                    if (!videoPreview.attr('src') || videoPreview.attr('src') === '') {
                        isValid = false;
                        errorMessage = 'Please upload a video file before saving the lesson.';
                    }
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script>
$(document).ready(function() {
    $('#course').on('change', function() {
        var courseId = $(this).val();
        var moduleSelect = $('#moduleSelect');
        
        // Clear module dropdown
        moduleSelect.html('<option value=""><?php echo app('translator')->get("Loading..."); ?></option>');
        
        if (courseId && courseId != '0') {
            $.ajax({
                url: '<?php echo e(route("admin.lesson.getModulesByCourse", "")); ?>/' + courseId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    moduleSelect.html('<option value=""><?php echo app('translator')->get("Select Module"); ?></option>');
                    $.each(data, function(key, module) {
                        moduleSelect.append('<option value="' + module.id + '">' + module.title + '</option>');
                    });
                },
                error: function() {
                    moduleSelect.html('<option value=""><?php echo app('translator')->get("Error loading modules"); ?></option>');
                }
            });
        } else {
            moduleSelect.html('<option value=""><?php echo app('translator')->get("Select Course First"); ?></option>');
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/admin/lessons/create.blade.php ENDPATH**/ ?>