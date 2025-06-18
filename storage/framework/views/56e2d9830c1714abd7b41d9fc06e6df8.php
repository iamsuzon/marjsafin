<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'title' => '',
    'links' => [
        [
            'name' => '',
            'route' => '',
            'is_icon' => false,
            'active' => false,
            'has_permission' => true
        ]
    ]
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'title' => '',
    'links' => [
        [
            'name' => '',
            'route' => '',
            'is_icon' => false,
            'active' => false,
            'has_permission' => true
        ]
    ]
]); ?>
<?php foreach (array_filter(([
    'title' => '',
    'links' => [
        [
            'name' => '',
            'route' => '',
            'is_icon' => false,
            'active' => false,
            'has_permission' => true
        ]
    ]
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-15">
    <div class="d-flex align-items-center gap-8">
        <div class="icon text-title text-23">
            <i class="ri-terminal-line"></i>
        </div>
        <h6 class="card-title text-18"><?php echo e($title); ?></h6>
    </div>
    <!-- Sub Menu -->
    <div class="sub-menu-wrapper">
        <ul class="sub-menu-list">
            <?php $__currentLoopData = $links ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(isset($link['has_permission']) && ! $link['has_permission']) continue; ?>

                <li class="sub-menu-item">
                    <a href="<?php echo e($link['route']); ?>" class="single <?php echo e($link['active'] ? 'active' : ''); ?>">
                        <?php if(isset($link['is_icon']) && $link['is_icon']): ?>
                            <i class="<?php echo e($link['name']); ?>"></i>
                        <?php else: ?>
                            <?php echo e($link['name']); ?>

                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <!-- / Sub Menu -->
</div>
<?php /**PATH C:\Herd\marjsafin\resources\views/components/page-tabs.blade.php ENDPATH**/ ?>