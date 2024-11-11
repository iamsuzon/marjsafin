@extends('admin.layout.user-master')

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
                        <h2 class="manage__title">Application List</h2>

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
                    <div class="table-responsives max-height-100vh scroll-active">
                        <table class="table-color-col table-head-border table-td-border">
                            <thead>
                            <tr>
                                <th class="mw-45">#SL</th>
                                <th>Date</th>
                                <th>Medical Type</th>
                                <th>Registration</th>
                                <th>Passport</th>

                                @hasrole('super-admin')
                                <th>Reference</th>
                                @endhasrole

                                <th>Traveling To</th>
                                <th>Center</th>

                                @hasrole('super-admin')
                                    <th>Score</th>
                                @endhasrole

                                <th>Comment</th>

                                @hasrole('super-admin')
                                    <th>Medical Status</th>
                                @endhasrole

                                @hasanyrole('super-admin|admin')
                                    <th>Action</th>
                                @endhasanyrole
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($applicationList ?? [] as $item)
                                @role('analyst')
                                    @if(in_array(strtolower($item->center_name), ['altaskhis_markaz', 'yadan_medical', 'malancha_medical', 'kent_medical']))
                                        @continue
                                    @endif
                                @endrole

                                <tr>
                                    <td class="mw-45 d-flex align-items-center">{{$item->id}}</td>
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
                                    @hasrole('super-admin')
                                        <td>
                                            <p>{{$item->ref_ledger}}</p>
                                            <p>User: {{$item->user->username}}, Ref: {{$item->ref_no}}</p>
                                        </td>
                                    @endhasrole
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
                                    @hasrole('super-admin')
                                        <td>
                                            {{$item->applicationPayment?->admin_amount}}
                                        </td>
                                    @endhasrole
                                    <td>
                                        <p @class([
                                            'text-capitalize',
                                            'text-success' => $item->health_status == 'fit' || $item->health_status == 'cfit',
                                            'text-danger' => $item->health_status == 'unfit',
                                            'text-warning' => $item->health_status == 'held-up',
                                        ])>{{getHealthConditionsName($item->health_status)}}</p>
                                        <p>C: {{$item->health_status_details}}</p>

                                        @hasrole('super-admin')
                                            <p>A: {{$item->applicationCustomComment?->health_condition}}</p>
                                        @endhasrole
                                    </td>

                                    @hasrole('super-admin')
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

                                        <td>
                                            <p class="badge bg-{{$class}}">{{$item->medical_status}}</p>
                                        </td>
                                    @endhasrole

                                    @hasanyrole('super-admin|admin')
                                    <td class="text-end px-15 d-flex gap-10">
                                        @can('modify-application')
                                            <a class="edit-btn" href="javascript:void(0)"
                                               data-id="{{$item->id}}"
                                               data-health_status="{{$item->health_status}}"
                                               data-health_status_details="{{$item->applicationCustomComment?->health_condition ?? $item->health_status_details}}"
                                               data-application_admin_payment="{{$item->applicationPayment?->admin_amount ?? ''}}"
                                               data-bs-toggle="modal" data-bs-target="#edit-modal">
                                                <i class="ri-file-edit-line"></i>
                                            </a>
                                        @endcan

                                        @can('update-application')
                                                <a class="application_update_btn download-btn" href="javascript:void(0)" data-bs-target="#application-update-modal" data-bs-toggle="modal"
                                                    data-id="{{$item->id}}" data-passport="{{$item->passport_number}}" data-registarion="{{$item->serial_number}}" data-medical="{{$item->medical_date}}"
                                                >
                                                    <i class="ri-calendar-schedule-fill"></i>
                                                </a>

                                            <a class="view-btn" href="{{route('admin.application.edit', $item->id)}}">
                                                <i class="ri-pencil-fill"></i>
                                            </a>
                                        @endcan

                                        @can('delete-application')
                                            <a class="delete-btn" href="javascript:void(0)"
                                               data-id="{{$item->id}}">
                                                <i class="ri-delete-bin-6-line"></i>
                                            </a>
                                        @endcan
                                    </td>
                                    @endhasanyrole
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No Data Found</td>
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
                                <!-- Date Picker -->
                                <div class="contact-form">
                                    <label class="contact-label">Comment<span
                                            class="fillable mx-1">*</span></label>
                                    <textarea class="form-control input" name="health_condition"
                                              placeholder="health condition"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="contact-form">
                                    <label class="contact-label">Admin Score</label>
                                    <input class="form-control input" type="text" name="application_admin_payment">
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

    <div class="modal fade" id="application-update-modal" tabindex="-1" aria-labelledby="scoreModalLabel" aria-hidden="true">
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

            $(document).on('click', '.application_update_btn', function () {
                let el = $(this);
                let id = el.data('id');
                let passport_number = el.data('passport');
                let registration_number = el.data('registarion');
                let medical_date = el.data('medical');

                let modal = $('#application-update-modal');
                modal.find('input[name="id"]').val(id);
                modal.find('input[name="passport_number"]').val(passport_number);
                modal.find('input[name="registration_number"]').val(registration_number);
                modal.find('input[name="medical_date"]').val(medical_date);
            });

            $(document).on('submit', '#application-update-modal form', function (e) {
                e.preventDefault();

                let form = $(this);
                let id = form.find('input[name="id"]').val();
                let registration_number = form.find('input[name="registration_number"]').val();
                let medical_date = form.find('input[name="medical_date"]').val();

                $.ajax({
                    url: `{{route('admin.application.update.reg-date')}}`,
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
                            alert(response.message);
                        }
                    }
                });
            });

            $(document).on('click', '.edit-btn', function () {
                let el = $(this);
                let id = el.data('id');
                let health_status_details = el.data('health_status_details');
                let application_admin_payment = el.data('application_admin_payment');

                // let allNull = [health_status_details].every(item => item === '');
                let allNull = false;

                let from = $('#edit-form');
                from.find('textarea[name="health_condition"]').val('');
                from.find('input[name="application_admin_payment"]').val('');

                if (allNull)
                {
                    from.find('textarea[name="health_condition"]').attr('placeholder', '');
                    from.find('textarea[name="health_condition"]').attr('disabled', true);
                    from.find('input[name="application_admin_payment"]').attr('disabled', true);
                    from.find('button[type="submit"]').addClass('btn-dark-fill').attr('disabled', true);
                } else {
                    from.find('textarea[name="health_condition"]').attr('disabled', false);
                    from.find('input[name="application_admin_payment"]').attr('disabled', false);
                    from.find('button[type="submit"]').removeClass('btn-dark-fill').attr('disabled', false);

                    from.find('textarea[name="health_condition"]').val(health_status_details);
                    from.find('input[name="application_admin_payment"]').val(application_admin_payment);
                }

                $('#edit-form input[name="id"]').val(id);
            })

            $(document).on('click', '#edit-form button[type=submit]', function (e) {
                e.preventDefault();

                let form = $('#edit-form');

                let id = form.find(`input[name="id"]`).val();
                let health_condition = form.find(`textarea[name="health_condition"]`).val();
                let application_payment = form.find(`input[name="application_admin_payment"]`).val();

                if (!id) {
                    toastError('All fields are required');
                    return;
                }

                axios.post(`{{route('admin.application.result.update')}}?id=${id}`, {
                    _token: '{{csrf_token()}}',
                    health_condition: health_condition,
                    application_payment: application_payment
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

                window.location.href = `{{route('admin.application.list')}}?start_date=${start_date}&end_date=${end_date}`;
            });

            $(document).on('click', '.reset_btn', function () {
                location.href = `{{route('admin.application.list')}}`;
            });

            $(document).on('click', '.search_btn_passport', function (e) {
                e.preventDefault();

                let passport_search = $('input[name="passport_search"]').val();

                window.location.href = `{{route('admin.application.list')}}?passport_search=${passport_search}`;
            });

            $(document).on('click', '.delete-btn', function () {
                let id = $(this).data('id');

                customSwal({
                    route: `{{route('admin.application.delete')}}?id=${id}`,
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
@endsection
