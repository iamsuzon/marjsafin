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
        <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-15">
            <div class="d-flex align-items-center gap-8">
                <div class="icon text-title text-23">
                    <i class="ri-terminal-line"></i>
                </div>
                <h6 class="card-title text-18">Medical List</h6>
            </div>
            <!-- Sub Menu -->
            <div class="sub-menu-wrapper">
                <ul class="sub-menu-list">
                    <li class="sub-menu-item">
                        <a href="{{route('admin.medical-center.list')}}" class="single {{activeCurrentUrl(route('admin.medical-center.list'))}}">
                            Manage Centers
                        </a>
                    </li>
                    <li class="sub-menu-item">
                        <a href="{{route('admin.medical-center.list.application')}}" class="single {{activeCurrentUrl(route('admin.medical-center.list.application'))}}">
                            All Center Applications
                        </a>
                    </li>
                </ul>
            </div>
            <!-- / Sub Menu -->
        </div>

        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <h2 class="manage__title">Medical List</h2>

                        <form id="search-from">
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
                                    <!-- Date Picker -->
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
                                        <button class="btn-primary-fill generate_all_btn" type="submit">Generate PDF</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show my-2" role="alert">
                                {{ session('error') }}
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
                            @forelse($medicalCenterList ?? [] as $item)
                                <tr>
                                    <td class="align-items-center">{{$loop->iteration}}</td>
                                    <td>
                                        <a href="{{route('admin.medical.application.list')}}?center={{$item->username}}">{{$item->name}}</a>
                                    </td>
                                    <td>{{$item->application_count}}</td>
                                    <td>
                                        <a href="{{route('admin.medical.application.list')}}?center={{$item->username}}" class="btn-primary-fill">Open</a>
                                        <a href="{{route('admin.application.list.generate.pdf')}}?center={{$item->username}}" class="btn-primary-fill bg-info text-white">Generate PDF</a>
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

@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click', '.generate_all_btn', function (e) {
                e.preventDefault();

                let form = $('#search-from');
                let start_date = form.find('.start_date').val();
                let end_date = form.find('.end_date').val();

                window.location.href = `{{route('admin.medical.application.list.generate.pdf')}}?start_date=${start_date}&end_date=${end_date}`;
            });
        });
    </script>
@endsection
