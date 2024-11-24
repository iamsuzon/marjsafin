@extends('user.layout.user-master')

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
                        <h2 class="manage__title">User List ({{$user_list->count()}})</h2>
                    </div>
                </div>

                <div class="row mt-50">
                    <div class="table-responsives max-height-100vh scroll-active">
                        <table class="table-color-col table-head-border table-td-border">
                            <thead>
                            <tr>
                                <th class="mw-45">#SL</th>
                                <th>Name</th>
                                <th>Today Applications</th>
                                <th>Score</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($user_list ?? [] as $item)
                                <tr>
                                    <td class="align-items-center">{{$loop->iteration}}</td>
                                    <td>
                                        <a href="{{route('union.user.application.list')}}?username={{$item->username}}">{{$item->name}}</a>
                                    </td>
                                    <td>{{$item->application_count}}</td>
                                    <td>{{$item->balance}}</td>
                                    <td>
                                        <a href="{{route('union.user.application.list')}}?username={{$item->username}}" class="btn-primary-fill">Open</a>
{{--                                        <a href="{{route('union.application.list.generate.pdf')}}?center={{$item->username}}" class="btn-primary-fill bg-info text-white">Generate PDF</a>--}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan=4" class="text-center">No Data Found</td>
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
