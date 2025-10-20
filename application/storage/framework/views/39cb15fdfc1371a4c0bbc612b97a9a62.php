
<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-4">
                    <form action="<?php echo e(route('admin.course.update', $course->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('put'); ?>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4 col-xl-4">
                                    <div class="form-group">
                                        <div class="image-upload">
                                            <div class="thumb">
                                                <div class="avatar-preview">
                                                    <div class="profilePicPreview"
                                                        style="background-image: url(<?php echo e(getImage(getFilePath('course_image') . '/' . @$course->image)); ?>);">
                                                        <button type="button" class="remove-image"><i
                                                                class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="avatar-edit">
                                                    <input type="file" class="profilePicUpload" name="image"
                                                        id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                                    <small class="pt-4 text-danger mb-4"><?php echo app('translator')->get('image size'); ?>
                                                        <?php echo e(getFileSize('course_image')); ?></small>
                                                    <label for="profilePicUpload1"
                                                        class="btn btn--primary"><?php echo app('translator')->get('Upload'); ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group mb-3">
                                                <label class="mb-2"><?php echo app('translator')->get('Name'); ?> </label>
                                                <input class="form-control" name="name"
                                                    value="<?php echo e($course->name ?? old('name')); ?>" placeholder="Enter a name"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group mb-3">
                                                <label class="mb-2"><?php echo app('translator')->get('Categories'); ?> </label>
                                                <select class="form--control form-select" name="categories[]" id="categories" multiple required>
                                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item->id); ?>"
                                                            <?php echo e(in_array($item->id, $course->selected_categories ?? [$course->category_id]) ? 'selected' : ''); ?>>
                                                            <?php echo e(__($item->name)); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <small class="text-muted"><?php echo app('translator')->get('Hold Ctrl/Cmd to select multiple categories. First selected will be primary.'); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="mb-2"><?php echo app('translator')->get('Price'); ?> </label>
                                        <input class="form-control" type="number" name="price"
                                            value="<?php echo e($course->price ?? old('price')); ?>" placeholder="Enter a price"
                                            min="0" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="mb-2"><?php echo app('translator')->get('Discount'); ?> (%) </label>
                                        <input class="form-control" type="number" name="discount"
                                            value="<?php echo e($course->discount ?? old('discount')); ?>"
                                            placeholder="Enter a discount" min="0">
                                    </div>
                                </div>


                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label class=" mb-2"><?php echo app('translator')->get('Status'); ?></label>
                                        <select class="form--control form-select" name="status" id="category" required>
                                            <option value=""><?php echo app('translator')->get('Select One'); ?></option>
                                            <option value="1"<?php echo e($course->status == 1 ? 'selected' : ''); ?>>
                                                <?php echo app('translator')->get('Active'); ?></option>
                                            <option value="0"<?php echo e($course->status == 0 ? 'selected' : ''); ?>>
                                                <?php echo app('translator')->get('Pending'); ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="text-end">
                                            <button type="button" class="btn btn-success btn--sm addFile">
                                                <i class="fa fa-plus"></i> <?php echo app('translator')->get('Add New'); ?>
                                            </button>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="file-upload">
                                                    <label class="form-label"><?php echo app('translator')->get('Course Outline'); ?></label>
                                                    <input type="text" name="course_outline[]" id="inputCourseOutline"
                                                        class="form-control form--control mb-2" required
                                                        placeholder="Course Outline"
                                                        value="<?php echo e($course->course_outline[0]); ?>" />
                                                </div>
                                            </div>

                                        </div>
                                        <div id="fileUploadsContainer">
                                            <?php
                                                $outlines = $course->course_outline;
                                                unset($outlines[0]);
                                            ?>
                                            <?php $__currentLoopData = $outlines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="row elements">
                                                    <div class="col-sm-12 my-3">
                                                        <div class="file-upload input-group">
                                                            <input type="text" name="course_outline[]"
                                                                id="inputCourseOutline"
                                                                class="form-control form--control"
                                                                placeholder="Hostel Facilaties" value="<?php echo e($item); ?>"
                                                                required />
                                                            <button
                                                                class="input-group-text btn--danger remove-btn border-0"><i
                                                                    class="las la-times"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class=" mb-2"><?php echo app('translator')->get("What you'll Learn"); ?></label>
                                        <textarea class="form-control trumEdit" name="learn_description"><?php echo e(__($course->learn_description)); ?></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class=" mb-2"><?php echo app('translator')->get('Course Curriculum'); ?></label>
                                        <textarea class="form-control trumEdit" name="curriculum"><?php echo e(__($course->curriculum)); ?></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label class=" mb-2"><?php echo app('translator')->get('Description'); ?></label>
                                        <textarea class="form-control trumEdit" name="description"><?php echo e(__($course->description)); ?></textarea>
                                    </div>
                                </div>
                                
                                <!-- // Add after the description field (similar structure but with existing values) -->
                                
                                <!-- About the Course Section -->
                                <div class="col-lg-12">
                                    <h5 class="mb-3 mt-4"><?php echo app('translator')->get('About the Course'); ?></h5>
                                    <hr>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="mb-2"><?php echo app('translator')->get('Course Duration'); ?></label>
                                        <input class="form-control" type="text" name="duration" 
                                               placeholder="e.g., 5 Hours 30 mins"
                                               value="<?php echo e($course->duration ?? old('duration', '5 Hours 30 mins')); ?>">
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="mb-2"><?php echo app('translator')->get('Number of Assignments'); ?></label>
                                        <input class="form-control" type="number" name="assignments_count" 
                                               placeholder="e.g., 3"
                                               value="<?php echo e($course->assignments_count ?? old('assignments_count', 3)); ?>">
                                    </div>
                                </div>
                                
                                
                                
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="mb-2"><?php echo app('translator')->get('Access Duration'); ?></label>
                                        <input class="form-control" type="text" name="access_duration" 
                                               placeholder="e.g., 12 Months"
                                               value="<?php echo e($course->access_duration ?? old('access_duration', '12 Months')); ?>">
                                    </div>
                                </div>
                                
                                
                                
                                <!-- Course FAQ Section -->
                                <div class="col-lg-12">
                                    <h5 class="mb-3 mt-4"><?php echo app('translator')->get('Course FAQ'); ?></h5>
                                    <hr>
                                    <small class="text-muted mb-3 d-block"><?php echo app('translator')->get('Add frequently asked questions. Click "Add FAQ" to add more questions.'); ?></small>
                                </div>
                                
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <div id="faq-container">
                                            <?php if($course->course_faqs && count($course->course_faqs) > 0): ?>
                                                <?php $__currentLoopData = $course->course_faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="faq-item mb-3 p-3 border rounded">
                                                        <div class="row">
                                                            <div class="col-lg-5">
                                                                <label class="mb-2"><?php echo app('translator')->get('Question'); ?></label>
                                                                <input class="form-control" type="text" name="faqs[<?php echo e($index); ?>][question]" 
                                                                       placeholder="Enter FAQ question"
                                                                       value="<?php echo e($faq['question'] ?? ''); ?>">
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <label class="mb-2"><?php echo app('translator')->get('Answer'); ?></label>
                                                                <textarea class="form-control" name="faqs[<?php echo e($index); ?>][answer]" rows="3" 
                                                                          placeholder="Enter FAQ answer"><?php echo e($faq['answer'] ?? ''); ?></textarea>
                                                            </div>
                                                            <div class="col-lg-1 d-flex align-items-end">
                                                                <button type="button" class="btn btn-danger btn-sm remove-faq"><?php echo app('translator')->get('Remove'); ?></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <div class="faq-item mb-3 p-3 border rounded">
                                                    <div class="row">
                                                        <div class="col-lg-5">
                                                            <label class="mb-2"><?php echo app('translator')->get('Question'); ?></label>
                                                            <input class="form-control" type="text" name="faqs[0][question]" 
                                                                   placeholder="Enter FAQ question"
                                                                   value="What will I learn in this course?">
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="mb-2"><?php echo app('translator')->get('Answer'); ?></label>
                                                            <textarea class="form-control" name="faqs[0][answer]" rows="3" 
                                                                      placeholder="Enter FAQ answer">You will learn comprehensive skills and knowledge in this field through practical exercises and real-world examples.</textarea>
                                                        </div>
                                                        <div class="col-lg-1 d-flex align-items-end">
                                                            <button type="button" class="btn btn-danger btn-sm remove-faq"><?php echo app('translator')->get('Remove'); ?></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm" id="add-faq"><?php echo app('translator')->get('Add FAQ'); ?></button>
                                    </div>
                                </div>
                                
                                <!-- Certificate Section -->
                                <div class="col-lg-12">
                                    <h5 class="mb-3 mt-4"><?php echo app('translator')->get('Earn a Certificate'); ?></h5>
                                    <hr>
                                </div>
                                
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label class="mb-2"><?php echo app('translator')->get('Certificate Description'); ?></label>
                                        <textarea class="form-control trumEdit" name="certificate_description" rows="5"><?php echo e($course->certificate_description ?? '<p><strong>Demonstrate Your Commitment</strong><br>Be a growth-driven professional and advance your career by learning new skills</p><p><strong>Share your Accomplishment</strong><br>Showcase your verified certificate on your social media platforms and CV</p>'); ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label class="mb-2"><?php echo app('translator')->get('Certificate Image'); ?></label>
                                        <?php if($course->certificate_image): ?>
                                            <div class="mb-2">
                                                <img src="<?php echo e(getImage(getFilePath('certificate') . '/' . $course->certificate_image)); ?>" 
                                                     alt="Certificate" class="img-thumbnail" style="max-width: 300px;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" name="certificate_image" accept=".png, .jpg, .jpeg">
                                        <small class="text-muted"><?php echo app('translator')->get('Upload certificate template image (leave empty to keep current or use default)'); ?></small>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="mb-2"><?php echo app('translator')->get('Course Preview Video'); ?></label>
                                        
                                        <input type="file" class="form-control" name="preview_video" accept="video/*">
                                        <small class="text-muted"><?php echo app('translator')->get('Select intro video If provided, this video will be shown instead of the course image on the details page.'); ?></small>
                                    </div>
                                </div>
                                
                                <!-- Instructor Fields -->
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="mb-2"><?php echo app('translator')->get('Instructor Image'); ?></label>
                                        <?php if($course->instructor_image): ?>
                                            <div class="mb-2">
                                                <img src="<?php echo e(getImage(getFilePath('instructor_image') . '/' . $course->instructor_image)); ?>" 
                                                     alt="Instructor" class="img-thumbnail" style="max-width: 150px;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" name="instructor_image" accept=".png, .jpg, .jpeg">
                                        <small class="text-muted"><?php echo app('translator')->get('Upload instructor profile image (leave empty to keep current)'); ?></small>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label class=" mb-2"><?php echo app('translator')->get('Instructor Details'); ?></label>
                                        <textarea class="form-control trumEdit" name="instructor_details" placeholder="Enter instructor biography, qualifications, experience, etc."><?php echo e(__($course->instructor_details)); ?></textarea>
                                    </div>
                                </div>
                                
                                <!-- SEO Fields -->
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class=" mb-2"><?php echo app('translator')->get('SEO Title'); ?></label>
                                        <input class="form-control" name="seo_title" 
                                               value="<?php echo e($course->seo_title ?? old('seo_title')); ?>" 
                                               placeholder="Enter SEO title for search engines" maxlength="60">
                                        <small class="text-muted"><?php echo app('translator')->get('Recommended: 50-60 characters'); ?></small>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class=" mb-2"><?php echo app('translator')->get('SEO Description'); ?></label>
                                        <textarea class="form-control" name="seo_description" rows="3" 
                                                  placeholder="Enter SEO description for search engines" maxlength="160"><?php echo e($course->seo_description ?? old('seo_description')); ?></textarea>
                                        <small class="text-muted"><?php echo app('translator')->get('Recommended: 150-160 characters'); ?></small>
                                    </div>
                                </div>

                                <!-- Launch Type -->
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="mb-2"><?php echo app('translator')->get('Launch Type'); ?> </label>
                                        <select class="form--control form-select" name="launch_type" id="launch_type" required>
                                            <option value="regular" <?php echo e(($course->launch_type ?? 'regular') == 'regular' ? 'selected' : ''); ?>><?php echo app('translator')->get('Regular Course'); ?></option>
                                            <option value="new_launch" <?php echo e(($course->launch_type ?? '') == 'new_launch' ? 'selected' : ''); ?>><?php echo app('translator')->get('New Launch'); ?></option>
                                            <option value="upcoming" <?php echo e(($course->launch_type ?? '') == 'upcoming' ? 'selected' : ''); ?>><?php echo app('translator')->get('Upcoming Course'); ?></option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Launch Date -->
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="mb-2"><?php echo app('translator')->get('Launch Date'); ?> </label>
                                        <input class="form-control" type="date" name="launch_date" id="launch_date" 
                                            value="<?php echo e($course->launch_date ? $course->launch_date->format('Y-m-d') : old('launch_date')); ?>">
                                        <small class="text-muted"><?php echo app('translator')->get('Leave empty for regular courses'); ?></small>
                                    </div>
                                </div>
                                

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn--primary btn-global" id="btn-save"
                                value="add"><?php echo app('translator')->get('Save'); ?></button>
                        </div>
                    </form>
                </div>
            </div><!-- card end -->
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .ck.ck-content.ck-editor__editable.ck-rounded-corners.ck-editor__editable_inline {
            height: 200px;
        }
        .launch-fields {
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            var fileAdded = <?php echo e(count($course->course_outline) - 1); ?>;
            
            // Image validation before form submission
            $('form').on('submit', function(e) {
                // Description validation
                var description = $('textarea[name="description"]').val();
                if (!description || description.trim() === '') {
                    e.preventDefault();
                    
                    // Show error message for description
                    if ($('.description-error-message').length === 0) {
                        $('textarea[name="description"]').after('<div class="alert alert-danger description-error-message mt-2"><i class="fas fa-exclamation-triangle"></i> Please enter a course description before saving.</div>');
                    }
                    
                    // Scroll to description field
                    $('html, body').animate({
                        scrollTop: $('textarea[name="description"]').offset().top - 100
                    }, 500);
                    
                    return false;
                }
                
                // Remove error message if description is entered
                $('.description-error-message').remove();
                
                // Learn Description validation
                var learnDescription = $('textarea[name="learn_description"]').val();
                if (!learnDescription || learnDescription.trim() === '') {
                    e.preventDefault();
                    
                    // Show error message for learn description
                    if ($('.learn-description-error-message').length === 0) {
                        $('textarea[name="learn_description"]').after('<div class="alert alert-danger learn-description-error-message mt-2"><i class="fas fa-exclamation-triangle"></i> Please enter what students will learn before saving.</div>');
                    }
                    
                    // Scroll to learn description field
                    $('html, body').animate({
                        scrollTop: $('textarea[name="learn_description"]').offset().top - 100
                    }, 500);
                    
                    return false;
                }
                
                // Remove error message if learn description is entered
                $('.learn-description-error-message').remove();
                
                // Curriculum validation
                var curriculum = $('textarea[name="curriculum"]').val();
                if (!curriculum || curriculum.trim() === '') {
                    e.preventDefault();
                    
                    // Show error message for curriculum
                    if ($('.curriculum-error-message').length === 0) {
                        $('textarea[name="curriculum"]').after('<div class="alert alert-danger curriculum-error-message mt-2"><i class="fas fa-exclamation-triangle"></i> Please enter course curriculum before saving.</div>');
                    }
                    
                    // Scroll to curriculum field
                    $('html, body').animate({
                        scrollTop: $('textarea[name="curriculum"]').offset().top - 100
                    }, 500);
                    
                    return false;
                }
                
                // Remove error message if curriculum is entered
                $('.curriculum-error-message').remove();
                
                var imageInput = $('#profilePicUpload1')[0];
                var hasImage = imageInput.files && imageInput.files.length > 0;
                var hasExistingImage = $('.profilePicPreview').css('background-image') !== 'none' && $('.profilePicPreview').css('background-image') !== '';
                var hasPreviewImage = $('.profilePicPreview').hasClass('has-image');
                
                // For edit form, check if there's an existing image or new image uploaded
                if (!hasImage && !hasExistingImage && !hasPreviewImage) {
                    e.preventDefault();
                    
                    // Show error message
                    if ($('.image-error-message').length === 0) {
                        $('.image-upload').after('<div class="alert alert-danger image-error-message mt-2"><i class="fas fa-exclamation-triangle"></i> Please upload a course image before saving.</div>');
                    }
                    
                    // Scroll to image upload section
                    $('html, body').animate({
                        scrollTop: $('.image-upload').offset().top - 100
                    }, 500);
                    
                    return false;
                }
                
                // Remove error message if image is selected
                $('.image-error-message').remove();
            });
            
            // Remove error messages when fields are filled
            $('textarea[name="description"]').on('input', function() {
                if ($(this).val().trim() !== '') {
                    $('.description-error-message').remove();
                }
            });
            
            $('textarea[name="learn_description"]').on('input', function() {
                if ($(this).val().trim() !== '') {
                    $('.learn-description-error-message').remove();
                }
            });
            
            $('textarea[name="curriculum"]').on('input', function() {
                if ($(this).val().trim() !== '') {
                    $('.curriculum-error-message').remove();
                }
            });
            
            // Remove error message when image is selected
            $('#profilePicUpload1').on('change', function() {
                if (this.files && this.files.length > 0) {
                    $('.image-error-message').remove();
                }
            });
            
            // Show/hide launch fields based on launch type
            $('#launch_type').on('change', function() {
                var launchType = $(this).val();
                if (launchType === 'regular') {
                    $('#launch_date').prop('required', false);
                } else {
                    $('#launch_date').prop('required', true);
                }
            });
            
            // Trigger change event on page load
            $('#launch_type').trigger('change');
            
            $('.addFile').on('click', function() {
                if (fileAdded >= 20) {
                    notify('error', 'You\'ve added maximum number of file');
                    return false;
                }
                fileAdded++;
                $("#fileUploadsContainer").append(`
                    <div class="row elements">
                        <div class="col-sm-12 my-3">
                            <div class="file-upload input-group">
                                <input type="text" name="course_outline[]" id="inputCourseOutline" class="form-control form--control "
                                    placeholder="Course Outline" required />  
                                    <button class="input-group-text btn--danger remove-btn border-0"><i class="las la-times"></i></button>                                          
                            </div>
                        </div>
                    </div>
                `)
            });
            $(document).on('click', '.remove-btn', function() {
                fileAdded--;
                $(this).closest('.elements').remove();
            });
            
            // FAQ Management JavaScript - ADD THIS SECTION
            let faqIndex = <?php echo e(isset($course) && $course->course_faqs ? count($course->course_faqs) : 1); ?>;
            
            // Add FAQ
            $('#add-faq').click(function() {
                const faqHtml = `
                    <div class="faq-item mb-3 p-3 border rounded">
                        <div class="row">
                            <div class="col-lg-5">
                                <label class="mb-2"><?php echo app('translator')->get('Question'); ?></label>
                                <input class="form-control" type="text" name="faqs[${faqIndex}][question]"
                                       placeholder="Enter FAQ question">
                            </div>
                            <div class="col-lg-6">
                                <label class="mb-2"><?php echo app('translator')->get('Answer'); ?></label>
                                <textarea class="form-control" name="faqs[${faqIndex}][answer]" rows="3"
                                          placeholder="Enter FAQ answer"></textarea>
                            </div>
                            <div class="col-lg-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-faq"><?php echo app('translator')->get('Remove'); ?></button>
                            </div>
                        </div>
                    </div>
                `;
                $('#faq-container').append(faqHtml);
                faqIndex++;
            });
            
            // Remove FAQ
            $(document).on('click', '.remove-faq', function() {
                if ($('.faq-item').length > 1) {
                    $(this).closest('.faq-item').remove();
                } else {
                    alert('<?php echo app('translator')->get("At least one FAQ is required"); ?>');
                }
            });
            // END FAQ Management JavaScript
            
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/admin/courses/edit.blade.php ENDPATH**/ ?>