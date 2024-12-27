@extends('user.layout.common-master')
@section('title', 'Slip List')

@section('sidebar')
    @include('user.partials.medical-sidebar')
@endsection

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
                    'route' => route('user.application.list'),
                    'active' => false
                ],
                                [
                    'name' => 'Slip List',
                    'route' => route('user.slip.list'),
                    'active' => true
                ]
            ]"/>

            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <h2 class="manage__title">Slip List ({{$slipList->count()}})</h2>

                        <form id="search-from">
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
                                        <input type="text" class="contact-input passport_search"
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
                                আরো স্কোর এর জন্য রিকোয়েস্ট করুন <a href="{{session('url')}}" class="btn btn-primary">Request
                                    Score</a>
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
                                <th>Slip Type</th>
                                {{--                                <th>Registration</th>--}}
                                <th>Passport</th>
                                <th>Reference</th>
                                <th>City</th>
                                <th>Medical Center</th>
                                <th>Link</th>
                                <th>Score</th>
                                {{--                                <th>PDF</th>--}}
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
                                    {{--                                    <td>--}}
                                    {{--                                        <p>{{$item->serial_number}}</p>--}}
                                    {{--                                        <p>{{$item->ems_number}}</p>--}}
                                    {{--                                    </td>--}}
                                    <td>
                                        <p>{{$item->passport_number}}</p>
                                        <p>{{$item->given_name}}</p>
                                        <p>{{$item->surname}}</p>
                                        <p>NID: {{$item->nid_no}}</p>
                                        <p class="text-capitalize">{{$item->gender}}</p>
                                    </td>
                                    <td>
                                        <p>{{$item->ref_no}}</p>
                                    </td>
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
                                    <td>
                                        @php
                                            $class = match($item->slipStatusLink?->slip_status) {
                                                'processing' => 'bg-warning',
                                                'in-queue' => 'bg-primary',
                                                'completed' => 'bg-success',
                                                default => 'bg-info'
                                            };
                                        @endphp
                                        <p class="badge {{$class}} text-capitalize">{{$item->slipStatusLink?->slip_status}}</p>
                                        <a class="slip-link" href="{{$item?->slipStatusLink?->link}}" target="_blank">
                                            <p>{{$item?->slipStatusLink?->link}}</p>
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                            $payment_status = $item->slipPayment?->payment_status;
                                        @endphp

                                        @if($payment_status)
                                            @php
                                                $slip_rate = $item->slipPayment?->slip_rate;
                                                $slip_discount = $item->slipPayment?->discount ?? 0;
                                                $payment_amount = $slip_rate - $slip_discount;
                                            @endphp

                                            <p class="mb-10 text-danger">-{{$payment_amount}}</p>

                                            @if($payment_status === 'pending')
                                                @if(auth('web')->user()->slip_balance > 0)
                                                    <a href="javascript:void(0)" class="pay-bill-btn btn-primary-fill"
                                                       data-id="{{$item->id}}">
                                                        <i class="ri-money-dollar-circle-line"></i> Pay Score
                                                    </a>
                                                @else
                                                    <a href="{{route('user.score.request', $item->user_id)}}?for=slip"
                                                       class="btn-primary-fill"
                                                       style="background: #ffc107; color: #000">No Score Available</a>
                                                @endif
                                            @endif
                                        @endif
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

                    <div class="d-flex justify-content-start">
                        {{$slipList->links()}}
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

            $(document).on('click', '.search_btn', function (e) {
                e.preventDefault();

                let form = $('#search-from');
                let start_date = form.find('.start_date').val();
                let end_date = form.find('.end_date').val();

                window.location.href = `{{route('user.slip.list')}}?start_date=${start_date}&end_date=${end_date}`;
            });

            $(document).on('click', '.reset_btn', function () {
                location.href = `{{route('user.slip.list')}}`;
            });

            $(document).on('click', '.search_btn_passport', function (e) {
                e.preventDefault();

                let passport_search = $('input[name="passport_search"]').val();

                window.location.href = `{{route('user.slip.list')}}?passport_search=${passport_search}`;
            });

            $(document).on('click', '.pay-bill-btn', function () {
                let el = $(this);
                let id = el.data('id');

                Swal.fire({
                    title: 'Score Submission Confirmation',
                    text: 'Are you sure you want to submit score for this application?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Submit Now',
                    cancelButtonText: 'No, Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `{{route('user.pay-slip-bill')}}?slip_id=${id}`;
                    }
                })
            });
        });
    </script>
@endsection
