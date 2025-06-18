<?php $__env->startSection('styles'); ?>
    <style>
        .swal2-icon.swal2-info, .swal2-icon.swal2-question, .swal2-icon.swal2-warning {
            font-size: 15px !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contents'); ?>
    <div class="page-content">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h2>Allocate Center List</h2>

                <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'super-admin|admin')): ?>
                <button type="button" class="btn-primary-fill" data-bs-toggle="modal" data-bs-target="#create-modal">
                    Create New Allocate Center
                </button>
                <?php endif; ?>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <p><strong>Kindly fill up all the required fields</strong></p>
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="table-responsive mt-25">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>#SL</th>
                        <th>Name</th>
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'super-admin|admin')): ?>
                        <th>Action</th>
                        <?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $allocationCenterList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($item->id); ?></td>
                            <td><?php echo e($item->name); ?></td>

                            <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'super-admin|admin')): ?>
                            <td class="text-end px-15 d-flex gap-10">
                                <a class="edit-btn" href="javascript:void(0)"
                                   data-bs-toggle="modal" data-bs-target="#edit-modal"
                                   data-id="<?php echo e($item->id); ?>" data-name="<?php echo e($item->name); ?>">
                                    <i class="ri-edit-line"></i>
                                </a>

                                
                                
                                
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td class="text-center py-15" colspan="5">No allocate center found</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="modal fade" id="create-modal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header mb-10 p-0 pb-10">
                        <div class="d-flex align-items-center gap-8">
                            <div class="icon text-20">
                                <i class="ri-bar-chart-horizontal-line"></i>
                            </div>
                            <h6 class="modal-title">New Allocate Center</h6>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ri-close-line" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div class="modal-body p-0">
                        <div class="row g-10">
                            <div class="col-md-12">
                                <div class="contact-form">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control input" id="name" name="name" required>
                                </div>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="d-flex align-items-center gap-16 flex-wrap mt-18">
                            <button class="btn-primary-fill create-btn" type="submit">
                                <span class="d-flex align-items-center gap-6">
                                    <i class="las la-check-circle"></i>
                                    <span>Confirm</span>
                                </span>
                            </button>
                            <button class="btn-cancel-fill" type="reset" data-bs-dismiss="modal">
                                <span class="d-flex align-items-center gap-6">
                                    <i class="ri-close-line"></i>
                                    <span>Discard</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit-modal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header mb-10 p-0 pb-10">
                        <div class="d-flex align-items-center gap-8">
                            <div class="icon text-20">
                                <i class="ri-bar-chart-horizontal-line"></i>
                            </div>
                            <h6 class="modal-title">Update Allocate Center</h6>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ri-close-line" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div class="modal-body p-0">
                        <div class="row g-10">
                            <div class="col-md-12">
                                <input type="hidden" name="allocate_center_id">

                                <div class="contact-form mt-15">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control input" id="name" name="name" required>
                                </div>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="d-flex align-items-center gap-16 flex-wrap mt-18">
                            <button class="btn-primary-fill update-btn" type="submit">
                                <span class="d-flex align-items-center gap-6">
                                    <i class="las la-check-circle"></i>
                                    <span>Update</span>
                                </span>
                            </button>
                            <button class="btn-cancel-fill" type="reset" data-bs-dismiss="modal">
                                <span class="d-flex align-items-center gap-6">
                                    <i class="ri-close-line"></i>
                                    <span>Discard</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $__env->stopSection(); ?>

        <?php $__env->startSection('scripts'); ?>
            <script>
                $(document).ready(function () {
                    $(document).on('click', '.edit-btn', function (e) {
                        e.preventDefault();

                        let id = $(this).data('id');
                        let name = $(this).data('name');

                        $('input[name=allocate_center_id]').val(id);
                        $('#edit-modal input[name=name]').val(name);
                    });

                    $(document).on('click', '.update-btn', function (e) {
                        e.preventDefault();

                        let id = $('input[name=allocate_center_id]').val();
                        let name = $('#edit-modal input[name=name]').val();

                        customSwal({
                            route: `<?php echo e(route('admin.allocate-center.update')); ?>`,
                            data: {
                                id: id,
                                name: name,
                            },
                            title: 'Update Allocate Center',
                            text: 'Are you sure you want to update this allocate center?',
                            confirmButtonText: 'Yes, Update',
                            cancelButtonText: 'No, Cancel',
                            successMessage: 'Allocate center updated successfully',
                            successFunction: function (response) {
                                let responseData = response.data;

                                if (responseData.status) {
                                    $('#edit-modal').modal('hide');
                                    toastSuccess(responseData.message);
                                    reloadThisPage(1000);
                                }
                            },
                            errorFunction: function (error) {
                                let errorMsg = error.response.data.message;
                                toastError(errorMsg);
                            }
                        })
                    });

                    $(document).on('click', '.create-btn', function (e) {
                        e.preventDefault();

                        let el = $('#create-modal');
                        let name = el.find('input[name=name]').val();

                        customSwal({
                            route: `<?php echo e(route('admin.allocate-center.new')); ?>`,
                            data: {
                                name: name,
                            },
                            title: 'Create New Allocate Center',
                            text: 'Are you sure you want to create this allocate center?',
                            confirmButtonText: 'Yes, Create',
                            cancelButtonText: 'No, Cancel',
                            successMessage: 'Allocate center created successfully',
                            successFunction: function (response) {
                                let responseData = response.data;

                                if (responseData.status) {
                                    el.modal('hide');
                                    toastSuccess(responseData.message);
                                    reloadThisPage(1000);
                                } else {
                                    toastError(responseData.message);
                                }
                            },
                            errorFunction: function (error) {
                                let errorMsg = error.response.data.message;
                                toastError(errorMsg);
                            }
                        })
                    });

                    $(document).on('click', '.delete-btn', function (e) {
                        e.preventDefault();

                        let id = $(this).data('id');
                        customSwal({
                            route: `<?php echo e(route('admin.medical-center.delete')); ?>?id=${id}`,
                            method: 'GET',
                            title: 'Delete Medical Center',
                            text: 'Are you sure you want to delete this medical center?',
                            confirmButtonText: 'Yes, Delete',
                            cancelButtonText: 'No, Cancel',
                            successMessage: 'Medical center deleted successfully',
                            successFunction: function (response) {
                                let responseData = response.data;

                                if (responseData.status) {
                                    toastSuccess(responseData.message);
                                    reloadThisPage(1000);
                                }
                            },
                            errorFunction: function (error) {
                                let errorMsg = error.response.data.message;
                                toastError(errorMsg);
                            }
                        })
                    });
                });
            </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.user-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Herd\marjsafin\resources\views/admin/allocation-center-list.blade.php ENDPATH**/ ?>