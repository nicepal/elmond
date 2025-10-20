<!-- ==================== Breadcumb Start Here ==================== -->
<div class="breadcumb" style="background-color: #ffffff;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcumb__wrapper">
                    <h2 class="breadcumb__title mb-4" style="color: #000000;"><?php echo e(@$pageTitle); ?></h2>
                    <ul class="breadcumb__list">
                        <li class="breadcumb__item"><a href="<?php echo e(route('home')); ?>" class="breadcumb__link" style="color: #000000;"><?php echo app('translator')->get('Home'); ?></a></li>
                        <li class="breadcumb__icon"> <i class="fa-solid fa-slash" style="color: #000000;"></i> </li>
                        <li class="breadcumb__item"> <span class="breadcumb__item-text" style="color: #000000;"> <?php echo e(@$pageTitle); ?> </span> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ==================== Breadcumb End Here ==================== --><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/presets/default/components/breadcumb.blade.php ENDPATH**/ ?>