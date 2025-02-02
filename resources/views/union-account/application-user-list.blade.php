@extends('union-account.layout.user-master')

@section('styles')
    <style>
        .manage__title {
            border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
        }
    </style>
@endsection

@section('contents')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <div class="manage__title d-flex justify-content-between">
                            <h2>Application List ({{$applicationList->count()}})</h2>
                            <a href="{{route('union.user.list')}}" class="btn-primary-fill">
                                <i class="ri-arrow-left-line"></i>
                            </a>
                        </div>

                        <form id="search-form">
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
                                        <input type="text" class="contact-input"
                                               placeholder="Search By Passport Number" name="passport_search">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <div class="contact-form d-flex gap-10">
                                        <button class="btn-primary-fill search_btn_passport" type="submit">Search</button>
                                        <button class="btn-danger-fill reset_btn" type="reset">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row mt-50">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Unsuccessful!</strong> {{ session('error') }}

                            @if(session('url'))
                                আরো স্কোর এর জন্য রিকোয়েস্ট করুন <a href="{{session('url')}}" class="btn btn-primary">Request Score</a>
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

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
                                <th>Score</th>
                                <th>Comment</th>
                                <th>PDF</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($applicationList ?? [] as $item)
                                <tr>
                                    <td class="mw-45 d-flex align-items-center">{{$loop->iteration}}</td>
                                    <td>
                                        <p>Drft: {{$item->created_at->format('d/m/Y')}}</p>
                                        <p>Crte: {{$item->medical_date?->format('d/m/Y')}}</p>
                                    </td>
                                    <td>{{ucfirst($item->medical_type)}}</td>
                                    <td>
                                        <p>{{$item->serial_number}}</p>
                                        <p>{{$item->ems_number}}</p>
                                    </td>
                                    <td>
                                        <p>{{$item->passport_number}}</p>
                                        <p>{{$item->given_name}}</p>
                                        <p>NID: {{$item->nid_no}}</p>
                                    </td>
                                    <td>
                                        <p>{{$item->ref_no}}</p>
                                    </td>
                                    <td>
                                        <p class="text-capitalize">{{$item->gender}}</p>
                                        <p>{{travelingToName($item->traveling_to)}}</p>
                                    </td>
                                    <td>
                                        @php
                                            $center = centerName($item->center_name);
                                            $center_name = explode(' - ', $center)[0] ?? '';
                                            $center_address = explode(' - ', $center)[1] ?? '';
                                        @endphp

                                        <p class="text-capitalize">{{$center_name}}</p>
                                        <p class="text-capitalize">{{$center_address}}</p>
                                    </td>
                                    <td>
                                        @php
                                            $payment_status = $item->applicationPayment?->admin_amount;
                                        @endphp

                                        @if($payment_status)
                                            <p class="mb-10 text-danger">-{{$payment_status}}</p>

                                            @php
                                                $medical_status = $item->medical_status;

                                                $class = 'info';
                                                if ($medical_status == 'new') {
                                                    $class = 'info';
                                                } elseif ($medical_status == 'in-progress' || $medical_status == 'under-review') {
                                                    $class = 'warning';
                                                } elseif ($medical_status == 'fit') {
                                                    $class = 'success';
                                                }
                                            @endphp

                                            @if($item->user->balance > 0)
                                                @if(empty($item->paymentLog))
                                                    <a href="javascript:void(0)" class="pay-bill-btn btn-primary-fill"
                                                       data-id="{{$item->id}}" data-user_id="{{$item->user_id}}">
                                                        <i class="ri-money-dollar-circle-line"></i> Submit Score
                                                    </a>
                                                @else
                                                    <p class="badge bg-{{$class}}">{{getMedicalStatusName($item->medical_status ?? 'new')}}</p>
                                                @endif
                                            @else
                                                @if(!empty($item->paymentLog))
                                                    <p class="badge bg-{{$class}}">{{getMedicalStatusName($item->medical_status ?? 'new')}}</p>
                                                @else
                                                    <a href="{{route('union.user.score.request', $item->user_id)}}"
                                                       class="btn-primary-fill"
                                                       style="background: #ffc107; color: #000">No Score Available</a>
                                                @endif
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->allocatedMedicalCenter?->status)
                                            <p @class([
                                                'text-capitalize',
                                                'text-success' => $item->health_status == 'fit',
                                                'text-danger' => $item->health_status == 'unfit',
                                                'text-warning' => $item->health_status == 'held-up',
                                            ])>{{$item->health_status}}</p>
                                            <p>{{$item->applicationCustomComment?->health_condition}}</p>
                                        @endif
                                    </td>
                                    <td class="text-end px-15">
                                        <a class="view-btn" href="{{route('union.user.generate.pdf', $item->id)}}">
                                            <i class="ri-printer-line"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="update_btn btn btn-primary"
                                           data-bs-target="#update-modal" data-bs-toggle="modal"
                                           data-id="{{$item->id}}" data-passport_number="{{$item->passport_number}}"
                                           data-registration_number="{{$item->serial_number}}" data-medical_date="{{$item->medical_date}}"
                                        >Update</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
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
                        <h6 class="modal-title">Update Application</h6>
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
                                    <label class="contact-label">Health Status</label>
                                    <select class="select2-modal" name="health_status">
                                        <option value="" selected disabled>Select an option</option>
                                        <option value="fit">Fit</option>
                                        <option value="cfit">C.Fit</option>
                                        <option value="unfit">Unfit</option>
                                        <option value="held-up">Held-Up</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <!-- Date Picker -->
                                <div class="contact-form">
                                    <label class="contact-label">Comment</label>
                                    <textarea class="form-control input" name="health_condition"
                                              placeholder="Comment"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="contact-form">
                                    <label class="contact-label">Allocate Medical Center</label>
                                    <select class="select2-modal allocated_medical_center" name="allocated_medical_center">
                                        @foreach(allocateMedicalCenter() as $key => $center)
                                            <option value="{{$key}}">{{$center}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
{{--                            <div class="col-md-12">--}}
{{--                                <div class="contact-form">--}}
{{--                                    <label class="contact-label">Status</label>--}}
{{--                                    <input class="form-control input" type="number" name="application_payment"--}}
{{--                                           placeholder="exp: 1500">--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>

                        <!-- Submit button -->
                        <div class="d-flex align-items-center gap-16 flex-wrap mt-18">
                            <button class="btn-primary-fill" type="submit">
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

                    </form>
                </div>
            </div>
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
                                       placeholder="Choose Date" name="medical_date" value="{{old('medical_date')}}">
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click', '#search-form .search_btn', function (e) {
                e.preventDefault();

                let start_date = $('.start_date').val();
                let end_date = $('.end_date').val();

                window.location.href = `{{route('union.user.application.list')}}?username={{$username}}&start_date=${start_date}&end_date=${end_date}`;
            });

            $(document).on('click', '.reset_btn', function () {
                location.href = `{{route('union.user.application.list')}}?username={{$username}}`;
            });

            $(document).on('click', '.search_btn_passport', function (e) {
                e.preventDefault();

                let passport_search = $('input[name="passport_search"]').val();

                window.location.href = `{{route('union.user.application.list')}}?username={{$username}}&passport_search=${passport_search}`;
            });

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
                    url: `{{route('union.user.application.list.update')}}`,
                    type: 'POST',
                    data: {
                        _token: '{{csrf_token()}}',
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

            $(document).on('click', '.pay-bill-btn', function () {
                let el = $(this);
                let id = el.data('id');
                let user_id = el.data('user_id');

                Swal.fire({
                    title: 'Score Submission Confirmation',
                    text: 'Are you sure you want to submit score for this application?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Submit Now',
                    cancelButtonText: 'No, Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `{{route('union.user.pay-bill')}}?application_id=${id}&user_id=${user_id}`;
                    }
                })
            });
        })
    </script>
@endsection
