<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('Company'); ?></th>
                                    <th><?php echo app('translator')->get('Contact Person'); ?></th>
                                    <th><?php echo app('translator')->get('Email'); ?></th>
                                    <th><?php echo app('translator')->get('Mobile'); ?></th>
                                    <th><?php echo app('translator')->get('Employees'); ?></th>
                                    <th><?php echo app('translator')->get('Courses'); ?></th>
                                    <th><?php echo app('translator')->get('Status'); ?></th>
                                    <th><?php echo app('translator')->get('Joined At'); ?></th>
                                    <th><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organization): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <span class="fw-bold"><?php echo e($organization->company_name); ?></span><br>
                                            <span class="small text-muted"><?php echo e($organization->city); ?>, <?php echo e($organization->state); ?></span>
                                        </td>
                                        <td>
                                            <span><?php echo e($organization->contact_person_name); ?></span><br>
                                            <?php if($organization->designation): ?>
                                                <span class="small text-muted"><?php echo e($organization->designation); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($organization->email); ?></td>
                                        <td><?php echo e($organization->country_code); ?><?php echo e($organization->mobile); ?></td>
                                        <td>
                                            <span class="fw-bold"><?php echo e($organization->users()->count()); ?></span>
                                            <small class="text-muted d-block"><?php echo e($organization->activeUsers()->count()); ?> active</small>
                                        </td>
                                        <td>
                                            <span class="fw-bold"><?php echo e($organization->courses()->count()); ?></span>
                                            <small class="text-muted d-block"><?php echo e($organization->activeCourses()->count()); ?> active</small>
                                        </td>
                                        <td>
                                            <?php
                                                echo $organization->statusBadge;
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo e(showDateTime($organization->created_at)); ?><br>
                                            <span class="text-muted"><?php echo e(diffForHumans($organization->created_at)); ?></span>
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="<?php echo e(route('admin.organizations.show', $organization->id)); ?>" class="btn btn-sm btn-outline--primary">
                                                    <i class="las la-desktop"></i> <?php echo app('translator')->get('Details'); ?>
                                                </a>
                                                <a href="<?php echo e(route('admin.organizations.edit', $organization->id)); ?>" class="btn btn-sm btn-outline--primary">
                                                    <i class="las la-pen"></i> <?php echo app('translator')->get('Edit'); ?>
                                                </a>
                                                <?php if($organization->users()->count() == 0): ?>
                                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-action="<?php echo e(route('admin.organizations.destroy', $organization->id)); ?>" data-question="<?php echo app('translator')->get('Are you sure to delete this organization?'); ?>">
                                                        <i class="las la-trash"></i> <?php echo app('translator')->get('Delete'); ?>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
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
                <?php if($organizations->hasPages()): ?>
                    <div class="card-footer py-4">
                        <?php echo e(paginateLinks($organizations)); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (isset($component)) { $__componentOriginalbd5922df145d522b37bf664b524be380 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd5922df145d522b37bf664b524be380 = $attributes; } ?>
<?php $component = App\View\Components\ConfirmationModal::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('confirmation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\ConfirmationModal::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd5922df145d522b37bf664b524be380)): ?>
<?php $attributes = $__attributesOriginalbd5922df145d522b37bf664b524be380; ?>
<?php unset($__attributesOriginalbd5922df145d522b37bf664b524be380); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd5922df145d522b37bf664b524be380)): ?>
<?php $component = $__componentOriginalbd5922df145d522b37bf664b524be380; ?>
<?php unset($__componentOriginalbd5922df145d522b37bf664b524be380); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>
    <?php if (isset($component)) { $__componentOriginal3c8b11d14bd2f3c6983234fd6ddee71a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c8b11d14bd2f3c6983234fd6ddee71a = $attributes; } ?>
<?php $component = App\View\Components\SearchForm::resolve(['placeholder' => 'Search by company name, email, contact person...'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\SearchForm::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3c8b11d14bd2f3c6983234fd6ddee71a)): ?>
<?php $attributes = $__attributesOriginal3c8b11d14bd2f3c6983234fd6ddee71a; ?>
<?php unset($__attributesOriginal3c8b11d14bd2f3c6983234fd6ddee71a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3c8b11d14bd2f3c6983234fd6ddee71a)): ?>
<?php $component = $__componentOriginal3c8b11d14bd2f3c6983234fd6ddee71a; ?>
<?php unset($__componentOriginal3c8b11d14bd2f3c6983234fd6ddee71a); ?>
<?php endif; ?>
    <a href="<?php echo e(route('admin.organizations.create')); ?>" class="btn btn-sm btn-outline--primary">
        <i class="las la-plus"></i><?php echo app('translator')->get('Add New Organization'); ?>
    </a>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script>
    (function($){
        'use strict';
        $('.confirmationBtn').on('click', function () {
            var modal = $('#confirmationModal');
            var data  = $(this).data();
            modal.find('.question').text(`${data.question}`);
            modal.find('form').attr('action', `${data.action}`);
            modal.modal('show');
        });
    })(jQuery);
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/admin/organizations/list.blade.php ENDPATH**/ ?>