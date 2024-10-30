@extends('admin.layout.user-master')

@section('styles')
    <style>
        .manage__title {
            border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
        }

        .approve_btn {
            border: 1px solid transparent !important;
        }
        .approve_btn:hover {
            background: transparent !important;
            border: 1px solid green !important;
            color: green !important;
        }

        .decline_btn {
            background: red !important;
            border: 1px solid transparent !important;
        }
        .decline_btn:hover {
            background: transparent !important;
            border: 1px solid red !important;
            color: red !important;
        }
    </style>
@endsection

@section('contents')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <h2 class="manage__title">Transaction History</h2>

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
                            {{ session('error') }}
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
                                <th>Username</th>
                                <th>Payment Type</th>
                                <th>Deposit Date</th>
                                <th>Payment Method</th>
                                <th>Reference No</th>
                                <th>Amount(BDT)</th>
                                <th>Deposit Slip</th>
                                <th>Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($transactionHistory ?? [] as $item)
                                <tr>
                                    <td class="mw-45 d-flex align-items-center">{{$item->id}}</td>
                                    <td>
                                        <p>{{$item->user?->name}}</p>
                                        <p>{{$item->user?->username}}</p>
                                    </td>
                                    <td>
                                        <p class="text-capitalize">{{$item->payment_type}}</p>
                                    </td>
                                    <td>{{$item->deposit_date->format('d-m-Y')}}</td>
                                    <td>
                                        <p>{{getPaymentMethodName($item->payment_method)}}</p>
                                    </td>
                                    <td>{{$item->reference_no}}</td>
                                    <td>
                                        <p class="{{$item->payment_type === 'deposit' ? 'text-success' : 'text-danger'}}">
                                            {{$item->payment_type === 'deposit' ? '+' : '-'}}{{$item->amount}}
                                        </p>
                                    </td>
                                    <td>
                                        @if($item->deposit_slip)
                                            <a href="{{customAsset('assets/uploads/deposit/'.$item->deposit_slip)}}"
                                               target="_blank">
                                                @php
                                                    $file_type = $item->deposit_slip ? pathinfo($item->deposit_slip, PATHINFO_EXTENSION) : '';
                                                @endphp

                                                @if($file_type === 'pdf')
                                                    <img src="{{customAsset('assets/images/pdf.webp')}}"
                                                         alt="Deposit Slip"
                                                         class="img-fluid" style="max-width: 50px">
                                                @else
                                                    <img src="{{customAsset('assets/uploads/deposit/'.$item->deposit_slip)}}"
                                                         alt="Deposit Slip"
                                                         class="img-fluid" style="max-width: 50px">
                                                @endif
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{$item->remarks}}</td>
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click', '#search-form .search_btn', function (e) {
                e.preventDefault();

                let start_date = $('.start_date').val();
                let end_date = $('.end_date').val();

                window.location.href = `{{route('admin.transaction-history')}}?start_date=${start_date}&end_date=${end_date}`;
            });

            $(document).on('click', '.reset_btn', function () {
                location.href = `{{route('admin.transaction-history')}}`;
            });

            $(document).on('click', '.approve_btn', function (e) {
                e.preventDefault();

                let el = $(this);
                let id = el.data('id')

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to approve this deposit request!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Approve it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = `{{route('admin.deposit-request-history.change-status')}}?status=approved&id=${id}`;
                    }
                })
            });

            $(document).on('click', '.decline_btn', function (e) {
                e.preventDefault();

                let el = $(this);
                let id = el.data('id')
                console.log(id)

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to decline this deposit request!",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Decline it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = `{{route('admin.deposit-request-history.change-status')}}?status=declined&id=${id}`;
                    }
                })
            });
        })
    </script>
@endsection
