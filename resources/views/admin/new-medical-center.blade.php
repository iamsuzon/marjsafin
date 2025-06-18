@extends('admin.layout.user-master')

@section('styles')
    <style>
        .error {
            color: red;
            font-size: 12px;
            display: none; /* Hide the error message initially */
        }

        .input-error {
            border: 1px solid red;
            background-color: #ffe6e6; /* Light red background */
        }
    </style>
@endsection

@section('contents')
    <div class="page-content">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h2>Create New Medical Center</h2>
                <a href="{{route('admin.medical-center.list')}}" class="btn-primary-fill">
                    <i class="ri-arrow-left-line"></i>
                </a>
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

            <form action="{{route('admin.new.medical-center')}}" method="POST" id="registration-form">
                @csrf

                <div class="row g-16 mt-25">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                        <div class="contact-form">
                            <label class="contact-label">Name <span class="fillable mx-1">*</span></label>
                            <select name="name" id="name" class="form-control">
                                <option value="" selected disabled>Select a center</option>
                                @foreach($centers ?? [] as $slug => $value)
                                    <option value="{{$slug}}" {{old('name') === $slug ? 'selected' : ''}}>{{ $value }}</option>
                                @endforeach
                            </select>
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
                            <label class="contact-label">Address</label>
                            <input class="form-control input" type="text" name="address" placeholder="Address" value="{{old('address')}}">
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

@section('scripts')
    <script>
        $(document).ready(function(){
            $(document).on('submit', '#registration-form', function(){
                // Check if the username input has a space
                if ($('input[name=username]').val().indexOf(' ') >= 0) {
                    $('input[name=username]').addClass('input-error'); // Add red border and background
                    $('.error').show(); // Show the error message
                    return false; // Prevent form submission
                }else {
                    $('input[name=username]').removeClass('input-error'); // Remove red border if not a space
                    $('.error').hide(); // Hide the error message if no space is present
                }
            });

            $('input[name=username]').on('keypress', function(e){
                if (e.which === 32) { // Detect spacebar key
                    $(this).addClass('input-error'); // Add red border and background
                    $('.error').show(); // Show the error message
                    return false; // Prevent space from being added
                } else {
                    $(this).removeClass('input-error'); // Remove red border if not a space
                    $('.error').hide(); // Hide the error message if no space is pressed
                }
            });
        });
    </script>
@endsection
