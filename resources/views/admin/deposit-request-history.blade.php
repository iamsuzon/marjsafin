@extends('admin.layout.user-master')

@section('styles')
    <style>
        .manage__title {
            border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
        }

        .approve_btn {
            border: 1px solid transparent !important;
        }
        .approve_btn:hover {
            background: transparent !important;
            border: 1px solid green !important;
            color: green !important;
        }

        .decline_btn {
            background: red !important;
            border: 1px solid transparent !important;
        }
        .decline_btn:hover {
            background: transparent !important;
            border: 1px solid red !important;
            color: red !important;
        }
    </style>
@endsection

@section('contents')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <h2 class="manage__title">Score Request History List</h2>
                    </div>
                </div>

                <div class="row mt-50">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
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

                    <div class="table-responsives max-height-100vh scroll-active">
                        <table class="table-color-col table-head-border table-td-border">
                            <thead>
                            <tr>
                                <th class="mw-45">#SL</th>
                                <th>Username</th>
                                <th>Type</th>
                                <th>For</th>
                                <th>Request Date</th>
                                <th>Status</th>

                                @hasanyrole('super-admin')
                                <th>Action</th>
                                @endhasanyrole
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($deposit_history ?? [] as $item)
                                <tr>
                                    <td class="mw-45 d-flex align-items-center">{{$item->id}}</td>
                                    <td>
                                        <p>{{$item->user?->name}}</p>
                                        <p>{{$item->user?->username}}</p>
                                    </td>
                                    <td>
                                        Score Request
                                    </td>
                                    <td>
                                        <p class="text-capitalize">{{$item->score_type === 'slip' ? 'Slip' : 'Medical'}}</p>
                                    </td>
                                    <td>{{$item->deposit_date->format('d-m-Y')}}</td>
                                    <td>
                                        @php
                                            $class = ['approved' => 'text-success', 'pending' => 'text-warning', 'declined' => 'text-danger'];
                                        @endphp
                                        <p class="{{$class[$item->status]}} text-capitalize">{{$item->status}}</p>
                                    </td>

                                    @hasanyrole('super-admin')
                                    <td>
                                        @if($item->status === 'pending')
                                            <div>
                                                <a class="add_score_btn btn btn-primary btn-sm" href="javascript:void(0)"
                                                    data-bs-target="#add-score-modal" data-bs-toggle="modal"
                                                    data-id="{{$item->id}}" data-name="{{$item->user?->name}}">Add Score</a>
                                            </div>
                                        @else
                                            @php
                                                $class = ['approved' => 'success', 'pending' => 'warning', 'declined' => 'danger'];
                                            @endphp
                                            <p class="badge bg-{{$class[$item->status]}} text-capitalize">{{$item->status}}</p>
                                        @endif
                                    </td>
                                    @endhasanyrole
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-score-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header mb-10 p-0 pb-10">
                    <div class="d-flex align-items-center gap-8">
                        <div class="icon text-20">
                            <i class="ri-bar-chart-horizontal-line"></i>
                        </div>
                        <h6 class="modal-title">Add Score</h6>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ri-close-line" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="modal-body p-0">
                    <form action="#" method="POST" id="edit-form" enctype="multipart/form-data">

                        <input type="hidden" name="id">

                        <div class="row g-10">
                            <div class="col-md-12">
                                <!-- Date Picker -->
                                <div class="contact-form">
                                    <label class="contact-label">User Name</label>
                                    <input class="form-control input" name="name" disabled>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <!-- Date Picker -->
                                <div class="contact-form">
                                    <label class="contact-label">Score Amount</label>
                                    <input type="number" class="form-control input" name="score_amount">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <!-- Date Picker -->
                                <div class="contact-form">
                                    <label class="contact-label">Remarks</label>
                                    <input type="text" class="form-control input" name="remarks">
                                </div>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="d-flex align-items-center gap-16 flex-wrap mt-18">
                            <button class="btn-primary-fill" type="submit">
                                <span class="d-flex align-items-center gap-6">
                                    <i class="las la-check-circle"></i>
                                    <span>Update</span>
                                </span>
                            </button>
                            <button class="btn-cancel-fill" type="reset" data-bs-dismiss="modal">
                                <span class="d-flex align-items-center gap-6">
                                    <i class="ri-close-line"></i>
                                    <span>Discard</span>
                                </span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click', '.add_score_btn', function () {
                let el = $(this);
                let id = el.data('id');
                let name = el.data('name');

                let from = $('#add-score-modal');

                from.find('input[name="id"]').val('');
                from.find('input[name="name"]').val('');
                from.find('input[name="score_amount"]').val('');

                from.find('input[name="id"]').val(id);
                from.find('input[name="name"]').val(name);
            });


            $(document).on('submit', '#add-score-modal form', function (e) {
                e.preventDefault();

                let from = $('#add-score-modal');
                let id = from.find('input[name="id"]').val();
                let score_amount = from.find('input[name="score_amount"]').val();
                let remarks = from.find('input[name="remarks"]').val();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to add score to this user?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Add it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{route('admin.score-request-history.add-score')}}',
                            type: 'POST',
                            data: {
                                _token: '{{csrf_token()}}',
                                request_id: id,
                                score_amount: score_amount,
                                remarks: remarks
                            },
                            success: function (response) {
                                if (response.status) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: response.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });

                                    setTimeout(() => {
                                        location.reload();
                                    }, 1500);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: response.message
                                    });
                                }
                            },
                            error: function (error) {
                                let errors = error.responseJSON.errors;
                                let errorValues = Object.values(errors).map((value) => {
                                    return value;
                                });

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    html: errorValues.join('<br>')
                                });
                            }
                        });
                    }
                })
            });
        })
    </script>
@endsection
