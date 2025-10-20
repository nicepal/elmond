
<?php $__env->startSection('content'); ?>
    <section class="account py-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-12">
                    <div class="account-form base--card mt-5">
                        <div class="account-form__content mb-4">
                            <h3 class="account-form__title mb-2 text-center"><?php echo e(__($pageTitle)); ?></h3>
                        </div>
                        <form method="POST" action="<?php echo e(route('user.data.submit')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="form-group col-sm-6 mb-4">
                                    <label class="form--label"><?php echo app('translator')->get('First Name'); ?></label>
                                    <input type="text" class="form-control form--control" name="firstname"
                                        value="<?php echo e($user->firstname ?? old('firstname')); ?>" placeholder="<?php echo app('translator')->get('First Name'); ?>" required>
                                </div>

                                <div class="form-group col-sm-6 mb-4">
                                    <label class="form--label"><?php echo app('translator')->get('Last Name'); ?></label>
                                    <input type="text" class="form-control form--control" name="lastname"
                                        value="<?php echo e($user->lastname ?? old('lastname')); ?>" placeholder="<?php echo app('translator')->get('Last Name'); ?>" required>
                                </div>
                                <div class="form-group col-sm-6 mb-4">
                                    <label class="form--label"><?php echo app('translator')->get('Address'); ?></label>
                                    <input type="text" class="form-control form--control" name="address"
                                        value="<?php echo e(@$user->address->address ?? old('address')); ?>" placeholder="<?php echo app('translator')->get('Address'); ?>">
                                </div>
                                <div class="form-group col-sm-6 mb-4">
                                    <label class="form--label"><?php echo app('translator')->get('State'); ?></label>
                                    <input type="text" class="form-control form--control" name="state"
                                        value="<?php echo e(@$user->address->state ?? old('state')); ?>" placeholder="<?php echo app('translator')->get('State'); ?>">
                                </div>
                                <div class="form-group col-sm-6 mb-4">
                                    <label class="form--label"><?php echo app('translator')->get('Zip Code'); ?></label>
                                    <input type="text" class="form-control form--control" name="zip"
                                        value="<?php echo e(@$user->address->zip ?? old('zip')); ?>" placeholder="<?php echo app('translator')->get('Zip'); ?>">
                                </div>

                                <div class="form-group col-sm-6 mb-4">
                                    <label class="form--label"><?php echo app('translator')->get('City'); ?></label>
                                    <input type="text" class="form-control form--control" name="city"
                                        value="<?php echo e(@$user->address->city ?? old('city')); ?>" placeholder="<?php echo app('translator')->get('City'); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class=" btn btn--base w-100">
                                    <?php echo app('translator')->get('Save'); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/presets/default/user/user_data.blade.php ENDPATH**/ ?>