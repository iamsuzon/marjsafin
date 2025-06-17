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
        .copy-url {
            cursor: pointer;
        }
        .action-dropdown .dropdown-menu {
            margin-top: 5px !important;
            background-color: #FFFFFF !important;
        }

        .action-dropdown .dropdown-item {
            margin-top: 8px !important;
            background-color: #FFFFFF !important;
            color: var(--primary) !important;
            border: 1px solid var(--primary) !important;
            padding: 8px 10px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            border-radius: 5px !important;
            transition: background-color 0.3s ease;

            &:hover {
                background-color: var(--primary) !important;
                color: #FFFFFF !important;
                border: 1px solid var(--primary) !important;
            }
        }

        p:has(a.disabled) {
            background-color: slategrey;
        }
    </style>
@endsection

@section('contents')
    <div class="page-content">
        <div class="container-fluid">
            <x-page-tabs title="Links" :links="[
                [
                    'name' => 'Book Appointment Link Register',
                    'route' => route('user.appointment.booking.registration'),
                    'active' => true,
                    'has_permission' => hasLinkPermission()
                ],
                [
                    'name' => 'ri-bank-card-line',
                    'is_icon' => true,
                    'route' => route('user.card.manage'),
                    'active' => false,
                    'has_permission' => hasLinkPermission()
                ]
            ]"/>

            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <div class="manage__title">
                            <div class="d-flex justify-content-start gap-20">
                                <div class="d-flex gap-10 justify-content-start align-items-center">
                                    <h2>Link List</h2>
                                    <a href="javascript:void(0)" class="btn rounded-5 play-btn">
                                        <i class="ri-play-large-fill"></i>
                                    </a>
                                </div>
                                <div class="d-flex gap-10 justify-content-start align-items-center">
                                    <p class="fw-bold">Card: {{ $added_cards }}</p>
                                    <p class="fw-bold">Slip: {{ $user_slip_numbers ?? 0 }}</p>
                                    <p class="fw-bold">Link: 0</p>
                                </div>
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
                                <th>Note</th>
                                <th>Date & Ref</th>
                                <th>Link Type</th>
                                <th>Details</th>
                                <th>Paid Center Name</th>
                                <th>Links</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($linkList ?? [] as $item)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="serial-id" name="serial-id[]" data-id="{{ $item->id }}">
                                            <label class="form-check-label" for="serial-id">
                                                {{ $item->id }}
                                            </label>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td>
                                        <p>Date: {{ $item->created_at->format('d/m/Y') }}</p>
                                        <p>Reference: {{ $item->reference }}</p>
                                    </td>
                                    <td>
                                        <p class="timer text-danger"></p>
                                        <p>{{ucwords(str_replace('_',' ',$item->type))}}</p>
                                    </td>
                                    <td>
                                        <p>PP: {{ $item->passport_number }}</p>
                                        <p>FN: {{ $item->first_name }}</p>
                                        <p>LS: {{ $item->last_name }}</p>
                                        <p>NID: {{$item->nid_number }}</p>
                                        <p class="text-capitalize">Gender: {{ $item->gender }}</p>
                                    </td>
                                    <td class="medical-center-names">
                                        <p class="text-capitalize choice-medical">
                                            {{ str_replace('-',' ',$item->center_name) }}
                                        </p>
                                        <p>-------</p>
                                        @foreach($item->links ?? [] as $link)
                                            <p class="link-medical">{{ $link->medical_center }}</p>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($item->links ?? [] as $link)
                                            <div class="my-2 d-flex justify-content-between align-items-center gap-5">
                                                <p class="badge badge-success text-white pay-btn" data-id="{{ $link->id }}" data-appointment-url="{{ $link->url }}" data-url="{{ $link->url }}">
                                                    <a href="javascript:void(0)" class="text-white">{{$loop->iteration}}. Link</a>
                                                </p>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @php
                                            if ($item->type === 'normal') {
                                                $status = $item->links()->count() === 1 ? 'Submitted' : 'Pending';
                                            } elseif ($item->type === 'normal_plus') {
                                                $status = $item->links()->count() === 4 ? 'Submitted' : 'Pending';
                                            } elseif ($item->type === 'special') {
                                                $status = $item->links()->count() === 5 ? 'Submitted' : 'Pending';
                                            } elseif ($item->type === 'special_plus') {
                                                $status = $item->links()->count() === 7 ? 'Submitted' : 'Pending';
                                            }
                                        @endphp

                                        {{ $status }}
                                    </td>
                                    <td>
                                        <div class="action-dropdown dropdown">
                                            <button class="btn btn-primary btm-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item" href="{{ route('user.appointment.booking.edit.registration', $item->passport_number) }}">Edit</a></li>
                                                <li><a class="dropdown-item ready-payment-btn" href="javascript:void(0)" data-id="{{ $item->id }}">Ready Payment Processing</a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Complete</a></li>
                                            </ul>
                                        </div>
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

            let intervalId;
            $(document).on('click', '.play-btn', function () {
                let el = $(this);
                el.removeClass('play-btn').addClass('stop-btn');
                el.find('i').removeClass('ri-play-large-fill').addClass('loading-icon ri-loader-2-fill');

                toastSuccess(`কিছুক্ষণের মধ্যে অটোমেটিক হয়ে যাবে। ধন্যবাদ।`);

                intervalId = setInterval(() => {
                    // setTimeout(() => {
                        $.ajax({
                            url: `{{ route('user.appointment.booking.list.ajax') }}`,
                            type: 'GET',
                            success: function (response) {
                                if (response.status) {
                                    $('tbody').html(response.tbody);
                                    $('.pagination-custom-wrapper').html(response.pagination);
                                } else {
                                    toastError(response.message);
                                }
                            },
                            error: function (xhr) {
                                console.log('An error occurred while processing your request.');
                            }
                        });
                    // }, 15000);
                }, 5000);
            });

            $(document).on('click', '.stop-btn', function () {
                let el = $(this);
                el.removeClass('stop-btn').addClass('play-btn');
                el.find('i').removeClass('loading-icon ri-loader-2-fill').addClass('ri-play-large-fill');

                clearInterval(intervalId);
            });

            $(document).on('click', '.pay-btn', function () {
                let url = $(this).data('url');
                console.log(url);
                navigator.clipboard.writeText(url).then(() => {
                    toastSuccess('Link copied to clipboard!');
                }).catch(err => {
                    toastError('Failed to copy URL: ' + err);
                });
            });


            $(document).on('click', '.ready-payment-btn', function () {
                let el = $(this);
                let id = el.data('id');

                // store the id in local storage corresponding the current time and store it as an array item
                let currentTime = new Date().getTime();
                let readyPaymentIds = JSON.parse(localStorage.getItem('readyPaymentIds')) || [];

                // so we have to add the id as like [[id: id, time: currentTime], ...] but first check if the id already exists
                let existingIndex = readyPaymentIds.findIndex(item => item.id === id);

                if (existingIndex === -1) {
                    readyPaymentIds.push({id: id, time: currentTime});
                    localStorage.setItem('readyPaymentIds', JSON.stringify(readyPaymentIds));
                } else {
                    // toastError("Counter Already Running");
                    // return ;
                }

                // if the local storage has readyPaymentIds then show a decreasing timer of the time corresponding to the id of th each item
                if (readyPaymentIds.length > 0) {
                    let timerElement = el.closest('tr').find('.timer');
                    timerElement.text('Processing...');
                    startTimer(readyPaymentIds);

                    $.ajax({
                        url: `{{ route('user.init.payment.process') }}`,
                        type: 'POST',
                        data: {
                            _token: `{{ csrf_token() }}`,
                            id: id
                        },
                        success: function (response) {
                            if (response.status) {
                                toastSuccess(response.message);
                            } else {
                                toastError(response.message);
                                stopTimer(id);
                            }
                        },
                        error: function (xhr) {
                            console.log(xhr);
                            console.log('An error occurred while processing your request.');
                            stopTimer(id);
                        }
                    });
                } else {
                    toastError('No IDs found in local storage.');
                }
            });

            let timerIntervalArray = [];
            function startTimer(readyPaymentIds) {
                let checkTrId = readyPaymentIds.find(item => item.id === $('input#serial-id[type="checkbox"]').data('id'));
                if (!checkTrId) {
                    return;
                }

                readyPaymentIds.forEach(element => {
                    startPaymentTimer(element);
                });

                // let timerInterval = setInterval(() => {
                //         readyPaymentIds.forEach(element => {
                //
                //             let timerElement = $(`tr:has(input[data-id="${element.id}"]) .timer`);
                //
                //             let id = element.id;
                //             let time = element.time;
                //             let currentTime = new Date().getTime();
                //             let countdown = Math.max(0, Math.floor((time + 7200000 - currentTime) / 1000)); // 2 hours countdown
                //
                //             if (countdown > 0) {
                //                 let hours = Math.floor(countdown / 3600);
                //                 let minutes = Math.floor((countdown % 3600) / 60);
                //                 let seconds = countdown % 60;
                //                 timerElement.text(`${hours.toString().padStart(2, '0')} : ${minutes.toString().padStart(2, '0')} : ${seconds.toString().padStart(2, '0')}`);
                //             } else {
                //                 timerElement.text(``);
                //                 readyPaymentIds = readyPaymentIds.filter(item => item.id !== id);
                //                 localStorage.setItem('readyPaymentIds', JSON.stringify(readyPaymentIds));
                //
                //                 if (readyPaymentIds.length === 0) {
                //                     clearInterval(timerInterval);
                //                 }
                //             }
                //     });
                // }, 1000);
            }

            let readyPaymentTimers = {}; // Object to store interval IDs per readyPaymentId
            function startPaymentTimer(element) {
                let id = element.id;
                let time = element.time;

                // Clear any existing timer for this ID first to prevent duplicates
                if (readyPaymentTimers[id]) {
                    clearInterval(readyPaymentTimers[id]);
                }

                let intervalId = setInterval(() => {
                    let timerElement = $(`tr:has(input[data-id="${id}"]) .timer`);
                    let currentTime = new Date().getTime();
                    let countdown = Math.max(0, Math.floor((time + 7200000 - currentTime) / 1000)); // 2 hours countdown

                    if (countdown > 0) {
                        let hours = Math.floor(countdown / 3600);
                        let minutes = Math.floor((countdown % 3600) / 60);
                        let seconds = countdown % 60;
                        timerElement.text(`${hours.toString().padStart(2, '0')} : ${minutes.toString().padStart(2, '0')} : ${seconds.toString().padStart(2, '0')}`);
                    } else {
                        timerElement.text(``);
                        clearInterval(intervalId); // Stop this specific timer
                        delete readyPaymentTimers[id]; // Remove from our tracking object

                        // Remove from readyPaymentIds and localStorage if it's the source of truth
                        readyPaymentIds = readyPaymentIds.filter(item => item.id !== id);
                        localStorage.setItem('readyPaymentIds', JSON.stringify(readyPaymentIds));

                        // If all timers are done, you might want to do something globally
                        if (Object.keys(readyPaymentTimers).length === 0) {
                            console.log("All payment timers have completed.");
                        }
                    }
                }, 1000);

                readyPaymentTimers[id] = intervalId; // Store the interval ID
                localStorage.setItem('readyPaymentTimers', JSON.stringify(readyPaymentTimers));
            }

            function stopTimer(singlePaymentId) {
                // Clear the specific timer for the given ID
                if (readyPaymentTimers[singlePaymentId]) {
                    clearInterval(readyPaymentTimers[singlePaymentId]);
                    delete readyPaymentTimers[singlePaymentId]; // Remove from our tracking object

                    $(`tr:has(input[data-id="${singlePaymentId}"]) .timer`).text(``);
                }

                // Also remove from localStorage if needed
                let readyPaymentIds = JSON.parse(localStorage.getItem('readyPaymentIds')) || [];
                readyPaymentIds = readyPaymentIds.filter(item => item.id !== singlePaymentId);
                localStorage.setItem('readyPaymentIds', JSON.stringify(readyPaymentIds));

                // If all timers are done, you might want to do something globally
                if (Object.keys(readyPaymentTimers).length === 0) {
                    console.log("All payment timers have completed.");
                }
            }

            let readyPaymentIds = JSON.parse(localStorage.getItem('readyPaymentIds')) || [];
            if (readyPaymentIds.length > 0) {
                startTimer(readyPaymentIds);
            }

            let popup = null;
            let pollInterval = null;
            $(document).on('click', '.pay-btn', function (e) {
                return ;
                let el = $(this);
                let id = el.data('id');
                let appointment_url = el.data('appointment-url');

                if (el.find('a').hasClass('disabled'))
                {
                    return;
                }

                // popup = window.open('', '_blank','width=500,height=700');
                // popup.document.open();
                // popup.document.write(`<h1>Payment is under process. Please wait..</h1>`);
                // popup.document.close();

                el.find('a').html(`<i class="loading-icon ri-loader-2-fill"></i>`);
                el.find('a').addClass('disabled');

                axios.get(`{{ route('user.scrap.payment.page.data') }}?id=${id}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'text/html',
                    }
                })
                .then(function (response) {
                    const html = response.data;

                        if (popup) {
                            // popup.document.open();
                            // popup.document.write(html);
                            // popup.document.close();

                            let attempt = 0;
                            const maxAttempts = 10;

                            pollInterval = setInterval(() => {
                                attempt++;
                                checkLastPage(appointment_url, id, el, attempt);

                                if (attempt >= maxAttempts) {
                                    popup.close();
                                    clearInterval(pollInterval);
                                }
                            }, 30000);
                        } else {
                            alert('Popup blocked. Please allow popups.');
                        }
                })
                .catch(function (error) {
                    toastError('Something went wrong. Try again later');
                })
                .finally(function () {
                    el.find('a').text(`Pay`);
                    el.find('a').removeClass('disabled');
                });
            });

            function checkLastPage(appointment_url, link_id, el)
            {
                axios.post(`{{ route('user.check.last.page') }}`, {
                    _token: `{{ csrf_token() }}`,
                    link_id: link_id,
                    current_url: appointment_url
                })
                .then(function (response) {
                    const data = response.data;

                    if (data.status) {
                        toastSuccess(data.message);
                        el.closest('tr').find('.medical-center-names').text(data.medical_center);
                        clearInterval(pollInterval);

                        if (popup && !popup.closed) {
                            popup.close();
                        }
                    } else {
                        if (popup && popup.closed) {
                            clearInterval(pollInterval);
                            toastError(data.message);
                        }
                    }
                })
                .catch(function (error) {
                    toastError('Payment Failed!');
                });
            }
        });
    </script>
@endsection



{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<!-- Return for case of Http Method is Post -->--}}
{{--<head>--}}
{{--    <meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-inline' https://cdn.payfort.com;">--}}
{{--    <title>Return URL</title>--}}
{{--    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">--}}
{{--    <script src="https://cdn.payfort.com/jquery/jquery-2.1.4.min.js" integrity="sha256-qw0GO0/ygnGSwORBA9MJFFeh0jdMO2JDchxWebth6uI=" crossorigin="anonymous"></script>--}}
{{--    <script>--}}
{{--        /**--}}
{{--         * Return url for case of Http Method is Post--}}
{{--         */--}}

{{--        $(document).ready(function() {--}}
{{--            init();--}}
{{--        });--}}

{{--        var init = function() {--}}
{{--            var s = returnUrlParams;--}}
{{--            // alert(obj.toSource());--}}
{{--            // console.log(obj);--}}
{{--            //	var s = jQuery.parseJSON(obj);--}}
{{--            jQuery.each(s, function(key, value) {--}}
{{--                var input = $('<input>').attr({--}}
{{--                    'value' : value,--}}
{{--                    'name' : key,--}}
{{--                    'type' : "text"--}}
{{--                });--}}
{{--                $('#returnUrlForm').append(input);--}}
{{--            });--}}
{{--            // $('#returnUrlForm').submit();--}}
{{--        };--}}
{{--    </script>--}}
{{--    <script type="text/javascript">--}}
{{--        var returnUrlParams = {"response_code":"18000","card_number":"529366******5853","card_holder_name":"MD MOHIUDDIN","signature":"6298fce25df00d8f862b17c5de65e68fb7ce814cc7faa99e4f042f62d3c3fd1170d46ceb844a121c2744203187cd99c806f274d8e970583aec05fdbb86352912","merchant_identifier":"abKSBKKe","expiry_date":"2703","access_code":"QbRIlPcveY8j3Hv7CxNO","language":"en","response_message":"Success","service_command":"TOKENIZATION","merchant_reference":"Appointment-91902202570873038-yf20Wd44cM","token_name":"c3dfba552aa8492eb3ea81312889c31c","return_url":"https://wafid.com/appointment/0LzMnwz9vKleO2K/pay/","status":"18","card_bin":"529366"};--}}
{{--    </script>--}}





{{--    <script>--}}
{{--        (function (n, i, v, r, s, c, x, z) {--}}
{{--            x = window.AwsRumClient = {q: [], n: n, i: i, v: v, r: r, c: c};--}}
{{--            window[n] = function (c, p) {--}}
{{--                x.q.push({c: c, p: p});--}}
{{--            };--}}
{{--            z = document.createElement('script');--}}
{{--            z.async = true;--}}
{{--            z.src = s;--}}
{{--            z.integrity = "sha256-BsQtaTcImfZ5Kk+IvRIQsw8IPyxgIjsNR5pcoYHpGSA=";--}}
{{--            z.crossOrigin = "anonymous";--}}
{{--            let child = document.getElementsByTagName('script')[0];--}}
{{--            if(z.contains(child)){--}}
{{--                document.head.insertBefore(z, child);--}}
{{--            } else {--}}
{{--                document.head.appendChild(z);--}}
{{--            }--}}

{{--        })('cwr', 'a9679614-1aff-4d18-aff9-56c60851230b', '1.0.0', 'us-east-1', 'https://cdn.payfort.com/monitoring/js/cwr_1_12_0.js', {--}}
{{--            sessionSampleRate: 1,--}}
{{--            guestRoleArn: "arn:aws:iam::578084145834:role/RUM-Monitor-us-east-1-578084145834-9922202679461-Unauth",--}}
{{--            identityPoolId: "us-east-1:4f5aae78-ef26-4d8c-9800-50fd1fb94b2c",--}}
{{--            endpoint: "https://dataplane.rum.us-east-1.amazonaws.com",--}}
{{--            telemetries: ["performance", "errors", "http"],--}}
{{--            allowCookies: true,--}}
{{--            enableXRay: true,--}}
{{--            sessionAttributes: {--}}
{{--                token: '',--}}
{{--                referrer: document.referrer,--}}
{{--            }--}}
{{--        });--}}
{{--    </script>--}}
{{--</head>--}}
{{--<body>--}}
{{--<form method="POST" action="https://wafid.com/appointment/0LzMnwz9vKleO2K/pay/" id="returnUrlForm"--}}
{{--      name="returnUrlForm">--}}
{{--    <button type="submit">Submit</button>--}}
{{--</form>--}}
{{--</body>--}}
{{--</html>--}}
