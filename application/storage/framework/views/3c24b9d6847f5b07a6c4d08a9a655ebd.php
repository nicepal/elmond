<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive p-4">
                        <form action="<?php echo e(route('admin.organizations.update', $organization->id)); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Company Name'); ?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="company_name" value="<?php echo e(old('company_name', $organization->company_name)); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Contact Person Name'); ?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="contact_person_name" value="<?php echo e(old('contact_person_name', $organization->contact_person_name)); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Email'); ?> <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" value="<?php echo e(old('email', $organization->email)); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Designation'); ?></label>
                                            <input type="text" class="form-control" name="designation" value="<?php echo e(old('designation', $organization->designation)); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Country'); ?> <span class="text-danger">*</span></label>
                                            <select name="country_code" class="form-control" required>
                                                <option value=""><?php echo app('translator')->get('Select One'); ?></option>
                                                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option data-mobile_code="<?php echo e($country->dial_code); ?>" value="<?php echo e($country->dial_code); ?>" data-code="<?php echo e($key); ?>" <?php echo e($organization->country_code == $country->dial_code ? 'selected' : ''); ?>><?php echo e(__($country->country)); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Mobile'); ?> <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text mobile-code"><?php echo e($organization->country_code); ?></span>
                                                <input type="number" name="mobile" value="<?php echo e(old('mobile', $organization->mobile)); ?>" class="form-control checkUser" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Address'); ?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="address" value="<?php echo e(old('address', $organization->address)); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('City'); ?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="city" value="<?php echo e(old('city', $organization->city)); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('State'); ?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="state" value="<?php echo e(old('state', $organization->state)); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Zip Code'); ?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="zip" value="<?php echo e(old('zip', $organization->zip)); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Status Field -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Status'); ?> <span class="text-danger">*</span></label>
                                            <select name="status" class="form-control" required>
                                                <option value="1" <?php echo e(old('status', $organization->status) == 1 ? 'selected' : ''); ?>><?php echo app('translator')->get('Active'); ?></option>
                                                <option value="0" <?php echo e(old('status', $organization->status) == 0 ? 'selected' : ''); ?>><?php echo app('translator')->get('Inactive'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Course Assignment Section -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Assign Courses'); ?></label>
                                            <div class="row">
                                                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="col-md-6 mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="courses[]" value="<?php echo e($course->id); ?>" id="course_<?php echo e($course->id); ?>" <?php echo e(in_array($course->id, $assignedCourses) ? 'checked' : ''); ?>>
                                                            <label class="form-check-label" for="course_<?php echo e($course->id); ?>">
                                                                <?php echo e($course->name); ?> 
                                                                <span class="text-muted">(<?php echo e($general->cur_sym); ?><?php echo e(showAmount($course->price)); ?>)</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn--primary w-100 h-45"><?php echo app('translator')->get('Update Organization'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>
    <a href="<?php echo e(route('admin.organizations.show', $organization->id)); ?>" class="btn btn-sm btn-outline--primary">
        <i class="las la-eye"></i> <?php echo app('translator')->get('View Details'); ?>
    </a>
    <a href="<?php echo e(route('admin.organizations.index')); ?>" class="btn btn-sm btn-outline--dark">
        <i class="las la-list"></i> <?php echo app('translator')->get('All Organizations'); ?>
    </a>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script>
    "use strict";
    (function ($) {
        <?php if($countries): ?>
            $('select[name=country_code]').change(function(){
                $('input[name=mobile_code]').val($('select[name=country_code] :selected').data('mobile_code'));
                $('input[name=country]').val($('select[name=country_code] :selected').data('code'));
                $('.mobile-code').text('+'+$('select[name=country_code] :selected').data('mobile_code'));
            });
            
            // Initialize mobile code display
            var phoneCode = $('select[name=country_code] :selected').data('mobile_code');
            var countryCode = $('select[name=country_code] :selected').data('code');
            $('input[name=mobile_code]').val(phoneCode);
            $('input[name=country]').val(countryCode);
            $('.mobile-code').text('+'+phoneCode);
        <?php endif; ?>
    })(jQuery);
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/admin/organizations/edit.blade.php ENDPATH**/ ?>