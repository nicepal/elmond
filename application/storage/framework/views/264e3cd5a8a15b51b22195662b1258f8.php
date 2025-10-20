<!doctype html>
<html lang="<?php echo e(config('app.locale')); ?>" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title> <?php echo e($general->siteName(__($pageTitle))); ?></title>
    <?php echo $__env->make('includes.seo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- Bootstrap CSS -->
    <link href="<?php echo e(asset('assets/common/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/common/css/all.min.css')); ?>" rel="stylesheet">
    <!-- Slick Awesome CSS-->
    <link rel="stylesheet" href="<?php echo e(asset('assets/common/css/line-awesome.min.css')); ?>">
    <!-- Slick CSS-->
    <link rel="stylesheet" href="<?php echo e(asset($activeTemplateTrue . 'css/slick.css')); ?>">
    <!-- Animate CSS-->
    <link rel="stylesheet" href="<?php echo e(asset($activeTemplateTrue . 'css/animate.min.css')); ?>">
    <!-- Odometer CSS-->
    <link rel="stylesheet" href="<?php echo e(asset($activeTemplateTrue . 'css/odometer.css')); ?>">
    <!-- Magnific Popup CSS-->
    <link rel="stylesheet" href="<?php echo e(asset($activeTemplateTrue . 'css/magnific-popup.css')); ?>">
    <!-- plyr -->
    <link rel="stylesheet" href="<?php echo e(asset($activeTemplateTrue . 'css/plyr.css')); ?>">
    <!-- Main CSS -->
    <link rel="stylesheet" href="<?php echo e(asset($activeTemplateTrue . 'css/main.css')); ?>">
    <!-- Custom CSS-->
    <link rel="stylesheet" href="<?php echo e(asset($activeTemplateTrue . 'css/custom.css')); ?>">
    <!-- Launch Courses CSS -->
    <link rel="stylesheet" href="<?php echo e(asset($activeTemplateTrue . 'css/launch-courses.css')); ?>">
    
    <?php echo $__env->yieldPushContent('style-lib'); ?>
    <?php echo $__env->yieldPushContent('style'); ?>
    
    <link rel="stylesheet"
        href="<?php echo e(asset($activeTemplateTrue . 'css/color.php')); ?>?color1=<?php echo e($general->base_color); ?>&color2=<?php echo e($general->secondary_color); ?>">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    
    <!-- Your custom CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/presets/default/css/course-details.css')); ?>">
</head>

<body>
    <!--==================== Preloader Start ====================-->
    <div id="loading">
        <div id="loading-center">
            <div id="loading-center-absolute">
                <span class="loader"></span>
            </div>
        </div>
    </div>
    <!--==================== Preloader End ====================-->

    <!--==================== Sidebar Overlay End ====================-->
    <div class="sidebar-overlay"></div>
    <!--==================== Sidebar Overlay End ====================-->

    <?php if(
        !Route::is('user.login') &&
            !Route::is('user.register') &&
            !Route::is('user.password.email') &&
            !Route::is('instructor.login') &&
            !Route::is('instructor.register') &&
            !Route::is('instructor.password.email')): ?>
        
        <?php echo $__env->make($activeTemplate . 'components.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
    <?php endif; ?>

    <?php
        $pages = App\Models\Page::where('tempname', $activeTemplate)->get();
    ?>

    
    <?php if(!Route::is('home') && !Route::is('course.details') && !Route::is('user.login') && !Route::is('user.register') && !Route::is('instructor.login') && !Route::is('instructor.register')): ?>
        <?php echo $__env->make($activeTemplate . 'components.breadcumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>

    <?php if(!Route::is('user.login') && !Route::is('user.register') && !Route::is('user.password.email')&& !Route::is('instructor.login') && !Route::is('instructor.register') && !Route::is('instructor.password.email')): ?>
        
        <?php echo $__env->make($activeTemplate . 'components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
    <?php endif; ?>

    
    <?php echo $__env->make($activeTemplate . 'components.cookie_popup', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    

    <!-- WhatsApp Chat Widget -->
    <?php if($general->whatsapp_enabled && $general->whatsapp_number): ?>
    <div class="whatsapp-chat-widget">
        <a href="https://wa.me/<?php echo e(str_replace(['+', ' ', '-'], '', $general->whatsapp_number)); ?>?text=<?php echo e(urlencode($general->whatsapp_message ?? 'Hello! How can we help you?')); ?>" 
           target="_blank" class="whatsapp-btn" title="Chat with us on WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>
    <?php endif; ?>

    <style>
    .whatsapp-chat-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 999999;
    }
    
    .whatsapp-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background-color: #25D366;
        border-radius: 50%;
        color: white !important;
        font-size: 30px;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
        transition: all 0.3s ease;
        animation: pulse 2s infinite;
    }
    
    .whatsapp-btn:hover {
        background-color: #128C7E;
        transform: scale(1.1);
        color: white !important;
        text-decoration: none;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(37, 211, 102, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
        }
    }
    
    @media (max-width: 768px) {
        .whatsapp-chat-widget {
            bottom: 15px;
            right: 15px;
        }
        
        .whatsapp-btn {
            width: 50px;
            height: 50px;
            font-size: 24px;
        }
    }
    </style>

    <style>
    :root {
        --base-color: <?php echo e($general->base_color); ?>;
        --secondary-color: <?php echo e($general->secondary_color); ?>;
    }
    </style>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <!-- jQuery -->
    <script src="<?php echo e(asset('assets/common/js/jquery-3.7.1.min.js')); ?>"></script>
    
    <!-- Global Cart Count Update Script -->
    <script>
    $(document).ready(function() {
        // Update cart count on page load
        updateGlobalCartCount();
        
        function updateGlobalCartCount() {
            $.get('<?php echo e(route("user.cart.count")); ?>', function(response) {
                var count = response.count || 0;
                $('#cart-count').text(count);
                
                if (count > 0) {
                    $('#cart-count').removeClass('empty').show();
                } else {
                    $('#cart-count').addClass('empty').hide();
                }
            }).fail(function() {
                console.log('Failed to update cart count');
            });
        }
        
        // Make function globally available
        window.updateGlobalCartCount = updateGlobalCartCount;
    });
    </script>
    
    <!-- moment js -->
    <script src="<?php echo e(asset($activeTemplateTrue . 'js/moment.min.js')); ?>"></script>
    <!-- Slick js -->
    <script src="<?php echo e(asset($activeTemplateTrue . 'js/slick.min.js')); ?>"></script>
    <!-- Odometer js -->
    <script src="<?php echo e(asset($activeTemplateTrue . 'js/odometer.min.js')); ?>"></script>
    <!-- jquery appear js -->
    <script src="<?php echo e(asset($activeTemplateTrue . 'js/jquery.appear.min.js')); ?>"></script>
    <!-- wow js -->
    <script src="<?php echo e(asset($activeTemplateTrue . 'js/wow.min.js')); ?>"></script>
    <!-- Magnific Popup js -->
    <script src="<?php echo e(asset($activeTemplateTrue . 'js/jquery.magnific-popup.min.js')); ?>"></script>
    <!-- plyr -->
    <script src="<?php echo e(asset($activeTemplateTrue . 'js/plyr.js')); ?>"></script>
    <!-- main js -->
    <script src="<?php echo e(asset($activeTemplateTrue . 'js/main.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/common/js/bootstrap.bundle.min.js')); ?>"></script>

    <?php echo $__env->yieldPushContent('script-lib'); ?>
    <?php echo $__env->yieldPushContent('script'); ?>
    <?php echo $__env->make('includes.plugins', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('includes.notify', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('includes.language_js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<script>
// Initialize Swiper
var swiper = new Swiper(".testimonialSwiper", {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    breakpoints: {
        640: {
            slidesPerView: 1,
            spaceBetween: 20,
        },
        768: {
            slidesPerView: 2,
            spaceBetween: 30,
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 30,
        },
    },
});

// Smooth scrolling for navigation links
document.querySelectorAll('.page-scroll').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Update active navigation on scroll
window.addEventListener('scroll', function() {
    let current = '';
    const sections = document.querySelectorAll('section[id]');
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (scrollY >= (sectionTop - 200)) {
            current = section.getAttribute('id');
        }
    });
    
    document.querySelectorAll('#desktop-nav a').forEach(link => {
        link.parentElement.classList.remove('active');
        if (link.getAttribute('href') === '#' + current) {
            link.parentElement.classList.add('active');
        }
    });
});
</script>
</body>

</html>
<?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/presets/default/layouts/frontend.blade.php ENDPATH**/ ?>