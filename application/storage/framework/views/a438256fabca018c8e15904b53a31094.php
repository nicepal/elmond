
<?php $__env->startSection('content'); ?>
    <div class="row mx-lg-0">
        <div class="col-lg-12">
            <div class="tbl-wrap">
                <div class="col-12 d-lg-flex justify-content-end align-items-center mb-3">
                    <form method="GET" autocomplete="off">
                        <div class="search-box">
                            <input type="text" class="form--control" name="search" placeholder="<?php echo app('translator')->get('Search...'); ?>"
                                value="<?php echo e(request()->search); ?>">
                            <button type="submit" class="search-box__button"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="title ms-3 mb-4">
            <h4><?php echo app('translator')->get('All Courses'); ?></h4>
        </div>
        <div class="row gy-4 mx-lg-0 mb-5">
            <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6">
                    <div class="base-card">
                        <?php if(@$item->discount > 0): ?>
                            <span class="dis-tag">-<?php echo e(@$item->discount); ?>% </span>
                        <?php endif; ?>

                        <div class="thumb-wrap">
                            <img src="<?php echo e(getImage(getFilePath('course_image') . '/' . $item->image)); ?>" alt="...">
                        </div>
                        <div class="content-wrap">
                            <p class="category"><?php echo e(__(@$item->category->name)); ?></p>
                            <a href="<?php echo e(route('course.details', [slug($item->name), $item->id])); ?>">
                                <h6 class="title"><?php echo e(__(@$item->name)); ?></h6>
                            </a>
                            <ul class="product-status">
                                <li>
                                    <i class="fa-solid fa-clock"></i>
                                    <p><?php echo e(str_replace('ago', '', diffForHumans(@$item->created_at))); ?>

                                    </p>
                                </li>
                                <li>
                                    <i class="fa-solid fa-graduation-cap"></i>
                                    <p><?php echo e(__(@$item->enrolls->count())); ?> <?php echo app('translator')->get('Students'); ?></p>
                                </li>
                            </ul>
                        </div>
                        <div class="carn-btm">
                            <ul class="star-wrap rating-wrap">
                                <?php
                                    $averageRatingHtml = calculateAverageRating(@$item->average_rating);
                                    if (!empty($averageRatingHtml['ratingHtml'])) {
                                        echo $averageRatingHtml['ratingHtml'];
                                    }
                                ?>

                                <li>
                                    <p> <?php echo e(@$item->average_rating ?? 0); ?>.0
                                        (<?php echo e(@$item->review_count ?? 0); ?>)
                                    </p>
                                </li>
                            </ul>

                            <div class="price-wrap">
                                <?php if(@$item->discount > 0): ?>
                                    <h6 class="price">
                                        <?php echo e(@$general->cur_sym); ?><?php echo e(priceCalculate(@$item->price, @$item->discount)); ?>

                                    </h6>
                                <?php elseif(@$item->price == 0.0): ?>
                                    <h6 class="price"><?php echo app('translator')->get('Free'); ?></h6>
                                <?php else: ?>
                                    <h6 class="price"><?php echo e(@$general->cur_sym); ?><?php echo e(@$item->price); ?></h6>
                                <?php endif; ?>
                           

                                <?php if(@$item->discount > 0): ?>
                                    <p class="dis-price">
                                        <?php echo e(@$general->cur_sym); ?><?php echo e(@$item->price); ?>

                                    </p>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div>
                    <h5 class="text-muted text-center text--base" colspan="100%"><?php echo app('translator')->get('No data found'); ?></h5>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if($courses->hasPages()): ?>
        <div class="card-footer text-end mb-4">
            <?php echo e($courses->links()); ?>

        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/presets/default/user/enrolls/all_courses.blade.php ENDPATH**/ ?>