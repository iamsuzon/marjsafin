@extends('union-account.layout.user-master')

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
                        <h2 class="manage__title">Application List</h2>

                        @if ($errors->any())
                            <div class="alert alert-danger mt-15">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mt-15" role="alert">
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mt-15" role="alert">
                                <strong>Success!</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{route('medical.change.password')}}" method="POST" id="search-form">
                            @csrf

                            <div class="row d-flex justify-content-center mt-25">
                                <div class="col-md-12 mt-15">
                                    <div class="contact-form">
                                        <label class="contact-label">Current Password</label>
                                        <input type="password" class="contact-input"
                                               placeholder="Write current password" name="old_password">
                                    </div>
                                </div>

                                <div class="col-md-12 mt-15">
                                    <div class="contact-form">
                                        <label class="contact-label">New Password</label>
                                        <input type="password" class="contact-input"
                                               placeholder="Write a new password" name="password">
                                    </div>
                                </div>

                                <div class="col-md-12 mt-15">
                                    <div class="contact-form">
                                        <label class="contact-label">Confirm Password</label>
                                        <input type="password" class="contact-input"
                                               placeholder="Write your new password again" name="password_confirmation">
                                    </div>
                                </div>

                                <div class="col-md-12 d-flex align-items-end justify-content-end mt-20">
                                    <div class="contact-form d-flex gap-10">
                                        <button class="btn-primary-fill search_btn_passport" type="submit">Search</button>
                                        <button class="btn-danger-fill reset_btn" type="reset">Discard</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {

        })
    </script>
@endsection
