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
</head>
<body>
<style>
    .login-bg::before {
        background: none;
    }
</style>

<div id="layout-wrapper">
    <main>
        <!-- Login area S t a r t  -->
        <div class="login-area login-bg">
            <div class="container-fluid">
                <div class="row justify-content-sm-center">
                    <div class="offset-lg-1 rtl-offset-lg-1 col-xxl-3 col-xl-5 col-lg-6 col-md-8 col-sm-10">
                        <div class="login-card">

                            <!-- Logo -->
                            <div class="logo logo-large mb-40">
                                <h1 class="font-600 text-center">Admin</h1>
                                <h4 class="text-18 font-600 text-center">Login to Medical Provide System</h4>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <!-- Form -->
                            <form action="{{route('admin.login')}}" method="POST">
                                @csrf

                                <div class="position-relative contact-form mb-24">
                                    <label class="contact-label">Username </label>
                                    <input class="form-control contact-input" type="text" name="username"
                                           placeholder="Enter Your Username" value="{{old('username')}}">
                                </div>
                                <!-- Password -->
                                <div class="position-relative contact-form mb-24">
                                    <div class="d-flex justify-content-between aligin-items-center">
                                        <label class="contact-label">Password</label>
                                        <a href="#"><span class="text-danger-soft text-12"> Forgot password? </span></a>
                                    </div>
                                    <input type="password" class="form-control contact-input password-input" name="password"
                                           id="txtPasswordLogin" placeholder="Confirm Password">
                                    <i class="toggle-password ri-eye-line"></i>
                                </div>


{{--                                <div class="position-relative contact-form mb-24">--}}
{{--                                    {!! captcha_img() !!}--}}

{{--                                    <input class="form-control contact-input mt-15" type="text" name="captcha"--}}
{{--                                           placeholder="Enter the captcha code">--}}
{{--                                </div>--}}

                                <button class="btn-primary-fill justify-content-center w-100" type="submit">
                                        <span class="d-flex align-items-center justify-content-center gap-6">
                                            <i class="las la-check-circle"></i>
                                            <span>Login</span>
                                        </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Login -->
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
</body>
</html>
