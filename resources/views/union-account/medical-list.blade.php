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
                        <h2 class="manage__title">Medical List ({{$medical_list->count()}})</h2>
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
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($medical_list ?? [] as $item)
                                <tr>
                                    <td class="align-items-center">{{$loop->iteration}}</td>
                                    <td>
                                        <a href="{{route('union.application.list')}}?center={{$item->username}}">{{$item->name}}</a>
                                    </td>
                                    <td>{{$item->application_count}}</td>
                                    <td>
                                        <a href="{{route('union.application.list')}}?center={{$item->username}}" class="btn-primary-fill">Open</a>
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
