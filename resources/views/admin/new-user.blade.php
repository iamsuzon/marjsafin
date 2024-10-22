@extends('admin.layout.user-master')

@section('contents')
    <div class="page-content">
        <div class="card">
            <h2>Create New User</h2>

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

            <form action="{{route('admin.new.user')}}" method="POST" id="registration-form">
                @csrf

                <div class="row g-16 mt-25">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                        <div class="contact-form">
                            <label class="contact-label">Name <span class="fillable mx-1">*</span></label>
                            <input class="form-control input" type="text" name="name" placeholder="Name" value="{{old('name')}}">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                        <div class="contact-form">
                            <label class="contact-label">Username <span class="fillable mx-1">*</span></label>
                            <input class="form-control input" type="text" name="username" placeholder="Username" value="{{old('username')}}">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                        <div class="contact-form">
                            <label class="contact-label">Email <span class="fillable mx-1">*</span></label>
                            <input class="form-control input" type="text" name="email" placeholder="Email address" value="{{old('email')}}">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                        <div class="contact-form">
                            <label class="contact-label">Password <span class="fillable mx-1">*</span></label>
                            <input class="form-control input" type="text" name="password" placeholder="Password" value="{{old('password')}}">
                        </div>
                    </div>
                </div>

                <div class="row mt-25">
                    <div class="col-xl-12 d-flex justify-content-end">
                        <button class="btn-primary-fill" type="submit">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
