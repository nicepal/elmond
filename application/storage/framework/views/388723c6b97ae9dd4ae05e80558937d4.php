
<?php $__env->startSection('content'); ?>
    <div class="mx-3 mb-4">
        <div class="dash-box">
            <div class="row">
                <div class="col-lg-6 my-auto">
                    <h2 class="mb-0"><?php echo app('translator')->get('Welcome Back'); ?><span>
                            <?php echo e(auth()->user()->fullname ?? auth('instructor')->user()->username); ?></span></h2>
                </div>
                <div class="col-lg-6 dash-thumb-top  mt-4 mt-lg-0 mt-md-0">
                    <img class="img-fluid d-flex ms-auto" src="<?php echo e(asset('assets/images/instructor/profile/dashboard.png')); ?>"
                        alt="images">
                </div>
            </div>
        </div>
    </div>
    <div class="row gy-4 mx-lg-0">
        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="<?php echo e(route('user.deposit.history')); ?>">
                <div class="dashboard-card">
                    <div class="card-shape">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                            <path fill-opacity="1"
                                d="M0,32L30,69.3C60,107,120,181,180,186.7C240,192,300,128,360,138.7C420,149,480,235,540,229.3C600,224,660,128,720,101.3C780,75,840,117,900,138.7C960,160,1020,160,1080,176C1140,192,1200,224,1260,240C1320,256,1380,256,1410,256L1440,256L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z">
                            </path>
                        </svg>
                    </div>
                    <div class="dashboard-card__content">
                        <h5 class="dashboard-card__amount"><?php echo e($deposit->approved()->count()); ?></h5>
                        <h5 class="dashboard-card__title"><?php echo app('translator')->get('Approved Payments'); ?></h5>
                    </div>
                    <div class="dashboard-card__icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </a>
        </div>


        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="<?php echo e(route('user.enroll.courses')); ?>">
                <div class="dashboard-card">
                    <div class="card-shape">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                            <path fill-opacity="1"
                                d="M0,32L30,69.3C60,107,120,181,180,186.7C240,192,300,128,360,138.7C420,149,480,235,540,229.3C600,224,660,128,720,101.3C780,75,840,117,900,138.7C960,160,1020,160,1080,176C1140,192,1200,224,1260,240C1320,256,1380,256,1410,256L1440,256L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z">
                            </path>
                        </svg>
                    </div>
                    <div class="dashboard-card__content">
                        <h5 class="dashboard-card__amount"><?php echo e(@$enroll->pending()->count()); ?></h5>
                        <h5 class="dashboard-card__title"><?php echo app('translator')->get('Enroll Course'); ?></h5>
                    </div>
                    <div class="dashboard-card__icon">
                        <i class="fa-solid fa-book-journal-whills"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="<?php echo e(route('user.enroll.courses')); ?>">
                <div class="dashboard-card">
                    <div class="card-shape">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                            <path fill-opacity="1"
                                d="M0,32L30,69.3C60,107,120,181,180,186.7C240,192,300,128,360,138.7C420,149,480,235,540,229.3C600,224,660,128,720,101.3C780,75,840,117,900,138.7C960,160,1020,160,1080,176C1140,192,1200,224,1260,240C1320,256,1380,256,1410,256L1440,256L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z">
                            </path>
                        </svg>
                    </div>
                    <div class="dashboard-card__content">
                        <h5 class="dashboard-card__amount"><?php echo e(@$enroll->approved()->count()); ?></h5>
                        <h5 class="dashboard-card__title"><?php echo app('translator')->get('Purchase Course'); ?></h5>
                    </div>
                    <div class="dashboard-card__icon">

                        <i class="fa-solid fa-book-open"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6">
            <a class="d-block" href="<?php echo e(route('ticket.open')); ?>">
                <div class="dashboard-card">
                    <div class="card-shape">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                            <path fill-opacity="1"
                                d="M0,32L30,69.3C60,107,120,181,180,186.7C240,192,300,128,360,138.7C420,149,480,235,540,229.3C600,224,660,128,720,101.3C780,75,840,117,900,138.7C960,160,1020,160,1080,176C1140,192,1200,224,1260,240C1320,256,1380,256,1410,256L1440,256L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z">
                            </path>
                        </svg>
                    </div>
                    <div class="dashboard-card__content">
                        <h5 class="dashboard-card__amount"><?php echo e($ticket->open()->count()); ?></h5>
                        <h5 class="dashboard-card__title"><?php echo app('translator')->get('Open Ticket'); ?></h5>
                    </div>
                    <div class="dashboard-card__icon">
                        <i class="fa-solid fa-comment-dots"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="chart mb-4">
            <div class="chart-bg">
                <div id="chart"></div>
            </div>
        </div>
    </div>


    <div>
        <div class="title ms-3 mb-4">
            <h4><?php echo app('translator')->get('Enroll Courses'); ?></h4>
        </div>
        <div class="row gy-4 mx-lg-0 mb-5">
            <?php $__empty_1 = true; $__currentLoopData = $enrolls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6">
                    <div class="base-card">
                        <?php if(@$item->course->discount > 0): ?>
                            <span class="dis-tag">-<?php echo e(@$item->course->discount); ?>% </span>
                        <?php endif; ?>
                        <div class="view-cta">
                            <a href="<?php echo e(route('course.details', [slug($item->course->name), $item->course_id])); ?>"
                                class="btn btn--base"><?php echo app('translator')->get('view'); ?></a>
                        </div>
                        <div class="thumb-wrap">
                            <img src="<?php echo e(getImage(getFilePath('course_image') . '/' . $item->course->image)); ?>"
                                alt="...">
                        </div>
                        <div class="content-wrap">
                            <p class="category"><?php echo e(__(@$item->course->category->name)); ?></p>
                            <a href="<?php echo e(route('course.details', [slug($item->course->name), $item->course_id])); ?>">
                                <h6 class="title"><?php echo e(__(@$item->course->name)); ?></h6>
                            </a>
                            <ul class="product-status">
                                <li>
                                    <i class="fa-solid fa-clock"></i>
                                    <p><?php echo e(str_replace('ago', '', diffForHumans(@$item->course->created_at))); ?></p>
                                </li>
                                <li>
                                    <i class="fa-solid fa-graduation-cap"></i>
                                    <p><?php echo e(__(@$item->enrollCount($item->id))); ?> <?php echo app('translator')->get('Students'); ?></p>
                                </li>
                            </ul>
                        </div>
                        <div class="carn-btm">
                            <ul class="star-wrap rating-wrap">
                                <?php
                                    $averageRatingHtml = calculateAverageRating(@$item->course->average_rating);
                                    if (!empty($averageRatingHtml['ratingHtml'])) {
                                        echo $averageRatingHtml['ratingHtml'];
                                    }
                                ?>

                                <li>
                                    <p> <?php echo e(@$item->course->average_rating ?? 0); ?>.0
                                        (<?php echo e(@$item->course->review_count ?? 0); ?>)
                                    </p>
                                </li>
                            </ul>

                            <div class="price-wrap">
                                <?php if(@$item->course->discount > 0): ?>
                                    <h6 class="price">
                                        <?php echo e(@$general->cur_sym); ?><?php echo e(priceCalculate(@$item->course->price, @$item->course->discount)); ?>

                                    </h6>
                                <?php elseif(@$item->course->price == 0.0): ?>
                                    <h6 class="price"><?php echo app('translator')->get('Free'); ?></h6>
                                <?php else: ?>
                                    <h6 class="price"><?php echo e(@$general->cur_sym); ?><?php echo e(@$item->course->price); ?></h6>
                                <?php endif; ?>


                                <?php if(@$item->course->discount > 0): ?>
                                    <p class="dis-price">
                                        <?php echo e(@$general->cur_sym); ?><?php echo e(@$item->course->price); ?>

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


    <!-- pagination -->
    <?php if($enrolls->hasPages()): ?>
        <div class="card-footer text-end">
            <?php echo e($enrolls->links()); ?>

        </div>
    <?php endif; ?>
    <!-- / pagination -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        "use strict";
        // [ account-chart ] start
        (function() {
            var options = {
                chart: {
                    type: 'bar',
                    stacked: false,
                    height: '310px'
                },
                stroke: {
                    width: [0, 3],
                    curve: 'smooth'
                },
                colors: ['#00adad', '#67BAA7'],
                plotOptions: {
                    bar: {
                        columnWidth: '50%'
                    }
                },
                colors: ['#ff99007a', '#E91E63'],
                series: [{
                    name: '<?php echo app('translator')->get('Enrolls'); ?>',
                    type: 'column',
                    data: <?php echo json_encode($enrollChart['values'], 15, 512) ?>
                }, {
                    name: '<?php echo app('translator')->get('Payments'); ?>',
                    type: 'area',
                    data: <?php echo json_encode($depositChart['values'], 15, 512) ?>
                }],
                fill: {
                    opacity: [0.85, 1],
                },
                labels: <?php echo json_encode($enrollChart['labels'], 15, 512) ?>,
                markers: {
                    size: 0
                },
                xaxis: {
                    type: 'text'
                },
                yaxis: {
                    min: 0
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function(y) {
                            if (typeof y !== "undefined") {
                                return y.toFixed(0);
                            }
                            return y;

                        }
                    }
                },
                legend: {
                    labels: {
                        useSeriesColors: true
                    },
                    markers: {
                        customHTML: [
                            function() {
                                return ''
                            },
                            function() {
                                return ''
                            }
                        ]
                    }
                }
            }
            var chart = new ApexCharts(
                document.querySelector("#chart"),
                options
            );
            chart.render();
        })();
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/presets/default/user/dashboard.blade.php ENDPATH**/ ?>