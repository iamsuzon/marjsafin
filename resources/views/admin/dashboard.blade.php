@extends('admin.layout.user-master')

@section('contents')
    <div class="page-content">

                <!-- Hero -->
                <div class="hero hero-bg overflow-hidden ">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="hero-padding position-relative z-index-5">
                                <div class="welcome-card">
                                    <img class="position-absolute img-1 img-fluid" src="{{asset('assets/images/icon/hero-icon1.png')}}"
                                         alt="">
                                    <img class="position-absolute img-2 img-fluid" src="{{asset('assets/images/icon/hero-icon2.png')}}"
                                         alt="">
                                    <img class="position-absolute img-3 img-fluid" src="{{asset('assets/images/icon/hero-icon3.png')}}"
                                         alt="">
                                    <img class="position-absolute img-4 img-fluid" src="{{asset('assets/images/icon/hero-icon4.png')}}"
                                         alt="">
                                    <img class="position-absolute img-5 img-fluid" src="{{asset('assets/images/icon/hero-icon5.png')}}"
                                         alt="">
                                    <img class="position-absolute img-5 img-fluid" src="{{asset('assets/images/icon/hero-icon6.png')}}"
                                         alt="">
                                </div>
                                <div class="hero-caption">
{{--                                    <p class="para">Available Balance</p>--}}
{{--                                    <h2 class="title">$126541651321</h2>--}}
                                    <h2 class="title">Admin Dashboard</h2>
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

            </div>
@endsection
