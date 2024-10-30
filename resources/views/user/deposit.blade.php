@extends('user.layout.user-master')

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
            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <h2 class="manage__title mb-25">Deposit Request</h2>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> {{ session('success') }} To view the details <a class="text-primary fw-bold" href="{{route('user.application.list')}}">click here</a>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Unsuccessful!</strong> {{ session('error') }}
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

                        <form action="{{route('user.deposit.index')}}" method="POST" id="deposit-form" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-16">
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Amount (BDT)<span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="number" name="amount" placeholder="Amount Number" value="{{old('amount')}}">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Payment Method<span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="payment_method">
                                            <option value="" selected disabled>Select an option</option>
                                            @foreach(paymentMethods() ?? [] as $index => $item)
                                                <option {{old('payment_method') === $index ? 'selected' : ''}} value="{{ $index }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Reference No<span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="reference" placeholder="Reference No" value="{{old('reference')}}">
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Deposit Date<small>(Day-Month-Year)</small><span class="fillable mx-1">*</span></label>
                                        <div class="d-flex justify-content-between date-pic-icon">
                                            <input type="text" class="input single-date-picker"
                                                   placeholder="Choose Date" name="deposit_date" value="{{old('deposit_date')}}">
                                            <span> <b class="caret"></b></span>
                                            <i class="ri-calendar-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Deposit Slip<span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="file" name="deposit_slip" placeholder="Deposit Slip">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Remarks<span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="remarks" placeholder="Remarks" value="{{old('remarks')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-16 mt-25">
                                <div class="contact-form d-flex justify-content-end gap-10">
                                    <button class="btn-primary-fill" type="submit">Submit Deposit Request</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
    </script>
@endsection
