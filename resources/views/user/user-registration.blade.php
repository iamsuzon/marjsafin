@extends('user.layout.common-master')
@section('title', 'Medical Registration')

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
        #notice-modal {
            z-index: 99999;
        }
    </style>
@endsection

@section('contents')
    <div class="page-content">
        <div class="container-fluid">
            <x-page-tabs title="Registration" :links="[
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
            ]" />

            @if(hasMedicalPermission())
                <div class="card">
                <div class="row">
                    <div class="col-12">
                        <h2 class="manage__title mb-25">Medical</h2>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> {{ session('success') }} To view the details <a class="text-primary fw-bold" href="{{route('user.application.list')}}">click here</a>
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

                        <form action="{{route('user.registration')}}" method="POST" id="registration-form">
                            @csrf

                            <div class="row g-16">
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Medical Type<span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="medical_type">
                                            <option value="" selected disabled>Select an option</option>
                                            @foreach(medicalType() ?? [] as $index => $country)
                                                <option {{old('medical_type') === $index ? 'selected' : ''}} value="{{ $index }}">{{ $country }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Passport Number <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="passport_number" placeholder="Passport Number" value="{{old('passport_number')}}">
                                    </div>
                                </div>
{{--                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">--}}
{{--                                    <div class="contact-form">--}}
{{--                                        <label class="contact-label">Gender<span class="fillable mx-1">*</span></label>--}}
{{--                                        <select class="select2" name="gender">--}}
{{--                                            <option value="" selected disabled>Select an option</option>--}}
{{--                                            <option {{old('gender') === 'male' ? 'selected' : ''}} value="male">Male</option>--}}
{{--                                            <option {{old('gender') === 'female' ? 'selected' : ''}} value="female">Female</option>--}}
{{--                                            <option {{old('gender') === 'other' ? 'selected' : ''}} value="other">Other</option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Traveling To<span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="traveling_to">
                                            <option value="" selected disabled>Select an option</option>
                                            @foreach(countryList() ?? [] as $index => $country)
                                                <option {{old('traveling_to') === $index ? 'selected' : ''}} value="{{ $index }}">{{ $country }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
{{--                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">--}}
{{--                                    <div class="contact-form">--}}
{{--                                        <label class="contact-label">Marital Status</label>--}}
{{--                                        <select class="select2" name="marital_status">--}}
{{--                                            <option value="" selected disabled>Select an option</option>--}}
{{--                                            <option {{old('marital_status') === 'unmarried' ? 'selected' : ''}} value="unmarried">Unmarried</option>--}}
{{--                                            <option {{old('marital_status') === 'married' ? 'selected' : ''}} value="married">Married</option>--}}
{{--                                            <option {{old('marital_status') === 'other' ? 'selected' : ''}} value="other">Other</option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Center Name<span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="center_name">
                                            <option value="" selected disabled>Select an option</option>
                                            @foreach(centerList() ?? [] as $index => $center)
                                                @php
                                                    $center = explode(' - ', $center)[0];
                                                @endphp
                                                <option {{old('center_name') === $index ? 'selected' : ''}} value="{{ $index }}">{{ $center }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Surname <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="surname" placeholder="Surname" value="{{old('surname')}}">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Given Name <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="given_name" placeholder="Given Name" value="{{old('given_name')}}">
                                    </div>
                                </div>
{{--                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">--}}
{{--                                    <div class="contact-form">--}}
{{--                                        <label class="contact-label">Father Name</label>--}}
{{--                                        <input class="form-control input" type="text" name="father_name" placeholder="Father Name" value="{{old('father_name')}}">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">--}}
{{--                                    <div class="contact-form">--}}
{{--                                        <label class="contact-label">Mother Name</label>--}}
{{--                                        <input class="form-control input" type="text" name="mother_name" placeholder="Mother Name" value="{{old('mother_name')}}">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">--}}
{{--                                    <div class="contact-form">--}}
{{--                                        <label class="contact-label">Religion</label>--}}
{{--                                        <select class="select2" name="religion">--}}
{{--                                            <option value="" selected disabled>Select an option</option>--}}
{{--                                            @foreach(religionList() ?? [] as $index => $religion)--}}
{{--                                                <option {{old('religion') === $index ? 'selected' : ''}} value="{{ $index }}">{{ $religion }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">--}}
{{--                                    <div class="contact-form">--}}
{{--                                        <label class="contact-label">PP Issue Place<span class="fillable mx-1">*</span></label>--}}
{{--                                        <select class="select2" name="pp_issue_place">--}}
{{--                                            <option value="" selected disabled>Select an option</option>--}}
{{--                                            @foreach(ppIssuePlaceList() ?? [] as $index => $ppIssuePlace)--}}
{{--                                                <option {{old('pp_issue_place') === $index ? 'selected' : ''}} value="{{ $index }}">{{ $ppIssuePlace }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">--}}
{{--                                    <div class="contact-form">--}}
{{--                                        <label class="contact-label">Profession<span class="fillable mx-1">*</span></label>--}}
{{--                                        <select class="select2" name="profession">--}}
{{--                                            <option value="" selected disabled>Select an option</option>--}}
{{--                                            @foreach(ProfessionList() ?? [] as $index => $item)--}}
{{--                                                <option {{old('profession') === $index ? 'selected' : ''}} value="{{ $index }}">{{ $item }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">--}}
{{--                                    <div class="contact-form">--}}
{{--                                        <label class="contact-label">Nationality<span class="fillable mx-1">*</span></label>--}}
{{--                                        <select class="select2" name="nationality">--}}
{{--                                            <option value="" selected disabled>Select an option</option>--}}
{{--                                            @foreach(nationality() ?? [] as $index => $item)--}}
{{--                                                <option {{old('nationality') === $index ? 'selected' : ''}} value="{{ $index }}">{{ $item }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">--}}
{{--                                    <div class="contact-form">--}}
{{--                                        <label class="contact-label">Date of Birth <small>(Day-Month-Year)</small></label>--}}
{{--                                        <div class="d-flex justify-content-between">--}}
{{--                                            <input type="date" class="contact-input"--}}
{{--                                                   placeholder="{{old('date_of_birth') ?? 'Choose Date'}}" name="date_of_birth" value="{{old('date_of_birth')}}">--}}
{{--                                            <span> <b class="caret"></b></span>--}}
{{--                                        </div>--}}
{{--                                        <div class="d-flex justify-content-between date-pic-icon">--}}
{{--                                            <input type="text" class="input single-date-picker"--}}
{{--                                                   placeholder="Choose Date" name="date_of_birth" value="{{old('date_of_birth')}}">--}}
{{--                                            <span> <b class="caret"></b></span>--}}
{{--                                            <i class="ri-calendar-line"></i>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">--}}
{{--                                    <div class="contact-form">--}}
{{--                                        <label class="contact-label">Contact No<span class="fillable mx-1">*</span></label>--}}
{{--                                        <input class="form-control input" type="text" name="contact_no" placeholder="Passport Number" value="{{old('contact_no')}}">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">NID No</label>
                                        <input class="form-control input" type="number" name="nid_no" placeholder="NID Number" value="{{old('nid_no')}}">
                                    </div>
                                </div>

{{--                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">--}}
{{--                                    <div class="contact-form">--}}
{{--                                        <label class="contact-label">Passport Issue Date <small>(Day-Month-Year)</small></label>--}}
{{--                                        <div class="d-flex justify-content-between">--}}
{{--                                            <input type="date" class="contact-input"--}}
{{--                                                   placeholder="{{old('passport_issue_date') ?? 'Choose Date'}}" name="passport_issue_date" value="{{old('passport_issue_date')}}">--}}
{{--                                            <span> <b class="caret"></b></span>--}}
{{--                                        </div>--}}
{{--                                        <div class="d-flex justify-content-between date-pic-icon">--}}
{{--                                            <input type="text" class="input single-date-picker"--}}
{{--                                                   placeholder="Choose Date" name="passport_issue_date" value="{{old('passport_issue_date')}}">--}}
{{--                                            <span> <b class="caret"></b></span>--}}
{{--                                            <i class="ri-calendar-line"></i>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">--}}
{{--                                    <div class="contact-form">--}}
{{--                                        <label class="contact-label">Passport Expiry Date <small>(Day-Month-Year)</small></label>--}}
{{--                                        <div class="d-flex justify-content-between">--}}
{{--                                            <input type="date" class="contact-input"--}}
{{--                                                   placeholder="{{old('passport_expiry_date') ?? 'Choose Date'}}" name="passport_expiry_date" value="{{old('passport_expiry_date')}}">--}}
{{--                                            <span> <b class="caret"></b></span>--}}
{{--                                        </div>--}}
{{--                                        <div class="d-flex justify-content-between date-pic-icon">--}}
{{--                                            <input type="text" class="input single-date-picker"--}}
{{--                                                   placeholder="Choose Date" name="passport_expiry_date" value="{{old('passport_expiry_date')}}">--}}
{{--                                            <span> <b class="caret"></b></span>--}}
{{--                                            <i class="ri-calendar-line"></i>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">--}}
{{--                                    <div class="contact-form">--}}
{{--                                        <label class="contact-label">Problems<span class="fillable mx-1">*</span></label>--}}
{{--                                        <select class="multiple-select"  multiple="multiple" name="problem[]">--}}
{{--                                            @foreach(problemList() ?? [] as $index => $problem)--}}
{{--                                                <option {{old('problem') === $index ? 'selected' : ''}} value="{{ $index }}">{{ $problem }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Ref No<span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="ref_no" placeholder="Ref Number" value="{{old('ref_no')}}">
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
            @endif
        </div>
    </div>

{{--  notice moda  l--}}
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
                            <p class="lh-lg">{!! $noticeText !!}</p>
                        </div>
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

            $('input[name="date_of_birth"]').val(`{{old('date_of_birth')}}`);
            $('input[name="passport_issue_date"]').val(`{{old('passport_issue_date')}}`);
            $('input[name="passport_expiry_date"]').val(`{{old('passport_expiry_date')}}`);
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
                let showNotice = `{{$showNotice}}`;

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
@endsection
