<li class="sidebar-menu-item <?php echo e(activeCurrentSidebarMenu('dashboard')); ?>">
    <a href="<?php echo e(route('dashboard')); ?>" class="parent-item-content">
        <i class="ri-dashboard-line"></i>
        <span class="on-half-expanded">Dashboard</span>
    </a>
</li>

<?php if(hasLinkPermission()): ?>
    <li class="sidebar-menu-item <?php echo e(activeCurrentSidebarMenu(['user.appointment.booking.registration', 'user.appointment.booking.list'])); ?>">
        <a href="<?php echo e(route('user.appointment.booking.list')); ?>" class="parent-item-content">
            <i class="ri-links-line"></i>
            <span class="on-half-expanded">Link Manage</span>
        </a>
    </li>
<?php endif; ?>

<!-- Single Menu -->
<li class="sidebar-menu-item <?php echo e(activeCurrentSidebarMenu(['user.registration', 'user.slip.registration'])); ?>">
    <a href="<?php echo e(route('user.registration')); ?>" class="parent-item-content">
        <i class="ri-hand-heart-line"></i>
        <span class="on-half-expanded">Registration</span>
    </a>
</li>

<!-- Single Menu -->
<li class="sidebar-menu-item <?php echo e(activeCurrentSidebarMenu(['user.application.list', 'user.slip.list'])); ?>">
    <a href="<?php echo e(route('user.application.list')); ?>" class="parent-item-content">
        <i class="ri-file-list-2-line"></i>
        <span class="on-half-expanded">Application List</span>
    </a>
</li>

<li class="sidebar-menu-item <?php echo e(activeCurrentSidebarMenu('user.transaction.history')); ?>">
    <a href="<?php echo e(route('user.transaction.history')); ?>" class="parent-item-content">
        <i class="ri-list-indefinite"></i>
        <span class="on-half-expanded">Transaction History</span>
    </a>
</li>
<?php /**PATH E:\herd-sites\marjsafin\resources\views/user/partials/medical-sidebar.blade.php ENDPATH**/ ?>