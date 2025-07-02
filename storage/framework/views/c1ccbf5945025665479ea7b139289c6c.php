<?php $__env->startSection('title', 'Medical List'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('user.partials.medical-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <style>
        .manage__title {
            border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contents'); ?>
    <div class="page-content">
        <div class="container-fluid">
            <?php if (isset($component)) { $__componentOriginalf5da842333934bac1365ea2ff9d5dd81 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf5da842333934bac1365ea2ff9d5dd81 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-tabs','data' => ['title' => 'Medical','links' => [
                [
                    'name' => 'Medical List',
                    'route' => route('user.application.list'),
                    'active' => true,
                    'has_permission' => hasMedicalPermission()
                ],
                [
                    'name' => 'Slip List',
                    'route' => route('user.slip.list'),
                    'active' => false,
                    'has_permission' => hasSlipPermission()
                ]
            ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('page-tabs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Medical','links' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                [
                    'name' => 'Medical List',
                    'route' => route('user.application.list'),
                    'active' => true,
                    'has_permission' => hasMedicalPermission()
                ],
                [
                    'name' => 'Slip List',
                    'route' => route('user.slip.list'),
                    'active' => false,
                    'has_permission' => hasSlipPermission()
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

            <?php if(hasMedicalPermission()): ?>
                <div class="card">
                <div class="row">
                    <div class="col-12">
                        <h2 class="manage__title">Medical Application List (<?php echo e($applicationList->count()); ?>)</h2>

                        <form id="search-from">
                            <div class="row d-flex justify-content-center mt-25">
                                <div class="col-md-2">
                                    <div class="contact-form">
                                        <label class="contact-label">Start Date </label>
                                        <div class="d-flex justify-content-between date-pic-icon">
                                            <input type="text" class="contact-input single-date-picker start_date"
                                                   placeholder="Choose Date">
                                            <span> <b class="caret"></b></span>
                                            <i class="ri-calendar-line"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <!-- Date Picker -->
                                    <div class="contact-form">
                                        <label class="contact-label">end Date </label>
                                        <div class="d-flex justify-content-between date-pic-icon">
                                            <input type="text" class="contact-input single-date-picker end_date"
                                                   placeholder="Choose Date">
                                            <span> <b class="caret"></b></span>
                                            <i class="ri-calendar-line"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 d-flex align-items-end">
                                    <div class="contact-form d-flex">
                                        <button class="btn-primary-fill search_btn" type="submit">Search</button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="contact-form">
                                        <label class="contact-label">Passport Number</label>
                                        <input type="text" class="contact-input passport_search"
                                               placeholder="Search By Passport Number" name="passport_search">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <div class="contact-form d-flex gap-10">
                                        <button class="btn-primary-fill search_btn_passport" type="submit">Search
                                        </button>
                                        <button class="btn-danger-fill reset_btn" type="reset">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </form>
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


                            <?php if(session('url')): ?>
                                    আরো স্কোর এর জন্য রিকোয়েস্ট করুন <a href="<?php echo e(session('url')); ?>" class="btn btn-primary">Request Score</a>
                            <?php endif; ?>
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
                                <th>Date</th>
                                <th>Medical Type</th>
                                <th>Registration</th>
                                <th>Passport</th>
                                <th>Reference</th>
                                <th>Traveling To</th>
                                <th>Center</th>
                                <th>Allocate Center</th>
                                <th>Score</th>
                                <th>Comment</th>
                                <th>PDF</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $applicationList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="mw-45 d-flex align-items-center"><?php echo e(listSerialNumber($applicationList, $loop)); ?></td>
                                    <td>
                                        <p>Drft: <?php echo e($item->created_at->format('d/m/Y')); ?></p>
                                        <p>Crte: <?php echo e($item->medical_date?->format('d/m/Y')); ?></p>
                                    </td>
                                    <td><?php echo e(ucfirst($item->medical_type)); ?></td>
                                    <td>
                                        <p><?php echo e($item->serial_number); ?></p>
                                        <p><?php echo e($item->ems_number); ?></p>
                                    </td>
                                    <td>
                                        <p><?php echo e($item->passport_number); ?></p>
                                        <p><?php echo e($item->given_name); ?></p>
                                        <p><?php echo e($item->surname); ?></p>
                                        <?php if($item->nid_no != 0): ?>
                                            <p>NID: <?php echo e($item->nid_no); ?></p>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <p><?php echo e($item->ref_no); ?></p>
                                    </td>
                                    <td>
                                        <p class="text-capitalize"><?php echo e($item->gender === 'none' ? '' : $item->gender); ?></p>
                                        <p><?php echo e(travelingToName($item->traveling_to)); ?></p>
                                    </td>
                                    <td>
                                        <?php
                                            $center = centerName($item->center_name);
                                            $center_name = explode(' - ', $center)[0] ?? '';
                                            $center_address = explode(' - ', $center)[1] ?? '';
                                        ?>

                                        <p class="text-capitalize"><?php echo e($center_name); ?></p>
                                        <p class="text-capitalize"><?php echo e($center_address); ?></p>
                                    </td>
                                    <td class="text-capitalize"><?php echo e(getAllocatedMedicalCenterHumanName($item)); ?></td>
                                    <td>
                                        <?php
                                            $payment_status = $item->applicationPayment?->admin_amount;
                                        ?>

                                        <?php if($payment_status): ?>
                                            <p class="mb-10 text-danger">-<?php echo e($payment_status); ?></p>

                                            <?php
                                                $medical_status = $item->medical_status;

                                                $class = 'info';
                                                if ($medical_status == 'new') {
                                                    $class = 'info';
                                                } elseif ($medical_status == 'in-progress' || $medical_status == 'under-review') {
                                                    $class = 'warning';
                                                } elseif ($medical_status == 'fit') {
                                                    $class = 'success';
                                                }
                                            ?>

                                            <?php if(auth('web')->user()->balance > 0): ?>
                                                <?php if(empty($item->paymentLog)): ?>
                                                    <a href="javascript:void(0)" class="pay-bill-btn btn-primary-fill"
                                                       data-id="<?php echo e($item->id); ?>">
                                                        <i class="ri-money-dollar-circle-line"></i> Submit Score
                                                    </a>
                                                <?php else: ?>
                                                    <p class="badge bg-<?php echo e($class); ?>"><?php echo e(getMedicalStatusName($item->medical_status ?? 'new')); ?></p>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if(!empty($item->paymentLog)): ?>
                                                    <p class="badge bg-<?php echo e($class); ?>"><?php echo e(getMedicalStatusName($item->medical_status ?? 'new')); ?></p>
                                                <?php else: ?>
                                                    <a href="<?php echo e(route('user.score.request', $item->user_id)); ?>"
                                                       class="btn-primary-fill"
                                                       style="background: #ffc107; color: #000">No Score Available</a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($item->allocatedMedicalCenter?->status): ?>
                                            <p class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                'text-capitalize',
                                                'text-success' => $item->health_status == 'fit',
                                                'text-danger' => $item->health_status == 'unfit',
                                                'text-warning' => $item->health_status == 'held-up',
                                            ]); ?>"><?php echo e($item->health_status); ?></p>
                                            <p><?php echo e($item->applicationCustomComment?->health_condition); ?></p>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end px-15">
                                        <a class="view-btn" href="<?php echo e(route('user.generate.pdf', $item->id)); ?>">
                                            <i class="ri-printer-line"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="update_btn btn btn-primary"
                                           data-bs-target="#update-modal" data-bs-toggle="modal"
                                           data-id="<?php echo e($item->id); ?>" data-passport_number="<?php echo e($item->passport_number); ?>"
                                           data-registration_number="<?php echo e($item->serial_number); ?>" data-medical_date="<?php echo e($item->medical_date); ?>"
                                        >Update</a>
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

                    <div class="d-flex justify-content-start">
                        <?php echo e($applicationList->links()); ?>

                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="modal fade" id="update-modal" tabindex="-1" aria-labelledby="scoreModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="scoreModalLabel">Submit Medical Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <input type="hidden" name="id">

                    <div class="modal-body">
                        <div class="contact-form">
                            <label for="score">Passport Number</label>
                            <input class="form-control" type="text" name="passport_number" disabled>
                        </div>

                        <div class="contact-form mt-15">
                            <label for="score">Registration Number</label>
                            <input class="form-control" type="text" name="registration_number">
                        </div>

                        <div class="contact-form mt-15">
                            <label class="contact-label">Medical Date <small>(Day-Month-Year)</small></label>
                            <div class="d-flex justify-content-between date-pic-icon">
                                <input type="text" class="input single-date-picker"
                                       placeholder="Choose Date" name="medical_date" value="<?php echo e(old('medical_date')); ?>">
                                <span> <b class="caret"></b></span>
                                <i class="ri-calendar-line"></i>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Yes, Submit Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(document).ready(function () {
            // Initialize datepicker for every date input field
            $('.single-date-picker').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoApply: false,
                locale: {
                    format: 'DD-MM-YYYY',
                    separator: ' - ',
                }
            });
            $('.single-date-picker').val("");

            $(document).on('click', '.update_btn', function () {
                let el = $(this);
                let id = el.data('id');
                let passport_number = el.data('passport_number');
                let registration_number = el.data('registration_number');
                let medical_date = el.data('medical_date');

                let modal = $('#update-modal');
                modal.find('input[name="id"]').val(id);
                modal.find('input[name="passport_number"]').val(passport_number);
                modal.find('input[name="registration_number"]').val(registration_number);
                modal.find('input[name="medical_date"]').val(medical_date);
            });

            $(document).on('submit', '#update-modal form', function (e) {
                e.preventDefault();

                let form = $(this);
                let id = form.find('input[name="id"]').val();
                let registration_number = form.find('input[name="registration_number"]').val();
                let medical_date = form.find('input[name="medical_date"]').val();

                $.ajax({
                    url: `<?php echo e(route('user.application.list.update')); ?>`,
                    type: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        id: id,
                        registration_number: registration_number,
                        medical_date: medical_date,
                    },
                    success: function (response) {
                        if (response.status) {
                            toastSuccess(response.message);
                            reloadThisPage();
                        } else {
                            toastError(response.message);
                        }
                    },
                    error: function (error) {
                        let errors = error.responseJSON.errors;
                        if (errors) {
                            let message = '';
                            for (let key in errors) {
                                message += errors[key][0] + '\n';
                            }
                            toastError(message);
                        }
                    }
                });
            });

            $(document).on('click', '.search_btn', function (e) {
                e.preventDefault();

                let form = $('#search-from');
                let start_date = form.find('.start_date').val();
                let end_date = form.find('.end_date').val();

                window.location.href = `<?php echo e(route('user.application.list')); ?>?start_date=${start_date}&end_date=${end_date}`;
            });

            $(document).on('click', '.reset_btn', function () {
                location.href = `<?php echo e(route('user.application.list')); ?>`;
            });

            $(document).on('click', '.search_btn_passport', function (e) {
                e.preventDefault();

                let passport_search = $('input[name="passport_search"]').val();

                window.location.href = `<?php echo e(route('user.application.list')); ?>?passport_search=${passport_search}`;
            });

            $(document).on('click', '.pay-bill-btn', function () {
                let el = $(this);
                let id = el.data('id');

                Swal.fire({
                    title: 'Score Submission Confirmation',
                    text: 'Are you sure you want to submit score for this application?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Submit Now',
                    cancelButtonText: 'No, Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `<?php echo e(route('user.pay-bill')); ?>?application_id=${id}`;
                    }
                })
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.common-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Herd\marjsafin\resources\views/user/user-panel.blade.php ENDPATH**/ ?>