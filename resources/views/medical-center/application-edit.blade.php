@extends('user.layout.user-master')

@section('styles')
    <style>
        .manage__title {
                border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
        }
        .fillable {
            color: red;
        }
    </style>
@endsection

@section('contents')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between mb-20">
                            <h2>Registration Edit</h2>
                            <a href="{{route('admin.application.list')}}" class="btn-primary-fill">
                                <i class="ri-arrow-left-line"></i>
                            </a>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> {{ session('success') }} To view the details <a class="text-primary fw-bold" href="{{route('admin.application.list')}}">click here</a>
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

                        <form action="{{route('admin.application.edit', $application->id)}}" method="POST" id="registration-form">
                            @csrf

                            <div class="row g-16">
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Passport Number <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="passport_number" placeholder="Passport Number" value="{{$application->passport_number}}">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Gender<span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="gender">
                                            <option value="" selected disabled>Select an option</option>
                                            <option {{$application->gender === 'male' ? 'selected' : ''}} value="male">Male</option>
                                            <option {{$application->gender === 'female' ? 'selected' : ''}} value="female">Female</option>
                                            <option {{$application->gender === 'other' ? 'selected' : ''}} value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Traveling To<span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="traveling_to">
                                            <option value="" selected disabled>Select an option</option>
                                            @foreach(countryList() ?? [] as $index => $country)
                                                <option {{$application->traveling_to === $index ? 'selected' : ''}} value="{{ $index }}">{{ $country }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Marital Status</label>
                                        <select class="select2" name="marital_status">
                                            <option value="" selected disabled>Select an option</option>
                                            <option {{$application->marital_status === 'unmarried' ? 'selected' : ''}} value="unmarried">Unmarried</option>
                                            <option {{$application->marital_status === 'married' ? 'selected' : ''}} value="married">Married</option>
                                            <option {{$application->marital_status === 'other' ? 'selected' : ''}} value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Center Name<span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="center_name">
                                            <option value="" selected disabled>Select an option</option>
                                            @foreach(centerList() ?? [] as $index => $center)
                                                @php
                                                    $center = explode(' - ', $center)[0];
                                                @endphp
                                                <option {{$application->center_name === $index ? 'selected' : ''}} value="{{ $index }}">{{ $center }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Surname <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="surname" placeholder="Surname" value="{{$application->surname}}">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Given Name <span class="fillable mx-1">*</span></label>
                                        <input class="form-control input" type="text" name="given_name" placeholder="Given Name" value="{{$application->given_name}}">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Father Name</label>
                                        <input class="form-control input" type="text" name="father_name" placeholder="Father Name" value="{{$application->father_name}}">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Mother Name</label>
                                        <input class="form-control input" type="text" name="mother_name" placeholder="Mother Name" value="{{$application->mother_name}}">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Religion</label>
                                        <select class="select2" name="religion">
                                            <option value="" selected disabled>Select an option</option>
                                            @foreach(religionList() ?? [] as $index => $religion)
                                                <option {{$application->religion === $index ? 'selected' : ''}} value="{{ $index }}">{{ $religion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">PP Issue Place</label>
                                        <select class="select2" name="pp_issue_place">
                                            <option value="" selected disabled>Select an option</option>
                                            @foreach(ppIssuePlaceList() ?? [] as $index => $ppIssuePlace)
                                                <option {{$application->pp_issue_place === $index ? 'selected' : ''}} value="{{ $index }}">{{ $ppIssuePlace }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Profession<span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="profession">
                                            <option value="" selected disabled>Select an option</option>
                                            @foreach(ProfessionList() ?? [] as $index => $item)
                                                <option {{$application->profession === $index ? 'selected' : ''}} value="{{ $index }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Nationality<span class="fillable mx-1">*</span></label>
                                        <select class="select2" name="nationality">
                                            <option value="" selected disabled>Select an option</option>
                                            @foreach(nationality() ?? [] as $index => $item)
                                                <option {{$application->nationality === $index ? 'selected' : ''}} value="{{ $index }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Date of Birth</label>
                                        <div class="d-flex justify-content-between">
                                            <input type="date" class="contact-input" name="date_of_birth" value="{{$application->date_of_birth->format('Y-m-d')}}">
                                            <span> <b class="caret"></b></span>
                                        </div>
                                    </div>
                                </div>
{{--                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">--}}
{{--                                    <div class="contact-form">--}}
{{--                                        <label class="contact-label">Contact No<span class="fillable mx-1">*</span></label>--}}
{{--                                        <input class="form-control input" type="text" name="contact_no" placeholder="Passport Number" value="{{old('contact_no')}}">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">NID No</label>
                                        <input class="form-control input" type="number" name="nid_no" placeholder="NID Number" value="{{$application->nid_no}}">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Passport Issue Date</label>
                                        <div class="d-flex justify-content-between">
                                            <input type="date" class="contact-input" name="passport_issue_date" value="{{$application->passport_issue_date->format('Y-m-d')}}">
                                            <span> <b class="caret"></b></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="contact-form">
                                        <label class="contact-label">Passport Expiry Date</label>
                                        <div class="d-flex justify-content-between">
                                            <input type="date" class="contact-input" name="passport_expiry_date" value="{{$application->passport_expiry_date->format('Y-m-d')}}">
                                            <span> <b class="caret"></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-16 mt-25">
                                <div class="contact-form d-flex justify-content-end gap-10">
                                    <button class="btn-primary-fill" type="submit">Submit</button>
                                    <button class="btn-danger-fill" type="reset">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
