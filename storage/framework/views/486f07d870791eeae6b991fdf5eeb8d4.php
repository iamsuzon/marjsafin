<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('user.partials.medical-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contents'); ?>
    <div class="page-content">

        <?php if(hasSlipPermission()): ?>
            <div>
                <a href="<?php echo e(route('user.slip.medical-center.rates')); ?>" class="btn btn-primary">Medical Centers</a>
            </div>
        <?php endif; ?>

        <!-- Hero -->
        <div class="hero hero-bg overflow-hidden ">
            <div class="row">
                <div class="col-lg-12">
                    <div class="hero-padding position-relative z-index-5">
                        <div class="welcome-card">
                            <img class="position-absolute img-1 img-fluid"
                                 src="<?php echo e(customAsset('assets/images/icon/hero-icon1.png')); ?>"
                                 alt="">
                            <img class="position-absolute img-2 img-fluid"
                                 src="<?php echo e(customAsset('assets/images/icon/hero-icon2.png')); ?>"
                                 alt="">
                            <img class="position-absolute img-3 img-fluid"
                                 src="<?php echo e(customAsset('assets/images/icon/hero-icon3.png')); ?>"
                                 alt="">
                            <img class="position-absolute img-4 img-fluid"
                                 src="<?php echo e(customAsset('assets/images/icon/hero-icon4.png')); ?>"
                                 alt="">
                            <img class="position-absolute img-5 img-fluid"
                                 src="<?php echo e(customAsset('assets/images/icon/hero-icon5.png')); ?>"
                                 alt="">
                            <img class="position-absolute img-5 img-fluid"
                                 src="<?php echo e(customAsset('assets/images/icon/hero-icon6.png')); ?>"
                                 alt="">
                        </div>
                        <div class="hero-caption">
                            
                            
                            <h2 class="title">Dashboard</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Hero -->

        <?php if(hasLinkPermission()): ?>
            <div>
                <a href="<?php echo e(route('user.dashboard.medical-center.types')); ?>?type=normal"
                   class="btn btn-primary">Normal</a>
                <a href="<?php echo e(route('user.dashboard.medical-center.types')); ?>?type=normal-plus" class="btn btn-primary">Normal
                    Plus</a>
                <a href="<?php echo e(route('user.dashboard.medical-center.types')); ?>?type=special"
                   class="btn btn-primary">Special</a>
                <a href="<?php echo e(route('user.dashboard.medical-center.types')); ?>?type=special-plus" class="btn btn-primary">Special
                    Plus</a>
            </div>
        <?php endif; ?>

        <a href="<?php echo e(route('user.scrap.payment.page.data')); ?>" class="btn btn-success">Test</a>

        <?php if(session()->has('error')): ?>
            <div class="alert alert-danger bg-danger text-white alert-dismissible text-center fs-5 fade show pt-5 pb-5"
                 role="alert">
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!--/ Table -->
        <?php if(auth()->guard('web')->check()): ?>
            <marquee class="dashboard-marquee" style="color: red" width="100%" direction="left">
                <?php echo e($adText ?? ''); ?>

            </marquee>
        <?php endif; ?>
    </div>

    <div class="modal" id="notice-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header mb-10 p-0 pb-10">
                    <div class="d-flex align-items-center gap-8">
                        <div class="icon text-20">
                            <i class="ri-bar-chart-horizontal-line"></i>
                        </div>
                        <h6 class="modal-title">Notice</h6>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ri-close-line" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="modal-body p-0">
                    <div class="row g-10">
                        <div class="col-lg-12">
                            <h4 class="notice-title mb-25 bg-danger text-white p-3 fw-bold">Important Notice</h4>
                            <p class="lh-lg"><?php echo $noticeText; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(document).ready(function () {
            $('.single-date-picker').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoApply: false,
                locale: {
                    format: 'DD-MM-YYYY',
                    separator: ' - ',
                }
            });

            let showNotice = `<?php echo e($showNotice); ?>`;
            if (showNotice) {
                $('#notice-modal').modal('show');
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.common-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\herd-sites\marjsafin\resources\views/user/dashboard.blade.php ENDPATH**/ ?>