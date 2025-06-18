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
                    'active' => false,
                    'has_permission' => hasLinkPermission()
                ],
                [
                    'name' => 'Complete List',
                    'route' => route('user.appointment.booking.list.complete'),
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
                                </div>
                                <div class="d-flex gap-10 justify-content-start align-items-center">
                                    <p class="fw-bold">Card: {{ $added_cards }}</p>
                                    <p class="fw-bold">Slip: {{ $user_slip_numbers ?? 0 }}</p>
                                    <p class="fw-bold">Link: 0</p>
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
                                                <p class="link-medical">{{ $link->medical_center }}</p>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        @foreach($item->links ?? [] as $link)
                                            <div class="my-2 d-flex justify-content-between align-items-center gap-5">
                                                <p class="badge badge-success text-white">
                                                    <a href="{{ str_replace('pay', 'slip', $link->url) }}" class="text-white" target="_blank">{{$loop->iteration}}. Slip</a>
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
