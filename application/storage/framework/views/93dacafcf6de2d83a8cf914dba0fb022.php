<div class="row">
    <div class="col">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link <?php echo e(Request::routeIs('admin.course.index') ? 'active' : ''); ?>"
                    href="<?php echo e(route('admin.course.index')); ?>"><?php echo app('translator')->get('My courses'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(Request::routeIs('admin.course.new.launches') ? 'active' : ''); ?>"
                    href="<?php echo e(route('admin.course.new.launches')); ?>"><?php echo app('translator')->get('New Launches'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(Request::routeIs('admin.course.upcoming') ? 'active' : ''); ?>"
                    href="<?php echo e(route('admin.course.upcoming')); ?>"><?php echo app('translator')->get('Upcoming Courses'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(Request::routeIs('admin.course.featured') ? 'active' : ''); ?>"
                    href="<?php echo e(route('admin.course.featured')); ?>"><?php echo app('translator')->get('Featured Courses'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(Request::routeIs('admin.course.instructor') ? 'active' : ''); ?>"
                    href="<?php echo e(route('admin.course.instructor')); ?>"><?php echo app('translator')->get('Instructor Course'); ?>
                </a>
            </li>
        </ul>
    </div>
</div>
<?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/admin/components/tabs/course.blade.php ENDPATH**/ ?>