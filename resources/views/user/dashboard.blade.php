@extends('user.layout.user-master')

@section('contents')
    <div class="page-content">

        <!-- Hero -->
        <div class="hero hero-bg overflow-hidden ">
            <div class="row">
                <div class="col-lg-12">
                    <div class="hero-padding position-relative z-index-5">
                        <div class="welcome-card">
                            <img class="position-absolute img-1 img-fluid"
                                 src="{{customAsset('assets/images/icon/hero-icon1.png')}}"
                                 alt="">
                            <img class="position-absolute img-2 img-fluid"
                                 src="{{customAsset('assets/images/icon/hero-icon2.png')}}"
                                 alt="">
                            <img class="position-absolute img-3 img-fluid"
                                 src="{{customAsset('assets/images/icon/hero-icon3.png')}}"
                                 alt="">
                            <img class="position-absolute img-4 img-fluid"
                                 src="{{customAsset('assets/images/icon/hero-icon4.png')}}"
                                 alt="">
                            <img class="position-absolute img-5 img-fluid"
                                 src="{{customAsset('assets/images/icon/hero-icon5.png')}}"
                                 alt="">
                            <img class="position-absolute img-5 img-fluid"
                                 src="{{customAsset('assets/images/icon/hero-icon6.png')}}"
                                 alt="">
                        </div>
                        <div class="hero-caption">
                            {{--                                    <p class="para">Available Balance</p>--}}
                            {{--                                    <h2 class="title">$126541651321</h2>--}}
                            <h2 class="title">Dashboard</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Hero -->

        <!-- Dashboard Card S t a r t  -->
        {{--                <div class="profile-card card">--}}
        {{--                    <!-- Title -->--}}
        {{--                    <div class="d-flex justify-content-between align-items-center">--}}
        {{--                        <div class="section-tittle mb-20">--}}
        {{--                            <h2 class="title text-capitalize font-500">Total Statistics</h2>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                    <div class="row g-24">--}}
        {{--                        <div class="col-xxl-3 col-lg-6 col-md-6 col-sm-12">--}}
        {{--                            <div class="card-style h-calc d-flex align-items-start gap-15">--}}
        {{--                                <div class="icon">--}}
        {{--                                    <i class="ri-exchange-dollar-line"></i>--}}
        {{--                                </div>--}}
        {{--                                <div class="cat-caption">--}}
        {{--                                    <p class="pera text-capitalize font-500">Total Withdraw</p>--}}
        {{--                                    <!-- Counter  -->--}}
        {{--                                    <div class="single-counter">--}}
        {{--                                        <p class="currency ">$</p>--}}
        {{--                                        <p class="amount">152025541</p>--}}
        {{--                                    </div>--}}
        {{--                                </div>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                        <div class="col-xxl-3 col-lg-6 col-md-6 col-sm-12">--}}
        {{--                            <div class="card-style h-calc d-flex align-items-start gap-15">--}}
        {{--                                <div class="icon">--}}
        {{--                                    <i class="ri-hand-heart-line"></i>--}}
        {{--                                </div>--}}
        {{--                                <div class="cat-caption">--}}
        {{--                                    <p class="pera text-capitalize font-500">Total Donor</p>--}}
        {{--                                    <!-- Counter  -->--}}
        {{--                                    <div class="single-counter">--}}
        {{--                                        <p class="currency ">$</p>--}}
        {{--                                        <p class="amount">152025541</p>--}}
        {{--                                    </div>--}}
        {{--                                </div>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                        <div class="col-xxl-3 col-lg-6 col-md-6 col-sm-12">--}}
        {{--                            <div class="card-style h-calc d-flex align-items-start gap-15">--}}
        {{--                                <div class="icon">--}}
        {{--                                    <i class="ri-briefcase-2-line"></i>--}}
        {{--                                </div>--}}
        {{--                                <div class="cat-caption">--}}
        {{--                                    <p class="pera text-capitalize font-500">Total campaigns</p>--}}
        {{--                                    <!-- Counter  -->--}}
        {{--                                    <div class="single-counter">--}}
        {{--                                        <p class="currency ">$</p>--}}
        {{--                                        <p class="amount">152025541</p>--}}
        {{--                                    </div>--}}
        {{--                                </div>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                        <div class="col-xxl-3 col-lg-6 col-md-6 col-sm-12">--}}
        {{--                            <div class="card-style h-calc d-flex align-items-start gap-15">--}}
        {{--                                <div class="icon">--}}
        {{--                                    <i class="ri-flag-2-line"></i>--}}
        {{--                                </div>--}}
        {{--                                <div class="cat-caption">--}}
        {{--                                    <p class="pera text-capitalize font-500">Total Events</p>--}}
        {{--                                    <!-- Counter  -->--}}
        {{--                                    <div class="single-counter">--}}
        {{--                                        <p class="currency ">$</p>--}}
        {{--                                        <p class="amount">152025541</p>--}}
        {{--                                    </div>--}}
        {{--                                </div>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        <!-- / card -->

        <!-- Chart -->
        {{--                <div class="card">--}}
        {{--                    <div class="section-tittle">--}}
        {{--                        <h2 class="title text-capitalize font-500">Monthly Donation Logs</h2>--}}
        {{--                    </div>--}}
        {{--                    <div class="s" id="multipleAxis"></div>--}}
        {{--                </div>--}}
        <!--/ Chart -->

        <!--Table -->
        {{--                <div class="card payment-card">--}}
        {{--                    <!-- Title -->--}}
        {{--                    <div class="d-flex justify-content-between flex-wrap gap-10 align-items-center mb-10">--}}
        {{--                        <div class="section-tittle">--}}
        {{--                            <h2 class="title text-capitalize font-500">Recent Donation Logs</h2>--}}
        {{--                        </div>--}}
        {{--                        <div class="fileter-selct">--}}
        {{--                            <select class="select2">--}}
        {{--                                <option value="0">Last Month</option>--}}
        {{--                                <option value="1">Last week</option>--}}
        {{--                                <option value="1">Last 7days</option>--}}
        {{--                            </select>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}

        {{--                    <!-- Table -->--}}
        {{--                    <div class="table-responsives table-height-350 scroll-active">--}}
        {{--                        <table class="table-color-col table-head-border">--}}
        {{--                            <thead>--}}
        {{--                            <tr>--}}
        {{--                                <th>Name</th>--}}
        {{--                                <th>Location <i class="ri-flag-2-line text-primary"></i></th>--}}
        {{--                                <th>email</th>--}}
        {{--                                <th>Number</th>--}}
        {{--                                <th>Donation Type</th>--}}
        {{--                                <th>invoice id</th>--}}
        {{--                                <th>date</th>--}}
        {{--                                <th>amount</th>--}}
        {{--                            </tr>--}}
        {{--                            </thead>--}}
        {{--                            <tbody>--}}
        {{--                            <tr>--}}
        {{--                                <td class="d-flex align-items-center">--}}
        {{--                                    <div class="profile-photo">--}}
        {{--                                        <img src="assets/images/customer/customer1.png" alt="profile">--}}
        {{--                                    </div>--}}
        {{--                                    <span class="ml-10 line-clamp-2">Mr. John</span>--}}
        {{--                                </td>--}}
        {{--                                <td>los angeles</td>--}}
        {{--                                <td>info@gamil.com</td>--}}
        {{--                                <td>018XXXXXXXX</td>--}}
        {{--                                <td>war</td>--}}
        {{--                                <td>INV2585</td>--}}
        {{--                                <td>--}}
        {{--                                    <span class="badge-basic-success-text">28-08-2025</span>--}}
        {{--                                </td>--}}
        {{--                                <td class="font-600">$152</td>--}}
        {{--                            </tr>--}}
        {{--                            <tr>--}}
        {{--                                <td class="d-flex align-items-center">--}}
        {{--                                    <div class="profile-photo">--}}
        {{--                                        <img src="assets/images/customer/customer1.png" alt="profile">--}}
        {{--                                    </div>--}}
        {{--                                    <span class="ml-10 line-clamp-2">Mr. John</span>--}}
        {{--                                </td>--}}
        {{--                                <td>los angeles</td>--}}
        {{--                                <td>info@gamil.com</td>--}}
        {{--                                <td>018XXXXXXXX</td>--}}
        {{--                                <td>war</td>--}}
        {{--                                <td>INV2585</td>--}}
        {{--                                <td>--}}
        {{--                                    <span class="badge-basic-success-text">28-08-2025</span>--}}
        {{--                                </td>--}}
        {{--                                <td class="font-600">$152</td>--}}
        {{--                            </tr>--}}
        {{--                            <tr>--}}
        {{--                                <td class="d-flex align-items-center">--}}
        {{--                                    <div class="profile-photo">--}}
        {{--                                        <img src="assets/images/customer/customer1.png" alt="profile">--}}
        {{--                                    </div>--}}
        {{--                                    <span class="ml-10 line-clamp-2">Mr. John</span>--}}
        {{--                                </td>--}}
        {{--                                <td>los angeles</td>--}}
        {{--                                <td>info@gamil.com</td>--}}
        {{--                                <td>018XXXXXXXX</td>--}}
        {{--                                <td>war</td>--}}
        {{--                                <td>INV2585</td>--}}
        {{--                                <td>--}}
        {{--                                    <span class="badge-basic-success-text">28-08-2025</span>--}}
        {{--                                </td>--}}
        {{--                                <td class="font-600">$152</td>--}}
        {{--                            </tr>--}}
        {{--                            <tr>--}}
        {{--                                <td class="d-flex align-items-center">--}}
        {{--                                    <div class="profile-photo">--}}
        {{--                                        <img src="assets/images/customer/customer1.png" alt="profile">--}}
        {{--                                    </div>--}}
        {{--                                    <span class="ml-10 line-clamp-2">Mr. John</span>--}}
        {{--                                </td>--}}
        {{--                                <td>los angeles</td>--}}
        {{--                                <td>info@gamil.com</td>--}}
        {{--                                <td>018XXXXXXXX</td>--}}
        {{--                                <td>war</td>--}}
        {{--                                <td>INV2585</td>--}}
        {{--                                <td>--}}
        {{--                                    <span class="badge-basic-success-text">28-08-2025</span>--}}
        {{--                                </td>--}}
        {{--                                <td class="font-600">$152</td>--}}
        {{--                            </tr>--}}
        {{--                            </tbody>--}}
        {{--                        </table>--}}
        {{--                    </div>--}}
        {{--                    <!-- End Table -->--}}

        {{--                </div>--}}
        <!--/ Table -->
        @auth('web')
            <marquee class="dashboard-marquee" style="color: red" width="100%" direction="left">
                {{$adText ?? ''}}
            </marquee>
        @endauth
    </div>

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

                <div class="modal-body p-0">
                    <div class="row g-10">
                        <div class="col-lg-12">
                            <h4 class="notice-title mb-25 bg-danger text-white p-3 fw-bold">Important Notice</h4>
                            <p class="lh-lg">আসসালামু আলাইকুম,
                                <br><br>
                                সম্মানিত গ্রাহকদের জানানো যাচ্ছে যে, <br>
                                রিসিপ্ট দ্রুত টাইপ করার সুবিধার্থে,
                                স্টারছাড়া অপশন যেমন ( Marital Status, Father Name, Mother Name, PP Issue Place, Date of Birth, NID No, Passport Issue Date, Passport Expiry Date, Religion)
                                <br><br>
                                টাইপ করা ছাড়া সাবমিট দিতে পারবেন।সাবমিট দিয়ে সাকসেসফুল লেখা দেখলে , এপ্লিকেশন অপশন এ গিয়ে আপনার সাবমিট করা ডাটার শেষ অপশন এর PDF অপশন এর নিচ এ প্রিন্টার চিন্ন ক্লিক দিবেন এতে যে রিসিপ্ত টা হবে তা আপনার মেডিকেল কেন্ডিডেট এর কাছে দিয়ে দিবেন।এ রিসিপ্ত টাতে কোনো প্রকার কলমের দাগ ও দেওয়া যাবে না। ধন্যবাদ।
                                <br><br>
                                [বি:দ্র: স্লিপ সাবমিট দেওয়ার আগে অবশ্যই মেডিকেল না করানোর সম্ভাবনা হলে।  সাবমিট দিবেন না। শুধু মাত্র যে জাবে তার টাই দিবেন।এতে করে কাজ এ এলো মেলো হবে না। ]</p>
                        </div>
                    </div>
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

            $('#notice-modal').modal('show');
        });
    </script>
@endsection
