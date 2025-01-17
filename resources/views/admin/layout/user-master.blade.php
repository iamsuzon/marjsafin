<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{env('APP_NAME')}}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    <link rel="stylesheet" href="{{customAsset('assets/css/bootstrap-5.3.1.min.css')}}">
    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
        rel="stylesheet"
    />
    <!-- Plugin -->
    <link rel="stylesheet" type="text/css" href="{{customAsset('assets/css/plugin.css')}}">
    <link rel="stylesheet" type="text/css" href="{{customAsset('assets/css/chart/apexcharts.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{customAsset('assets/css/toastr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{customAsset('assets/css/main-style.css')}}">

    @yield('styles')
</head>
<body>
<style>
    .swal2-icon {
        font-size: 20px !important;
    }

    .sidebar .sidebar-menu .sidebar-menu .sidebar-menu-item .parent-item-content.exclude-menu-icon::after {
        content: none;
    }

    .unread-message {
        background: rgba(235, 59, 90, 0.32);
    }

    .notification-listing .list {
        padding: 4px;
        border-radius: 5px;
        position: relative;
    }

    .notification-listing .list:hover {
        background: #dadada;
    }

    .notification-listing .list span {
        position: absolute;
        right: 5px;
        top: 80%;
        transform: translateY(-50%);
        font-size: 10px;
    }
</style>

