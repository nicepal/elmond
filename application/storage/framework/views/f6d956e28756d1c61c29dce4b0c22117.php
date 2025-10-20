
<?php $__env->startSection('panel'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <form action="" method="POST">
                <?php echo csrf_field(); ?>
                <div class="card-body">
                    <div class="form-group">
                        <label class="mb-4"><?php echo app('translator')->get('Email Send Method'); ?></label>
                        <select name="email_method" class="form-control">
                            <option value="php" <?php if($general->mail_config->name == 'php'): ?> selected <?php endif; ?>><?php echo app('translator')->get('PHP
                                Mail'); ?></option>
                            <option value="smtp" <?php if($general->mail_config->name == 'smtp'): ?> selected
                                <?php endif; ?>><?php echo app('translator')->get('SMTP'); ?></option>
                        </select>
                    </div>
                    <div class="row mt-4 d-none configForm" id="smtp">
                        <div class="col-md-12">
                            <h6 class="mb-2"><?php echo app('translator')->get('SMTP Configuration'); ?></h6>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold"><?php echo app('translator')->get('Host'); ?> </label>
                                <input type="text" class="form-control" placeholder="e.g. <?php echo app('translator')->get('smtp.googlemail.com'); ?>"
                                    name="host" value="<?php echo e($general->mail_config->host ?? ''); ?>" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold"><?php echo app('translator')->get('Port'); ?> </label>
                                <input type="text" class="form-control" placeholder="<?php echo app('translator')->get('Available port'); ?>"
                                    name="port" value="<?php echo e($general->mail_config->port ?? ''); ?>" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold"><?php echo app('translator')->get('Encryption'); ?></label>
                                <select class="form-control" name="enc">
                                    <option value="ssl" <?php echo e(@$general->mail_config->enc == 'ssl' ? 'selected' : ""); ?>><?php echo app('translator')->get('SSL'); ?></option>
                                    <option value="tls" <?php echo e(@$general->mail_config->enc == 'tls' ? 'selected' : ""); ?>><?php echo app('translator')->get('TLS'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold"><?php echo app('translator')->get('Username'); ?> </label>
                                <input type="text" class="form-control"
                                    placeholder="<?php echo app('translator')->get('Normally your email'); ?> address" name="username"
                                    value="<?php echo e($general->mail_config->username ?? ''); ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold"><?php echo app('translator')->get('Password'); ?> </label>
                                <input type="text" class="form-control"
                                    placeholder="<?php echo app('translator')->get('Normally your email password'); ?>" name="password"
                                    value="<?php echo e($general->mail_config->password ?? ''); ?>" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn--primary btn-global"><?php echo app('translator')->get('Save'); ?></button>
                </div>
            </form>
        </div><!-- card end -->
    </div>


</div>



<div id="testMailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo app('translator')->get('Send Test Email'); ?></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="<?php echo e(route('admin.setting.notification.email.test')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Sent to'); ?> </label>
                                <input type="text" name="email" class="form-control"
                                    placeholder="<?php echo app('translator')->get('Email Address'); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary btn-global"><?php echo app('translator')->get('Send'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('breadcrumb-plugins'); ?>
<button type="button" data-bs-target="#testMailModal" data-bs-toggle="modal" class="btn btn-sm btn--primary"><?php echo app('translator')->get('Test
    Mail'); ?></button>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script'); ?>
<script>
    (function ($) {
        "use strict";

        var method = '<?php echo e($general->mail_config->name); ?>';
        emailMethod(method);
        $('select[name=email_method]').on('change', function () {
            var method = $(this).val();
            emailMethod(method);
        });

        function emailMethod(method) {
            $('.configForm').addClass('d-none');
            if (method != 'php') {
                $(`#${method}`).removeClass('d-none');
            }
        }
    })(jQuery);

</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/admin/notification/email_setting.blade.php ENDPATH**/ ?>