@extends('admin.layout.user-master')

@section('styles')
    <style>
        .btn-set a {
            padding: 4px 10px;
            font-size: 13px;

            i {
                font-size: 15px;
            }
        }

        .btn-pdf {
            background-color: #0963f6;
            color: #fff;

            &:hover {
                border-color: #0d6efd;
                background: transparent;
                color: #0d6efd;
            }
        }

        .item {
            border: 1px solid #e5e5e5;
            padding-top: 10px;
            padding-bottom: 20px;
        }
    </style>
@endsection

@section('contents')
    <div class="page-content">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h2 style="border-bottom: 1px solid var(--primary)">Slip Medical Center List</h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-15" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-15" role="alert">
                    {{ session('error') }}
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

            <div class="mt-25">
                <form action="{{route('admin.slip.medical-center')}}" method="POST">
                    @csrf

                    @foreach($slipMedicalCenters ?? [] as $center)
                        @continue(empty($center['list']))

                        <div class="mb-10 p-2">
                            <h5 class="mb-3">{{$center['title']}}</h5>

                            @foreach($center['list'] ?? [] as $index => $item)
                                @php
                                    $rate = $center['rates'][$index]['rate'] ?? '';
                                    $discount = $center['rates'][$index]['discount'] ?? '';
                                @endphp

                                <div class="row mb-15 item">
                                    <div class="col-3">
                                        <p class="mt-20 ml-20">{{$item}}</p>
                                        <input type="hidden" name="center_slug[]" value="{{$index}}">
                                    </div>

                                    <div class="col-3">
                                        <div class="form-group">
                                            <p>Rate (BDT)</p>
                                            <input class="form-control" type="number" name="rate[]" value="{{$rate}}">
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="form-group">
                                            <p>Discount (%)</p>
                                            <input class="form-control" type="number" name="discount[]" value="{{$discount}}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-center mt-25">
                        <button class="btn-primary-fill search_btn px-25" type="submit">Save Rates</button>
                    </div>
                </form>
            </div>
        </div>
@endsection
