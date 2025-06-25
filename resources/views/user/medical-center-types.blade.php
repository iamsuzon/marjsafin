@extends('user.layout.common-master')
@section('title', 'Type Medical Center')

@section('sidebar')
    @include('user.partials.medical-sidebar')
@endsection

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
                        <h2 class="manage__title">{{ucwords($type)}} Medical Centers</h2>
                    </div>
                </div>

                <div class="row mt-50">
                    <div class="table-responsives max-height-100vh scroll-active">
                        <table class="table-color-col table-head-border table-td-border">
                            <thead>
                            <tr>
                                <th class="mw-45">#SL</th>
                                <th>Type</th>
                                <th>Name</th>
                                <th>Note</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($medical_centers ?? [] as $center)
                                @php
                                    $name = strtoupper($center->name);
                                    $first_letter = substr($name, 0, 1);
                                    $iteration_number = $loop->iteration;

                                    $serial = "${iteration_number}{$first_letter}";
                                @endphp

                                <tr>
                                    <td>{{ $serial }}</td>
                                    <td>{{ ucwords($type) }}</td>
                                    <td>{{ $center->name }}</td>
                                    <td>{{ $center->note }}</td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
