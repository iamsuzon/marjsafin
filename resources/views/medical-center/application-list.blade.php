@extends('user.layout.user-master')

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
                            <h2 class="">Application List ({{$applicationList->count()}})</h2>
                            <a href="javascript:void(0)" class="btn btn-info qr_btn"
                               data-bs-toggle="modal" data-bs-target="#staticBackdrop">Scan QR</a>
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
                    <div class="table-responsives max-height-100vh scroll-active">
                        <table class="table-color-col table-head-border table-td-border">
                            <thead>
                            <tr>
                                <th class="mw-45">#SL</th>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Registration</th>
                                <th>Passport</th>
                                <th>Reference</th>
                                <th>Traveling To</th>
                                <th>Center</th>
                                <th>Comment</th>
                                <th>Allocate Center</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($applicationList ?? [] as $item)
                                <tr>
                                    <td class="align-items-center">{{$loop->iteration}}</td>
                                    <td>{{$item->pdf_code}}</td>
                                    <td>
                                        <p>Drft: {{$item->created_at->format('d/m/Y')}}</p>
                                        <p>Crte: {{$item->updated_at->format('d/m/Y')}}</p>
                                    </td>
                                    <td>
                                        <p>{{$item->serial_number}}</p>
                                        <p>{{$item->ems_number}}</p>
                                    </td>
                                    <td>
                                        <p>{{$item->passport_number}}</p>
                                        <p>{{$item->given_name}} {{$item->surname}}</p>
                                        <p>NID: {{$item->nid_no}}</p>
                                    </td>
                                    <td>
                                        <p>From Raju</p>
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
                                        <p @class([
                                            'text-capitalize',
                                            'text-success' => $item->health_status == 'fit' || $item->health_status == 'cfit',
                                            'text-danger' => $item->health_status == 'unfit',
                                            'text-warning' => $item->health_status == 'held-up',
                                        ])>{{getHealthConditionsName($item->health_status)}}</p>
                                        <p>{{$item->health_status_details}}</p>
                                    </td>
                                    <td>
                                        <p>{{getAllocatedMedicalCenterName($item) ?? ''}}</p>
                                    </td>
                                    <td class="text-end px-15 d-flex gap-10">
                                        <a class="edit-btn" href="javascript:void(0)"
                                           data-id="{{$item->id}}"
                                           data-health_status="{{$item->health_status}}"
                                           data-health_status_details="{{$item->health_status_details}}"
                                           data-allocated_medical_center="{{strtolower(getAllocatedMedicalCenterName($item))}}"
                                           data-application_payment="{{$item->applicationPayment?->center_amount}}"
                                           data-bs-toggle="modal" data-bs-target="#edit-modal">
                                            <i class="ri-file-edit-line"></i>
                                        </a>

                                        <a class="view-btn" href="{{route('medical.application.edit', $item->id)}}">
                                            <i class="ri-pencil-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan=11" class="text-center">No Data Found</td>
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

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Scan QR</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ri-close-line" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="mb-3 text-center">Scan the QR Code</h5>
                    <input class="form-control" type="number" name="qr_application_id" placeholder="Scan QR Code here">

                    <div class="form-group mt-3 serial_wrapper">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click', '.edit-btn', function () {
                let el = $(this);
                let id = el.data('id');
                let health_status = el.data('health_status');
                let health_status_details = el.data('health_status_details');
                let allocated_medical_center = el.data('allocated_medical_center');
                // let application_payment = el.data('application_payment');

                let from = $('#edit-form');

                from.find('select[name=health_status]').val([health_status]).trigger('change');
                from.find('textarea[name=health_condition]').val(health_status_details);
                from.find(`select[name=allocated_medical_center]`).val([allocated_medical_center]).trigger('change');
                // from.find(`input[name=application_payment]`).val(application_payment);

                $('#edit-form input[name="id"]').val(id);
            })

            $(document).on('click', '#edit-form button[type=submit]', function (e) {
                e.preventDefault();

                let form = $('#edit-form');

                let id = form.find(`input[name="id"]`).val();
                let health_status = form.find(`select[name="health_status"]`).val();
                let health_condition = form.find(`textarea[name="health_condition"]`).val();
                let allocated_medical_center = form.find(`select[name="allocated_medical_center"]`).val();
                // let application_payment = form.find(`input[name="application_payment"]`).val();

                if (!id) {
                    toastError('All fields are required');
                    return;
                }

                axios.post(`{{route('medical.application.result.update')}}?id=${id}`, {
                    _token: '{{csrf_token()}}',
                    health_status: health_status,
                    health_condition: health_condition,
                    allocated_medical_center: allocated_medical_center,
                    // application_payment: application_payment,
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

                window.location.href = `{{route('medical.application.list')}}?start_date=${start_date}&end_date=${end_date}`;
            });

            $(document).on('click', '.reset_btn', function () {
                location.href = `{{route('medical.application.list')}}`;
            });

            $(document).on('click', '.search_btn_passport', function (e) {
                e.preventDefault();

                let passport_search = $('input[name="passport_search"]').val();

                window.location.href = `{{route('medical.application.list')}}?passport_search=${passport_search}`;
            });

            $(document).on('click', '.qr_btn', function () {
                $('#staticBackdrop').on('shown.bs.modal', function () {
                    $('#staticBackdrop input[name="qr_application_id"]').focus();
                });
            })

            $(document).on('change', '#staticBackdrop input[name="qr_application_id"]', function () {
                let el = $(this);
                let application_id = el.val();

                axios.post(`{{route('medical.check.application-id')}}`, {
                    _token: '{{csrf_token()}}',
                    application_id: application_id,
                })
                    .then(res => {
                        let data = res.data;

                        console.log(data);

                        if (data.status) {
                            toastSuccess(data.message);
                            $(this).hide();

                            let serial_wrapper = $('.serial_wrapper');
                            serial_wrapper.append(`
                                <h5 class="alert alert-success my-2">Application found - Passport No: ${data.application.passport_number}</h5>
                                <input type="hidden" name="application_id" value="${data.application.id}" readonly>
                                <label for="serial_number">Serial Number</label>
                                <input type="text" class="form-control" id="serial_number" name="serial_number">
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary mt-3" id="submit_serial">Submit</button>
                                </div>`
                            );

                            $('#serial_number').focus();
                        } else {
                            toastError(data.message);

                            let serial_wrapper = $('.serial_wrapper');
                            serial_wrapper.empty();

                            setTimeout(() => {
                                el.val('');
                                el.focus()
                            }, 2000)
                        }
                    })
                    .catch(err => {
                        console.log(err);
                    })
            });

            $(document).on('click', '#submit_serial', function (e) {
                let application_id = $('#staticBackdrop input[name="application_id"]').val();
                let serial_number = $('#staticBackdrop #serial_number').val();

                if (!serial_number) {
                    toastError('Serial Number is required');
                    return;
                }

                axios.post(`{{route('medical.submit.serial-number')}}`, {
                    _token: '{{csrf_token()}}',
                    application_id: application_id,
                    serial_number: serial_number,
                })
                    .then(res => {
                        let data = res.data;

                        if (data.status) {
                            toastSuccess(data.message);

                            let serial_wrapper = $('.serial_wrapper');
                            serial_wrapper.append(`
                                <h5 class="alert alert-success my-2">${data.message}</h5>`
                            );

                            setTimeout(() => {
                                $('#staticBackdrop input[name="qr_application_id"]').val('');
                                let serial_wrapper = $('.serial_wrapper');
                                serial_wrapper.empty();

                                let qr_application_id = $('#staticBackdrop input[name="qr_application_id"]');
                                qr_application_id.show();
                                qr_application_id.focus();
                            }, 1500);
                        } else {
                            toastError(data.message);
                        }
                    })
                    .catch(err => {
                        console.log(err);
                    })
            });

            $(document).on('click', '#staticBackdrop .btn-close', function () {
                $('#staticBackdrop input[name="application_id"]').val('');
                let serial_wrapper = $('.serial_wrapper');
                serial_wrapper.empty();
            });

            $(document).on('keydown', function (e) {
                if (e.key === 'Enter' || e.which === 13) {
                    $('#submit_serial').click();
                }
            });
        })
    </script>
@endsection
