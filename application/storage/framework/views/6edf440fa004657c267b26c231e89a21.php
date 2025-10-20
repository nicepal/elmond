
<?php $__env->startSection('content'); ?>
    <!-- product details section -->
    <section class="product-info">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="info-wrap">
                        <div class="product-tag">
                            <p class="tag-name"><?php echo e(@$course->category?->name); ?></p>
                        </div>
                        <h1 class="title"><?php echo e(@$course->name); ?></h1>
                        <ul class="rating-wrap">
                            <?php
                                $averageRatingHtml = calculateAverageRating($course->average_rating);
                                if (!empty($averageRatingHtml['ratingHtml'])) {
                                    echo $averageRatingHtml['ratingHtml'];
                                }
                            ?>
                            <li>
                                <p> (<?php echo e($course->review_count); ?> <?php echo app('translator')->get('ratings'); ?>) <?php echo e($course->enrolls->count()); ?>

                                    <?php echo app('translator')->get('Students'); ?></p>
                            </li>
                        </ul>
                        <ul class="key-wrap">
                            <li>
                                <i class="fa-solid fa-clock"></i>
                                <p><?php echo e(str_replace('ago', '', diffForHumans(@$course->created_at))); ?></p>
                            </li>
                            <li>
                                <i class="fa-solid fa-graduation-cap"></i>
                                <p><?php echo e($course->enrolls->count()); ?> <?php echo app('translator')->get('Students'); ?></p>
                            </li>
                            <li>
                                <i class="fa-solid fa-file-video"></i>
                                <p><?php echo e(@$course->lessons->count()); ?> <?php echo app('translator')->get('Lessons'); ?></p>
                            </li>
                        </ul>
                    </div>
                    <div class="content-wrap mt-5">
                        <?php if(auth()->user() && $course->checkedPurchase()): ?>
                            <span class="btn btn--base-3"><?php echo app('translator')->get('Already Purchased'); ?></span>
                        <?php else: ?>
                            <div class="d-flex gap-2">
                                <a href="<?php echo e(route('user.enroll.enroll', $course->id)); ?>" class="btn btn--base-3">
                                    <?php echo app('translator')->get('Buy Now'); ?> 
                                    <?php if($course->discount > 0): ?>
                                        (<del><?php echo e($general->cur_sym); ?><?php echo e($course->price); ?></del> <?php echo e($general->cur_sym); ?><?php echo e($course->price - ($course->price * $course->discount / 100)); ?>)
                                    <?php else: ?>
                                        (<?php echo e($general->cur_sym); ?><?php echo e($course->price); ?>)
                                    <?php endif; ?>
                                    <i class="fa-solid fa-angles-right"></i>
                                </a>
                                <button class="btn btn--base-2 add-to-cart-btn" 
                                        data-course-id="<?php echo e($course->id); ?>" 
                                        data-course-name="<?php echo e($course->name); ?>">
                                    <i class="fas fa-shopping-cart me-1"></i> <?php echo app('translator')->get('Add to Cart'); ?>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="details-card2 mt-5">
                        <h1 class="title"><?php echo app('translator')->get('This Course Includes'); ?></h1>
                        <ul class="key-wrap">
                            <?php $__currentLoopData = $course->course_outline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <i class="fa-solid fa-circle-check"></i>
                                    <p><?php echo e($item); ?></p>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="details">
                        <div class="details-">
                            <div class="thumb-">
                                <?php if($course->preview_video): ?>
                                    <?php
                                        $videoUrl = $course->preview_video;
                                        $isYouTube = false;
                                        $embedUrl = '';
                                        // Check if it's a YouTube URL
                                        if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
                                            $videoId = substr($videoUrl, strpos($videoUrl, 'v=') + 2);
                                            $videoId = explode('&', $videoId)[0];
                                            $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                                            $isYouTube = true;
                                        } elseif (strpos($videoUrl, 'youtu.be/') !== false) {
                                            $videoId = substr($videoUrl, strrpos($videoUrl, '/') + 1);
                                            // Remove any query parameters (like ?si=...)
                                            $videoId = explode('?', $videoId)[0];
                                            $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                                            $isYouTube = true;
                                        } elseif (strpos($videoUrl, 'youtube.com/embed/') !== false) {
                                            $embedUrl = $videoUrl; // Already an embed URL
                                            $isYouTube = true;
                                        }
                                    ?>
                                    
                                    <?php if($isYouTube): ?>
                                        <iframe width="100%" height="300" src="<?php echo e($embedUrl); ?>" 
                                                frameborder="0" 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                                referrerpolicy="strict-origin-when-cross-origin" 
                                                allowfullscreen></iframe>
                                    <?php else: ?>
                                        <video width="100%" height="300" controls>
                                            <source src="<?php echo e(asset( $videoUrl)); ?>" type="video/mp4">
                                            <source src="<?php echo e(asset( $videoUrl)); ?>" type="video/webm">
                                            <source src="<?php echo e(asset($videoUrl)); ?>" type="video/ogg">
                                            Your browser does not support the video tag.
                                        </video>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <img src="<?php echo e(getImage(getFilePath('course_image') . '/' . $course->image)); ?>" 
                                         alt="<?php echo e($course->name); ?>" 
                                         class="img-fluid course-image">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- product details section -->
    
    <!-- Navigation Menu -->
    <div class="heads">
        <nav id="desktop-nav">
            <ul>
                <li class="active"><a href="#Highlights" class="page-scroll"><?php echo app('translator')->get('Course Highlights'); ?></a></li>
                <li><a href="#Curriculum" class="page-scroll"><?php echo app('translator')->get('Curriculum'); ?></a></li>
                <li><a href="#Instructor" class="page-scroll"><?php echo app('translator')->get('Instructor'); ?></a></li>
                <li><a href="#FAQ" class="page-scroll"><?php echo app('translator')->get('FAQ\'s'); ?></a></li>
                <li><a href="#Certificate" class="page-scroll"><?php echo app('translator')->get('Certificate'); ?></a></li>
                <li><a href="#Testimonials" class="page-scroll"><?php echo app('translator')->get('Testimonials'); ?></a></li>
            </ul>
        </nav>
    </div>

    <!-- Course Highlights Section -->
    <section class="product-details" id="Highlights">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="key learn">
                        <h2 class="title wow animate__ animate__fadeInUp  animated" align="center" class="title"><?php echo app('translator')->get("What you'll learn"); ?></h2>
                        <div class="discription">
                            <h3 class="title wow animate__ animate__fadeInUp  animated" align="center"><?php echo app('translator')->get('Course Highlights'); ?></h3>
                            <hr class="">
                            <?php if($course->learn_description): ?>
                                <?php
                                    echo __($course->learn_description);
                                ?>
                            <?php else: ?>
                                <!-- Static content for demo -->
                                <ul class="crs">
                                    <li><i aria-hidden="true" class="fas fa-circle-check"></i> <?php echo app('translator')->get('Complete course content with practical examples'); ?></li>
                                    <li><i aria-hidden="true" class="fas fa-circle-check"></i> <?php echo app('translator')->get('Learn industry best practices and techniques'); ?></li>
                                    <li><i aria-hidden="true" class="fas fa-circle-check"></i> <?php echo app('translator')->get('Hands-on projects and assignments'); ?></li>
                                    <li><i aria-hidden="true" class="fas fa-circle-check"></i> <?php echo app('translator')->get('Expert guidance and support'); ?></li>
                                    <li><i aria-hidden="true" class="fas fa-circle-check"></i> <?php echo app('translator')->get('Certificate upon completion'); ?></li>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About the Course Section -->
    <section class="abt-cour bg-light">
        <div class="container">
            <div class="row gy-4">
                <h2 class="title wow animate__ animate__fadeInUp  animated" align="center"><?php echo app('translator')->get('About the Course'); ?></h2>
                <hr class="pb-2">
                <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-4 col-sm-6">
                    <a href="#" class="category-card">
                        <div class="icon-wrap">
                            <img src="<?php echo e(asset('assets/images/frontend/course/clock.png')); ?>" alt="duration">
                        </div>
                        <div class="content-wrap">
                            <h6 class="title"><?php echo e($course->duration ?? '5 Hours 30 mins'); ?></h6>
                        </div>
                    </a>
                </div>
                <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-4 col-sm-6">
                    <a href="#" class="category-card">
                        <div class="icon-wrap">
                            <img src="<?php echo e(asset('assets/images/frontend/course/video.png')); ?>" alt="modules">
                        </div>
                        <div class="content-wrap">
                            <h6 class="title"><?php echo e($course->lessons->count()); ?> <?php echo app('translator')->get('Modules'); ?></h6>
                        </div>
                    </a>
                </div>
                <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-4 col-sm-6">
                    <a href="#" class="category-card">
                        <div class="icon-wrap">
                            <img src="<?php echo e(asset('assets/images/frontend/course/news.png')); ?>" alt="tests">
                        </div>
                        <div class="content-wrap">
                            <h6 class="title"><?php echo e($course->quizzes->count()); ?> <?php echo app('translator')->get('Practice Tests'); ?></h6>
                        </div>
                    </a>
                </div>
                <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-4 col-sm-6">
                    <a href="#" class="category-card">
                        <div class="icon-wrap">
                            <img src="<?php echo e(asset('assets/images/frontend/course/online-training.png')); ?>" alt="assignments">
                        </div>
                        <div class="content-wrap">
                            <h6 class="title"><?php echo e($course->assignments_count ?? '3'); ?> <?php echo app('translator')->get('Assignments'); ?></h6>
                        </div>
                    </a>
                </div>
                <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-4 col-sm-6">
                    <a href="#" class="category-card">
                        <div class="icon-wrap">
                            <img src="<?php echo e(asset('assets/images/frontend/course/learner.png')); ?>" alt="learners">
                        </div>
                        <div class="content-wrap">
                            <h6 class="title"><?php echo e($course->enrolls->count()); ?> <?php echo app('translator')->get('Learners'); ?></h6>
                        </div>
                    </a>
                </div>
                <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-4 col-sm-6">
                    <a href="#" class="category-card">
                        <div class="icon-wrap">
                            <img src="<?php echo e(asset('assets/images/frontend/course/planning.png')); ?>" alt="access">
                        </div>
                        <div class="content-wrap">
                            <h6 class="title"><?php echo e($course->access_duration ?? '12 Months'); ?> <?php echo app('translator')->get('Access'); ?></h6>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Curriculum Section -->
    <section class="pt-5 pb-5 product-details" id="Curriculum">
        <div class="container">
            <div class="key curriculum">
                <h2 class="title wow animate__ animate__fadeInUp  animated" align="center"><?php echo app('translator')->get('Curriculum'); ?></h2>
                <hr class="pb-2">
                <div class="discription">
                    <?php if($course->curriculum): ?>
                        <?php
                            echo __($course->curriculum);
                        ?>
                    <?php else: ?>
                        <p><?php echo app('translator')->get('Comprehensive course curriculum designed to provide you with complete knowledge and practical skills.'); ?></p>
                    <?php endif; ?>
                </div>
                <?php if($course->lessons->count() > 0): ?>
                    <div class="curriculam-list">
                        <ul class="list-group">
                            <?php
                                $modules = $course->modules()->with('lessons')->get();
                                $lessonsWithoutModule = $course->lessons()->whereNull('module_id')->get();
                            ?>
                            
                            
                            <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                                <li class="list-group-item module-header" 
                                    style="background-color: rgba(<?php echo e(hexdec(substr($general->base_color, 1, 2))); ?>, <?php echo e(hexdec(substr($general->base_color, 3, 2))); ?>, <?php echo e(hexdec(substr($general->base_color, 5, 2))); ?>, 0.1); font-weight: bold; border-left: 4px solid #<?php echo e($general->base_color); ?>; cursor: pointer;"
                                    onclick="toggleModule(<?php echo e($module->id); ?>)">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-folder me-2" style="color: #<?php echo e($general->base_color); ?>;"></i>
                                            <span><?php echo e(__($module->title)); ?></span>
                                            <span class="badge ms-2" style="background-color: #<?php echo e($general->base_color); ?>; color: white;"><?php echo e($module->lessons->count()); ?> <?php echo app('translator')->get('Lessons'); ?></span>
                                        </div>
                                        <i class="fa-solid fa-chevron-down module-toggle-icon" id="toggle-icon-<?php echo e($module->id); ?>" style="color: #<?php echo e($general->base_color); ?>; transition: transform 0.3s ease;"></i>
                                    </div>
                                </li>
                                
                                
                                <div class="module-lessons" id="module-lessons-<?php echo e($module->id); ?>" style="display: none;">
                                    <?php $__currentLoopData = $module->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center course-data lesson-item"
                                            onclick="lessonPreview(this, <?php echo e(@$course->id); ?>, <?php echo e($lesson->id); ?>)"
                                            data-description="<?php echo e($lesson->description); ?>"
                                            style="padding-left: 3rem; border-left: 2px solid #<?php echo e($general->secondary_color); ?>40;">
                                            <div class="d-flex align-items-center">
                                                <i class="fa-regular fa-circle-play pre-i me-2"></i>
                                                <span><?php echo e(__($lesson->title)); ?></span>
                                            </div>
                                            <?php if($lesson->value == 0): ?>
                                                <p class="mb-0"><?php echo app('translator')->get('Free'); ?> <i class="fa-solid fa-lock-open"></i></p>
                                            <?php else: ?>
                                                <p class="mb-0"><?php echo app('translator')->get('Premium'); ?> <i class="fa-solid fa-lock"></i></p>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            
                            <?php $__currentLoopData = $lessonsWithoutModule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center course-data"
                                    onclick="lessonPreview(this, <?php echo e(@$course->id); ?>, <?php echo e($lesson->id); ?>)"
                                    data-description="<?php echo e($lesson->description); ?>">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-regular fa-circle-play pre-i me-2"></i>
                                        <span><?php echo e(__($lesson->title)); ?></span>
                                    </div>
                                    <?php if($lesson->value == 0): ?>
                                        <p class="mb-0"><?php echo app('translator')->get('Free'); ?> <i class="fa-solid fa-lock-open"></i></p>
                                    <?php else: ?>
                                        <p class="mb-0"><?php echo app('translator')->get('Premium'); ?> <i class="fa-solid fa-lock"></i></p>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="key requirements py-3">
                <h1 class="title"><?php echo app('translator')->get('Descriptions'); ?></h1>
                <div class="discription wyg">
                    <?php
                        echo __($course->description);
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Instructor Section -->
    <section class="py-5 faq-section Instructor" id="Instructor">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title wow animate__ animate__fadeInUp  animated" align="center"><?php echo app('translator')->get('Instructor'); ?></h2>
                    <hr class="pb-3">
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-4">
                    <div class="key instru">
                        <?php if($course->instructor_image): ?>
                            <img src="<?php echo e(getImage(getFilePath('instructor_image') . '/' . $course->instructor_image)); ?>" 
                                 alt="Instructor" class="img-fluid">
                        <?php else: ?>
                            <img src="<?php echo e(asset('assets/images/frontend/course/default-instructor.png')); ?>" 
                                 alt="Default Instructor" class="img-fluid">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="instructor-content">
                        <?php if($course->instructor_details): ?>
                            <?php echo $course->instructor_details; ?>

                        <?php else: ?>
                            <h4><?php echo app('translator')->get('Expert Instructor'); ?></h4>
                            <p><?php echo app('translator')->get('Our experienced instructor brings years of industry expertise to guide you through this comprehensive course. With a proven track record of success, they are committed to helping you achieve your learning goals.'); ?></p>
                            <ul>
                                <li><?php echo app('translator')->get('Industry Expert with 10+ years experience'); ?></li>
                                <li><?php echo app('translator')->get('Certified Professional in the field'); ?></li>
                                <li><?php echo app('translator')->get('Passionate about teaching and mentoring'); ?></li>
                                <li><?php echo app('translator')->get('Committed to student success'); ?></li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5" id="FAQ">
        <div class="container">
            <div class="w-lg-50 mx-auto">
                <h2 class="title wow animate__ animate__fadeInUp  animated" align="center"><?php echo app('translator')->get('Course FAQ\'s'); ?></h2>
                <hr class="pb-3">
                <div class="accordion accordion-flush" id="accordionExample">
                    <?php if($course->course_faqs && count($course->course_faqs) > 0): ?>
                        <?php $__currentLoopData = $course->course_faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button <?php echo e($index == 0 ? '' : 'collapsed'); ?>" type="button" 
                                            data-bs-toggle="collapse" data-bs-target="#faq<?php echo e($index); ?>" 
                                            aria-expanded="<?php echo e($index == 0 ? 'true' : 'false'); ?>" 
                                            aria-controls="faq<?php echo e($index); ?>">
                                        <h5><?php echo e(__($faq['question'])); ?></h5>
                                    </button>
                                </h2>
                                <div id="faq<?php echo e($index); ?>" class="accordion-collapse collapse <?php echo e($index == 0 ? 'show' : ''); ?>" 
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <?php echo e(__($faq['answer'])); ?>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <!-- Fallback static FAQs when no dynamic FAQs are available -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#coll1" aria-expanded="true" aria-controls="coll1">
                                    <h5><?php echo app('translator')->get('What will I learn in this course?'); ?></h5>
                                </button>
                            </h2>
                            <div id="coll1" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <?php echo app('translator')->get('This comprehensive course will provide you with practical skills and knowledge that you can apply immediately in your work or projects.'); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#coll2" aria-expanded="false" aria-controls="coll2">
                                    <h5><?php echo app('translator')->get('Who can join this course?'); ?></h5>
                                </button>
                            </h2>
                            <div id="coll2" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <?php echo app('translator')->get('This course is designed for beginners to advanced learners who want to enhance their skills and knowledge in this field.'); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#coll3" aria-expanded="false" aria-controls="coll3">
                                    <h5><?php echo app('translator')->get('Do you provide certificates?'); ?></h5>
                                </button>
                            </h2>
                            <div id="coll3" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <?php echo app('translator')->get('Yes, we provide course completion certificates upon successful completion of all course requirements.'); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Certificate Section -->
    <section class="Certificate bg-light" id="Certificate">
        <div class="container">
            <div class="key Certi">
                <h2 class="title wow animate__ animate__fadeInUp  animated" align="center"><?php echo app('translator')->get('Earn a Certificate'); ?></h2>
                <hr class="pb-3">
                <div class="row d-flex align-items-center">
                    <div class="col-lg-6">
                        <?php if($course->certificate_description): ?>
                            <!-- If custom certificate description exists, show it -->
                            <div class="certificate-content">
                                <?php echo $course->certificate_description; ?>

                            </div>
                        <?php else: ?>
                            <!-- Default static content -->
                            <p><strong><?php echo app('translator')->get('Demonstrate Your Commitment'); ?></strong><br>
                                <?php echo app('translator')->get('Be a growth-driven professional and advance your career by learning new skills'); ?>
                            </p>
                            <p><strong><?php echo app('translator')->get('Share your Accomplishment'); ?></strong><br>
                                <?php echo app('translator')->get('Showcase your verified certificate on your social media platforms and CV'); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-6">
                        <?php if($course->certificate_image): ?>
                            <!-- Dynamic certificate image from backend -->
                            <img src="<?php echo e(getImage(getFilePath('certificate') . '/' . $course->certificate_image)); ?>" 
                                 alt="<?php echo e($course->name); ?> Certificate" 
                                 class="img-fluid rounded shadow" 
                                 width="100%">
                        <?php else: ?>
                            <!-- Default certificate image -->
                            <img src="<?php echo e(asset('assets/images/frontend/course/default-certificate.jpg')); ?>" 
                                 alt="Course Certificate" 
                                 class="img-fluid rounded shadow" 
                                 width="100%">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section (Without Arrows) -->
    <section class="testimonials py-5" id="Testimonials">
        <div class="container">
            <h2 class="title wow animate__ animate__fadeInUp  animated" align="center"><?php echo app('translator')->get('Our Community Trust Us'); ?></h2>
            <hr class="pb-3">
            
            <!-- Swiper -->
            <div class="swiper testimonialSwiper">
                <div class="swiper-wrapper">
                    <?php if($reviews->count() > 0): ?>
                        <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="swiper-slide">
                                <div class="testimonial-card">
                                    <p class="card-text"><?php echo e($review->message); ?></p>
                                    <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . @$review->user?->image, getFileSize('userProfile'))); ?>" alt="user" class="user-image">
                                    <h5 class="fw-bold"><?php echo e($review->user?->fullname); ?></h5>
                                    <span class="text-secondary"><?php echo app('translator')->get('Student'); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <!-- Dummy testimonials without quotes -->
                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <p class="card-text"><?php echo app('translator')->get('This course exceeded my expectations. The content is well-structured and the instructor is very knowledgeable. I highly recommend it to anyone looking to advance their skills.'); ?></p>
                                <h5 class="fw-bold"><?php echo app('translator')->get('Sarah Johnson'); ?></h5>
                                <span class="text-secondary"><?php echo app('translator')->get('Digital Marketing Specialist'); ?></span>
                            </div>
                        </div>
                        
                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <p class="card-text"><?php echo app('translator')->get('Amazing learning experience! The practical examples and hands-on approach made complex concepts easy to understand. Worth every penny!'); ?></p>
                                <h5 class="fw-bold"><?php echo app('translator')->get('Michael Chen'); ?></h5>
                                <span class="text-secondary"><?php echo app('translator')->get('Software Developer'); ?></span>
                            </div>
                        </div>
                        
                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <p class="card-text"><?php echo app('translator')->get('The instructor expertise and teaching style are outstanding. I was able to apply what I learned immediately in my work projects.'); ?></p>
                                <h5 class="fw-bold"><?php echo app('translator')->get('Emily Rodriguez'); ?></h5>
                                <span class="text-secondary"><?php echo app('translator')->get('Marketing Manager'); ?></span>
                            </div>
                        </div>
                        
                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <p class="card-text"><?php echo app('translator')->get('Excellent course with comprehensive coverage of all topics. The support from the instructor and community was fantastic throughout my learning journey.'); ?></p>
                                <h5 class="fw-bold"><?php echo app('translator')->get('David Thompson'); ?></h5>
                                <span class="text-secondary"><?php echo app('translator')->get('Business Analyst'); ?></span>
                            </div>
                        </div>
                        
                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <p class="card-text"><?php echo app('translator')->get('This course transformed my career! The practical skills I gained helped me land my dream job. Thank you for such an amazing learning experience.'); ?></p>
                                <h5 class="fw-bold"><?php echo app('translator')->get('Lisa Wang'); ?></h5>
                                <span class="text-secondary"><?php echo app('translator')->get('UX Designer'); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-pagination"></div>
                <!-- Arrows removed -->
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <!-- <?php if($course->quizzes->count() > 0): ?>
        <div class="mb-4 text-center">
            <a href="<?php echo e(route('user.quiz.courseQuiz', $course->id)); ?>" class="btn btn--base"><?php echo app('translator')->get('Take Quizzes'); ?></a>
        </div>
    <?php endif; ?> -->
    
    <!-- <div class="container">
        <div class="key rating mb-0">
            <h1 class="title mb-4"><i class="fa-solid fa-star"></i>(<?php echo e($course->average_rating); ?>) <?php echo app('translator')->get('Write a review'); ?></h1>
            <div class="row">
                <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-12">
                        <div class="review-card">
                            <div class="user-info">
                                <div class="thumb-wrap">
                                    <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . @$item->user?->image, getFileSize('userProfile'))); ?>" alt="user_image">
                                </div>
                                <div class="user-name">
                                    <h1 class="name"><?php echo e(@$item->user?->fullname); ?></h1>
                                    <div class="d-lg-flex d-md-flex d-block">
                                        <ul class="rating-list rating-wrap">
                                            <?php
                                                $averageRatingHtml = calculateIndividualRating($item->rating);
                                                if (!empty($averageRatingHtml['ratingHtml'])) {
                                                    echo $averageRatingHtml['ratingHtml'];
                                                }
                                            ?>
                                            <li>
                                                <p>(<?php echo e(__($item->rating)); ?>.0)</p>
                                            </li>
                                        </ul>
                                        <p class="mx-md-2 mt-lg-0 mt-md-0 mt-2"><?php echo e(diffForHumans($item->created_at)); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="content">
                                <p class="discription"><?php echo @$item->message; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <h5 class="text-center no-review"><?php echo app('translator')->get('No Reviews'); ?></h5>
                <?php endif; ?>
                <div class="row gy-4">
                    <?php if($reviews->hasPages()): ?>
                        <div class="py-4">
                            <?php echo e(paginateLinks($reviews)); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="review-box">
            <form action="<?php echo e(route('user.reviews.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="rating" id="rating" value="0">
                <input type="hidden" name="course_id" value="<?php echo e($course->id); ?>">
                <div class="d-flex">
                    <div>
                        <h5 class="title-three"><?php echo app('translator')->get('Giving Rating'); ?>:</h5>
                    </div>
                    <div class="rating-wrap rating-stars ps-2">
                        <div>
                            <i class="far fa-star" data-rating="1"></i>
                            <i class="far fa-star" data-rating="2"></i>
                            <i class="far fa-star" data-rating="3"></i>
                            <i class="far fa-star" data-rating="4"></i>
                            <i class="far fa-star" data-rating="5"></i>
                        </div>
                    </div>
                </div>
                <textarea class="form--control" name="message" placeholder="<?php echo app('translator')->get('Write Your Review'); ?>" id="message"></textarea>
                <div class="col-sm-12 mt-4">
                    <button type="submit" class="btn btn--base button w-100"><?php echo app('translator')->get('Submit Review'); ?></button>
                </div>
            </form>
        </div>
    </div> -->

    
    <div class="modal fade modal-lg designModal" id="exampleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-end py-1">
                    <div class="modal-btn-wrap d-flex justify-content-end">
                        <button data-bs-dismiss="modal" aria-label="Close" onclick="modalClose()"><i class="fa-solid fa-xmark "></i></button>
                    </div>
                </div>
                <div class="modal-body coustom-modal-body custom-video-preview">
                    <video id="player" playsinline controls data-poster="">
                        <source src="#" type="video/mp4">
                        <track kind="captions" label="English captions" src="/path/to/captions.vtt" srclang="en" default />
                    </video>
                    <div class="description">
                        <ul class="list-group"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-lg designModal" id="videoUrlModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-end py-1">
                    <div class="modal-btn-wrap">
                        <button data-bs-dismiss="modal" aria-label="Close" onclick="videoUrlModalClose()"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                </div>
                <div class="modal-body coustom-modal-body custom-video-preview"></div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-lg designModal" id="meetingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h5 class="modal-title"><?php echo app('translator')->get('Meeting Info'); ?></h5>
                    <div class="modal-btn-wrap">
                        <button data-bs-dismiss="modal" aria-label="Close" onclick="mettingModalClose()"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                </div>
                <div class="modal-body coustom-modal-body custom-video-preview mb-2"></div>
            </div>
        </div>
    </div>
    <!-- Sticky Buy Now Button -->
    <div class="sticky-buy-now">
        <div class="container">
            <div class="sticky-buy-now-content">
                <div class="course-info">
                    <h4><?php echo e(@$course->name); ?></h4>
                    <div class="price">
                        <?php if($course->discount > 0): ?>
                            <del><?php echo e($general->cur_sym); ?><?php echo e($course->price); ?></del> 
                            <span><?php echo e($general->cur_sym); ?><?php echo e($course->price - ($course->price * $course->discount / 100)); ?></span>
                        <?php else: ?>
                            <span><?php echo e($general->cur_sym); ?><?php echo e($course->price); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="action-buttons">
                    <?php if(auth()->user() && $course->checkedPurchase()): ?>
                        <span class="btn btn--base-3"><?php echo app('translator')->get('Already Purchased'); ?></span>
                    <?php else: ?>
                        <a href="<?php echo e(route('user.enroll.enroll', $course->id)); ?>" class="btn btn--base-3">
                            <?php echo app('translator')->get('Buy Now'); ?> <i class="fa-solid fa-angles-right"></i>
                        </a>
                        <button class="btn btn--base-2 add-to-cart-btn" 
                                data-course-id="<?php echo e($course->id); ?>" 
                                data-course-name="<?php echo e($course->name); ?>">
                            <i class="fas fa-shopping-cart me-1"></i> <?php echo app('translator')->get('Add to Cart'); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('style'); ?>
