@extends('admin.layout.user-master')

@section('styles')
    <style>
        ul.excel-list li {
            padding: 15px 10px;
            border: 1px solid #00715d;
            margin-bottom: 15px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            cursor: pointer;

            &.date-wise-csv {
                display: block;
                justify-content: initial;

                span {
                    align-self: center;
                }
            }

            &:hover {
                background-color: #00715d;

                a, span, small {
                    color: #fff;
                }
            }

            a, span {
                margin-inline: 20px;
            }

            small {
                font-size: 13px;
            }
        }

        ul.excel-list li.date-wise-csv {
            &:hover {
                background-color: #fff;

                a, span, small {
                    color: #00715d;
                }
            }
        }
    </style>
@endsection

@section('contents')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <div class="manage__title d-flex justify-content-between">
                            <h2>Download Report in CSV</h2>

                            <div>
                                @php
                                    $medicalCenters = \App\Models\MedicalCenter::select(['id', 'name'])->get();
                                    $excel_medical_center_id = \App\Models\StaticOption::where('key', 'medical_center_id_for_excel')->first()->value ?? '0';
                                @endphp

                                <label for="medical-center">Select Medical Center</label>
                                <select name="medical_center" id="medical-center" class="form-control">
                                    <option {{$excel_medical_center_id == '0' ? 'selected' : ''}} value="0">All Medical
                                        Center
                                    </option>
                                    @foreach($medicalCenters as $center)
                                        <option
                                            {{$excel_medical_center_id == $center->id ? 'selected' : ''}} value="{{$center->id}}">{{ucfirst(strtolower($center->name))}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-50">
                    <div class="table-responsives max-height-100vh scroll-active">
                        <ul class="excel-list">
                            <li class="date-wise-csv p-4 pr-0">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex justify-content-between gap-21">
                                        <div class="contact-form">
                                            <label class="contact-label">Start Date </label>
                                            <div class="d-flex justify-content-between date-pic-icon">
                                                <input type="text"
                                                       class="contact-input single-date-picker start_date"
                                                       placeholder="Choose Date">
                                                <span> <b class="caret"></b></span>
                                                <i class="ri-calendar-line"></i>
                                            </div>
                                        </div>
                                        <div class="contact-form">
                                            <label class="contact-label">End Date </label>
                                            <div class="d-flex justify-content-between date-pic-icon">
                                                <input type="text" class="contact-input single-date-picker end_date"
                                                       placeholder="Choose Date">
                                                <span> <b class="caret"></b></span>
                                                <i class="ri-calendar-line"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <span>
                                        <a href="javascript:void(0)" data-key="custom">Generate CSV</a>
                                    </span>
                                </div>
                            </li>

                            @foreach($list ?? [] as $key => $item)
                                <li class="normal-csv">
                                    <a href="javascript:void(0)" data-key="{{$item['key']}}">{{$item['title']}}
                                        <small>({{$item['date']}})</small></a>
                                    <span>Generate CSV</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('change', 'select[name=medical_center]', function () {
                let el = $(this);
                let medicalCenterId = $(this).val();

                axios.post('{{route('admin.excel.report.set.medical')}}', {medical_center_id: medicalCenterId})
                    .then(function (response) {
                        let data = response.data;

                        if (data.status) {
                            el.find('option').removeAttr('selected');
                            el.find('option[value=' + medicalCenterId + ']').attr('selected', 'selected');
                            toastSuccess(data.message);
                        } else {
                            toastError(data.message);
                        }
                    })
                    .catch(function (error) {

                    });
            });

            $(document).on('click', 'ul.excel-list li.normal-csv, ul.excel-list li.date-wise-csv span', function () {
                let el = $(this);
                let key = el.find('a').data('key');
                let startDate = $('.start_date').val();
                let endDate = $('.end_date').val();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to download this report in CSV format!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33333',
                    confirmButtonText: 'Yes, download it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post('{{route('admin.report.excel.list')}}', {
                            key: key,
                            start_date: startDate,
                            end_date: endDate
                        })
                            .then(function (response) {
                                if (response.data.status === false) {
                                    toastError(response.data.message);
                                    return;
                                }

                                const contentDisposition = response.headers['content-disposition'];
                                let fileName = 'orders.csv'; // Default name

                                if (contentDisposition && contentDisposition.includes('filename=')) {
                                    fileName = contentDisposition.split('filename=')[1].trim();
                                    fileName = fileName.replace(/['"]/g, ''); // Remove quotes if present
                                }

                                const url = window.URL.createObjectURL(new Blob([response.data]));
                                const link = document.createElement('a');
                                link.href = url;
                                link.setAttribute('download', fileName); // Specify the file name
                                document.body.appendChild(link);
                                link.click();
                                link.remove();
                            })
                            .catch(function (error) {

                            });
                    }
                })
            });
        });
    </script>
@endsection
