<?php $__empty_1 = true; $__currentLoopData = $linkList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr>
        <td>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="serial-id" name="serial-id[]" data-id="<?php echo e($item->id); ?>">
                <label class="form-check-label" for="serial-id">
                    <?php echo e($item->id); ?>

                </label>
            </div>
        </td>
        <td><?php echo e($item->note); ?></td>
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
            <?php $__currentLoopData = $item->links ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p class="link-medical"><?php echo e($loop->iteration); ?>. <?php echo e($link->medical_center); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </td>
        <td>
            <?php $__currentLoopData = $item->links ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="my-2 d-flex justify-content-between align-items-center gap-5">
                    <?php if($link->ready_data): ?>
                        <p class="badge badge-success text-white pay-btn" data-pay-id="<?php echo e($link->ready_data['link_id']); ?>" data-id="<?php echo e($link->id); ?>" data-appointment-url="<?php echo e($link->url); ?>" data-url="<?php echo e($link->url); ?>">
                            <a href="javascript:void(0)" class="text-white"><?php echo e($loop->iteration); ?>. Pay</a>
                        </p>
                    <?php elseif($link->type): ?>
                        <p class="badge badge-success text-white pay-btn">
                            <a href="javascript:void(0)" class="text-white"><?php echo e($loop->iteration); ?>. Paying..</a>
                        </p>
                    <?php elseif($link->medical_center): ?>
                        <p class="badge badge-success text-white pay-btn">
                            <a href="<?php echo e($link->url); ?>" class="text-white" target="_blank"><?php echo e($loop->iteration); ?>. Slip</a>
                        </p>
                    <?php else: ?>
                        <p class="badge badge-success text-white pay-btn" data-id="<?php echo e($link->id); ?>" data-appointment-url="<?php echo e($link->url); ?>" data-url="<?php echo e($link->url); ?>">
                            <a href="javascript:void(0)" class="text-white"><?php echo e($loop->iteration); ?>. Link</a>
                        </p>
                    <?php endif; ?>
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
        <td>
            <div class="action-dropdown dropdown">
                <button class="btn btn-primary btm-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    Actions
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="<?php echo e(route('user.appointment.booking.edit.registration', $item->passport_number)); ?>">Edit</a></li>
                    <li><a class="dropdown-item ready-payment-btn" href="javascript:void(0)" data-id="<?php echo e($item->id); ?>">Ready Payment Processing</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0)">Complete</a></li>
                </ul>
            </div>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="10" class="text-center">No Data Found</td>
    </tr>
<?php endif; ?>
<?php /**PATH C:\Herd\marjsafin\resources\views/user/appointment-booking/render/tbody.blade.php ENDPATH**/ ?>