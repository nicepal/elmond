<div class="row">
    <div class="col">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link <?php echo e(Request::routeIs('admin.ticket.pending') ? 'active' : ''); ?>"
                    href="<?php echo e(route('admin.deposit.pending')); ?>"><?php echo app('translator')->get('Pending'); ?>
                    <?php if($pendingTicketCount): ?>
                    <span class="badge rounded-pill bg--white text-muted"><?php echo e($pendingTicketCount); ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(Request::routeIs('admin.ticket.closed') ? 'active' : ''); ?>"
                    href="<?php echo e(route('admin.ticket.closed')); ?>"><?php echo app('translator')->get('Closed'); ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(Request::routeIs('admin.ticket.answered') ? 'active' : ''); ?>"
                    href="<?php echo e(route('admin.ticket.answered')); ?>"><?php echo app('translator')->get('Answered'); ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e(Request::routeIs('admin.ticket') ? 'active' : ''); ?>"
                    href="<?php echo e(route('admin.ticket')); ?>"><?php echo app('translator')->get('All'); ?>
                </a>
            </li>
        </ul>
    </div>
</div><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/admin/components/tabs/ticket.blade.php ENDPATH**/ ?>