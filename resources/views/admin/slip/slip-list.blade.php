@extends('admin.layout.user-master')

@section('styles')
    <style>
        .manage__title {
            border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
        }

        a.slip-link p {
            white-space: nowrap;
            width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        a.slip-link p:hover {
            overflow: visible;
            color: var(--primary);
        }
    </style>
@endsection

@section('contents')
    <div class="page-content">
        <div class="container-fluid">
            <x-page-tabs title="Slip" :links="[
                [
                    'name' => 'Medical List',
                    'route' => route('admin.application.list'),
                    'active' => false
                ],
                                [
                    'name' => 'Slip List',
                    'route' => route('admin.slip.list'),
                    'active' => true
                ]
            ]"/>

            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <h2 class="manage__title">
                            Slip List

                            @if($start_date && $end_date)
                                <span class="text-capitalize">({{$start_date}} <i class="ri-arrow-right-line"></i> {{$end_date}})</span>
                            @endif
                        </h2>

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
                                <th>Slip Type</th>
                                <th>Passport</th>
                                <th>Info</th>

                                @hasrole('super-admin|sub-admin')
                                <th>Reference</th>
                                @endhasrole

                                <th>City</th>
                                <th>Medical Center</th>

                                @hasrole('super-admin|sub-admin')
                                <th>Score</th>
                                @endhasrole

                                @hasanyrole('super-admin|admin|sub-admin')
                                <th>Link</th>
                                <th>Action</th>
                                @endhasanyrole
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($slipList ?? [] as $item)
                                <tr>
                                    <td class="mw-45 d-flex align-items-center">{{listSerialNumber($slipList, $loop)}}</td>
                                    <td>
                                        <p>Drft: {{$item->created_at->format('d/m/Y')}}</p>
                                        <p>Crte: {{$item->medical_date?->format('d/m/Y')}}</p>
                                    </td>
                                    <td>{{ucfirst($item->slip_type)}}</td>
                                    <td>
                                        <p>{{$item->passport_number}}</p>
                                        <p>{{$item->given_name}} {{$item->surname}}</p>
                                        <p>NID: {{$item->nid_no}}</p>
                                        <p class="text-capitalize">{{$item->gender}} - {{$item->marital_status}}</p>
                                    </td>
                                    <td>
                                        <p>DOB: {{$item->date_of_birth?->format('d-m-Y')}}</p>
                                        <p>P.Issue Date: {{$item->passport_issue_date?->format('d-m-Y')}}</p>
                                        <p>P.Expire Date: {{$item->passport_expiry_date?->format('d-m-Y')}}</p>
                                    </td>
                                    @hasrole('super-admin|sub-admin')
                                    <td>
                                        <p>{{$item->ref_ledger}}</p>
                                        <p>User: {{$item->user->username}}</p>
                                        <p>Ref: {{$item->ref_no}}</p>
                                    </td>
                                    @endhasrole
                                    <td>
                                        <p>{{slipCenterList()[$item->city_id]['title']}}</p>
                                    </td>
                                    <td>
                                        @php
                                            $centerList = slipCenterList();
                                            $center = $centerList[$item->city_id];
                                            $name = $center['list'][$item->center_slug];
                                        @endphp

                                        <p class="text-capitalize">{{$name}}</p>
                                    </td>
                                    @hasrole('super-admin|sub-admin')
                                    <td>
                                        @php
                                            $class = match($item?->slipPayment?->payment_status) {
                                                'pending' => 'bg-warning',
                                                'paid' => 'bg-success',
                                                default => 'bg-danger'
                                            };
                                        @endphp
                                        <p class="badge {{$class}} text-capitalize">{{$item?->slipPayment?->payment_status}}</p>

                                        @hasrole('super-admin')
                                        <p>Score: {{$item?->slipPayment?->slip_rate}}</p>

                                        @if($item->slipPayment?->discount > 0)
                                            <p>Discount: {{$item->slipPayment?->discount}}</p>
                                            <p>Total: $item->slipPayment?->slip_rate - $item->slipPayment?->discount</p>
                                        @endif
                                        @endhasrole
                                    </td>
                                    @endhasrole

                                    @hasrole('super-admin|sub-admin')
                                    <td>
                                        @php
                                            $class = match($item->slipStatusLink?->slip_status) {
                                                'processed-link' => 'bg-warning',
                                                'cancelled' => 'bg-danger',
                                                'we-cant-not-expired' => 'bg-danger',
                                                'cancelled-for-time-out' => 'bg-danger',
                                                'completed' => 'bg-success',
                                                default => 'bg-info'
                                            };
                                        @endphp

                                        <a class="slip-link badge {{$class}} text-capitalize"
                                           href="{{$item?->slipStatusLink?->link ?? '#'}}"
                                           target="{{$item?->slipStatusLink?->link ? '_blank' : ''}}">{{$item->slipStatusLink?->slip_status}}</a>
                                    </td>
                                    @endhasrole

                                    @hasanyrole('super-admin|admin|sub-admin')
                                    <td class="text-end px-15 d-flex gap-10">
                                        @if(! in_array($item->slipStatusLink?->slip_status, ['cancelled', 'we-cant-not-expired', 'cancelled-for-time-out']))
                                            <a class="edit-btn" href="javascript:void(0)"
                                               data-id="{{$item->id}}"
                                               data-status="{{$item->slipStatusLink?->slip_status}}"
                                               data-link="{{$item->slipStatusLink?->link}}"
                                               data-bs-toggle="modal" data-bs-target="#edit-modal">
                                                <i class="ri-file-edit-line"></i>
                                            </a>
                                        @endif

                                        @can('delete-application')
                                            @if($item->slipPayment?->payment_status === 'pending')
                                                <a class="delete-btn" href="javascript:void(0)"
                                                   data-id="{{$item->id}}">
                                                    <i class="ri-delete-bin-6-line"></i>
                                                </a>
                                            @endif
                                        @endcan
                                    </td>
                                    @endhasanyrole
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-start">
                        {{$slipList->links()}}
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
                        <h6 class="modal-title">Update Slip</h6>
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
                                    <label class="contact-label">Slip Status</label>
                                    <select name="slip_status" id="slip_status" class="form-control">
                                        <option value="">Select an option</option>
                                        <option value="processing">Processing</option>
                                        <option value="processed-link">Processed-Link</option>
                                        <option value="cancelled">Cancelled:Non</option>
                                        <option value="we-cant-not-expired">We Can't Not Expired</option>
                                        <option value="cancelled-for-time-out">Cancelled for Time Out</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="contact-form">
                                    <label class="contact-label">Link</label>
                                    <input class="form-control input" type="text" name="link">
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

            $(document).on('click', '.edit-btn', function () {
                let el = $(this);
                let id = el.data('id');
                let status = el.data('status');
                let link = el.data('link');

                let modal = $('#edit-modal');
                modal.find('input[name="id"]').val(id);
                modal.find('select[name="slip_status"]').val(status);
                modal.find('input[name="link"]').val(link);
            });

            $(document).on('click', '#edit-form button[type=submit]', function (e) {
                e.preventDefault();

                let form = $('#edit-form');

                let id = form.find(`input[name="id"]`).val();
                let slip_status = form.find(`select[name="slip_status"]`).val();
                let link = form.find(`input[name="link"]`).val();

                if (!id || !slip_status) {
                    toastError('All fields are required');
                    return;
                }

                axios.post(`{{route('admin.slip.list')}}?id=${id}`, {
                    _token: '{{csrf_token()}}',
                    slip_status: slip_status,
                    link: link,
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

                window.location.href = `{{route('admin.slip.list')}}?start_date=${start_date}&end_date=${end_date}`;
            });

            $(document).on('click', '.reset_btn', function () {
                location.href = `{{route('admin.slip.list')}}`;
            });

            $(document).on('click', '.search_btn_passport', function (e) {
                e.preventDefault();

                let passport_search = $('input[name="passport_search"]').val();

                window.location.href = `{{route('admin.slip.list')}}?passport_search=${passport_search}`;
            });

            $(document).on('click', '.delete-btn', function () {
                let id = $(this).data('id');

                customSwal({
                    route: `{{route('admin.slip.delete')}}?id=${id}`,
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
