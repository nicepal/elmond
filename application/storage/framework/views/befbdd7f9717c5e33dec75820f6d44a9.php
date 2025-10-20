
<?php $__env->startSection('content'); ?>
    <div class="row mx-lg-0">
        <div class="col-lg-12">
            <div class="tbl-wrap">
                <div class="col-12 mb-3 d-flex justify-content-end">
                    <form method="GET" autocomplete="off">
                        <div class="search-box w-100">
                            <input type="text" class="form--control" name="search" placeholder="<?php echo app('translator')->get('Search...'); ?>"
                                value="<?php echo e(request()->search); ?>">
                            <button type="submit" class="search-box__button"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="table-area m-0">
                    <table class="table table--responsive--lg">
                        <thead>
                            <tr>
                                <th class="text-center"><?php echo app('translator')->get('Gateway'); ?></th>
                                <th class="text-center"><?php echo app('translator')->get('Initiated'); ?></th>
                                <th class="text-center"><?php echo app('translator')->get('Amount'); ?></th>
                                <th class="text-center"><?php echo app('translator')->get('Conversion'); ?></th>
                                <th class="text-center"><?php echo app('translator')->get('Status'); ?></th>
                                <th class="text-center"><?php echo app('translator')->get('Details'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td data-label="Gateway" class="text-center">
                                        <span class="fw-bold"> <span
                                                ><?php echo e(__($deposit->gateway?->name)); ?></span>
                                        </span>
                                    </td>

                                    <td class="text-center" data-label="Initiated">
                                        <?php echo e(showDateTime($deposit->created_at)); ?>

                                    </td>
                                    <td class="text-center" data-label="Amount">
                                        <span title="<?php echo app('translator')->get('Amount with charge'); ?>">
                                            <?php echo e(showAmount($deposit->amount + $deposit->charge)); ?>

                                            <?php echo e(__($general->cur_text)); ?>

                                        </span>
                                    </td>
                                    <td class="text-center" data-label="Conversion">
                                        <span><?php echo e(showAmount($deposit->final_amo)); ?>

                                            <?php echo e(__($deposit->method_currency)); ?></span>
                                    </td>
                                    <td class="text-center" data-label="Status">
                                        <?php echo $deposit->statusBadge ?>
                                    </td>
                                    <?php
                                        $details = $deposit->detail != null ? json_encode($deposit->detail) : null;
                                    ?>

                                    <td data-label="Gateway" class="table-dropdown">
                                        <a href="javascript:void(0)"
                                            class="btn btn--sm <?php if($deposit->method_code >= 1000): ?> detailBtn <?php else: ?> disabled <?php endif; ?>"
                                            <?php if($deposit->method_code >= 1000): ?> data-info="<?php echo e($details); ?>" <?php endif; ?>
                                            <?php if($deposit->status == 3): ?> data-admin_feedback="<?php echo e($deposit->admin_feedback); ?>" <?php endif; ?>>
                                            <?php echo app('translator')->get('View'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="100%" class="text-center" data-label="Gateway"><?php echo e(__($emptyMessage)); ?>

                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-5">
                    <nav class="d-flex justify-content-end">
                        <?php echo e($deposits->links()); ?>

                    </nav>
                </div>
            </div>
        </div>
    </div>

    
    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo app('translator')->get('Details'); ?></h5>
                    <span class="close" data-bs-dismiss="modal">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData mb-2">
                    </ul>
                    <div class="feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--base btn-sm" data-bs-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var userData = $(this).data('info');
                var html = '';
                if (userData) {
                    userData.forEach(element => {
                        if (element.type != 'file') {
                            html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${element.name}</span>
                                <span">${element.value}</span>
                            </li>`;
                        }
                    });
                }

                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong><?php echo app('translator')->get('Admin Feedback'); ?></strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);
                modal.modal('show');
            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/presets/default/user/deposit_history.blade.php ENDPATH**/ ?>