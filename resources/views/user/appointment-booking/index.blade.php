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
                    'name' => 'Complete List',
                    'route' => route('user.appointment.booking.list.complete'),
                    'active' => false,
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
                            <div class="d-flex justify-content-between align-items-center">
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

                                <div>
                                    <select class="form-select" aria-label="City" name="city-filter">
                                        <option value="" selected disabled>Select City</option>

                                        @foreach(appointmentBookingCity() ?? [] as $key => $value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
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
                                <tr data-ap-id="{{ $item->id }}">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="serial-id" name="serial-id[]" data-id="{{ $item->id }}">
                                            <label class="form-check-label" for="serial-id">
                                                {{ $item->id }}
                                            </label>
                                        </div>
                                    </td>
                                    <td class="note">{{ $item->note }}</td>
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
                                        <div class="link-medical-wrapper">
                                            @foreach($item->links ?? [] as $link)
                                                <p class="link-medical">{{$loop->iteration}}. {{ $link->medical_center }}</p>
                                            @endforeach
                                        </div>
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
                                                <li><a class="dropdown-item complete" href="javascript:void(0)" data-id="{{ $item->id }}">Complete</a></li>
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
                }, 3000);
            });

            $(document).on('click', '.stop-btn', function () {
                let el = $(this);
                el.removeClass('stop-btn').addClass('play-btn');
                el.find('i').removeClass('loading-icon ri-loader-2-fill').addClass('ri-play-large-fill');

                clearInterval(intervalId);
            });

            $(document).on('click', '.pay-btn', function () {
                let el = $(this);
                let pay_id = el.attr('data-pay-id');

                if (pay_id === '' || pay_id === undefined) {
                    return ;
                }

                let btn_text = el.text();
                let btn_array = btn_text.trim().split('.');
                el.text(`${btn_array[0]}. Paying..`);

                $.ajax({
                    url: `{{ route('user.pay.payment.links') }}`,
                    type: 'POST',
                    data: {
                        _token: `{{ csrf_token() }}`,
                        pay_id: pay_id
                    },
                    success: function (response) {
                        if (response.status) {
                            // el.text(btn_text);
                        } else {
                            toastError(response.message);
                            el.text(btn_text);
                        }
                    },
                    error: function (xhr) {
                        // el.text(btn_text);
                    }
                });
            });

            $(document).on('click', 'a.complete', function (e) {
                let el = $(this);
                let id = el.attr('data-id');

                $.ajax({
                    url: `{{ route('user.complete.appointment') }}`,
                    type: 'POST',
                    data: {
                        _token: `{{ csrf_token() }}`,
                        id: id
                    },
                    success: function (response) {
                        if (response.status) {
                            $(`tr[data-ap-id=${id}]`).remove();

                            toastSuccess(response.message);
                            stopTimer(id);
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr);
                        console.log('An error occurred while processing your request.');
                        stopTimer(id);
                    }
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

                        {{--$.ajax({--}}
                        {{--    url: `{{ route('user.ready.payment.links') }}`,--}}
                        {{--    type: 'POST',--}}
                        {{--    data: {--}}
                        {{--        _token: `{{ csrf_token() }}`,--}}
                        {{--        id: id--}}
                        {{--    },--}}
                        {{--    success: function (response) {--}}
                        {{--        if (response.status) {--}}
                        {{--            let linkArray = response.paymentLinks;--}}
                        {{--            let appointment_booking_id = response.appointment_booking_id;--}}
                        {{--            let note = response.note;--}}
                        {{--            let appointmentBookingMedical = response.appointmentBookingMedical;--}}

                        {{--            let tr = $(`tr[data-ap-id=${appointment_booking_id}]`);--}}
                        {{--            tr.find('.note').text(note);--}}

                        {{--            let j = 1;--}}
                        {{--            let Link_medical_wrapper = $('.link-medical-wrapper');--}}
                        {{--            appointmentBookingMedical.forEach(function (value, index) {--}}
                        {{--                let p_tag = $('<p>', {--}}
                        {{--                    text: `${j++}. ${value.medical_center}`,--}}
                        {{--                    class: 'link-medical'--}}
                        {{--                });--}}

                        {{--                Link_medical_wrapper.append(p_tag);--}}
                        {{--            });--}}

                        {{--            let i = 1;--}}
                        {{--            linkArray.forEach(function(value, index) {--}}
                        {{--                let payBtn = $(`.pay-btn[data-id=${value.link_id}]`);--}}
                        {{--                payBtn.attr('data-pay-id', value.link_id);--}}
                        {{--                payBtn.find('a').text(`${i++}. Pay`);--}}
                        {{--            });--}}
                        {{--        }--}}
                        {{--    },--}}
                        {{--    error: function (xhr) {--}}

                        {{--    }--}}
                        {{--});--}}
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

            $(document).on('change', 'select[name=city-filter]', function () {
                let el = $(this);
                let city_value = el.val();

                location.href = `{{route('user.appointment.booking.list')}}?c=${city_value}`;
            });
        });
    </script>
@endsection
