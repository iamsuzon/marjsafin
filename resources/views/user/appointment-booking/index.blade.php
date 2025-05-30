@extends('user.layout.common-master')
@section('title', 'Link List')

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
        .play-btn {
            color: #FFFFFF !important;
            background-color: var(--primary);
            border-color: var(--primary);

            &:hover {
                color: var(--primary) !important;
                border-color: var(--primary);
            }
        }
        .stop-btn {
            color: #FFFFFF !important;
            background-color: var(--red);
            border-color: var(--red);

            &:hover {
                color: var(--red) !important;
                border-color: var(--red);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .loading-icon {
            display: inline-block;
            animation: spin 1s linear infinite;
        }
    </style>
@endsection

@section('contents')
    <div class="page-content">
        <div class="container-fluid">
            <x-page-tabs title="Links" :links="[
                [
                    'name' => 'Book Appointment Link List',
                    'route' => route('user.appointment.booking.registration'),
                    'active' => false,
                    'has_permission' => hasLinkPermission()
                ]
            ]"/>

            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <div class="manage__title">
                            <div class="d-flex gap-10 justify-content-start align-items-center">
                                <h2>Link List</h2>
                                <a href="javascript:void(0)" class="btn rounded-5 play-btn">
                                    <i class="ri-play-large-fill"></i>
                                </a>
                            </div>
                        </div>

                        <!-- <form id="search-from">
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
                        </form> -->
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

                            <!-- @if(session('url'))
                                আরো স্কোর এর জন্য রিকোয়েস্ট করুন <a href="{{session('url')}}" class="btn btn-primary">Request
                                    Score</a>
                            @endif -->
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
                                <th>Link Type</th>
                                <th>Details</th>
                                <th>Passport</th>
                                <th>Link Number</th>
                                <th>Links</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($linkList ?? [] as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td>{{ucfirst($item->type)}}</td>
                                    <td>
                                        <p>FN: {{ $item->first_name }}</p>
                                        <p>LS: {{ $item->last_name }}</p>
                                        <p>NID: {{$item->nid_number }}</p>
                                        <p class="text-capitalize">Gender: {{ $item->gender }}</p>
                                    </td>
                                    <td>{{ $item->passport_number }}</td>
                                    <td>{{ $item->links()->count() }}</td>
                                    <td>
                                        @foreach($item->links ?? [] as $link)
                                            <a href="{{ $link->url }}" target="_blank">{{ $loop->iteration }}. {{ Str::limit($link->url, 40) }}</a>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($item->links()->count() < 1)
                                            <a href="javascript:void(0)" class="btn btn-primary link-now-btn" data-id="{{ $item->id }}">Link Now</a>
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

                    <div class="d-flex justify-content-start pagination-custom-wrapper">
                        {{$linkList->links()}}
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

            function sendSubmitRequest(form) {
                $.ajax({
                    url: `{{ route('user.appointment.booking.submit.request.ajax') }}`,
                    type: 'GET',
                    success: function (response) {
                    },
                    error: function (xhr) {
                        console.log('An error occurred while processing your request.');
                    }
                });
            }

            let intervalId;
            $(document).on('click', '.play-btn', function () {
                let el = $(this);
                el.removeClass('play-btn').addClass('stop-btn');
                el.find('i').removeClass('ri-play-large-fill').addClass('loading-icon ri-loader-2-fill');

                intervalId = setInterval(() => {
                    sendSubmitRequest();

                    setTimeout(() => {
                        $.ajax({
                            url: `{{ route('user.appointment.booking.list.ajax') }}`,
                            type: 'GET',
                            success: function (response) {
                                if (response.status) {
                                    $('tbody').html(response.tbody);
                                    $('.pagination-custom-wrapper').html(response.pagination);

                                    toastSuccess(response.message);
                                } else {
                                    toastError(response.message);
                                }
                            },
                            error: function (xhr) {
                                console.log('An error occurred while processing your request.');
                            }
                        });
                    }, 15000);
                }, 30000);
            });

            $(document).on('click', '.stop-btn', function () {
                let el = $(this);
                el.removeClass('stop-btn').addClass('play-btn');
                el.find('i').removeClass('loading-icon ri-loader-2-fill').addClass('ri-play-large-fill');

                clearInterval(intervalId);
            });

            $(document).on('click', '.link-now-btn', function (e) {
                e.preventDefault();
                let el = $(this);
                let id = el.attr('data-id');

                el.text('Processing...');
                clearInterval(intervalId);

                $.ajax({
                    url: `{{ route('user.appointment.booking.submit.request.now.ajax') }}?id=${id}`,
                    type: 'GET',
                    success: function (response) {
                        if (response.status) {
                            toastSuccess(response.message);

                            setTimeout(() => {
                                location.read();
                            }, 30000);
                        } else {
                            el.text('Link Now');
                            toastError(response.message);
                        }
                    },
                    error: function (xhr) {
                        el.text('Link Now');
                        console.log('An error occurred while processing your request.');
                    }
                });
            });
        });
    </script>
@endsection
