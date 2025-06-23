<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $__env->yieldContent('title'); ?> - <?php echo e(env('APP_NAME')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    <link rel="stylesheet" href="<?php echo e(customAsset('assets/css/bootstrap-5.3.1.min.css')); ?>">
    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
        rel="stylesheet"
    />
    <!-- Plugin -->
    <link rel="stylesheet" type="text/css" href="<?php echo e(customAsset('assets/css/plugin.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(customAsset('assets/css/chart/apexcharts.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(customAsset('assets/css/toastr.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(customAsset('assets/css/main-style.css')); ?>">

    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>

<style>
    .unread-message {
        background: #f1f1f1;
    }

    .notification-listing .list {
        padding: 4px;
        border-radius: 5px;
        position: relative;
    }

    .notification-listing .list:hover {
        background: #dadada;
    }

    .notification-listing .list span {
        position: absolute;
        right: 5px;
        top: 80%;
        transform: translateY(-50%);
        font-size: 10px;
    }

    .user-details .balance {
        font-size: 13px;
        font-weight: 600;
    }
</style>

<div id="layout-wrapper">
    <header class="header">
        <!-- Header Left -->
        <div class="left-content d-flex flex-wrap gap-10">
            <!-- Sidebar Toggle Button -->
            <button class="half-expand-toggle sidebar-toggle">
                <i class="ri-arrow-left-right-fill"></i>
            </button>
        </div>
        <!-- / Left -->

        <?php if(auth()->guard('web')->check()): ?>
            <div class="d-flex flex-wrap">
                <marquee style="color: red" width="100%" direction="left">
                    মেডিকেল ডাটা সঠিক ভাবে সাবমিট করে মেডিকেল করলে ৩য় দিন রিপোর্ট প্রদান করার চান্স 99% (যদি সার্ভার
                    সংক্রান্ত কোন সমস্যা না হয়) । অন্যথায় ১ দিন পর রিপোর্ট পাবেন।
                </marquee>
            </div>
        <?php endif; ?>

        <!-- Header Right -->
        <ul class="header-right">
            <!-- Notification -->
            <li class="cart-list notification dropdown" id="notification-div">
                <?php if(auth()->guard('union_account')->check()): ?>
                    <?php
                        $notifications = auth('union_account')->user()->unionNotification();
                        $unreadNotifications = $notifications->whereNull('read_at')->count();
                    ?>
                    <a href="javascript:void(0)" class="cart-items dropdown-toggle toggle-arro-hidden"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-notification-2-line p-0"></i>

                        <?php if($unreadNotifications > 0): ?>
                            <span class="count"><?php echo e($unreadNotifications); ?></span>
                        <?php endif; ?>
                    </a>

                    <div class="dropdown-list-style dropdown-menu dropdown-menu-end">
                        <div class="notification-header d-flex justify-content-between align-items-center mb-10">
                            <h6>Notifications</h6>
                            <button class="clear-notification">clear</button>
                        </div>
                        <ul class="notification-listing scroll-active p-0">
                            <?php $__empty_1 = true; $__currentLoopData = $notifications ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <li class="list mb-6 <?php echo e(! $notification->read_at ? 'unread-message' : ''); ?>">
                                    <a class="list-items custom-break-spaces dropdown-item"
                                       href="<?php echo e(route('union.application.list.single', $notification->link)); ?>">
                                        <i class="ri-notification-3-line"></i>
                                        <p class="line-clamp-2"><?php echo e($notification->message); ?></p>
                                        <span>
                                            <small><?php echo e($notification->created_at->diffForHumans()); ?></small>
                                        </span>
                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <li class="list">
                                    <a class="dropdown-item my-4" href="javascript:void(0)">
                                        <p class="line-clamp text-center">No notification found</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>

                        <?php if($notifications->count() > 10): ?>
                            <a href="<?php echo e(route('union.notification.all')); ?>" class="see-all-notification border-0">see all
                                notification</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </li>

            <!-- Login User -->
            <li class="cart-list dropdown">
                <!-- User Profile -->
                <div class="user-info dropdown-toggle toggle-arro-hidden" data-bs-toggle="dropdown"
                     aria-expanded="false" role="button">
                    <div class="user-img">
                        <img src="<?php echo e(customAsset('assets/images/profile.png')); ?>" class="img-cover" alt="img">
                    </div>
                </div>
                <!-- Profile List -->
                <div class="dropdown-menu dropdown-list-style dropdown-menu-end white-bg with-248">
                    <ul class="profileListing">
                        <!-- User info -->
                        <?php if(auth()->guard('web')->check()): ?>
                            <a href="#" class="user-sub-info">
                                <div class="user-details">
                                    <p class="balance text-primary">Medical Score: <?php echo e(number_format(auth('web')->user()->balance, 1)); ?></p>
                                    <p class="balance text-primary">Slip Score: <?php echo e(number_format(auth('web')->user()->slip_balance, 1)); ?></p>
                                </div>
                            </a>
                        <?php endif; ?>

                        <a href="#" class="user-sub-info">
                            <div class="user-details">
                                <span class="name"><?php echo e(auth()->user()->username); ?></span>
                                <p class="pera"><?php echo e(auth()->user()->email); ?></p>
                            </div>
                        </a>
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        <li class="list">
                            <?php
                                if (auth('web')->check()) {
                                    $route = route('logout');
                                } elseif (auth('medical_center')->check()) {
                                    $route = route('medical.logout');
                                } elseif (auth('union_account')->check()) {
                                    $route = route('union.logout');
                                }
                            ?>

                            <a class="list-items dropdown-item"
                               href="<?php echo e($route); ?>">
                                <span>logout</span>
                                <i class="ri-logout-box-line"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        <!-- / Header Right -->
    </header>
    <aside class="sidebar">
        <div class="sidebar-menu">
            <div class="sidebar-menu scroll-hide">
                <ul class="sidebar-dropdown-menu parent-menu-list">
                    <li class="sidebar-menu-item">
                        <a href="#" class="parent-item-content">
                            <?php echo e(Auth::user()->username); ?>

                        </a>
                    </li>

                    <?php echo $__env->yieldContent('sidebar'); ?>

                    <li class="sidebar-menu-item">
                        <a href="<?php echo e(route('logout')); ?>"
                           class="parent-item-content">
                            <i class="ri-caravan-line"></i>
                            <span class="on-half-expanded">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Logo -->
            <div class="sidebar-logo d-flex justify-content-between align-items-start gap-10">
                <a href="/" class="d-block">
                    <img class="full-logo" src="<?php echo e(customAsset('assets/images/logo.png')); ?>" alt="img">
                </a>
                <button class="single change-mode border-0 mt-6">
                    <i class="ri-moon-line"></i>
                </button>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <?php echo $__env->yieldContent('contents'); ?>

        <div class="footer d-flex justify-content-between align-items-center">
            <p>&copy; 2025 <a href="#">Admin</a>. All rights reserved.</p>
            <p>Version: 2.0</p>
        </div>
    </main>
</div>

<script src="<?php echo e(customAsset('assets/js/jquery-3.7.1.min.js')); ?>"></script>
<script src="<?php echo e(customAsset('assets/js/bootstrap-5.3.1.min.js')); ?>"></script>
<script src="<?php echo e(customAsset('assets/js/popper.min.js')); ?>"></script>
<!-- Plugin -->
<script src="<?php echo e(customAsset('assets/js/plugin.js')); ?>"></script>
<script src="<?php echo e(customAsset('assets/js/chart/apexcharts.js')); ?>"></script>
<script src="<?php echo e(customAsset('assets/js/chart/chart-custom.js')); ?>"></script>
<!-- Axios JS -->
<script src="<?php echo e(customAsset('assets/js/toastr.min.js')); ?>"></script>
<script src="<?php echo e(customAsset('assets/js/sweetalert2.js')); ?>"></script>
<script src="<?php echo e(customAsset('assets/js/axios.min.js')); ?>"></script>
<!-- Main Custom JS -->
<script src="<?php echo e(customAsset('assets/js/main.js')); ?>"></script>
<!-- Dev Custom JS -->
<script src="<?php echo e(customAsset('assets/js/custom.js')); ?>"></script>

<?php echo $__env->yieldContent('scripts'); ?>




















</body>
</html>
<?php /**PATH E:\herd-sites\marjsafin\resources\views/user/layout/common-master.blade.php ENDPATH**/ ?>