<style>
:root {
    --base-color: #<?php echo e($general->base_color); ?>;
    --secondary-color: #<?php echo e($general->secondary_color); ?>;
    --base-color-light: rgba(<?php echo e(hexdec(substr($general->base_color, 1, 2))); ?>, <?php echo e(hexdec(substr($general->base_color, 3, 2))); ?>, <?php echo e(hexdec(substr($general->base_color, 5, 2))); ?>, 0.1);
    --secondary-color-light: rgba(<?php echo e(hexdec(substr($general->secondary_color, 1, 2))); ?>, <?php echo e(hexdec(substr($general->secondary_color, 3, 2))); ?>, <?php echo e(hexdec(substr($general->secondary_color, 5, 2))); ?>, 0.1);
}

/* Sticky Buy Now Button Styles */
.sticky-buy-now {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #fff;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    padding: 15px 0;
    z-index: 99;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.sticky-buy-now.visible {
    transform: translateY(0);
}

.sticky-buy-now-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sticky-buy-now .course-info {
    flex: 1;
}

.sticky-buy-now .course-info h4 {
    margin: 0;
    font-size: 18px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 300px;
}

.sticky-buy-now .price {
    font-weight: bold;
    color: var(--base-color, #007bff);
}

.sticky-buy-now .price del {
    color: #999;
    margin-right: 5px;
}

.sticky-buy-now .action-buttons {
    display: flex;
    gap: 10px;
}

@media (max-width: 767px) {
    .sticky-buy-now-content {
        flex-direction: column;
        gap: 10px;
    }
    
    .sticky-buy-now .action-buttons {
        width: 100%;
        justify-content: center;
    }
}

/* Navigation active state */
#desktop-nav ul li a:hover,
#desktop-nav ul li.active a {
    color: var(--base-color);
    background: var(--base-color-light);
}

#desktop-nav ul li a::after {
    background: var(--base-color);
}

