<?php $__env->startSection('title', 'Book Appointment'); ?>

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
        .confirm-label {
            font-size: 14px;
            color: #000;
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
                    'name' => 'Book Appointment Link List',
                    'route' => route('user.appointment.booking.list'),
                    'active' => true,
                    'has_permission' => hasLinkPermission()
                ],
                [
                    'name' => 'Complete List',
                    'route' => route('user.appointment.booking.list.complete'),
                    'active' => false,
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
                    'name' => 'Book Appointment Link List',
                    'route' => route('user.appointment.booking.list'),
                    'active' => true,
                    'has_permission' => hasLinkPermission()
                ],
                [
                    'name' => 'Complete List',
                    'route' => route('user.appointment.booking.list.complete'),
                    'active' => false,
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
                        <h2 class="manage__title mb-25">Appointment Booking</h2>

                        <?php if(session('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> <?php echo e(session('success')); ?> To view the details <a class="text-primary fw-bold" href="<?php echo e(session('url')); ?>">click here</a>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if(session('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Request Failed!</strong> <?php echo e(session('error')); ?>

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

                        <form action="<?php echo e(route('user.appointment.booking.registration')); ?>" method="POST" id="link-registration-form">
                            <?php echo csrf_field(); ?>

                            <div class="row g-16">
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Link Type <span class="fillable mx-1">*</span></label>
                                        <select class="form-control" name="type">
                                            <option value="" selected disabled>Select Link Type</option>
                                            <?php $__currentLoopData = $booking_types ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($index); ?>" <?php echo e(old('type') === $index ? 'selected' : ''); ?>><?php echo e($type); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Reference <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="reference" placeholder="Reference Name" value="<?php echo e(old('reference')); ?>">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Country <span class="fillable mx-1">*</span></label>
                                        <select class="form-control" name="country" disabled>
                                            <?php $__currentLoopData = $origin_country ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option selected value="<?php echo e($index); ?>"><?php echo e($country); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">City <span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="city">
                                            <option value="" selected disabled>Select your city</option>
                                            <?php $__currentLoopData = $origin_city ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($index); ?>"><?php echo e($city); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Country Traveling To <span class="fillable mx-1">*</span></label>
                                        <select class="form-control" name="country_traveling_to">
                                            <option value="" >Select your traveling country</option>
                                            <?php $__currentLoopData = $traveling_country ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $tc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($index); ?>" <?php echo e(($index === 'SA' || old('country_traveling_to') === $index) ? 'selected' : ''); ?>><?php echo e($tc); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Center Name <span class="fillable mx-1">*</span></label>
                                        <select class="form-control center select2" name="center">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">First Name <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="first_name" placeholder="First Name" value="<?php echo e(old('first_name')); ?>">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Last Name <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="last_name" placeholder="Last Name" value="<?php echo e(old('last_name')); ?>">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Date of Birth <small>(Day-Month-Year)</small><span class="fillable mx-1">*</span></label>

                                        <div class="d-flex justify-content-between date-pic-icon">
                                            <input type="text" class="input single-date-picker dob-date-picker"
                                                   placeholder="Select Date" name="dob" value="<?php echo e(old('dob')); ?>">
                                            <span> <b class="caret"></b></span>
                                            <i class="ri-calendar-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Gender <span class="fillable mx-1">*</span></label>
                                        <select class="form-control" name="gender">
                                            <option value="" selected disabled>Select Your Gender</option>
                                            <?php $__currentLoopData = $gender; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($index); ?>" <?php echo e(old('gender') === $index ? 'selected' : ''); ?>><?php echo e($item); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Marital Status <span class="fillable mx-1">*</span></label>
                                        <select class="form-control" name="marital_status">
                                            <option value="" selected disabled>Select an option</option>
                                            <?php $__currentLoopData = $marital_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($index); ?>" <?php echo e(old('marital_status') === $index ? 'selected' : ''); ?>><?php echo e($item); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Passport Number <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="passport_number" id="passport_number" placeholder="Passport Number" value="<?php echo e(old('passport_number')); ?>">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Confirm Passport Number <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="confirm_passport_number" id="confirm_passport_number" placeholder="Confirm Passport Number" value="<?php echo e(old('confirm_passport_number')); ?>">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Passport Issue Date <small>(Day-Month-Year)</small><span class="fillable mx-1">*</span></label>

                                        <div class="d-flex justify-content-between date-pic-icon">
                                            <input type="text" class="input single-date-picker passport-issue-date-picker"
                                                   placeholder="Select Date" name="passport_issue_date" value="<?php echo e(old('passport_issue_date')); ?>">
                                            <span> <b class="caret"></b></span>
                                            <i class="ri-calendar-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Passport Issue Place <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="passport_issue_place" placeholder="Passport Issue Place" value="Dhaka">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Passport Expiry Validity<span class="fillable mx-1">*</span></label>

                                        <select class="form-control" name="passport_expiry_date">
                                            <option value="" selected disabled>Select an option</option>
                                            <?php $__currentLoopData = [5,10]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($year); ?>" <?php echo e($year === (int) old('passport_expiry_date') ? 'selected' : ''); ?>><?php echo e($year); ?> Years</option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Visa Type</label>
                                        <select class="form-control" name="visa_type">
                                            <option value="" selected disabled>Select Visa Type</option>
                                            <?php $__currentLoopData = $visa_type ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php echo e(($index === 'wv' || old('visa_type') === $index) ? 'selected' : ''); ?> value="<?php echo e($index); ?>"><?php echo e($value); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Email <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="email" name="email" placeholder="your@name.com" value="<?php echo e(old('email')); ?>">
                                    </div>
                                </div> -->
                                <!-- <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Phone Number</label>
                                        <input class="form-control input" type="text" name="phone_number" placeholder="Your Phone Number" value="<?php echo e(old('phone_number')); ?>">
                                    </div>
                                </div> -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">National ID</label>
                                        <input class="form-control input" type="text" name="nid_number" placeholder="Your NID Number" value="<?php echo e(old('nid_number')); ?>">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Applied Position</label>
                                        <select class="select2" name="applied_position">
                                            <option value="" selected disabled>Select an option</option>
                                            <?php $__currentLoopData = $applied_position ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($index); ?>"><?php echo e($value); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-16 mt-25">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-check">
                                    <input type="checkbox" name="confirm" id="acknowledge" class="form-check-input">
                                    <label for="acknowledge" class="form-check-label confirm-label">I acknowledge that the information I have provided in this form is complete, true, and accurate</label>
                                </div>
                            </div>

                            <div class="row g-16 mt-25">
                                <div class="contact-form d-flex justify-content-end gap-10">
                                    <button class="btn-danger-fill" type="reset">Cancel</button>
                                    <button class="btn-primary-fill" type="submit">Submit And Continue</button>
                                </div>
                            </div>
                        </form>
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
            $('.dob-date-picker').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoApply: false,
                locale: {
                    format: 'DD-MM-YYYY',
                    separator: ' - ',
                }
            });

            $('.dob-date-picker').val("");
            $('.dob-date-picker').val(`<?php echo e(old('dob')); ?>`);
            $('input[name="passport_issue_date"]').val(`<?php echo e(old('passport_issue_date')); ?>`);

            let applied_position = $('select[name="applied_position"]');
            if (`<?php echo e(old('applied_position')); ?>`) {
                applied_position.val(`<?php echo e(old("applied_position")); ?>`).trigger('change');
            } else {
                applied_position.val('31').trigger('change');
            }

            let timeoutId;
            $('input[name=confirm_passport_number]').on('paste', function (e) {
                e.preventDefault();
                timeoutId = setTimeout(() => {
                    clearTimeout(timeoutId);
                    toastError('Pasting is disabled. Please type the passport number manually.');
                }, 800);
            });

            $('#link-registration-form').on('submit', function (e) {
                let passport = $('#passport_number').val();
                let confirm = $('#confirm_passport_number').val();

                if (passport === '' || confirm === '') {
                    e.preventDefault();
                    toastError('Enter your passport number.');
                    return;
                }

                if (passport !== '' && confirm !== '') {
                    if (passport.trim() !== confirm.trim()) {
                        e.preventDefault();
                        toastError('Passport numbers do not match!');
                        return;
                    }
                }

                let is_checked = $('#acknowledge');
                if (! is_checked.is(':checked') ) {
                    e.preventDefault();
                    toastError('Please acknowledge the information provided is complete, true, and accurate.');
                    return;
                }
            });

            $(document).on('change', 'select[name=city]', function () {
                let el = $(this);
                let city_code = el.val();

                let center_collection = <?php echo json_encode($center_list, 15, 512) ?>;
                let center = center_collection[city_code];

                let $centerSelect = $('select.center');
                $centerSelect.empty();

                if (center && center.list) {
                    $.each(center.list, function (index, item) {
                        let newOption = new Option(item, index, false, false);
                        $centerSelect.append(newOption);
                    });
                }

                $centerSelect.trigger('change'); // Refresh Select2 or trigger logic
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.common-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Herd\marjsafin\resources\views/user/appointment-booking/registration.blade.php ENDPATH**/ ?>