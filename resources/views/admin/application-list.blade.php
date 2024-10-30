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
                                    <th>Status (BDT)</th>
                                @endhasrole

                                <th>Result</th>

                                @hasanyrole('super-admin|admin|analyst')
                                    <th>Action</th>
                                @endhasanyrole
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($applicationList ?? [] as $item)
                                <tr>
                                    <td class="mw-45 d-flex align-items-center">{{$item->id}}</td>
                                    <td>
                                        <p>Drft: {{$item->created_at->format('d/m/Y')}}</p>
                                        <p>Crte: {{$item->updated_at->format('d/m/Y')}}</p>
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
                                        @php
                                            $center_amount = $item->applicationPayment?->center_amount ? $item->applicationPayment->center_amount : '';
                                            $admin_amount = $item->applicationPayment?->admin_amount ? $item->applicationPayment->admin_amount : '';
                                        @endphp
                                        <td>
                                            @if($center_amount)
                                                <p>C: {{$center_amount}}</p>
                                            @endif

                                            @if($admin_amount)
                                                <p>A: {{$admin_amount}}</p>
                                            @endif
                                        </td>
                                    @endhasrole
                                    <td>
                                        <p @class([
                                            'text-capitalize',
                                            'text-success' => $item->health_status == 'fit',
                                            'text-danger' => $item->health_status == 'unfit',
                                            'text-warning' => $item->health_status == 'held-up',
                                        ])>{{$item->health_status}}</p>
                                        <p>{{$item->health_status_details}}</p>
                                    </td>

                                    @hasanyrole('super-admin|admin|analyst')
                                    <td class="text-end px-15 d-flex gap-10">
                                        @can('modify-application')
                                            <a class="edit-btn" href="javascript:void(0)"
                                               data-id="{{$item->id}}"
                                               data-ems_number="{{$item->ems_number}}"
                                               data-health_status="{{$item->health_status}}"
                                               data-health_status_details="{{$item->health_status_details}}"
                                               data-application_center_payment="{{$item->applicationPayment?->center_amount}}"
                                               data-application_admin_payment="{{$item->applicationPayment?->admin_amount}}"
                                               data-bs-toggle="modal" data-bs-target="#edit-modal">
                                                <i class="ri-file-edit-line"></i>
                                            </a>
                                        @endcan

                                        @can('update-application')
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
                                <div class="contact-form">
                                    <label class="contact-label">EMS Number<span
                                            class="fillable mx-1">*</span></label>
                                    <input class="form-control input" type="text" name="ems_number"
                                           placeholder="ems number">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="contact-form">
                                    <label class="contact-label">Health Status <span
                                            class="fillable mx-1">*</span></label>
                                    <select class="select2-modal" name="health_status">
                                        <option value="" selected disabled>Select an option</option>
                                        <option value="fit">Fit</option>
                                        <option value="unfit">Unfit</option>
                                        <option value="held-up">Held-Up</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <!-- Date Picker -->
                                <div class="contact-form">
                                    <label class="contact-label">Health Condition<span
                                            class="fillable mx-1">*</span></label>
                                    <textarea class="form-control input" name="health_condition"
                                              placeholder="health condition"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="contact-form">
                                    <label class="contact-label">Center Status</label>
                                    <input class="form-control input" type="text" name="application_center_payment" disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="contact-form">
                                    <label class="contact-label">Admin Status</label>
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click', '.edit-btn', function () {
                let el = $(this);
                let id = el.data('id');
                let ems_number = el.data('ems_number');
                let health_status = el.data('health_status');
                let health_status_details = el.data('health_status_details');
                let application_center_payment = el.data('application_center_payment');
                let application_admin_payment = el.data('application_admin_payment');

                let allNull = [ems_number, health_status, health_status_details, application_center_payment].every(item => item === '');

                let from = $('#edit-form');

                if (allNull)
                {
                    from.find('input[name="ems_number"]').attr('disabled', true);
                    from.find('select[name="health_status"]').attr('disabled', true);
                    from.find('textarea[name="health_condition"]').attr('disabled', true);
                    from.find('input[name="application_admin_payment"]').attr('disabled', true);
                    from.find('button[type="submit"]').addClass('btn-dark-fill').attr('disabled', true);
                } else {
                    from.find('input[name="ems_number"]').attr('disabled', false);
                    from.find('select[name="health_status"]').attr('disabled', false);
                    from.find('textarea[name="health_condition"]').attr('disabled', false);
                    from.find('input[name="application_admin_payment"]').attr('disabled', false);
                    from.find('button[type="submit"]').removeClass('btn-dark-fill').attr('disabled', false);

                    from.find('input[name="ems_number"]').val(ems_number);
                    from.find('select[name="health_status"]').val(health_status).trigger('change');
                    from.find('textarea[name="health_condition"]').val(health_status_details);
                    from.find('input[name="application_center_payment"]').val(application_center_payment);
                    from.find('input[name="application_admin_payment"]').val(application_admin_payment);
                }

                $('#edit-form input[name="id"]').val(id);
            })

            $(document).on('click', '#edit-form button[type=submit]', function (e) {
                e.preventDefault();

                let form = $('#edit-form');

                let id = form.find(`input[name="id"]`).val();
                let ems_number = form.find(`input[name="ems_number"]`).val();
                let health_status = form.find(`select[name="health_status"]`).val();
                let health_condition = form.find(`textarea[name="health_condition"]`).val();
                let application_payment = form.find(`input[name="application_admin_payment"]`).val();

                if (!id) {
                    toastError('All fields are required');
                    return;
                }

                axios.post(`{{route('admin.application.result.update')}}?id=${id}`, {
                    _token: '{{csrf_token()}}',
                    ems_number: ems_number,
                    health_status: health_status,
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
