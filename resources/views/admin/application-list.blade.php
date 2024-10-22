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
                                <th>Date</th>
                                <th>Registration</th>
                                <th>Passport</th>
                                <th>Reference</th>
                                <th>Traveling To</th>
                                <th>Result</th>
                                <th>Action</th>
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
                                        <p>{{$item->ref_ledger}}</p>
                                        <p>User: {{$item->user->username}}, Ref: {{$item->ref_no}}</p>
                                    </td>
                                    <td>
                                        <p class="text-capitalize">{{$item->gender}}</p>
                                        <p>{{travelingToName($item->traveling_to)}}</p>
                                    </td>
                                    <td>
                                        <p @class([
                                            'text-capitalize',
                                            'text-success' => $item->health_status == 'fit',
                                            'text-danger' => $item->health_status == 'unfit',
                                            'text-warning' => $item->health_status == 'held-up',
                                        ])>{{$item->health_status}}</p>
                                        <p>{{$item->health_status_details}}</p>
                                    </td>
                                    <td class="text-end px-15 d-flex gap-10">
                                        <a class="edit-btn" href="javascript:void(0)"
                                           data-id="{{$item->id}}"
                                           data-ems_number="{{$item->ems_number}}"
                                           data-health_status="{{$item->health_status}}"
                                           data-health_status_details="{{$item->health_status_details}}"
                                           data-bs-toggle="modal" data-bs-target="#edit-modal">
                                            <i class="ri-file-edit-line"></i>
                                        </a>
                                        <a class="view-btn" href="{{route('admin.application.edit', $item->id)}}">
                                            <i class="ri-pencil-fill"></i>
                                        </a>
                                        <a class="delete-btn" href="javascript:void(0)"
                                           data-id="{{$item->id}}">
                                            <i class="ri-delete-bin-6-line"></i>
                                        </a>
                                    </td>
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

                let from = $('#edit-form');

                from.find('input[name="ems_number"]').val(ems_number);
                from.find('select[name="health_status"]').val(health_status);
                from.find('textarea[name="health_condition"]').val(health_status_details);

                $('#edit-form input[name="id"]').val(id);
            })

            $(document).on('click', '#edit-form button[type=submit]', function (e) {
                e.preventDefault();

                let form = $('#edit-form');

                let id = form.find(`input[name="id"]`).val();
                let ems_number = form.find(`input[name="ems_number"]`).val();
                let health_status = form.find(`select[name="health_status"]`).val();
                let health_condition = form.find(`textarea[name="health_condition"]`).val();

                if (!ems_number || !health_status || !health_condition) {
                    toastError('All fields are required');
                    return;
                }

                axios.post(`{{route('admin.application.result.update')}}?id=${id}`, {
                    _token: '{{csrf_token()}}',
                    ems_number: ems_number,
                    health_status: health_status,
                    health_condition: health_condition
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
