<?php $__env->startSection('title', 'Medical Registration'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('user.partials.medical-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <style>
        .manage__title {
                border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
        }
        .fillable {
            color: red;
        }
        #notice-modal {
            z-index: 99999;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contents'); ?>
    <div class="page-content">
        <div class="container-fluid">
            <?php if (isset($component)) { $__componentOriginalf5da842333934bac1365ea2ff9d5dd81 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf5da842333934bac1365ea2ff9d5dd81 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-tabs','data' => ['title' => 'Registration','links' => [
                [
                    'name' => 'Medical',
                    'route' => route('user.registration'),
                    'active' => true,
                    'has_permission' => hasMedicalPermission()
                ],
                [
                    'name' => 'Slip',
                    'route' => route('user.slip.registration'),
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
<?php $component->withAttributes(['title' => 'Registration','links' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                [
                    'name' => 'Medical',
                    'route' => route('user.registration'),
                    'active' => true,
                    'has_permission' => hasMedicalPermission()
                ],
                [
                    'name' => 'Slip',
                    'route' => route('user.slip.registration'),
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
                        <h2 class="manage__title mb-25">Medical</h2>

                        <?php if(session('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> <?php echo e(session('success')); ?> To view the details <a class="text-primary fw-bold" href="<?php echo e(route('user.application.list')); ?>">click here</a>
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

                        <form action="<?php echo e(route('user.registration')); ?>" method="POST" id="registration-form">
                            <?php echo csrf_field(); ?>

                            <div class="row g-16">
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Medical Type<span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="medical_type">
                                            <option value="" selected disabled>Select an option</option>
                                            <?php $__currentLoopData = medicalType() ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php echo e(old('medical_type') === $index ? 'selected' : ''); ?> value="<?php echo e($index); ?>"><?php echo e($country); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Passport Number <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="passport_number" placeholder="Passport Number" value="<?php echo e(old('passport_number')); ?>">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Gender<span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="gender">
                                            <option value="" selected disabled>Select an option</option>
                                            <option <?php echo e(old('gender') === 'male' ? 'selected' : ''); ?> value="male">Male</option>
                                            <option <?php echo e(old('gender') === 'female' ? 'selected' : ''); ?> value="female">Female</option>
                                            <option <?php echo e(old('gender') === 'other' ? 'selected' : ''); ?> value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Traveling To<span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="traveling_to">
                                            <option value="" selected disabled>Select an option</option>
                                            <?php $__currentLoopData = countryList() ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php echo e(old('traveling_to') === $index ? 'selected' : ''); ?> value="<?php echo e($index); ?>"><?php echo e($country); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Marital Status</label>
                                        <select class="select2" name="marital_status">
                                            <option value="" selected disabled>Select an option</option>
                                            <option <?php echo e(old('marital_status') === 'unmarried' ? 'selected' : ''); ?> value="unmarried">Unmarried</option>
                                            <option <?php echo e(old('marital_status') === 'married' ? 'selected' : ''); ?> value="married">Married</option>
                                            <option <?php echo e(old('marital_status') === 'other' ? 'selected' : ''); ?> value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Center Name<span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="center_name">
                                            <option value="" selected disabled>Select an option</option>
                                            <?php $__currentLoopData = centerList() ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $center): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $center = explode(' - ', $center)[0];
                                                ?>
                                                <option <?php echo e(old('center_name') === $index ? 'selected' : ''); ?> value="<?php echo e($index); ?>"><?php echo e($center); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Surname <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="surname" placeholder="Surname" value="<?php echo e(old('surname')); ?>">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Given Name <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="given_name" placeholder="Given Name" value="<?php echo e(old('given_name')); ?>">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Father Name</label>
                                        <input class="form-control input" type="text" name="father_name" placeholder="Father Name" value="<?php echo e(old('father_name')); ?>">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Mother Name</label>
                                        <input class="form-control input" type="text" name="mother_name" placeholder="Mother Name" value="<?php echo e(old('mother_name')); ?>">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Religion</label>
                                        <select class="select2" name="religion">
                                            <option value="" selected disabled>Select an option</option>
                                            <?php $__currentLoopData = religionList() ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $religion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php echo e(old('religion') === $index ? 'selected' : ''); ?> value="<?php echo e($index); ?>"><?php echo e($religion); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">PP Issue Place<span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="pp_issue_place">
                                            <option value="" selected disabled>Select an option</option>
                                            <?php $__currentLoopData = ppIssuePlaceList() ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ppIssuePlace): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php echo e(old('pp_issue_place') === $index ? 'selected' : ''); ?> value="<?php echo e($index); ?>"><?php echo e($ppIssuePlace); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Profession<span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="profession">
                                            <option value="" selected disabled>Select an option</option>
                                            <?php $__currentLoopData = ProfessionList() ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php echo e(old('profession') === $index ? 'selected' : ''); ?> value="<?php echo e($index); ?>"><?php echo e($item); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Nationality<span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="nationality">
                                            <option value="" selected disabled>Select an option</option>
                                            <?php $__currentLoopData = nationality() ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php echo e(old('nationality') === $index ? 'selected' : ''); ?> value="<?php echo e($index); ?>"><?php echo e($item); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Date of Birth <small>(Day-Month-Year)</small></label>





                                        <div class="d-flex justify-content-between date-pic-icon">
                                            <input type="text" class="input single-date-picker"
                                                   placeholder="Choose Date" name="date_of_birth" value="<?php echo e(old('date_of_birth')); ?>">
                                            <span> <b class="caret"></b></span>
                                            <i class="ri-calendar-line"></i>
                                        </div>
                                    </div>
                                </div>






                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">NID No</label>
                                        <input class="form-control input" type="number" name="nid_no" placeholder="NID Number" value="<?php echo e(old('nid_no')); ?>">
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Passport Issue Date <small>(Day-Month-Year)</small></label>





                                        <div class="d-flex justify-content-between date-pic-icon">
                                            <input type="text" class="input single-date-picker"
                                                   placeholder="Choose Date" name="passport_issue_date" value="<?php echo e(old('passport_issue_date')); ?>">
                                            <span> <b class="caret"></b></span>
                                            <i class="ri-calendar-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Passport Expiry Date <small>(Day-Month-Year)</small></label>





                                        <div class="d-flex justify-content-between date-pic-icon">
                                            <input type="text" class="input single-date-picker"
                                                   placeholder="Choose Date" name="passport_expiry_date" value="<?php echo e(old('passport_expiry_date')); ?>">
                                            <span> <b class="caret"></b></span>
                                            <i class="ri-calendar-line"></i>
                                        </div>
                                    </div>
                                </div>










                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Ref No<span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="ref_no" placeholder="Ref Number" value="<?php echo e(old('ref_no')); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-16 mt-25">
                                <div class="contact-form d-flex justify-content-end gap-10">
                                    <button class="btn-primary-fill" type="submit">Submit</button>
                                    <button class="btn-danger-fill" type="reset">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
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

            $('input[name="date_of_birth"]').val(`<?php echo e(old('date_of_birth')); ?>`);
            $('input[name="passport_issue_date"]').val(`<?php echo e(old('passport_issue_date')); ?>`);
            $('input[name="passport_expiry_date"]').val(`<?php echo e(old('passport_expiry_date')); ?>`);
        });

        $(document).ready(function() {
            // Define function to show modal
            function showNoticeModal() {
                // $('#notice-modal').show(); // Show the modal
                localStorage.setItem('lastShownTime', new Date().getTime()); // Update last shown time
            }

            // Check if modal was shown in the last hour
            function shouldShowModal() {
                const lastShownTime = localStorage.getItem('lastShownTime');
                const oneHour = 10 * 60 * 1000; // One hour in milliseconds
                return !lastShownTime || (new Date().getTime() - lastShownTime) > oneHour;
            }

            // Initialize and show modal if needed
            if (shouldShowModal()) {
                let showNotice = `<?php echo e($showNotice); ?>`;

                if (showNotice) {
                    showNoticeModal();
                }
            }

            // Hide modal when close button is clicked or clicked outside of the modal
            $('#notice-modal .btn-close').click(function() {
                $('#notice-modal').slideUp(1000);
            });

            $('#notice-modal').click(function(e) {
                if (e.target === this) {
                    $('#notice-modal').slideUp(1000);
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.common-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Herd\marjsafin\resources\views/user/user-registration.blade.php ENDPATH**/ ?>