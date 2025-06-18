@extends('user.layout.common-master')
@section('title', 'Book Appointment')

@section('sidebar')
    @include('user.partials.medical-sidebar')
@endsection

@section('styles')
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
@endsection

@section('contents')
    <div class="page-content">
        <div class="container-fluid">
            <x-page-tabs title="Links" :links="[
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
            ]"/>

            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <h2 class="manage__title mb-25">Appointment Booking</h2>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> {{ session('success') }} To view the details <a class="text-primary fw-bold" href="{{session('url')}}">click here</a>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Request Failed!</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <p><strong>Kindly fill up all the required fields</strong></p>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{route('user.appointment.booking.registration')}}" method="POST" id="link-registration-form">
                            @csrf

                            <div class="row g-16">
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Link Type <span class="fillable mx-1">*</span></label>
                                        <select class="form-control" name="type">
                                            <option value="" selected disabled>Select Link Type</option>
                                            @foreach($booking_types ?? [] as $index => $type)
                                                <option value="{{ $index }}" {{ old('type') === $index ? 'selected' : '' }}>{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Reference <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="reference" placeholder="Reference Name" value="{{old('reference')}}">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Country <span class="fillable mx-1">*</span></label>
                                        <select class="form-control" name="country" disabled>
                                            @foreach($origin_country ?? [] as $index => $country)
                                                <option selected value="{{ $index }}">{{ $country }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">City <span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="city">
                                            <option value="" selected disabled>Select your city</option>
                                            @foreach($origin_city ?? [] as $index => $city)
                                                <option value="{{ $index }}">{{ $city }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Country Traveling To <span class="fillable mx-1">*</span></label>
                                        <select class="form-control" name="country_traveling_to">
                                            <option value="" >Select your traveling country</option>
                                            @foreach($traveling_country ?? [] as $index => $tc)
                                                <option value="{{ $index }}" {{ ($index === 'SA' || old('country_traveling_to') === $index) ? 'selected' : '' }}>{{ $tc }}</option>
                                            @endforeach
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
                                        <input class="form-control input" type="text" name="first_name" placeholder="First Name" value="{{old('first_name')}}">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Last Name <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="last_name" placeholder="Last Name" value="{{old('last_name')}}">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Date of Birth <small>(Day-Month-Year)</small><span class="fillable mx-1">*</span></label>

                                        <div class="d-flex justify-content-between date-pic-icon">
                                            <input type="text" class="input single-date-picker dob-date-picker"
                                                   placeholder="Select Date" name="dob" value="{{old('dob')}}">
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
                                            @foreach($gender as $index => $item)
                                                <option value="{{ $index }}" {{ old('gender') === $index ? 'selected' : '' }}>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Marital Status <span class="fillable mx-1">*</span></label>
                                        <select class="form-control" name="marital_status">
                                            <option value="" selected disabled>Select an option</option>
                                            @foreach($marital_status as $index => $item)
                                                <option value="{{ $index }}" {{ old('marital_status') === $index ? 'selected' : '' }}>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Passport Number <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="passport_number" id="passport_number" placeholder="Passport Number" value="{{old('passport_number')}}">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Confirm Passport Number <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="confirm_passport_number" id="confirm_passport_number" placeholder="Confirm Passport Number" value="{{old('confirm_passport_number')}}">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Passport Issue Date <small>(Day-Month-Year)</small><span class="fillable mx-1">*</span></label>

                                        <div class="d-flex justify-content-between date-pic-icon">
                                            <input type="text" class="input single-date-picker passport-issue-date-picker"
                                                   placeholder="Select Date" name="passport_issue_date" value="{{old('passport_issue_date')}}">
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
                                            @foreach([5,10] as $year)
                                                <option value="{{ $year }}" {{ $year === (int) old('passport_expiry_date') ? 'selected' : '' }}>{{ $year }} Years</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Visa Type</label>
                                        <select class="form-control" name="visa_type">
                                            <option value="" selected disabled>Select Visa Type</option>
                                            @foreach($visa_type ?? [] as $index => $value)
                                                <option {{($index === 'wv' || old('visa_type') === $index) ? 'selected' : ''}} value="{{ $index }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Email <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="email" name="email" placeholder="your@name.com" value="{{old('email')}}">
                                    </div>
                                </div> -->
                                <!-- <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Phone Number</label>
                                        <input class="form-control input" type="text" name="phone_number" placeholder="Your Phone Number" value="{{old('phone_number')}}">
                                    </div>
                                </div> -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">National ID</label>
                                        <input class="form-control input" type="text" name="nid_number" placeholder="Your NID Number" value="{{old('nid_number')}}">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Applied Position</label>
                                        <select class="select2" name="applied_position">
                                            <option value="" selected disabled>Select an option</option>
                                            @foreach($applied_position ?? [] as $index => $value)
                                                <option value="{{ $index }}">{{ $value }}</option>
                                            @endforeach
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
@endsection

@section('scripts')
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
            $('.dob-date-picker').val(`{{old('dob')}}`);
            $('input[name="passport_issue_date"]').val(`{{old('passport_issue_date')}}`);

            let applied_position = $('select[name="applied_position"]');
            if (`{{ old('applied_position') }}`) {
                applied_position.val(`{{ old("applied_position") }}`).trigger('change');
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

                let center_collection = @json($center_list);
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
@endsection
