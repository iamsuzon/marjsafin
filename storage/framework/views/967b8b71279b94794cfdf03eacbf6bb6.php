<?php $__env->startSection('title', 'Card Manage'); ?>

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
                    'name' => 'Book Appointment Link Register',
                    'route' => route('user.appointment.booking.registration'),
                    'active' => false,
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
                    'active' => true,
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
                    'active' => false,
                    'has_permission' => hasLinkPermission()
                ],
                [
                    'name' => 'ri-bank-card-line',
                    'is_icon' => true,
                    'route' => route('user.card.manage'),
                    'active' => true,
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
                        <h2 class="manage__title mb-25">Card Management</h2>

                        <?php if(session('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> <?php echo e(session('success')); ?> To view the details <a
                                    class="text-primary fw-bold" href="<?php echo e(session('url')); ?>">click here</a>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if(session('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Request Failed!</strong> <?php echo e(session('error')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
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

                        <div class="alert alert-info">
                            <p>For security reasons, this field will not display any data after the card is added.</p>
                        </div>

                        <form action="javascript:void(0)" method="POST" id="card-manage-form">

                            <div class="row g-16">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Card Holder Name <span
                                                class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="card_holder_name"
                                               id="card_holder_name"
                                               value="<?php echo e($card_holder_name); ?>"
                                               placeholder="Card Holder Name">
                                    </div>
                                    <div id="invalid_card_holder_name" class="invalid-feedback" style="display:none;color: red;"></div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Card Number <span
                                                class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="card_number"
                                               id="card_number"
                                               value="<?php echo e($card_number); ?>"
                                               placeholder="Card Number">
                                    </div>
                                    <div id="invalid_card_number" class="invalid-feedback" style="display:none;color: red;"></div>
                                </div>
                            </div>

                            <div class="row g-16 mt-3">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Expiry Date <span
                                                class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="expiry_date"
                                               id="expiry_date"
                                               placeholder="MMYY">
                                    </div>
                                    <div id="invalid_expiry_date" class="invalid-feedback" style="display:none;color: red;"></div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">CVC or CVV <span
                                                class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="card_security_code"
                                               id="card_security_code"
                                               placeholder="CVC or CVV">
                                    </div>
                                    <div id="invalid_cvv" class="invalid-feedback" style="display:none;color: red;"></div>
                                </div>
                            </div>

                            <div class="row g-16 mt-25">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-check">
                                        <input type="checkbox" name="confirm" id="acknowledge" class="form-check-input">
                                        <label for="acknowledge" class="form-check-label confirm-label">I acknowledge
                                            that the information I have provided in this form is complete, true, and
                                            accurate</label>
                                    </div>
                                </div>

                                <div class="row g-16 mt-25">
                                    <div class="contact-form d-flex justify-content-end gap-10">
                                        <button class="btn-danger-fill" type="reset">Cancel</button>
                                        <button class="btn-primary-fill" type="submit">Submit And Continue</button>
                                    </div>
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
            $('input[name="expiry_date"]').on('input paste', function () {
                let el = $(this);
                let value = el.val();

                value = value.replace(/\D/g, '');

                if (value.length >= 4) {
                    value = value.substring(0, 2) + '/' + value.substring(2, 4);
                }

                el.val(value);
            });

            $('input[name="expiry_date"]').on('keypress', function (e) {
                let el = $(this);
                let char = String.fromCharCode(e.which);
                let currentValue = el.val();

                if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
                    // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                    (e.keyCode === 65 && e.ctrlKey === true) ||
                    (e.keyCode === 67 && e.ctrlKey === true) ||
                    (e.keyCode === 86 && e.ctrlKey === true) ||
                    (e.keyCode === 88 && e.ctrlKey === true)) {
                    return;
                }

                if (char === '/' && currentValue.length === 2) {
                    return;
                }

                if (!/[0-9]/.test(char)) {
                    e.preventDefault();
                }
            });
        });
    </script>

    <script>
        (function () {
            // Track whether the user is deleting
            let isDeleting = false;
            // handle validation for card number
            const bins = {
                mada: '440647|440795|446404|457865|968208|457997|474491|636120|417633|468540|468541|468542|468543|968201|446393|409201|458456|484783|462220|455708|410621|455036|486094|486095|486096|504300|440533|489318|489319|445564|968211|410685|406996|432328|428671|428672|428673|968206|446672|543357|434107|407197|407395|412565|431361|604906|521076|529415|535825|543085|524130|554180|549760|968209|524514|529741|537767|535989|536023|513213|520058|558563|588982|589005|531095|530906|532013|968204|422817|422818|422819|428331|483010|483011|483012|589206|968207|419593|439954|530060|531196|420132',
            }
            const apsPaymentErrors = {
                invalid_card: 'Invalid Card',
                invalid_card_number: 'Invalid Card Number',
                invalid_card_length: 'Invalid Card Length',
                invalid_card_holder_name: 'Invalid Card Holder Name',
                invalid_card_cvv: 'Invalid Card CVV',
                invalid_expiry_date: 'Invalid Expiry Date',
            };
            const formElement = $('form').filter(function () {
                return this.id.match("card-manage-form");
            });
            let APSValidation = {
                validateCard: function (card_number) {
                    let card_type = "";
                    let card_validity = true;
                    let message = '';
                    let card_length = 0;

                    if (card_number) {
                        card_number = card_number.replace(/ /g, '').replace(/-/g, '');
                        // Visa
                        let visa_regex = new RegExp('^4[0-9]{0,15}$');
                        // MasterCard
                        let mastercard_regex = new RegExp('^5$|^5[0-5][0-9]{0,16}$');
                        //mada
                        let mada_regex = new RegExp('/^' + bins.mada + '/', 'm');

                        if (card_number.match(mada_regex)) {
                            card_type = 'mada';
                            card_length = 16;
                        } else if (card_number.match(visa_regex)) {
                            card_type = 'visa';
                            card_length = 16;
                        } else if (card_number.match(mastercard_regex)) {
                            card_type = 'mastercard';
                            card_length = 16;
                        } else {
                            card_validity = false;
                            message = apsPaymentErrors.invalid_card;
                        }

                        if (card_number.length < 16) {
                            card_validity = false;
                            message = apsPaymentErrors.invalid_card_length;
                        } else {
                            const {validity, msg} = APSValidation.validateCardNumber(card_number);
                            if (!validity) {
                                card_validity = false;
                                message = msg;
                            }
                        }
                    } else {
                        message = apsPaymentErrors.card_empty;
                        card_validity = false;
                    }
                    return {
                        card_type,
                        validity: card_validity,
                        msg: message,
                        card_length
                    }
                },
                validateCardNumberByAlgorithm: function (card_number) {
                    let checksum = 0; // running checksum total
                    let j = 1; // takes value of 1 or 2
                    // Process each digit one by one starting from the last
                    for (let i = card_number.length - 1; i >= 0; i--) {
                        let calc = 0;
                        // Extract the next digit and multiply by 1 or 2 on alternative digits.
                        calc = Number(card_number.charAt(i)) * j;
                        // If the result is in two digits add 1 to the checksum total
                        if (calc > 9) {
                            checksum = checksum + 1;
                            calc = calc - 10;
                        }
                        // Add the units element to the checksum total
                        checksum = checksum + calc;
                        // Switch the value of j
                        if (j === 1) {
                            j = 2;
                        } else {
                            j = 1;
                        }
                    }
                    //Check if it is divisible by 10 or not.
                    return (checksum % 10) === 0;
                },
                validateCardNumber: function (card_number) {
                    //Check if the number contains only numeric value
                    //and is of between 13 to 19 digits
                    let validity = false;
                    const regex = new RegExp("^[0-9]{13,19}$");
                    if (!regex.test(card_number)) {
                        if (!/^\d+$/.test(card_number)) {
                            return {
                                validity,
                                msg: apsPaymentErrors.invalid_card
                            }
                        } else {
                            return {
                                validity,
                                msg: apsPaymentErrors.invalid_card
                            }
                        }
                    }
                    validity = APSValidation.validateCardNumberByAlgorithm(card_number)
                    return {
                        validity,
                        msg: validity ? '' : apsPaymentErrors.invalid_card
                    };
                },
                validateHolderName: function (card_holder_name) {
                    let validity = true;
                    let message = '';
                    card_holder_name = card_holder_name.trim();
                    if (card_holder_name.length > 255 || card_holder_name.length === 0) {
                        validity = false;
                        message = apsPaymentErrors.invalid_card_holder_name;
                    }
                    return {
                        validity,
                        msg: message
                    }
                },
                validateCVV: function (card_cvv) {
                    let validity = false;
                    let message = apsPaymentErrors.invalid_card_cvv;
                    const regex = new RegExp("^[0-9]{1,3}$");
                    if (!regex.test(card_cvv)) {
                        return {
                            validity,
                            msg: message
                        };
                    }
                    if (card_cvv.length > 0) {
                        card_cvv = card_cvv.trim();
                        if (card_cvv.length === 3 && card_cvv !== '000') {
                            validity = true;
                            message = '';
                        } else if (card_cvv.length === 4 && card_cvv !== '000') {
                            validity = true;
                            message = '';
                        }

                    }
                    return {
                        validity,
                        msg: message
                    }
                },
                validateExpiryDate: function (card_expiry_date) {
                    let validity = false;
                    const expiryDate = card_expiry_date.trim();

                    // Check if the format is MM/YY
                    const regex = /^((0[1-9])|(1[0-2]))\/(\d{2})$/;
                    const match = expiryDate.match(regex);

                    if (match) {
                        // Extract month and year from the input
                        const month = parseInt(match[1], 10);  // First group is the month
                        const year = parseInt(match[4], 10);   // Third group is the year
                        // Get the current date
                        const currentDate = new Date();
                        const currentMonth = currentDate.getMonth() + 1; // Get current month (1-12)
                        const currentYear = currentDate.getFullYear() % 100; // Get last two digits of the year (00-99)

                        // Check if the expiry date is valid (month between 01-12 and the year is in the future or greater than current month/year)
                        if (year > currentYear || (year === currentYear && month >= currentMonth)) {
                            validity = true;
                        }
                    }

                    return {
                        validity,
                        msg: validity ? '' : apsPaymentErrors.invalid_expiry_date
                    };
                }
            };

            formElement.on(
                'submit',
                function (e) {
                    const holder_name_validate = validateField(document.getElementById('card_holder_name'), APSValidation.validateHolderName, "invalid_card_holder_name");
                    const card_number_validate = validateField(document.getElementById('card_number'), APSValidation.validateCard, "invalid_card_number");
                    const expiry_date_validate = validateField(document.getElementById('expiry_date'), APSValidation.validateExpiryDate, "invalid_expiry_date");
                    const cvv_validate = validateField(document.getElementById('card_security_code'), APSValidation.validateCVV, "invalid_cvv");
                    // Stop form submission if any validation fails
                    if (!(holder_name_validate && card_number_validate && expiry_date_validate && cvv_validate)) {
                        e.preventDefault();
                        return false;
                    }
                    var expiryDateInput = document.getElementById('expiry_date');
                    if (expiryDateInput && expiryDateInput.value.indexOf('/') !== -1) {
                        var expiryDateParts = expiryDateInput.value.split('/');
                        if (expiryDateParts.length === 2) {
                            var formattedExpiryDate = expiryDateParts[1] + expiryDateParts[0];
                            expiryDateInput.value = formattedExpiryDate;
                        }
                    }

                    let cardHolderName = $('input[name=card_holder_name]');
                    let cardNumber = $('input[name=card_number]');
                    let cardCvc = $('input[name=card_security_code]');
                    let cardExpiryDate = $('input[name=expiry_date]');
                    let checkedTermsAndCondition = $('input[name=confirm]');

                    if (! checkedTermsAndCondition.is(':checked')) {
                        e.preventDefault();
                        toastError('Kindly confirm the terms and condition')
                        return false;
                    }

                    $.ajax({
                        url: `<?php echo e(route('user.card.manage')); ?>`,
                        type: 'POST',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>',
                            card_holder_name: cardHolderName.val(),
                            card_number: cardNumber.val(),
                            card_security_code: cardCvc.val(),
                            card_expiry_date: cardExpiryDate.val(),
                        },
                        success: function (response) {
                            if (response.status) {
                                toastSuccess(response.message);
                                $('form#card-manage-form').trigger("reset");
                                $('input[name=card_holder_name]').val(response.card_holder_name);
                            } else {
                                toastError(response.message);
                            }
                        },
                        error: function (errors) {
                            toastError('Card Add Failed')
                        }
                    });
                });

            function validateField(inputElement, validationFn, errorMsgElementId) {
                const {validity, msg} = validationFn(inputElement.value);

                if (!validity) {
                    $(`#${errorMsgElementId}`).text(msg).show();
                    $(inputElement).attr('style', 'border-color: red !important');
                } else {
                    $(`#${errorMsgElementId}`).hide();
                    $(inputElement).css('border-color', '');

                }
                return validity;
            }

            $("#card_holder_name").on("blur", function () {
                validateField(this, APSValidation.validateHolderName, "invalid_card_holder_name");
            });
            $("#expiry_date").on("blur", function () {
                validateField(this, APSValidation.validateExpiryDate, "invalid_expiry_date");
            });
            $("#card_number").on("blur", function () {
                validateField(this, APSValidation.validateCard, "invalid_card_number");
            });
            $("#card_security_code").on("blur", function () {
                validateField(this, APSValidation.validateCVV, "invalid_cvv");
            });
            $("#card_number").on("input", function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
            $("#card_security_code").on("input", function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        })();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.common-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Herd\marjsafin\resources\views/user/card-manage/index.blade.php ENDPATH**/ ?>