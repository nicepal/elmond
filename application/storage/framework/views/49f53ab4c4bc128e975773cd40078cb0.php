<?php
    $testimonialSection = getContent('testimonial.content', true);
    $testimonialElementSection = getContent('testimonial.element', false, 3);
?>
<section id="testimonial-section-custom" class="testimonial-section pt-90">
    <div class="testimonial-wrap testimonial-wrap-reduced">
        <span class="bg-elemet1">
            <img src="<?php echo e(getImage(getFilePath('testimonial') . '/' . @$testimonialSection->data_values?->shape_image_one)); ?>"
                alt="image">
        </span>
        <span class="bg-elemet2">
            <img src="<?php echo e(getImage(getFilePath('testimonial') . '/' . @$testimonialSection->data_values?->shape_image_two)); ?>"
                alt="image">
        </span>
        <span class="bg-elemet3">
            <img src="<?php echo e(getImage(getFilePath('testimonial') . '/' . @$testimonialSection->data_values?->shape_image_three)); ?>"
                alt="image">
        </span>

        <div class="container">
            <!-- Added Title Section -->
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title"><?php echo e(__(@$testimonialSection->data_values?->heading ?? 'What Our Students Say')); ?></h2>
                    <p class="section-subtitle"><?php echo e(__(@$testimonialSection->data_values?->subheading ?? 'Hear from our satisfied students about their learning experience')); ?></p>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="testimonial-slider-container">
                        <!-- Custom Arrow Buttons -->
                        <div class="testimonial-arrows">
                            <button class="testimonial-prev" type="button"><i class="las la-angle-left"></i></button>
                            <button class="testimonial-next" type="button"><i class="las la-angle-right"></i></button>
                        </div>
                        
                        <div class="testimonial-slider">
                            <?php $__empty_1 = true; $__currentLoopData = $testimonialElementSection ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="testimonial-card">
                                    <div class="icon-thumb">
                                        <img src="<?php echo e(asset('assets/images/frontend/testimonial/testemonial-icon.png')); ?>"
                                            alt="image">
                                    </div>
                                    <h6 class="title"><?php echo e(__(@$item->data_values?->title)); ?></h6>
                                    <div class="user-thumb">
                                        <img src="<?php echo e(getImage(getFilePath('testimonial') . '/' . @$item->data_values?->image_one)); ?>"
                                            alt="image">
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <!-- Add some default content for testing -->
                                <div class="testimonial-card">
                                    <div class="icon-thumb">
                                        <img src="<?php echo e(asset('assets/images/frontend/testimonial/testemonial-icon.png')); ?>"
                                            alt="image">
                                    </div>
                                    <h6 class="title">Sample Testimonial 1</h6>
                                    <div class="user-thumb">
                                        <img src="<?php echo e(asset('assets/images/placeholder.jpg')); ?>" alt="image">
                                    </div>
                                </div>
                                <div class="testimonial-card">
                                    <div class="icon-thumb">
                                        <img src="<?php echo e(asset('assets/images/frontend/testimonial/testemonial-icon.png')); ?>"
                                            alt="image">
                                    </div>
                                    <h6 class="title">Sample Testimonial 2</h6>
                                    <div class="user-thumb">
                                        <img src="<?php echo e(asset('assets/images/placeholder.jpg')); ?>" alt="image">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php $__env->startPush('script'); ?>
<script>
(function($) {
    'use strict';
    
    $(document).ready(function() {
        console.log('Testimonial script loaded');
        
        // Check if slider element exists
        var sliderElement = $('#testimonial-section-custom .testimonial-slider');
        console.log('Slider element found:', sliderElement.length);
        
        // Wait a bit for DOM to be fully ready
        setTimeout(function() {
            // Initialize Slick Slider only if element exists and has content
            if (sliderElement.length > 0 && sliderElement.children().length > 0) {
                console.log('Initializing slick slider with', sliderElement.children().length, 'slides');
                
                // Destroy existing slider if any
                if (sliderElement.hasClass('slick-initialized')) {
                    sliderElement.slick('unslick');
                }
                
                sliderElement.slick({
                    dots: true,
                    infinite: true,
                    speed: 500,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 4000,
                    arrows: false, // Disable default arrows since we're using custom ones
                    fade: true,
                    cssEase: 'linear',
                    adaptiveHeight: true,
                    responsive: [
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                fade: false
                            }
                        }
                    ]
                });
                
                console.log('Slick slider initialized successfully');
                
                // Custom arrow click events - Use event delegation
                $(document).off('click.testimonial').on('click.testimonial', '#testimonial-section-custom .testimonial-prev', function(e) {
                    e.preventDefault();
                    console.log('Previous arrow clicked');
                    sliderElement.slick('slickPrev');
                });
                
                $(document).on('click.testimonial', '#testimonial-section-custom .testimonial-next', function(e) {
                    e.preventDefault();
                    console.log('Next arrow clicked');
                    sliderElement.slick('slickNext');
                });
                
            } else {
                console.warn('Slider element not found or has no content');
            }
        }, 100);
    });
    
})(jQuery);
</script>
<?php $__env->stopPush(); ?><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/presets/default/sections/testimonial.blade.php ENDPATH**/ ?>