<div id="layout-wrapper">
    <header class="header">
        <!-- Header Left -->
        <div class="left-content d-flex flex-wrap gap-10">
            <!-- Dashboard -->
            <a href="{{route('admin.dashboard')}}" class="btn-primary-fill btn-sm small-btna bg-primary text-white">
                <i class="ri-dashboard-line"></i>
            </a>

            <!-- Sidebar Toggle Button -->
            <button class="half-expand-toggle sidebar-toggle">
                <i class="ri-arrow-left-right-fill"></i>
            </button>

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
            @hasrole('super-admin|sub-admin')
                @hasrole('super-admin')
                    <li class="cart-list notification dropdown" id="notification-div">
                    @php
                        $notifications = \App\Models\Notification::whereNull('extra')->whereDate('created_at', '>=', now()->subDays(2))->latest()->get();
                        $unreadNotifications = $notifications->whereNull('read_at')->count();
                    @endphp
                    <a href="javascript:void(0)" class="cart-items dropdown-toggle toggle-arro-hidden"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-notification-2-line p-0"></i>

                        @if($unreadNotifications > 0)
                            <span class="count">{{$unreadNotifications}}</span>
                        @endif
                    </a>

                    <div class="dropdown-list-style dropdown-menu dropdown-menu-end">
                        <div class="notification-header d-flex justify-content-between align-items-center mb-10">
                            <h6>Medical Notifications</h6>
                            <button class="clear-notification">Clear</button>
                        </div>
                        <ul class="notification-listing scroll-active p-0">
                            @forelse($notifications ?? [] as $notification)
                                @php
                                    $notification_type = json_decode($notification->extra, true);
                                    if ($notification_type) {
                                        $notification_type = $notification_type['type'] ?? null;
                                    } else {
                                        $notification_type = 'application';
                                    }

                                    $route = route("admin.{$notification_type}.list.single", $notification->link);
                                @endphp

                                <li class="list mb-6 {{! $notification->read_at ? 'unread-message' : ''}}">
                                    <a class="list-items custom-break-spaces dropdown-item"
                                       href="{{$route}}" target="_blank">
                                        <i class="ri-notification-3-line"></i>
                                        <p class="line-clamp-2">{{$notification->message}}</p>
                                        <span>
                                                <small>{{$notification->created_at->diffForHumans()}}</small>
                                            </span>
                                    </a>
                                </li>
                            @empty
                                <li class="list">
                                    <a class="dropdown-item my-4" href="javascript:void(0)">
                                        <p class="line-clamp text-center">No notification found</p>
                                    </a>
                                </li>
                            @endforelse
                        </ul>

                        @if($notifications->count() > 10)
                            <a href="{{route('admin.notification.all')}}" class="see-all-notification border-0">see all
                                notification</a>
                        @endif
                    </div>
                </li>
                @endhasrole

                <li class="cart-list notification dropdown" id="notification-slip-div">
                    @php
                        $slipNotifications = \App\Models\Notification::whereNotNull('extra')->whereDate('created_at', '>=', now()->subDays(2))->latest()->get();
                        $unreadSlipNotifications = $slipNotifications->whereNull('read_at')->count();
                    @endphp
                    <a href="javascript:void(0)" class="cart-items dropdown-toggle toggle-arro-hidden"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-notification-badge-line p-0"></i>

                        @if($unreadSlipNotifications > 0)
                            <span class="count">{{$unreadSlipNotifications}}</span>
                        @endif
                    </a>

                    <div class="dropdown-list-style dropdown-menu dropdown-menu-end">
                        <div class="notification-header d-flex justify-content-between align-items-center mb-10">
                            <h6>Slip Notifications</h6>
                            <button class="clear-notification">Clear</button>
                        </div>
                        <ul class="notification-listing scroll-active p-0">
                            @forelse($slipNotifications ?? [] as $notification)
                                @php
                                    $notification_type = json_decode($notification->extra, true);
                                    if ($notification_type) {
                                        $notification_type = $notification_type['type'] ?? null;
                                    } else {
                                        $notification_type = 'application';
                                    }

                                    $route = route("admin.{$notification_type}.list.single", $notification->link);
                                @endphp

                                <li class="list mb-6 {{! $notification->read_at ? 'unread-message' : ''}}">
                                    <a class="list-items custom-break-spaces dropdown-item"
                                       href="{{$route}}" target="_blank">
                                        <i class="ri-notification-3-line"></i>
                                        <p class="line-clamp-2">{{$notification->message}}</p>
                                        <span>
                                                <small>{{$notification->created_at->diffForHumans()}}</small>
                                            </span>
                                    </a>
                                </li>
                            @empty
                                <li class="list">
                                    <a class="dropdown-item my-4" href="javascript:void(0)">
                                        <p class="line-clamp text-center">No notification found</p>
                                    </a>
                                </li>
                            @endforelse
                        </ul>

                        @if($slipNotifications->count() > 0)
                            <a href="{{route('admin.notification.all')}}" class="see-all-notification border-0">see all
                                notification</a>
                        @endif
                    </div>
                </li>
            @endhasrole

            {{--            <li class="cart-list notification dropdown">--}}
            {{--                <a href="{{route('admin.report.excel.list')}}" class="cart-items dropdown-toggle toggle-arro-hidden">--}}
            {{--                    <i class="ri-file-excel-2-line p-0"></i>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            <!-- Reports -->
            {{--            <li class="cart-list position-relative d-none d-md-block">--}}
            {{--                <a href="#" class="cart-items btn-light-outline btn-sm text-14 d-flex gap-10 align-items-center">--}}
            {{--                    <div class="icon">--}}
            {{--                        <i class="ri-download-cloud-2-line"></i>--}}
            {{--                    </div>--}}
            {{--                    <span>Download dali Reports</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            <!-- create -->
            {{--            <li class="cart-list position-relative d-none d-md-block">--}}
            {{--                <a href="create_campaign.html" class="cart-items btn-primary-fill btn-sm text-14">--}}
            {{--                    <div class="icon">--}}
            {{--                        <i class="ri-add-line"></i>--}}
            {{--                    </div>--}}
            {{--                    <span>create Campaign </span>--}}
            {{--                </a>--}}
            {{--            </li>--}}

            <!-- Login User -->
            <li class="cart-list dropdown">
                <!-- User Profile -->
                <div class="user-info dropdown-toggle toggle-arro-hidden" data-bs-toggle="dropdown"
                     aria-expanded="false" role="button">
                    <div class="user-img">
                        <img src="{{customAsset('assets/images/profile.png')}}" class="img-cover" alt="img">
                    </div>
                </div>
                <!-- Profile List -->
                <div class="dropdown-menu dropdown-list-style dropdown-menu-end white-bg with-248">
                    <ul class="profileListing">
                        <!-- User info -->
                        <a href="javascript:void(0)" class="user-sub-info">
                            <div class="user-details">
                                <span class="name">{{auth('admin')->user()->username}}</span>
                                <p class="pera">{{auth('admin')->user()->email}}</p>
                            </div>
                        </a>
                        <li class="list">
                            <a class="list-items dropdown-item" href="{{route('admin.logout')}}">
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
                    <li class="sidebar-menu-item {{activeCurrentSidebarMenu('admin.dashboard')}}">
                        <a href="{{route('admin.dashboard')}}" class="parent-item-content">
                            <i class="ri-dashboard-line"></i>
                            <span class="on-half-expanded">Dashboard</span>
                        </a>
                    </li>

                    <!-- Single Menu -->
                    <li class="sidebar-menu-item {{activeCurrentSidebarMenu(['admin.application.list', 'admin.slip.list'])}}">
                        <a href="{{route('admin.application.list')}}" class="parent-item-content">
                            <i class="ri-hand-heart-line"></i>
                            <span class="on-half-expanded">Application List</span>
                        </a>
                    </li>

                    @hasrole('super-admin')
                        <li class="sidebar-menu-item {{activeCurrentSidebarMenu('admin.application-list.allocations')}}">
                            <a href="{{route('admin.application-list.allocations')}}" class="parent-item-content">
                                <i class="ri-hand-heart-line"></i>
                                <span class="on-half-expanded">Medical Center Allocations</span>
                            </a>
                        </li>
                    @endunlessrole

                    <!-- Single Menu -->
                    @hasrole('super-admin')
                    <li class="sidebar-menu-item {{activeCurrentSidebarMenu('admin.user.list')}}">
                        <a href="{{route('admin.user.list')}}" class="parent-item-content">
                            <i class="ri-hand-heart-line"></i>
                            <span class="on-half-expanded">Customer List</span>
                        </a>
                    </li>
                    @endhasrole

                    @unlessrole('sub-admin')
                        <li class="sidebar-menu-item {{activeCurrentSidebarMenu('admin.medical-center.list')}} {{activeCurrentSidebarMenu('admin.medical-center.list.application')}}">
                            <a href="{{route('admin.medical-center.list')}}" class="parent-item-content">
                                <i class="ri-hand-heart-line"></i>
                                <span class="on-half-expanded">Medical Center List</span>
                            </a>
                        </li>
                    @endunlessrole

                    @hasrole('super-admin')
                    <li class="sidebar-menu-item {{activeCurrentSidebarMenu('admin.allocate-center.list')}}">
                        <a href="{{route('admin.allocate-center.list')}}" class="parent-item-content">
                            <i class="ri-hand-heart-line"></i>
                            <span class="on-half-expanded">Allocate Center List</span>
                        </a>
                    </li>
                    @endhasrole

                    <!-- Single Menu -->
                    <li class="sidebar-menu-item {{activeCurrentSidebarMenu('admin.change.password')}}">
                        <a href="{{route('admin.change.password')}}" class="parent-item-content">
                            <i class="ri-caravan-line"></i>
                            <span class="on-half-expanded">Password Manage</span>
                        </a>
                    </li>

                    @hasanyrole('super-admin')
                    @php
                        $depositRequestCount = 0;
                        try {
                            $depositRequestCount = App\Models\PaymentLog::where('status', 'pending')->count();
                        }
                        catch (\Exception $e) {}
                    @endphp

                    <li class="sidebar-menu-item {{activeCurrentSidebarMenu('admin.deposit-request-history')}}">
                        <a href="{{route('admin.score-request-history')}}"
                           class="parent-item-content exclude-menu-icon">
                            <i class="ri-caravan-line"></i>
                            <span class="on-half-expanded">Score Request History ({{$depositRequestCount}})</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item {{activeCurrentSidebarMenu('admin.transaction-history')}}">
                        <a href="{{route('admin.transaction-history')}}" class="parent-item-content">
                            <i class="ri-caravan-line"></i>
                            <span class="on-half-expanded">Transaction History</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item {{activeCurrentSidebarMenu('admin.union-accounts')}}">
                        <a href="{{route('admin.union-accounts')}}" class="parent-item-content">
                            <i class="ri-sparkling-fill"></i>
                            <span class="on-half-expanded">Union Accounts</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item {{activeCurrentSidebarMenu('admin.slip.medical-center')}}">
                        <a href="{{route('admin.slip.medical-center')}}" class="parent-item-content">
                            <i class="ri-wallet-2-fill"></i>
                            <span class="on-half-expanded">Slip Rates</span>
                        </a>
                    </li>
                    @endhasanyrole

                    @hasanyrole('super-admin|admin')
                    <li class="sidebar-menu-item">
                        <a href="{{route('admin.general.settings')}}" class="parent-item-content">
                            <i class="ri-caravan-line"></i>
                            <span class="on-half-expanded">General Settings</span>
                        </a>
                    </li>
                    @endhasanyrole

                    @if(app()->hasDebugModeEnabled())
                        <li class="sidebar-menu-item">
                            <a href="{{route('admin.upgrade.database')}}" class="parent-item-content">
                                <i class="ri-caravan-line"></i>
                                <span class="on-half-expanded">Database Upgrade <sup
                                        class="text-danger">Dev</sup></span>
                            </a>
                        </li>
                    @endif

                    <li class="sidebar-menu-item">
                        <a href="{{route('admin.logout')}}" class="parent-item-content">
                            <i class="ri-caravan-line"></i>
                            <span class="on-half-expanded">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Logo -->
            <div class="sidebar-logo d-flex justify-content-between align-items-start gap-10">
                <a href="/" class="d-block">
                    <img class="full-logo" src="{{customAsset('assets/images/logo.png')}}" alt="img">
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
            <p>Version: 2.0</p>
        </div>
    </main>
</div>

<script src="{{customAsset('assets/js/jquery-3.7.1.min.js')}}"></script>
<script src="{{customAsset('assets/js/bootstrap-5.3.1.min.js')}}"></script>
<script src="{{customAsset('assets/js/popper.min.js')}}"></script>
<!-- Plugin -->
<script src="{{customAsset('assets/js/plugin.js')}}"></script>
<script src="{{customAsset('assets/js/chart/apexcharts.js')}}"></script>
<script src="{{customAsset('assets/js/chart/chart-custom.js')}}"></script>
<!-- Axios JS -->
<script src="{{customAsset('assets/js/toastr.min.js')}}"></script>
<script src="{{customAsset('assets/js/sweetalert2.js')}}"></script>
<script src="{{customAsset('assets/js/axios.min.js')}}"></script>
<!-- Main Custom JS -->
<script src="{{customAsset('assets/js/main.js')}}"></script>
<!-- Dev Custom JS -->
<script src="{{customAsset('assets/js/custom.js')}}"></script>

@yield('scripts')
</body>
</html>
