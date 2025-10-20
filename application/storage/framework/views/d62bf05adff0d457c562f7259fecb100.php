<!-- Sidebar  -->

<!-- < dashboard side bar -->
<div class="dashboard_profile">
    <div class="dashboard_profile__details">
        <div class="sidebar-menu">
            <span class="sidebar-menu__close"><i class="las la-times"></i></span>
            <div class="logo-wrapper px-3">
                <a href="<?php echo e(route('home')); ?>" class="normal-logo" id="normal-logo"> <img
                        src="<?php echo e(getImage(getFilePath('logoIcon') . '/logo.png')); ?>" alt="logo-image"></a>
            </div>
            <ul class="sidebar-menu-list">
                <li class="sidebar-menu-list__item">
                    <a href="<?php echo e(route('user.home')); ?>" class="sidebar-menu-list__link <?php echo e(Route::is('user.home') ? 'active' : ''); ?>">
                        <span class="icon"><i class="fa-solid fa-house"></i></span>
                        <span class="text"><?php echo app('translator')->get('Dashboard'); ?></span>
                    </a>
                </li>


                <li class="sidebar-menu-list__item">
                    <a href="<?php echo e(route('user.enroll.all.courses')); ?>" class="sidebar-menu-list__link <?php echo e(isActiveRoute('user.enroll.all.courses') ? 'active' : ''); ?>">
                        <span class="icon"><i class="fa-solid fa-book"></i></span>
                        <span class="text"><?php echo app('translator')->get('All Courses'); ?></span>
                    </a>
                </li>

                <li class="sidebar-menu-list__item">
                    <a href="<?php echo e(route('user.enroll.courses')); ?>" class="sidebar-menu-list__link <?php echo e(isActiveRoute('user.enroll.courses') ? 'active' : ''); ?>">
                        <span class="icon"><i class="fa-solid fa-book-open"></i></span>
                        <span class="text"><?php echo app('translator')->get('Enroll Courses'); ?></span>
                    </a>
                </li>


               
                <li class="sidebar-menu-list__item has-dropdown <?php echo e(isActiveRoute('user.quiz.list') || isActiveRoute('user.quiz.course') || isActiveRoute('user.quiz.details') || isActiveRoute('user.quiz.result') ? 'active' : ''); ?>">
                    <a href="javascript:void(0)" class="sidebar-menu-list__link <?php echo e(isActiveRoute('user.quiz.list') || isActiveRoute('user.quiz.course') || isActiveRoute('user.quiz.details') || isActiveRoute('user.quiz.result') ? 'active' : ''); ?>">
                        <span class="icon"><i class="fa-solid fa-gauge-high"></i></span>
                        <span class="text"><?php echo app('translator')->get('Quizzes'); ?></span>
                    </a>
                    <div class="sidebar-submenu <?php echo e(isActiveRoute('user.quiz.list') || isActiveRoute('user.quiz.course') || isActiveRoute('user.quiz.details') || isActiveRoute('user.quiz.result')  ? 'd-block' : ''); ?>">
                        <ul class="sidebar-submenu-list">
                            <li class="sidebar-submenu-list__item">
                                <a href="<?php echo e(route('user.quiz.result')); ?>"
                                    class="sidebar-submenu-list__link"><?php echo app('translator')->get('My Quiz Result'); ?></a>
                            </li>
                          
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-list__item has-dropdown <?php echo e(isActiveRoute('user.deposit') ? 'active' : ''); ?>">
                    <a href="javascript:void(0)"
                        class="sidebar-menu-list__link <?php echo e(isActiveRoute('user.deposit') ? 'active' : ''); ?>">
                        <span class="icon"><i class="fa-solid fa-wallet"></i></span>
                        <span class="text"><?php echo app('translator')->get('Payments'); ?></span>
                    </a>
                    <div class="sidebar-submenu <?php echo e(isActiveRoute('user.deposit') ? 'd-block' : ''); ?>">
                        <ul class="sidebar-submenu-list">
                            <li class="sidebar-submenu-list__item">
                                <a href="<?php echo e(route('user.deposit.history')); ?>"
                                    class="sidebar-submenu-list__link"><?php echo app('translator')->get('Payment History'); ?></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-list__item has-dropdown <?php echo e(isActiveRoute('user.profile.setting') || isActiveRoute('user.change.password') || isActiveRoute('user.twofactor') ? 'active' : ''); ?>">
                    <a href="javascript:void(0)" class="sidebar-menu-list__link">
                        <span class="icon"><i class="fa-solid fa-user"></i></span>
                        <span class="text"><?php echo app('translator')->get('Account'); ?></span>
                    </a>
                    <div class="sidebar-submenu <?php echo e(isActiveRoute('user.profile.setting') || isActiveRoute('user.change.password') || isActiveRoute('user.twofactor') ? 'd-block' : ''); ?>">
                        <ul class="sidebar-submenu-list">
                            <li class="sidebar-submenu-list__item">
                                <a href="<?php echo e(route('user.profile.setting')); ?>"
                                    class="sidebar-submenu-list__link"><?php echo app('translator')->get('Profile Setting'); ?></a>
                            </li>
                            <li class="sidebar-submenu-list__item">
                                <a href="<?php echo e(route('user.change.password')); ?>"
                                    class="sidebar-submenu-list__link"><?php echo app('translator')->get('Change Password'); ?> </a>
                            </li>
                            <li class="sidebar-submenu-list__item">
                                <a href="<?php echo e(route('user.twofactor')); ?>"
                                    class="sidebar-submenu-list__link"><?php echo app('translator')->get('2Fa Security'); ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>


                <li class="sidebar-menu-list__item">
                    <a href="<?php echo e(route('user.transactions')); ?>"
                        class="sidebar-menu-list__link <?php echo e(Route::is('user.transactions') ? 'active' : ''); ?>">
                        <span class="icon"><i class="fa-solid fa-money-bill"></i></span>
                        <span class="text"><?php echo app('translator')->get('Transactions'); ?></span>
                    </a>
                </li>

                <li class="sidebar-menu-list__item">
                    <a href="<?php echo e(route('ticket')); ?>" class="sidebar-menu-list__link <?php echo e(Route::is('ticket') ? 'active' : ''); ?>">
                        <span class="icon"><i class="fa-solid fa-headset"></i></span>
                        <span class="text"><?php echo app('translator')->get('Support Ticket'); ?></span>
                    </a>
                </li>
                <li class="sidebar-menu-list__item">
                    <a href="<?php echo e(route('user.logout')); ?>" class="sidebar-menu-list__link">
                        <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                        <span class="text"><?php echo app('translator')->get('Log Out'); ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- dashboard side bar /> -->
<?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/presets/default/components/user/sidebar.blade.php ENDPATH**/ ?>