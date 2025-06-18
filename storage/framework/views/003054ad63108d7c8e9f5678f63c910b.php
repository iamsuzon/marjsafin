<?php $__env->startSection('title', 'Link List'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('user.partials.medical-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <style>
        .manage__title {
            border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
        }
        a.slip-link p {
            white-space: nowrap;
            width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        a.slip-link p:hover {
            overflow: visible;
            color: var(--primary);
        }
        .play-btn {
            color: #FFFFFF !important;
            background-color: var(--primary);
            border-color: var(--primary);

            &:hover {
                color: var(--primary) !important;
                border-color: var(--primary);
            }
        }
        .stop-btn {
            color: #FFFFFF !important;
            background-color: var(--red);
            border-color: var(--red);

            &:hover {
                color: var(--red) !important;
                border-color: var(--red);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .loading-icon {
            display: inline-block;
            animation: spin 1s linear infinite;
        }
        .copy-url {
            cursor: pointer;
        }
        .action-dropdown .dropdown-menu {
            margin-top: 5px !important;
            background-color: #FFFFFF !important;
        }

        .action-dropdown .dropdown-item {
            margin-top: 8px !important;
            background-color: #FFFFFF !important;
            color: var(--primary) !important;
            border: 1px solid var(--primary) !important;
            padding: 8px 10px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            border-radius: 5px !important;
            transition: background-color 0.3s ease;

            &:hover {
                background-color: var(--primary) !important;
                color: #FFFFFF !important;
                border: 1px solid var(--primary) !important;
            }
        }

        p:has(a.disabled) {
            background-color: slategrey;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contents'); ?>
    <div class="page-content">
        <div class="container-fluid">
            <?php if (isset($component)) { $__componentOriginalf5da842333934bac1365ea2ff9d5dd81 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf5da842333934bac1365ea2ff9d5dd81 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-tabs','data' => ['title' => 'Links','links' => [
                [
                    'name' => 'Book Appointment Link Register',
                    'route' => route('user.appointment.booking.registration'),
                    'active' => false,
                    'has_permission' => hasLinkPermission()
                ],
                [
                    'name' => 'Complete List',
                    'route' => route('user.appointment.booking.list.complete'),
                    'active' => true,
                    'has_permission' => hasLinkPermission()
                ],
                [
                    'name' => 'ri-bank-card-line',
                    'is_icon' => true,
                    'route' => route('user.card.manage'),
                    'active' => false,
                    'has_permission' => hasLinkPermission()
                ]
            ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('page-tabs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Links','links' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                [
                    'name' => 'Book Appointment Link Register',
                    'route' => route('user.appointment.booking.registration'),
                    'active' => false,
                    'has_permission' => hasLinkPermission()
                ],
                [
                    'name' => 'Complete List',
                    'route' => route('user.appointment.booking.list.complete'),
                    'active' => true,
                    'has_permission' => hasLinkPermission()
                ],
                [
                    'name' => 'ri-bank-card-line',
                    'is_icon' => true,
                    'route' => route('user.card.manage'),
                    'active' => false,
                    'has_permission' => hasLinkPermission()
                ]
            ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf5da842333934bac1365ea2ff9d5dd81)): ?>
<?php $attributes = $__attributesOriginalf5da842333934bac1365ea2ff9d5dd81; ?>
<?php unset($__attributesOriginalf5da842333934bac1365ea2ff9d5dd81); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf5da842333934bac1365ea2ff9d5dd81)): ?>
<?php $component = $__componentOriginalf5da842333934bac1365ea2ff9d5dd81; ?>
<?php unset($__componentOriginalf5da842333934bac1365ea2ff9d5dd81); ?>
<?php endif; ?>

            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <div class="manage__title">
                            <div class="d-flex justify-content-start gap-20">
                                <div class="d-flex gap-10 justify-content-start align-items-center">
                                    <h2>Link List</h2>
                                </div>
                                <div class="d-flex gap-10 justify-content-start align-items-center">
                                    <p class="fw-bold">Card: <?php echo e($added_cards); ?></p>
                                    <p class="fw-bold">Slip: <?php echo e($user_slip_numbers ?? 0); ?></p>
                                    <p class="fw-bold">Link: 0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-50">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Unsuccessful!</strong> <?php echo e(session('error')); ?>


                            <!-- <?php if(session('url')): ?>
                                আরো স্কোর এর জন্য রিকোয়েস্ট করুন <a href="<?php echo e(session('url')); ?>" class="btn btn-primary">Request
                                    Score</a>
                            <?php endif; ?> -->
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsives max-height-100vh scroll-active">
                        <table class="table-color-col table-head-border table-td-border">
                            <thead>
                            <tr>
                                <th class="mw-45">#SL</th>
                                <th>Note</th>
                                <th>Date & Ref</th>
                                <th>Link Type</th>
                                <th>Details</th>
                                <th>Paid Center Name</th>
                                <th>Links</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $linkList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr data-ap-id="<?php echo e($item->id); ?>">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="serial-id" name="serial-id[]" data-id="<?php echo e($item->id); ?>">
                                            <label class="form-check-label" for="serial-id">
                                                <?php echo e($item->id); ?>

                                            </label>
                                        </div>
                                    </td>
                                    <td class="note"><?php echo e($item->note); ?></td>
                                    <td>
                                        <p>Date: <?php echo e($item->created_at->format('d/m/Y')); ?></p>
                                        <p>Reference: <?php echo e($item->reference); ?></p>
                                    </td>
                                    <td>
                                        <p class="timer text-danger"></p>
                                        <p><?php echo e(ucwords(str_replace('_',' ',$item->type))); ?></p>
                                    </td>
                                    <td>
                                        <p>PP: <?php echo e($item->passport_number); ?></p>
                                        <p>FN: <?php echo e($item->first_name); ?></p>
                                        <p>LS: <?php echo e($item->last_name); ?></p>
                                        <p>NID: <?php echo e($item->nid_number); ?></p>
                                        <p class="text-capitalize">Gender: <?php echo e($item->gender); ?></p>
                                    </td>
                                    <td class="medical-center-names">
                                        <p class="text-capitalize choice-medical">
                                            <?php echo e(str_replace('-',' ',$item->center_name)); ?>

                                        </p>
                                        <p>-------</p>
                                        <div class="link-medical-wrapper">
                                            <?php $__currentLoopData = $item->links ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <p class="link-medical"><?php echo e($link->medical_center); ?></p>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php $__currentLoopData = $item->links ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="my-2 d-flex justify-content-between align-items-center gap-5">
                                                <p class="badge badge-success text-white">
                                                    <a href="<?php echo e(str_replace('pay', 'slip', $link->url)); ?>" class="text-white" target="_blank"><?php echo e($loop->iteration); ?>. Slip</a>
                                                </p>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>
                                    <td>
                                        <?php
                                            if ($item->type === 'normal') {
                                                $status = $item->links()->count() === 1 ? 'Submitted' : 'Pending';
                                            } elseif ($item->type === 'normal_plus') {
                                                $status = $item->links()->count() === 4 ? 'Submitted' : 'Pending';
                                            } elseif ($item->type === 'special') {
                                                $status = $item->links()->count() === 5 ? 'Submitted' : 'Pending';
                                            } elseif ($item->type === 'special_plus') {
                                                $status = $item->links()->count() === 7 ? 'Submitted' : 'Pending';
                                            }
                                        ?>

                                        <?php echo e($status); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="10" class="text-center">No Data Found</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-start pagination-custom-wrapper">
                        <?php echo e($linkList->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.common-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Herd\marjsafin\resources\views/user/appointment-booking/complete-list.blade.php ENDPATH**/ ?>