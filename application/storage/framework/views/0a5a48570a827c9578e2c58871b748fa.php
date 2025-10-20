

<?php $__env->startSection('panel'); ?>
    <div class="row">
 
        <div class="col-md-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two custom-data-table">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('Title'); ?></th>
                                    <th class="text-center"><?php echo app('translator')->get('Category'); ?></th>
                                    <th class="text-center"><?php echo app('translator')->get('Created at'); ?></th>
                                    <th class="text-center"><?php echo app('translator')->get('Status'); ?></th>
                                    <th class="text-center"><?php echo app('translator')->get('Live class'); ?></th>
                                    <th class="text-center"><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <span><?php echo e(__(@$item->title)); ?></span>
                                        </td>
                                        <td>
                                            <span>
                                                <?php echo e(__(@$item->course_category?->name)); ?></span>
                                        </td>


                                        <td class="text-center">
                                            <?php echo e(showDateTime($item->created_at)); ?> <br>
                                            <?php echo e(diffForHumans($item->created_at)); ?>

                                        </td>


                                        <td>
                                            <?php if($item->status == 1): ?>
                                                <span class="badge badge--success"><?php echo app('translator')->get('Active'); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge--danger"><?php echo app('translator')->get('Pending'); ?></span>
                                            <?php endif; ?>
                                        </td>


                                        <td>
                                            <?php if($item->preview_video == 3): ?>
                                                <span class="badge badge--danger "><?php echo app('translator')->get('Live'); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge--success"><?php echo app('translator')->get('N/A'); ?></span>
                                            <?php endif; ?>
                                        </td>


                                        <td class="text-center">
                                            <div class="button--group text-center">
                                                <a class="btn btn-sm btn--primary ms-1"
                                                    href="<?php echo e(route('admin.lesson.edit', $item->id)); ?>">
                                                    <i class="fa-solid fa-pen"></i></a>
                                                <a class="btn btn-sm btn--danger ms-1" href="javascript:void(0)"
                                                    data-url="<?php echo e(route('admin.lesson.delete', @$item->id)); ?>"
                                                    onclick="lessonDeleteModal(this)">
                                                    <i class="fa-solid fa-trash"></i></a></span>
                                            </div>

                                            <?php if($item->preview_video == 3): ?>
                                                <?php
                                                    $zoomData = @$item->zoom_data;
                                                ?>
                                                <a class="btn btn--success btn-sm mt-2"
                                                    href="<?php echo e(@$zoomData->data?->start_url); ?>">
                                                    <i class="fa-solid fa-video"></i></span>
                                            <?php endif; ?>
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

                    <?php if($lessons->hasPages()): ?>
                        <div class="card-footer text-end">
                            <?php echo e($lessons->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoModalLabel"><?php echo app('translator')->get('Alert'); ?></h5>
                    <button type="button" class="btn-close btn btn--danger" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <p><?php echo app('translator')->get('Are you sure? You want delete this course?'); ?></p>
                        <input type="text" hidden name="fileName" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--secondary" data-modal="0"
                            data-bs-dismiss="modal"><?php echo app('translator')->get('No'); ?></button>
                        <button type="submit" class="btn btn--primary" data-bs-dismiss="modal"
                            data-modal="1"><?php echo app('translator')->get('Yes'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>
    <div class="d-flex flex-wrap justify-content-end">
        <a class="btn btn-sm btn--primary me-2 d-flex align-items-center"
            href="<?php echo e(route('admin.lesson.create',$id)); ?>"><i class="las la-plus"></i><?php echo app('translator')->get('Add New'); ?></a>
        <form method="GET" class="form-inline">
            <div class="input-group justify-content-end">
                <input type="text" name="search" class="form-control bg--white search-color" placeholder="<?php echo app('translator')->get('Search by Username'); ?>"
                    value="<?php echo e(request()->search); ?>">
                <button class="btn btn--primary input-group-text" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </form>
    </div>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('script'); ?>
    <script>
        function lessonDeleteModal(object) {
            var data
            var videoModal = $('#videoModal');
            var url = $(object).data('url');
            videoModal.find('form').attr('action', url);
            videoModal.modal('show');
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/admin/lessons/index.blade.php ENDPATH**/ ?>