@extends('user.layout.user-master')

@section('contents')
    <div class="page-content">
        @auth('union_account')
            @if(auth()->user()->isMedical() && config('app.debug'))
                <div class="profile-card card">
                    <div class="row">
                        <div class="col-12">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{$error}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between">
                                <ul>
                                    <li>
                                        <strong>Total:</strong>
                                        <strong>{{$report['total']}}</strong>
                                    </li>
                                    <li>
                                        <strong class="text-success">Available:</strong>
                                        <strong>{{$report['available']['available_reports_count']}}</strong>
                                    </li>
                                    <li>
                                        <strong class="text-danger">Not Available:</strong>
                                        <strong>{{$report['unavailable']['unavailable_reports_count']}}</strong>
                                    </li>
                                </ul>

                                <div>
                                    <form class="submit-form" action="{{route('union.json.report.update')}}" method="POST">
                                        @csrf

                                        <input type="hidden" name="medical_center_id">
                                        <input type="hidden" name="fit_medical_center_id">
                                        <input type="hidden" name="unfit_medical_center_id">
                                        <input type="hidden" name="heldup_medical_center_id">

                                        <button class="btn-primary-fill" type="submit">Submit Report</button>
                                    </form>
                                </div>
                            </div>

                            <div class="mt-5 d-flex justify-content-center gap-21">
                                @php
                                    $medical_centers = \App\Models\MedicalCenter::select(['id', 'name'])->pluck('name', 'id');
                                    $allocate_centers = \App\Models\AllocateCenter::select(['id', 'name'])->pluck('name', 'id');
                                @endphp
                                <div class="form-group">
                                    <label for="medical_center">Medical Center Account</label>
                                    <select class="form-control" id="medical_center">
                                        <option value="">Select Medical Center</option>
                                        @foreach($medical_centers as $id => $name)
                                            <option value="{{$id}}">{{$name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="text-success" for="fit_medicals">Fit Allocate Center</label>
                                    <select class="form-control" id="fit_medicals">
                                        <option value="">Select Allocate Center</option>
                                        @foreach($allocate_centers as $id => $name)
                                            <option value="{{$id}}">{{$name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="text-danger" for="unfit_medicals">Unfit Allocate Center</label>
                                    <select class="form-control" id="unfit_medicals">
                                        <option value="">Select Allocate Center</option>
                                        @foreach($allocate_centers as $id => $name)
                                            <option value="{{$id}}">{{$name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="text-warning" for="heldup_medicals">Held-Up Allocate Center</label>
                                    <select class="form-control" id="heldup_medicals">
                                        <option value="">Select Allocate Center</option>
                                        @foreach($allocate_centers as $id => $name)
                                            <option value="{{$id}}">{{$name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mt-5">
                                <div class="collapse-wrapper style-one">
                                    <div class="accordion" id="accordionExample0">
                                        <!-- Single 01 -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading1">
                                                <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                                                    Unavailable Reports <span class="text-danger">({{$report['unavailable']['unavailable_reports_count']}})</span>
                                                </button>
                                            </h2>
                                            <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="heading1" data-bs-parent="#accordionExample0" >
                                                <div class="accordion-body">
                                                    <ul>
                                                        @foreach($report['unavailable']['unavailable_reports'] as $unavailable_report)
                                                            <li class="mb-4">{{$unavailable_report}}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading1">
                                                <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                                    Available Reports <span class="text-success">({{$report['available']['available_reports_count']}})</span>
                                                </button>
                                            </h2>
                                            <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2" data-bs-parent="#accordionExample1" >
                                                <div class="accordion-body">
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th>SL</th>
                                                                <th>Passport Number</th>
                                                                <th>Status</th>
                                                                <th>Remarks</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($report['available']['available_reports'] as $available_report)
                                                                <tr>
                                                                    <td>{{$loop->iteration}}</td>
                                                                    <td>{{$available_report['passport_number']}}</td>

                                                                    @php
                                                                        $class = '';;
                                                                        if ($available_report['status'] === 'fit')
                                                                        {
                                                                            $class = 'text-success';
                                                                        }
                                                                        else if ($available_report['status'] === 'unfit')
                                                                        {
                                                                            $class = 'text-danger';
                                                                        }
                                                                        else {
                                                                            $class = 'text-warning';
                                                                        }
                                                                    @endphp
                                                                    <td class="{{$class}} text-capitalize">{{$available_report['status']}}</td>
                                                                    <td>{{$available_report['remarks']}}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">

                        </div>
                    </div>
                </div>
            @endif
        @endauth
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('change', 'select#medical_center', function () {
                let el = $(this);
                let medical_center_id = el.val();

                let final_form = $('form.submit-form');
                final_form.find('input[name="medical_center_id"]').val(medical_center_id);
            });

            $(document).on('change', 'select#fit_medicals', function () {
                let el = $(this);
                let medical_center_id = el.val();

                let final_form = $('form.submit-form');
                final_form.find('input[name="fit_medical_center_id"]').val(medical_center_id);
            });

            $(document).on('change', 'select#unfit_medicals', function () {
                let el = $(this);
                let medical_center_id = el.val();

                let final_form = $('form.submit-form');
                final_form.find('input[name="unfit_medical_center_id"]').val(medical_center_id);
            });

            $(document).on('change', 'select#heldup_medicals', function () {
                let el = $(this);
                let medical_center_id = el.val();

                let final_form = $('form.submit-form');
                final_form.find('input[name="heldup_medical_center_id"]').val(medical_center_id);
            })
        });
    </script>
@endsection
