
<?php $__env->startSection('panel'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--md table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('Name'); ?></th>
                                <th><?php echo app('translator')->get('Code'); ?></th>
                                <th><?php echo app('translator')->get('Discount'); ?></th>
                                <th><?php echo app('translator')->get('Usage / Limit'); ?></th>
                                <th><?php echo app('translator')->get('Validity'); ?></th>
                                <th><?php echo app('translator')->get('Status'); ?></th>
                                <th><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td data-label="<?php echo app('translator')->get('Name'); ?>">
                                    <span class="fw-bold"><?php echo e($coupon->name); ?></span>
                                    <?php if($coupon->is_first_purchase_only): ?>
                                    <span class="badge badge--primary">First Purchase</span>
                                    <?php endif; ?>
                                    <?php if($coupon->is_registration_bonus): ?>
                                    <span class="badge badge--success">Registration Bonus</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="<?php echo app('translator')->get('Code'); ?>"><span class="badge badge--dark"><?php echo e($coupon->code); ?></span></td>
                                <td data-label="<?php echo app('translator')->get('Discount'); ?>">
                                    <?php if($coupon->discount_type == 'percentage'): ?>
                                    <?php echo e($coupon->discount_amount); ?>%
                                    <?php else: ?>
                                    ₹<?php echo e(number_format($coupon->discount_amount, 2)); ?>

                                    <?php endif; ?>
                                    <?php if($coupon->minimum_purchase > 0): ?>
                                    <small class="d-block">Min: ₹<?php echo e(number_format($coupon->minimum_purchase, 2)); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td data-label="<?php echo app('translator')->get('Usage / Limit'); ?>">
                                    <?php echo e($coupon->usages()->count()); ?> / <?php echo e($coupon->usage_limit ?: '∞'); ?>

                                    <?php if($coupon->usage_limit_per_user): ?>
                                    <small class="d-block"><?php echo e($coupon->usage_limit_per_user); ?> per user</small>
                                    <?php endif; ?>
                                </td>
                                <td data-label="<?php echo app('translator')->get('Validity'); ?>">
                                    <?php if($coupon->starts_at): ?>
                                    <span>From: <?php echo e($coupon->starts_at->format('M d, Y')); ?></span><br>
                                    <?php endif; ?>
                                    <?php if($coupon->expires_at): ?>
                                    <span>Until: <?php echo e($coupon->expires_at->format('M d, Y')); ?></span>
                                    <?php else: ?>
                                    <span class="text-muted">No expiry</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="<?php echo app('translator')->get('Status'); ?>">
                                    <?php if($coupon->active): ?>
                                    <span class="badge badge--success"><?php echo app('translator')->get('Active'); ?></span>
                                    <?php else: ?>
                                    <span class="badge badge--danger"><?php echo app('translator')->get('Inactive'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="<?php echo app('translator')->get('Action'); ?>">
                                    <a href="<?php echo e(route('admin.coupons.edit', $coupon->id)); ?>" class="btn btn-sm btn-outline--primary">
                                        <i class="la la-pencil"></i> <?php echo app('translator')->get('Edit'); ?>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                        data-action="<?php echo e(route('admin.coupons.destroy', $coupon->id)); ?>"
                                        data-question="<?php echo app('translator')->get('Are you sure to delete this coupon?'); ?>">
                                        <i class="la la-trash"></i> <?php echo app('translator')->get('Delete'); ?>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td class="text-muted text-center" colspan="100%"><?php echo e(__($emptyMessage)); ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if($coupons->hasPages()): ?>
            <div class="card-footer py-4">
                <?php echo e(paginateLinks($coupons)); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo app('translator')->get('Confirmation'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <p class="question"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal"><?php echo app('translator')->get('No'); ?></button>
                    <button type="submit" class="btn btn--primary"><?php echo app('translator')->get('Yes'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>
<a href="<?php echo e(route('admin.coupons.create')); ?>" class="btn btn-sm btn--primary box--shadow1 text--small">
    <i class="la la-plus"></i> <?php echo app('translator')->get('Add New'); ?>
</a>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script>
    (function($) {
        "use strict";
        
        $('.confirmationBtn').on('click', function() {
            var modal = $('#confirmationModal');
            modal.find('form').attr('action', $(this).data('action'));
            modal.find('.question').text($(this).data('question'));
            modal.modal('show');
        });
    })(jQuery);
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/admin/coupons/index.blade.php ENDPATH**/ ?>