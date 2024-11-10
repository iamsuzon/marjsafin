@extends('user.layout.user-master')

@section('styles')
    <style>
        .manage__title {
            border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
        }
        .unread-message {
            background-color: #f5f5f5 !important;
        }
        .unread-message a {
            font-weight: bold;
        }
        .unread-message:hover {
            background-color: #d1d1d1 !important;
        }
    </style>
@endsection

@section('contents')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <div class="manage__title d-flex justify-content-between">
                            <h2>All Notifications</h2>
                        </div>
                    </div>
                </div>

                <div class="row mt-50">
                    <div class="table-responsives max-height-100vh scroll-active">
                        <table class="table-color-col table-head-border table-td-border">
                            <thead>
                            <tr>
                                <th class="mw-45">#SL</th>
                                <th>Notification</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($notifications ?? [] as $item)
                                <tr class="{{! $item->read_at ? 'unread-message' : ''}}">
                                    <td class="align-items-center">{{$loop->iteration}}</td>
                                    <td class="align-items-center">
                                        <a href="{{$item->link}}">{{$item->message}}</a>
                                    </td>
                                    <td class="align-items-center">
                                        {{$item->created_at->format('d-M-Y')}}
                                        <small>({{$item->created_at->diffForHumans()}})</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan=11" class="text-center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