/* Module dropdown styles */
.module-header {
    transition: background-color 0.3s ease;
}

.module-header:hover {
    background-color: rgba(<?php echo e(hexdec(substr($general->base_color, 1, 2))); ?>, <?php echo e(hexdec(substr($general->base_color, 3, 2))); ?>, <?php echo e(hexdec(substr($general->base_color, 5, 2))); ?>, 0.2) !important;
}

.module-lessons {
    transition: all 0.3s ease;
    overflow: hidden;
}

.module-toggle-icon {
    transition: transform 0.3s ease;
}

.lesson-item {
    transition: background-color 0.2s ease;
}

.lesson-item:hover {
    background-color: rgba(<?php echo e(hexdec(substr($general->secondary_color, 1, 2))); ?>, <?php echo e(hexdec(substr($general->secondary_color, 3, 2))); ?>, <?php echo e(hexdec(substr($general->secondary_color, 5, 2))); ?>, 0.1);
}

.add-to-cart-btn i {
    color: #000 !important;
}

.btn--base-2 {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #000;
}

.btn--base-2:hover {
    background-color: #e9ecef;
    border-color: #adb5bd;
    color: #000;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script>
    $(document).ready(function() {
        // Show/hide sticky buy now button based on scroll position
        const productInfoSection = $('.product-info');
        const stickyBuyNow = $('.sticky-buy-now');
        
        if (productInfoSection.length && stickyBuyNow.length) {
            const productInfoBottom = productInfoSection.offset().top + productInfoSection.outerHeight();
            
            $(window).scroll(function() {
                if ($(window).scrollTop() > productInfoBottom) {
                    stickyBuyNow.addClass('visible');
                } else {
                    stickyBuyNow.removeClass('visible');
                }
            });
        }
    });
    
    function lessonPreview(object, course_id, id) {
            const modal = $('#exampleModal');
            const description = $(object).data('description');
            const video = modal.find('video');
            const source = video.find('source');
            const poster = modal.find('.plyr__poster');
            const uploadPath = "<?php echo e(asset(getFilePath('videoUpload'))); ?>";
            let documentsFile = '';
            
            const formatDate = (dateString) => {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                const options = {
                    year: "numeric",
                    month: "2-digit",
                    day: "2-digit",
                    hour: "2-digit",
                    minute: "2-digit",
                    second: "2-digit",
                    hour12: true
                };
                return new Intl.DateTimeFormat("en-US", options).format(date);
            };

            const populateModalBody = (container, zoomData, formattedDate) => {
                container.html(`
                    <div class="modal-body custom-modal-body custom-video-preview mb-2">
                        ${createDetailRow("<?php echo app('translator')->get('Topic'); ?>", zoomData.topic)}
                        ${createDetailRow("<?php echo app('translator')->get('Agenda'); ?>", zoomData.agenda)}
                        ${createDetailRow("<?php echo app('translator')->get('Duration'); ?>", `${zoomData.duration} min`)}
                        ${createDetailRow("<?php echo app('translator')->get('Meeting ID'); ?>", zoomData.id)}
                        ${createDetailRow("<?php echo app('translator')->get('Password'); ?>", zoomData.password)}
                        ${createDetailRow("<?php echo app('translator')->get('Date'); ?>", formattedDate)}
                        ${createDetailRow("<?php echo app('translator')->get('Meeting URL'); ?>", `<a href="${zoomData.start_url}"><u>Click Here</u></a>`)}
                    </div>
                `);
            };

            const createDetailRow = (label, value) => {
                return `
                        <div class="d-flex justify-content-between px-4 py-2">
                            <p>${label}</p>
                            <p>${value}</p>
                        </div>
                    `;
            };

            const showVideoPreview = (uploadVideo, image) => {
                if (description !== "" || description != null) {
                    documentsFile += `<div class="border rounded p-3 bg-light">
                            <h6 class="fw-bold text--base mb-2">Description:-</h6>
                            <p class="text-muted">${description}</p>
                        </div>`;

                    modal.find('.description .list-group').html(documentsFile);
                }
                if (uploadVideo) {
                    $('.custom-video-preview').removeClass('d-none');
                    $('.custom-video-link').addClass('d-none');
                    source.attr('src', `${uploadPath}/videoUpload/${uploadVideo}`);
                    modal.find('video').attr('data-poster', image);
                    modal.find('video').removeClass('d-none');

                    poster.css('background-image', `url(${image})`);
                    video[0].load();
                } else {
                    modal.find('video').addClass('d-none');
                }
                modal.show();
            };

            const showUploadDocument = (uploadDocuments) => {
                const fileIcons = {
                    'jpg': 'jpg.png',
                    'jpeg': 'jpeg.png',
                    'pdf': 'pdf.png',
                    'ppt': 'ppt.png',
                    'pptx': 'pptx.png',
                    'zip': 'zip.png',
                    'csv': 'csv.png',
                    'webp': 'webp.png',
                    'bmp': 'bmp.png',
                    'doc': 'doc.png',
                    'docx': 'docx.png',
                    'png': 'png.png',
                };

                uploadDocuments.forEach(element => {
                    const fileExtension = element.file.split('.').pop().toLowerCase();
                    const fileIcon = fileIcons[fileExtension] || 'default.png';
                    const filePath = `<?php echo e(asset(getFilePath('lesson_file_ext'))); ?>` + '/'+ fileIcons[fileExtension];
                    const downloadRoute = `<?php echo e(route('lesson.document.download', ':id')); ?>`.replace(':id', element.id);

                    documentsFile += 
                        `<li class="list-group-item d-flex flex-wrap gap-3 justify-content-between align-items-center">
                                <div class="thumb--wrap">
                                    <img src="${filePath} "alt="file">
                                </div>
                                <div class="item--title">
                                    ${element.file}
                                </div>
                                <div class="btn--wrap">
                                    <a href='${downloadRoute}' class="btn btn--base btn--sm">Download</a>
                                </div>
                            </li>
                       `;
                });
                
            };

            const showIframeVideo = (videoUrl, description) => {
                const videoUrlModal = $('#videoUrlModal');
                videoUrlModal.find('.modal-body').html(`
                    <div class="ratio ratio-21x9">
                        <iframe class="myIframe" src="${videoUrl}" title="Video Preview" allowfullscreen></iframe>
                    </div>
        
                `);
                if (description !== "" || description != null) {
                    videoUrlModal.find('.modal-body').append(`
                        <div class="description m-3">
                            <div class="bg-light">
                                <h6 class="fw-bold text--base mb-2">Description:-</h6>
                                <p class="text-muted">${description}</p>
                            </div>
                        </div>
                    `);
                }
                videoUrlModal.show();
            };

            $.ajax({
                url: "<?php echo e(route('lesson.preview')); ?>",
                type: "POST",
                data: {
                    id: id,
                    course_id: course_id,
                    _token: "<?php echo e(csrf_token()); ?>"
                },
                success: function(response) {
                    if (response.status === 'success' && response.code === 1) {
                        const data = response.data;
                        console.log(data.lesson_documents.length);
                        if (data.lesson_documents.length > 0) {
                            showUploadDocument(data.lesson_documents);
                        }
                        if (data.preview_video === 1) {
                            showVideoPreview(data.upload_video, response.image);
                        } else if (data.preview_video === 3) {
                            const formattedDate = formatDate(data.zoom_data?.data?.start_time);
                            const meetingModal = $('#meetingModal');

                            if (!data.upload_video) {
                                populateModalBody(meetingModal.find('.modal-body'), data.zoom_data.data,
                                    formattedDate);
                                meetingModal.show();
                            } else {
                                showVideoPreview(data.upload_video, response.image);
                                populateModalBody(modal.find('.modal-body .description'), data.zoom_data.data,
                                    formattedDate);
                            }
                        } else {
                            showIframeVideo(data.video_url, description);
                        }
                    } else if (response.status === 'error' && response.code === 0) {
                        Toast.fire({
                            icon: response.status,
                            title: response.message
                        });
                    }
                },
                error: function(error) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Something went wrong. Please try again.'
                    });
                }
            });
        }

        function modalClose() {
            var modal = $('#exampleModal');
            modal.hide();
        }

        function mettingModalClose() {
            var modal = $('#meetingModal');
            modal.hide();
        }

        function videoUrlModalClose() {
            var modal = $('#videoUrlModal');
            var iframe = modal.find('.myIframe');
            iframe.attr('src', '');
            modal.hide();
        }
        
        // Module toggle functionality
        function toggleModule(moduleId) {
            const lessonsContainer = document.getElementById(`module-lessons-${moduleId}`);
            const toggleIcon = document.getElementById(`toggle-icon-${moduleId}`);
            
            if (lessonsContainer.style.display === 'none' || lessonsContainer.style.display === '') {
                // Show lessons
                lessonsContainer.style.display = 'block';
                toggleIcon.style.transform = 'rotate(180deg)';
                toggleIcon.classList.remove('fa-chevron-down');
                toggleIcon.classList.add('fa-chevron-up');
            } else {
                // Hide lessons
                lessonsContainer.style.display = 'none';
                toggleIcon.style.transform = 'rotate(0deg)';
                toggleIcon.classList.remove('fa-chevron-up');
                toggleIcon.classList.add('fa-chevron-down');
            }
        }
        
        // Prevent lesson click when clicking on module header
        document.addEventListener('DOMContentLoaded', function() {
            const moduleHeaders = document.querySelectorAll('.module-header');
            moduleHeaders.forEach(header => {
                header.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });
        
        // rating set
        $(document).ready(function() {
            'use strict'
            $('.rating-stars i').on('click', function() {
                var rating = parseInt($(this).data('rating'));
                $('#rating').val(rating);
                updateStars(rating);
            });
            $('#rating').on('input', function() {
                var rating = $(this).val();
                updateStars(rating);
            });

            function updateStars(rating) {
                var stars = $('.rating-stars i');
                stars.removeClass('fas').addClass('far');
                stars.each(function(index) {
                    if (index < rating) {
                        $(this).removeClass('far').addClass('fas');
                    }
                });
            }
        });
        // end rating set

        $('.show-more').on('click', function() {
            $('.accordion-item').removeClass('d-none');
            $('.accordion-item').css('visibility', 'visible');
            $('.accordion-item').css('animation-name', 'fadeInUp');
            $(this).remove();
        })
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .wyg h1,
        h2,
        h3,
        h4 {
            color: #383838;
        }

        .wyg strong {
            color: #383838
        }

        .wyg p {
            color: #666666
        }

        .wyg ul {
            margin-left: 40px
        }

        .wyg ul li {
            list-style-type: disc;
            color: #666666
        }

        .rating-comment-item .bottom ul {
            color: #ffc107;
        }

        .rating-wrap div {
            color: #ffc107;
        }
    </style>
    <style>
        /* Modal styles */
        .designModal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Modal content */
        .designModal .modal-content {
            width: 100%;
        }

        /* Close button */
        .designModal .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .designModal .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .course-data {
            cursor: pointer;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script>
$(document).ready(function() {
    // Update cart count on page load
    updateCartCount();
    
    // Check if course is already in cart
    checkCourseInCart();
    
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault();
        
        var courseId = $(this).data('course-id');
        var courseName = $(this).data('course-name');
        var button = $(this);
        
        // Disable button during request
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Adding...');
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            url: '<?php echo e(route("user.cart.add", ":course_id")); ?>'.replace(':course_id', courseId),
            type: 'POST',
            data: {
                course_id: courseId,
                _token: '<?php echo e(csrf_token()); ?>'
            },
            success: function(response) {
                console.log('Course added to cart:', response);
                
                // Show success toast notification
                if (response.message) {
                    showToast('success', response.message, courseName);
                }
                
                // Refresh the page to show "Remove from Cart" button
                setTimeout(function() {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr, status, error) {
                console.error('Error adding to cart:', error);
                console.log('Response:', xhr.responseText);
                
                // Re-enable button
                button.prop('disabled', false).html('<i class="fas fa-shopping-cart me-1"></i> Add to Cart');
                
                if (xhr.status === 500) {
                    showToast('error', 'Server error. Please try again.', '');
                } else {
                    showToast('error', 'Error adding course to cart. Please try again.', '');
                }
            }
        });
    });
    
    // Remove from cart functionality
    $(document).on('click', '.remove-from-cart-btn', function(e) {
        e.preventDefault();
        
        var courseId = $(this).data('course-id');
        var button = $(this);
        
        // Disable button during request
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Removing...');
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            url: '<?php echo e(route("user.cart.remove", ":course_id")); ?>'.replace(':course_id', courseId),
            type: 'POST',
            data: {
                course_id: courseId,
                _token: '<?php echo e(csrf_token()); ?>'
            },
            success: function(response) {
                console.log('Course removed from cart:', response);
                
                // Show success message
                if (response.message) {
                    showToast('success', response.message, '');
                }
                
                // Refresh the page to show "Add to Cart" button
                setTimeout(function() {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr, status, error) {
                console.error('Error removing from cart:', error);
                
                // Re-enable button
                button.prop('disabled', false).html('<i class="fa-solid fa-minus me-1"></i> Remove from Cart');
                showToast('error', 'Error removing course from cart. Please try again.', '');
            }
        });
    });
    
    function updateCartCount() {
        $.get('<?php echo e(route("user.cart.count")); ?>', function(response) {
            var count = response.count || 0;
            $('#cart-count').text(count);
            
            if (count > 0) {
                $('#cart-count').removeClass('empty');
            } else {
                $('#cart-count').addClass('empty');
            }
        }).fail(function() {
            console.log('Failed to update cart count');
        });
    }
    
    function checkCourseInCart() {
        var courseId = $('.add-to-cart-btn').data('course-id');
        
        if (!courseId) return;
        
        $.get('<?php echo e(route("user.cart.index")); ?>?json=1', function(response) {
            var courseInCart = false;
            
            if (response.items) {
                Object.keys(response.items).forEach(function(key) {
                    var item = response.items[key];
                    if (item.id == courseId) {
                        courseInCart = true;
                    }
                });
            }
            
            if (courseInCart) {
                // Show remove button instead of add button
                $('.add-to-cart-btn').hide();
                if ($('.remove-from-cart-btn').length === 0) {
                    $('.add-to-cart-btn').after('<button class="btn btn--danger remove-from-cart-btn" data-course-id="' + courseId + '"><i class="fa-solid fa-minus me-1"></i> Remove from Cart</button>');
                }
            } else {
                // Show add button
                $('.remove-from-cart-btn').remove();
                $('.add-to-cart-btn').show();
            }
        }).fail(function() {
            console.log('Failed to check cart status');
        });
    }
    
    // Custom toast notification function
    function showToast(type, message, courseName) {
        // Remove existing toasts
        $('.custom-toast').remove();
        
        var icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        var bgColor = type === 'success' ? '#28a745' : '#dc3545';
        var title = type === 'success' ? 'Success!' : 'Error!';
        
        var toastHtml = `
            <div class="custom-toast" style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${bgColor};
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                z-index: 9999;
                min-width: 300px;
                max-width: 400px;
                animation: slideInRight 0.3s ease-out;
            ">
                <div style="display: flex; align-items: center; margin-bottom: 5px;">
                    <i class="fas ${icon}" style="margin-right: 10px; font-size: 18px;"></i>
                    <strong style="font-size: 16px;">${title}</strong>
                    <button onclick="$(this).closest('.custom-toast').remove()" style="
                        background: none;
                        border: none;
                        color: white;
                        margin-left: auto;
                        cursor: pointer;
                        font-size: 18px;
                        padding: 0;
                        width: 20px;
                        height: 20px;
                    ">&times;</button>
                </div>
                <div style="font-size: 14px; opacity: 0.9;">
                    ${message}
                    ${courseName ? '<br><small><em>' + courseName + '</em></small>' : ''}
                </div>
            </div>
        `;
        
        $('body').append(toastHtml);
        
        // Auto remove after 4 seconds
        setTimeout(function() {
            $('.custom-toast').fadeOut(300, function() {
                $(this).remove();
            });
        }, 4000);
    }
});
</script>

<style>
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.custom-toast {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/presets/default/course/details.blade.php ENDPATH**/ ?>