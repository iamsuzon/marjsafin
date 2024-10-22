<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-5.3.1.min.css')}}">
    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
        rel="stylesheet"
    />
    <!-- Plugin -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/plugin.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/chart/apexcharts.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/toastr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/main-style.css')}}">

    @yield('styles')
</head>
<body>

<div id="layout-wrapper">
    <header class="header">
        <!-- Header Left -->
        <div class="left-content d-flex flex-wrap gap-10">
            <div class="header-search">
                <div class="search-icon">
                    <i class="ri-search-line"></i>
                </div>
                <input class="search-field" type="text" placeholder="Search...">
            </div>
        </div>
        <!-- / Left -->

        <!-- Header Right -->
        <ul class="header-right">
            <!-- Notification -->
            <li class="cart-list notification dropdown">
                <div class="dropdown-list-style dropdown-menu dropdown-menu-end">
                    {{--                    <div class="notification-header d-flex justify-content-between align-items-center mb-10">--}}
                    {{--                        <h6>Notifications</h6>--}}
                    {{--                        <button class="clear-notification">clear</button>--}}
                    {{--                    </div>--}}
                    {{--                    <ul class="notification-listing scroll-active p-0">--}}
                    {{--                        <li class="list">--}}
                    {{--                            <a class="list-items custom-break-spaces dropdown-item" href="javascript:void(0)">--}}
                    {{--                                <i class="ri-notification-3-line"></i>--}}
                    {{--                                <p class="line-clamp-2">Notifications show when you swipe down from  sadf asdf asdf asdf </p>--}}
                    {{--                            </a>--}}
                    {{--                        </li>--}}
                    {{--                        <li class="list">--}}
                    {{--                            <a class="list-items custom-break-spaces dropdown-item" href="javascript:void(0)">--}}
                    {{--                                <i class="ri-notification-3-line"></i>--}}
                    {{--                                <p class="line-clamp-2">Notifications show when you swipe down from  sadf asdf asdf asdf </p>--}}
                    {{--                            </a>--}}
                    {{--                        </li>--}}
                    {{--                        <li class="list">--}}
                    {{--                            <a class="list-items custom-break-spaces dropdown-item" href="javascript:void(0)">--}}
                    {{--                                <i class="ri-notification-3-line"></i>--}}
                    {{--                                <p class="line-clamp-2">Notifications show when you swipe down from  sadf asdf asdf asdf </p>--}}
                    {{--                            </a>--}}
                    {{--                        </li>--}}
                    {{--                        <li class="list">--}}
                    {{--                            <a class="list-items custom-break-spaces dropdown-item" href="javascript:void(0)">--}}
                    {{--                                <i class="ri-notification-3-line"></i>--}}
                    {{--                                <p class="line-clamp-2">Notifications show when you swipe down from  sadf asdf asdf asdf </p>--}}
                    {{--                            </a>--}}
                    {{--                        </li>--}}
                    {{--                        <li class="list">--}}
                    {{--                            <a class="list-items custom-break-spaces dropdown-item" href="javascript:void(0)">--}}
                    {{--                                <i class="ri-notification-3-line"></i>--}}
                    {{--                                <p class="line-clamp-2">Notifications show when you swipe down from  sadf asdf asdf asdf </p>--}}
                    {{--                            </a>--}}
                    {{--                        </li>--}}
                    {{--                        <li class="list">--}}
                    {{--                            <a class="list-items custom-break-spaces dropdown-item" href="javascript:void(0)">--}}
                    {{--                                <i class="ri-notification-3-line"></i>--}}
                    {{--                                <p class="line-clamp-2">Notifications show when you swipe down from  sadf asdf asdf asdf </p>--}}
                    {{--                            </a>--}}
                    {{--                        </li>--}}
                    {{--                        <li class="list">--}}
                    {{--                            <a class="list-items custom-break-spaces dropdown-item" href="javascript:void(0)">--}}
                    {{--                                <i class="ri-notification-3-line"></i>--}}
                    {{--                                <p class="line-clamp-2">Notifications show when you swipe down from  sadf asdf asdf asdf </p>--}}
                    {{--                            </a>--}}
                    {{--                        </li>--}}
                    {{--                    </ul>--}}
                    {{--                    <a href="notification.html" class="see-all-notification border-0">see all notification</a>--}}
                </div>
            </li>

            <!-- Login User -->
            <li class="cart-list dropdown">
                <!-- User Profile -->
                <div class="user-info dropdown-toggle toggle-arro-hidden" data-bs-toggle="dropdown"
                     aria-expanded="false" role="button">
                    <div class="user-img">
                        <img src="{{asset('assets/images/profile.png')}}" class="img-cover" alt="img">
                    </div>
                </div>
                <!-- Profile List -->
                <div class="dropdown-menu dropdown-list-style dropdown-menu-end white-bg with-248">
                    <ul class="profileListing">
                        <!-- User info -->
                        <a href="#" class="user-sub-info">
                            <div class="user-details">
                                <span class="name">{{auth('web')->user()->username}}</span>
                                <p class="pera">{{auth('web')->user()->email}}</p>
                            </div>
                        </a>
                        {{--                        <li class="list">--}}
                        {{--                            <a class="list-items dropdown-item" href="#">--}}
                        {{--                                <span>Profile</span>--}}
                        {{--                                <i class="ri-user-line"></i>--}}
                        {{--                            </a>--}}
                        {{--                        </li>--}}
                        {{--                        <li class="list">--}}
                        {{--                            <a class="list-items dropdown-item" href="#">--}}
                        {{--                                <span>updated password</span>--}}
                        {{--                                <i class="ri-key-2-line"></i>--}}
                        {{--                            </a>--}}
                        {{--                        </li>--}}
                        {{--                        <li class="list">--}}
                        {{--                            <a class="list-items dropdown-item" href="#">--}}
                        {{--                                <span>settings</span>--}}
                        {{--                                <i class="ri-settings-2-line"></i>--}}
                        {{--                            </a>--}}
                        {{--                        </li>--}}
                        <li class="list">
                            <a class="list-items dropdown-item" href="{{route('logout')}}">
                                <span>logout</span>
                                <i class="ri-logout-box-line"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        <!-- / Header Right -->
    </header>
    <aside class="sidebar">
        <div class="sidebar-menu">
            <div class="sidebar-menu scroll-hide">
                <ul class="sidebar-dropdown-menu parent-menu-list">
                    <li class="sidebar-menu-item">
                        <a href="#" class="parent-item-content">
                            {{Auth::user()->username}}
                        </a>
                    </li>

                    <!-- Single Menu -->
                    <li class="sidebar-menu-item {{activeCurrentSidebarMenu('dashboard')}}">
                        <a href="{{route('dashboard')}}" class="parent-item-content">
                            <i class="ri-dashboard-line"></i>
                            <span class="on-half-expanded">Dashboard</span>
                        </a>
                    </li>

                    <!-- Single Menu -->
                    <li class="sidebar-menu-item {{activeCurrentSidebarMenu('user.registration')}}">
                        <a href="{{route('user.registration')}}" class="parent-item-content">
                            <i class="ri-hand-heart-line"></i>
                            <span class="on-half-expanded">Registration</span>
                        </a>
                    </li>

                    <!-- Single Menu -->
                    <li class="sidebar-menu-item {{activeCurrentSidebarMenu('user.application.list')}}">
                        <a href="{{route('user.application.list')}}" class="parent-item-content">
                            <i class="ri-hand-heart-line"></i>
                            <span class="on-half-expanded">Application List</span>
                        </a>
                    </li>

                    <!-- Single Menu -->
{{--                    <li class="sidebar-menu-item">--}}
{{--                        <a href="campaign_categories.html" class="parent-item-content">--}}
{{--                            <i class="ri-caravan-line"></i>--}}
{{--                            <span class="on-half-expanded">New User</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}

                    <!-- Single Menu -->
                    <li class="sidebar-menu-item">
                        <a href="#" class="parent-item-content">
                            <i class="ri-caravan-line"></i>
                            <span class="on-half-expanded">Password Manage</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item">
                        <a href="{{route('logout')}}" class="parent-item-content">
                            <i class="ri-caravan-line"></i>
                            <span class="on-half-expanded">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Logo -->
            <div class="sidebar-logo d-flex justify-content-between align-items-start gap-10">
                <a href="/" class="d-block">
                    <img class="full-logo" src="./assets/images/logo.png" alt="img">
                </a>
                <button class="single change-mode border-0 mt-6">
                    <i class="ri-moon-line"></i>
                </button>
            </div>
        </div>
    </aside>

    <main class="main-content">
        @yield('contents')

        <div class="footer d-flex justify-content-between align-items-center">
            <p>&copy; 2025 <a href="#">Admin</a>. All rights reserved.</p>
            <p>Version: 1.1</p>
        </div>
    </main>
</div>

<script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-5.3.1.min.js')}}"></script>
<script src="{{asset('assets/js/popper.min.js')}}"></script>
<!-- Plugin -->
<script src="{{asset('assets/js/plugin.js')}}"></script>
<script src="{{asset('assets/js/chart/apexcharts.js')}}"></script>
<script src="{{asset('assets/js/chart/chart-custom.js')}}"></script>
<!-- Axios JS -->
<script src="{{asset('assets/js/toastr.min.js')}}"></script>
<script src="{{asset('assets/js/sweetalert2.js')}}"></script>
<script src="{{asset('assets/js/axios.min.js')}}"></script>
<!-- Main Custom JS -->
<script src="{{asset('assets/js/main.js')}}"></script>
<!-- Dev Custom JS -->
<script src="{{asset('assets/js/custom.js')}}"></script>
</body>
</html>
