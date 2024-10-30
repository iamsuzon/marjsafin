@extends('admin.layout.user-master')

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
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between mb-25">
                            <h2>General Settings</h2>
                            <a href="{{route('admin.dashboard')}}" class="btn-primary-fill">
                                <i class="ri-arrow-left-line"></i>
                            </a>
                        </div>

                        <x-error-msg/>
                        <x-success-msg/>

                        <form action="{{route('admin.general.settings')}}" method="POST">
                            @csrf

                            <div class="contact-form">
                                <label class="contact-label">Ad Text</label>
                                <textarea class="form-control" name="ad_text" rows="5"
                                          placeholder="Ad Text">{{$adText ?? ''}}</textarea>
                            </div>

                            <div class="contact-form mt-25">
                                <div class="switch-box-style d-flex align-items-center gap-6">
                                    <input id="show_notice" type="checkbox" name="show_notice" {{$showNotice ? 'checked' : ''}}>
                                    <label class="toggle-item" for="show_notice"></label>
                                    <p class="info-text hide-text">Hide Notice</p>
                                    <p class="info-text show-text">Show Notice</p>
                                </div>
                            </div>

                            <div class="contact-form mt-15 notice-box">
                                <label class="contact-label">Notice Text</label>
                                <textarea class="form-control" name="notice_text" rows="5"
                                          placeholder="Notice Text">{{$noticeText ?? ''}}</textarea>
                                <small>To show gap use @gap</small>
                            </div>

                            <div class="contact-form mt-20 d-flex justify-content-end">
                                <button class="btn-primary-fill">Update</button>
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
            $('#show_notice').change(function () {
                if ($(this).is(':checked')) {
                    $('.notice-box').show();
                } else {
                    $('.notice-box').hide();
                }
            });

            if ($('#show_notice').is(':checked')) {
                $('.notice-box').show();
            } else {
                $('.notice-box').hide();
            }
        })
    </script>
@endsection
