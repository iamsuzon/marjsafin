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
                        <div class="manage__title d-flex justify-content-between">
                            <h2>Application - Passport: {{$applicationList->first()->passport_number}}</h2>
                            <a href="{{route('admin.dashboard')}}" class="btn-primary-fill">
                                <i class="ri-arrow-left-line"></i>
                            </a>
                        </div>
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
                                <th>Medical Status</th>
{{--                                <th>Action</th>--}}
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
                                        <p>{{$item->given_name}}</p>
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
                                            'text-success' => $item->health_status == 'fit',
                                            'text-danger' => $item->health_status == 'unfit',
                                            'text-warning' => $item->health_status == 'held-up',
                                        ])>{{$item->health_status}}</p>
                                        <p>{{$item->health_status_details}}</p>
                                    </td>
                                    <td>
                                        <p>{{getAllocatedMedicalCenterName($item) ?? ''}}</p>
                                    </td>
                                    <td>
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

                                        <p class="mb-10 text-{{$class}}">{{getMedicalStatusName($item->medical_status ?? '')}}</p>
                                        <select class="select2" name="medical_status" data-id="{{$item->id}}">
                                            <option value="" selected disabled>Select an option</option>
                                            @foreach(medicalStatus() as $key => $status)
                                                <option value="{{$key}}" @if($item->medical_status == $key) selected @endif>{{$status}}</option>
                                            @endforeach
                                        </select>
                                    </td>
{{--                                    <td>--}}
{{--                                        <a class="edit-btn" href="javascript:void(0)"--}}
{{--                                           data-id="{{$item->id}}"--}}
{{--                                           data-health_status="{{$item->health_status}}"--}}
{{--                                           data-health_status_details="{{$item->health_status_details}}"--}}
{{--                                           data-allocated_medical_center="{{strtolower(getAllocatedMedicalCenterName($item))}}"--}}
{{--                                           data-application_payment="{{$item->applicationPayment?->center_amount}}"--}}
{{--                                           data-bs-toggle="modal" data-bs-target="#edit-modal">--}}
{{--                                            <i class="ri-file-edit-line"></i>--}}
{{--                                        </a>--}}

{{--                                        <a class="view-btn" href="{{route('medical.application.edit', $item->id)}}">--}}
{{--                                            <i class="ri-pencil-fill"></i>--}}
{{--                                        </a>--}}
{{--                                    </td>--}}
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

            {{--$(document).on('click', '#edit-form button[type=submit]', function (e) {--}}
            {{--    e.preventDefault();--}}

            {{--    let form = $('#edit-form');--}}

            {{--    let id = form.find(`input[name="id"]`).val();--}}
            {{--    let health_status = form.find(`select[name="health_status"]`).val();--}}
            {{--    let health_condition = form.find(`textarea[name="health_condition"]`).val();--}}
            {{--    let allocated_medical_center = form.find(`select[name="allocated_medical_center"]`).val();--}}
            {{--    // let application_payment = form.find(`input[name="application_payment"]`).val();--}}

            {{--    if (!id) {--}}
            {{--        toastError('All fields are required');--}}
            {{--        return;--}}
            {{--    }--}}

            {{--    axios.post(`{{route('admin.application.result.update')}}?id=${id}`, {--}}
            {{--        _token: '{{csrf_token()}}',--}}
            {{--        health_status: health_status,--}}
            {{--        health_condition: health_condition,--}}
            {{--        allocated_medical_center: allocated_medical_center,--}}
            {{--        // application_payment: application_payment,--}}
            {{--        center: '{{$username}}'--}}
            {{--    })--}}
            {{--        .then(res => {--}}
            {{--            let data = res.data;--}}

            {{--            if (data.status) {--}}
            {{--                toastSuccess(data.message);--}}
            {{--                reloadThisPage();--}}
            {{--            }--}}
            {{--        })--}}
            {{--        .catch(err => {--}}
            {{--            console.log(err);--}}
            {{--        })--}}
            {{--})--}}

            $(document).on('click', '#search-form .search_btn', function (e) {
                e.preventDefault();

                let start_date = $('.start_date').val();
                let end_date = $('.end_date').val();

                window.location.href = `{{route('admin.application.list')}}?center={{$username}}&start_date=${start_date}&end_date=${end_date}`;
            });

            $(document).on('click', '.reset_btn', function () {
                location.href = `{{route('admin.application.list')}}?center={{$username}}`;
            });

            $(document).on('click', '.search_btn_passport', function (e) {
                e.preventDefault();

                let passport_search = $('input[name="passport_search"]').val();

                window.location.href = `{{route('admin.application.list')}}?center={{$username}}&passport_search=${passport_search}`;
            });

            $(document).on('change', 'select[name=medical_status]', function () {
                let el = $(this);
                let id = el.data('id');
                let medical_status = el.val();

                axios.post(`{{route('admin.application.list.update.medical-status')}}`, {
                    _token: '{{csrf_token()}}',
                    id: id,
                    medical_status: medical_status,
                })
                    .then(res => {
                        let data = res.data;

                        if (data.status) {
                            toastSuccess(data.message);
                            reloadThisPage(1000);
                        }
                    })
                    .catch(err => {
                        console.log(err);
                    })
            });
        })
    </script>
@endsection
