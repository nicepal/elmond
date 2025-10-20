<?php
    $newLaunchCourses = App\Models\Course::with('category', 'enrolls', 'reviews')
        ->where('launch_type', 'new_launch')
        ->where('admin_status', 1)
        ->where('status', 1)
        ->orderBy('launch_date', 'desc')
        ->take(6)
        ->get();
?>

<?php if($newLaunchCourses->count() > 0): ?>
<section class="new-launch-courses pt-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-header text-center">
                    <h2 class="section-title"><?php echo app('translator')->get('New Launch Courses'); ?></h2>
                    <p class="section-desc"><?php echo app('translator')->get('Discover our latest course offerings'); ?></p>
                </div>
            </div>
        </div>
        
        <div class="row gy-4">
            <?php $__currentLoopData = $newLaunchCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6">
                    <div class="base-card mb-4">
                        <?php if($course->discount > 0): ?>
                            <span class="dis-tag">-<?php echo e($course->discount); ?>%</span>
                        <?php endif; ?>
                        <div class="launch-badge new-launch">
                            <i class="fas fa-rocket"></i> <?php echo app('translator')->get('New Launch'); ?>
                        </div>
                        <div class="thumb-wrap">
                            <img src="<?php echo e(getImage(getFilePath('course_image') . '/' . $course->image)); ?>"
                                alt="course_image">
                        </div>
                        <div class="content-wrap">
                            <p class="category"><?php echo e(__(@$course->category->name)); ?></p>
                            <a href="<?php echo e(route('course.details', [slug($course->name), $course->id])); ?>">
                                <h6 class="title"><?php echo e(__(strLimit(@$course->name, 23))); ?></h6>
                            </a>
                            <?php if($course->launch_date): ?>
                                <p class="launch-date new-launch-date">
                                    <i class="fas fa-calendar-alt"></i> 
                                    <?php echo app('translator')->get('Launched'); ?>: <?php echo e($course->launch_date->format('M d, Y')); ?>

                                </p>
                            <?php endif; ?>
                            <ul class="product-status">
                                <li>
                                    <i class="fa-solid fa-clock"></i>
                                    <p><?php echo e(str_replace('ago', '', diffForHumans(@$course->created_at))); ?></p>
                                </li>
                                <li>
                                    <i class="fa-solid fa-graduation-cap"></i>
                                    <p><?php echo e($course->enrolls->count()); ?> <?php echo app('translator')->get('Students'); ?></p>
                                </li>
                            </ul>
                        </div>
                        <div class="carn-btm">
                            <ul class="star-wrap rating-wrap">
                                <?php
                                    $averageRatingHtml = calculateAverageRating($course->average_rating);
                                    if (!empty($averageRatingHtml['ratingHtml'])) {
                                        echo $averageRatingHtml['ratingHtml'];
                                    }
                                ?>
                                <li>
                                    <p> <?php echo e(@$course->average_rating ?? 0); ?>.0 (<?php echo e(@$course->review_count ?? 0); ?>)</p>
                                </li>
                            </ul>
                            <div class="price-wrap">
                                <?php if(@$course->discount > 0): ?>
                                    <h6 class="price">
                                        <?php echo e($general->cur_sym); ?><?php echo e(priceCalculate(@$course->price, @$course->discount)); ?>

                                    </h6>
                                    <p class="dis-price"><?php echo e($general->cur_sym); ?><?php echo e(@$course->price); ?></p>
                                <?php elseif(@$course->price == 0.0): ?>
                                    <h6 class="price"><?php echo app('translator')->get('Free'); ?></h6>
                                <?php else: ?>
                                    <h6 class="price"><?php echo e($general->cur_sym); ?><?php echo e(@$course->price); ?></h6>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="<?php echo e(route('course')); ?>?launch_type=new_launch" class="btn btn--base btn-lg">
                <?php echo app('translator')->get('View All New Launch Courses'); ?> <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/presets/default/sections/new_launch_courses.blade.php ENDPATH**/ ?>