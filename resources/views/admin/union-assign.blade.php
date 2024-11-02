@extends('admin.layout.user-master')

@section('contents')
    <style>
        .fillable {
            color: red;
        }
    </style>

    <div class="page-content">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h2>Union List - {{$type === 'user' ? 'User' : 'Medical Center'}}</h2>

                @hasanyrole('super-admin|admin')
                <a href="{{route('admin.union-accounts')}}" class="btn-primary-fill">
                    <i class="ri-arrow-left-line"></i>
                </a>
                @endhasanyrole
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <p><strong>Kindly fill up all the required fields</strong></p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{route('admin.assign.union-accounts', $account->id)}}" method="POST" id="union-registration-form">
                @csrf

                <input type="hidden" value="{{$account->id}}" name="account_id">

                <div class="row g-16 mt-25">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                        <div class="contact-form">
                            <label class="contact-label">Name <span class="fillable mx-1">*</span></label>
                            <input class="form-control input" type="text" name="name" placeholder="Name" value="{{$account->name}}" disabled>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                        <div class="contact-form">
                            <label class="contact-label">Username <span class="fillable mx-1">*</span></label>
                            <input class="form-control input" type="text" name="name" placeholder="Name" value="{{$account->username}}" disabled>
                        </div>
                    </div>

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <div class="contact-form mt-20">
                            <p class="mb-15 fw-bold">Select all {{$type}}s <span class="fillable mx-1">*</span></p>

                            @foreach($sub_accounts as $sub_account)
                                <div class="form-group mb-3">
                                    <input id="{{$sub_account->username}}" type="checkbox" name="sub_account_id[]" value="{{$sub_account->id}}"
                                           @if(in_array($sub_account->id ,$account->sub_accounts)) checked @endif
                                    >
                                    <label for="{{$sub_account->username}}">{{$sub_account->name}}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="row mt-25">
                    <div class="col-xl-12 d-flex justify-content-end">
                        <button class="btn-primary-fill" type="submit">Update</button>
                    </div>
                </div>
            </form>
    </div>
@endsection
