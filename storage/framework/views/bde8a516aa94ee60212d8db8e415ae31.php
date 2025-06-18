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
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contents'); ?>
    <div class="page-content">
        <div class="container-fluid">
            <?php if (isset($component)) { $__componentOriginalf5da842333934bac1365ea2ff9d5dd81 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf5da842333934bac1365ea2ff9d5dd81 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-tabs','data' => ['title' => 'Slip','links' => [
                [
                    'name' => 'Medical List',
                    'route' => route('admin.application.list'),
                    'active' => false
                ],
                                [
                    'name' => 'Slip List',
                    'route' => route('admin.slip.list'),
                    'active' => false
                ],
                [
                    'name' => 'Link List',
                    'route' => route('admin.appointment-booking.list'),
                    'active' => true
                ]
            ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('page-tabs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Slip','links' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                [
                    'name' => 'Medical List',
                    'route' => route('admin.application.list'),
                    'active' => false
                ],
                                [
                    'name' => 'Slip List',
                    'route' => route('admin.slip.list'),
                    'active' => false
                ],
                [
                    'name' => 'Link List',
                    'route' => route('admin.appointment-booking.list'),
                    'active' => true
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
                        <h2 class="manage__title">
                            Link List
                        </h2>

                        <!--<form id="search-form">-->
                        <!--    <div class="row d-flex justify-content-center mt-25">-->
                        <!--        <div class="col-md-2">-->
                        <!--            <div class="contact-form">-->
                        <!--                <label class="contact-label">Start Date </label>-->
                        <!--                <div class="d-flex justify-content-between date-pic-icon">-->
                        <!--                    <input type="text" class="contact-input single-date-picker start_date"-->
                        <!--                           placeholder="Choose Date">-->
                        <!--                    <span> <b class="caret"></b></span>-->
                        <!--                    <i class="ri-calendar-line"></i>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--        </div>-->

                        <!--        <div class="col-md-2">-->
                                    <!-- Date Picker -->
                        <!--            <div class="contact-form">-->
                        <!--                <label class="contact-label">end Date </label>-->
                        <!--                <div class="d-flex justify-content-between date-pic-icon">-->
                        <!--                    <input type="text" class="contact-input single-date-picker end_date"-->
                        <!--                           placeholder="Choose Date">-->
                        <!--                    <span> <b class="caret"></b></span>-->
                        <!--                    <i class="ri-calendar-line"></i>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--        </div>-->

                        <!--        <div class="col-md-2 d-flex align-items-end">-->
                        <!--            <div class="contact-form d-flex">-->
                        <!--                <button class="btn-primary-fill search_btn" type="submit">Search</button>-->
                        <!--            </div>-->
                        <!--        </div>-->

                        <!--        <div class="col-md-2">-->
                        <!--            <div class="contact-form">-->
                        <!--                <label class="contact-label">Passport Number</label>-->
                        <!--                <input type="text" class="contact-input"-->
                        <!--                       placeholder="Search By Passport Number" name="passport_search">-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--        <div class="col-md-2 d-flex align-items-end">-->
                        <!--            <div class="contact-form d-flex gap-10">-->
                        <!--                <button class="btn-primary-fill search_btn_passport" type="submit">Search-->
                        <!--                </button>-->
                        <!--                <button class="btn-danger-fill reset_btn" type="reset">Reset</button>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</form>-->
                    </div>
                </div>

                <div class="row mt-50">
                    <div class="table-responsives max-height-100vh scroll-active">
                        <table class="table-color-col table-head-border table-td-border">
                            <thead>
                            <tr>
                                <th class="mw-45">#SL</th>
                                <th>Date</th>
                                <th>User</th>
                                <th>Link Type</th>
                                <th>Details</th>
                                <th>Passport</th>
                                <th>Link Number</th>
                                <th>Links</th>
                                <!--<th>Action</th>-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $linkList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($item->id); ?></td>
                                    <td><?php echo e($item->created_at->format('d/m/Y')); ?></td>
                                    <td><?php echo e($item->user?->name); ?></td>
                                    <td><?php echo e(ucfirst($item->type)); ?></td>
                                    <td>
                                        <p>FN: <?php echo e($item->first_name); ?></p>
                                        <p>LS: <?php echo e($item->last_name); ?></p>
                                        <p>NID: <?php echo e($item->nid_number); ?></p>
                                        <p class="text-capitalize">Gender: <?php echo e($item->gender); ?></p>
                                    </td>
                                    <td><?php echo e($item->passport_number); ?></td>
                                    <td><?php echo e($item->links()->count()); ?></td>
                                    <td>
                                        <?php $__currentLoopData = $item->links ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e($link->url); ?>" target="_blank"><?php echo e($loop->iteration); ?>. <?php echo e(Str::limit($link->url, 40)); ?></a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>
                                    <!--<td>-->
                                    <!--    <?php if($item->links()->count() < 1): ?>-->
                                    <!--        <a href="javascript:void(0)" class="btn btn-primary link-now-btn" data-id="<?php echo e($item->id); ?>">Link Now</a>-->
                                    <!--    <?php endif; ?>-->
                                    <!--</td>-->
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

    <div class="modal fade" id="edit-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header mb-10 p-0 pb-10">
                    <div class="d-flex align-items-center gap-8">
                        <div class="icon text-20">
                            <i class="ri-bar-chart-horizontal-line"></i>
                        </div>
                        <h6 class="modal-title">Update Slip</h6>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ri-close-line" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="modal-body p-0">
                    <form action="#" method="POST" id="edit-form" enctype="multipart/form-data">

                        <input type="hidden" name="id">

                        <div class="row g-10">
                            <div class="col-md-12">
                                <div class="contact-form">
                                    <label class="contact-label">Slip Status</label>
                                    <select name="slip_status" id="slip_status" class="form-control">
                                        <option value="">Select an option</option>
                                        <option value="processing">Processing</option>
                                        <option value="processed-link">Processed-Link</option>
                                        <option value="cancelled">Cancelled:Non</option>
                                        <option value="we-cant-not-expired">We Can't Not Expired</option>
                                        <option value="cancelled-for-time-out">Cancelled for Time Out</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="contact-form">
                                    <label class="contact-label">Link</label>
                                    <input class="form-control input" type="text" name="link">
                                </div>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="d-flex align-items-center gap-16 flex-wrap mt-18">
                            <button class="btn-primary-fill" type="submit">
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

                    </form>
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
            $('.single-date-picker').val("");

            $(document).on('click', '.edit-btn', function () {
                let el = $(this);
                let id = el.data('id');
                let status = el.data('status');
                let link = el.data('link');

                let modal = $('#edit-modal');
                modal.find('input[name="id"]').val(id);
                modal.find('select[name="slip_status"]').val(status);
                modal.find('input[name="link"]').val(link);
            });

            $(document).on('click', '#edit-form button[type=submit]', function (e) {
                e.preventDefault();

                let form = $('#edit-form');

                let id = form.find(`input[name="id"]`).val();
                let slip_status = form.find(`select[name="slip_status"]`).val();
                let link = form.find(`input[name="link"]`).val();

                if (!id || !slip_status) {
                    toastError('All fields are required');
                    return;
                }

                axios.post(`<?php echo e(route('admin.slip.list')); ?>?id=${id}`, {
                    _token: '<?php echo e(csrf_token()); ?>',
                    slip_status: slip_status,
                    link: link,
                })
                    .then(res => {
                        let data = res.data;

                        if (data.status) {
                            toastSuccess(data.message);
                            reloadThisPage();
                        }
                    })
                    .catch(err => {
                        console.log(err);
                    })
            })

            $(document).on('click', '#search-form .search_btn', function (e) {
                e.preventDefault();

                let start_date = $('.start_date').val();
                let end_date = $('.end_date').val();

                window.location.href = `<?php echo e(route('admin.slip.list')); ?>?start_date=${start_date}&end_date=${end_date}`;
            });

            $(document).on('click', '.reset_btn', function () {
                location.href = `<?php echo e(route('admin.slip.list')); ?>`;
            });

            $(document).on('click', '.search_btn_passport', function (e) {
                e.preventDefault();

                let passport_search = $('input[name="passport_search"]').val();

                window.location.href = `<?php echo e(route('admin.slip.list')); ?>?passport_search=${passport_search}`;
            });

            $(document).on('click', '.delete-btn', function () {
                let id = $(this).data('id');

                customSwal({
                    route: `<?php echo e(route('admin.slip.delete')); ?>?id=${id}`,
                    method: 'GET',
                    successFunction: (res) => {
                        if (res.status) {
                            toastSuccess(res.message);
                            reloadThisPage(1000);
                        }
                    }
                });
            })
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.user-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Herd\marjsafin\resources\views/admin/appointment-booking/booking-list.blade.php ENDPATH**/ ?>