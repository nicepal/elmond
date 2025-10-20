
<?php $__env->startSection('panel'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo e(route('admin.coupons.update', $coupon->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Coupon Name'); ?></label>
                                <input type="text" class="form-control" name="name" required value="<?php echo e(old('name', $coupon->name)); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Coupon Code'); ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="code" required value="<?php echo e(old('code', $coupon->code)); ?>">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn--primary generate-code"><?php echo app('translator')->get('Generate'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Description'); ?></label>
                                <textarea class="form-control" name="description" rows="3"><?php echo e(old('description', $coupon->description)); ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Discount Type'); ?></label>
                                <select class="form-control" name="discount_type" required>
                                    <option value="percentage" <?php echo e(old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : ''); ?>><?php echo app('translator')->get('Percentage'); ?></option>
                                    <option value="fixed" <?php echo e(old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : ''); ?>><?php echo app('translator')->get('Fixed Amount'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Discount Amount'); ?></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" class="form-control" name="discount_amount" required value="<?php echo e(old('discount_amount', $coupon->discount_amount)); ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text discount-type-addon"><?php echo e($coupon->discount_type == 'percentage' ? '%' : '₹'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Minimum Purchase Amount'); ?></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">₹</span>
                                    </div>
                                    <input type="number" step="0.01" min="0" class="form-control" name="minimum_purchase" value="<?php echo e(old('minimum_purchase', $coupon->minimum_purchase)); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Start Date'); ?></label>
                                <input type="datetime-local" class="form-control" name="starts_at" value="<?php echo e(old('starts_at', $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : '')); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Expiry Date'); ?></label>
                                <input type="datetime-local" class="form-control" name="expires_at" value="<?php echo e(old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '')); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Usage Limit (Total)'); ?></label>
                                <input type="number" min="0" class="form-control" name="usage_limit" value="<?php echo e(old('usage_limit', $coupon->usage_limit)); ?>" placeholder="Unlimited if empty">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Usage Limit Per User'); ?></label>
                                <input type="number" min="0" class="form-control" name="usage_limit_per_user" value="<?php echo e(old('usage_limit_per_user', $coupon->usage_limit_per_user)); ?>" placeholder="Unlimited if empty">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Applicable Courses'); ?></label>
                                <select class="form-control select2-multi-select" name="applicable_courses[]" multiple>
                                    <option value=""><?php echo app('translator')->get('All Courses'); ?></option>
                                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($course->id); ?>" <?php echo e((is_array(old('applicable_courses', $coupon->applicable_courses)) && in_array($course->id, old('applicable_courses', $coupon->applicable_courses ?? []))) ? 'selected' : ''); ?>>
                                        <?php echo e($course->title ?? $course->name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small class="text-muted"><?php echo app('translator')->get('Leave empty to apply to all courses'); ?></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_first_purchase_only" name="is_first_purchase_only" <?php echo e(old('is_first_purchase_only', $coupon->is_first_purchase_only) ? 'checked' : ''); ?>>
                                    <label class="custom-control-label" for="is_first_purchase_only"><?php echo app('translator')->get('First Purchase Only'); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_registration_bonus" name="is_registration_bonus" <?php echo e(old('is_registration_bonus', $coupon->is_registration_bonus) ? 'checked' : ''); ?>>
                                    <label class="custom-control-label" for="is_registration_bonus"><?php echo app('translator')->get('Registration Bonus'); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="active" name="active" <?php echo e(old('active', $coupon->active) ? 'checked' : ''); ?>>
                                    <label class="custom-control-label" for="active"><?php echo app('translator')->get('Active'); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn--primary btn-block"><?php echo app('translator')->get('Update Coupon'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>
<a href="<?php echo e(route('admin.coupons.index')); ?>" class="btn btn-sm btn--primary box--shadow1 text--small">
    <i class="la la-fw la-backward"></i> <?php echo app('translator')->get('Go Back'); ?>
</a>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script>
    (function($) {
        "use strict";
        
        // Update discount type addon
        $('select[name=discount_type]').on('change', function() {
            var type = $(this).val();
            if (type === 'percentage') {
                $('.discount-type-addon').text('%');
            } else {
                $('.discount-type-addon').text('₹');
            }
        });
        
        // Generate random coupon code
        $('.generate-code').on('click', function() {
            $.get('<?php echo e(route("admin.coupons.generate-code")); ?>', function(response) {
                $('input[name=code]').val(response.code);
            });
        });
        
        // Initialize select2
        $('.select2-multi-select').select2();
    })(jQuery);
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/admin/coupons/edit.blade.php ENDPATH**/ ?>