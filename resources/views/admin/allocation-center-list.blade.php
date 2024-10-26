@extends('admin.layout.user-master')

@section('styles')
    <style>
        .swal2-icon.swal2-info, .swal2-icon.swal2-question, .swal2-icon.swal2-warning{
            font-size: 15px !important;
        }
    </style>
@endsection

@section('contents')
    <div class="page-content">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h2>Allocate Center List</h2>
                <button type="button" class="btn-primary-fill" data-bs-toggle="modal" data-bs-target="#create-modal">Create New Allocate Center</button>
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

            <div class="table-responsive mt-25">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>#SL</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($allocationCenterList as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td class="text-end px-15 d-flex gap-10">
                                <a class="edit-btn" href="javascript:void(0)"
                                   data-bs-toggle="modal" data-bs-target="#edit-modal"
                                   data-id="{{$item->id}}" data-name="{{$item->name}}">
                                    <i class="ri-edit-line"></i>
                                </a>

{{--                                <a class="delete-btn" href="javascript:void(0)" data-id="{{$item->id}}">--}}
{{--                                    <i class="ri-delete-bin-6-line"></i>--}}
{{--                                </a>--}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center py-15" colspan="5">No allocate center found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        <div class="modal fade" id="create-modal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header mb-10 p-0 pb-10">
                        <div class="d-flex align-items-center gap-8">
                            <div class="icon text-20">
                                <i class="ri-bar-chart-horizontal-line"></i>
                            </div>
                            <h6 class="modal-title">New Allocate Center</h6>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ri-close-line" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div class="modal-body p-0">
                        <div class="row g-10">
                            <div class="col-md-12">
                                <div class="contact-form">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control input" id="name" name="name" required>
                                </div>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="d-flex align-items-center gap-16 flex-wrap mt-18">
                            <button class="btn-primary-fill create-btn" type="submit">
                                <span class="d-flex align-items-center gap-6">
                                    <i class="las la-check-circle"></i>
                                    <span>Confirm</span>
                                </span>
                            </button>
                            <button class="btn-cancel-fill" type="reset" data-bs-dismiss="modal">
                                <span class="d-flex align-items-center gap-6">
                                    <i class="ri-close-line"></i>
                                    <span>Discard</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit-modal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header mb-10 p-0 pb-10">
                        <div class="d-flex align-items-center gap-8">
                            <div class="icon text-20">
                                <i class="ri-bar-chart-horizontal-line"></i>
                            </div>
                            <h6 class="modal-title">Update Allocate Center</h6>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ri-close-line" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div class="modal-body p-0">
                        <div class="row g-10">
                            <div class="col-md-12">
                                <input type="hidden" name="allocate_center_id">

                                <div class="contact-form mt-15">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control input" id="name" name="name" required>
                                </div>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="d-flex align-items-center gap-16 flex-wrap mt-18">
                            <button class="btn-primary-fill update-btn" type="submit">
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
                    </div>
                </div>
            </div>
        </div>
        @endsection

        @section('scripts')
            <script>
                $(document).ready(function () {
                    $(document).on('click', '.edit-btn', function (e) {
                        e.preventDefault();

                        let id = $(this).data('id');
                        let name = $(this).data('name');

                        $('input[name=allocate_center_id]').val(id);
                        $('#edit-modal input[name=name]').val(name);
                    });

                    $(document).on('click', '.update-btn', function (e) {
                        e.preventDefault();

                        let id = $('input[name=allocate_center_id]').val();
                        let name = $('#edit-modal input[name=name]').val();

                        customSwal({
                            route: `{{route('admin.allocate-center.update')}}`,
                            data: {
                                id: id,
                                name: name,
                            },
                            title: 'Update Allocate Center',
                            text: 'Are you sure you want to update this allocate center?',
                            confirmButtonText: 'Yes, Update',
                            cancelButtonText: 'No, Cancel',
                            successMessage: 'Allocate center updated successfully',
                            successFunction: function (response) {
                                let responseData = response.data;

                                if (responseData.status) {
                                    $('#edit-modal').modal('hide');
                                    toastSuccess(responseData.message);
                                    reloadThisPage(1000);
                                }
                            },
                            errorFunction: function (error) {
                                let errorMsg = error.response.data.message;
                                toastError(errorMsg);
                            }
                        })
                    });

                    $(document).on('click', '.create-btn', function (e) {
                        e.preventDefault();

                        let el = $('#create-modal');
                        let name = el.find('input[name=name]').val();

                        customSwal({
                            route: `{{route('admin.allocate-center.new')}}`,
                            data: {
                                name: name,
                            },
                            title: 'Create New Allocate Center',
                            text: 'Are you sure you want to create this allocate center?',
                            confirmButtonText: 'Yes, Create',
                            cancelButtonText: 'No, Cancel',
                            successMessage: 'Allocate center created successfully',
                            successFunction: function (response) {
                                let responseData = response.data;

                                if (responseData.status) {
                                    el.modal('hide');
                                    toastSuccess(responseData.message);
                                    reloadThisPage(1000);
                                } else {
                                    toastError(responseData.message);
                                }
                            },
                            errorFunction: function (error) {
                                let errorMsg = error.response.data.message;
                                toastError(errorMsg);
                            }
                        })
                    });

                    $(document).on('click' ,'.delete-btn', function (e) {
                        e.preventDefault();

                        let id = $(this).data('id');
                        customSwal({
                            route: `{{route('admin.medical-center.delete')}}?id=${id}`,
                            method: 'GET',
                            title: 'Delete Medical Center',
                            text: 'Are you sure you want to delete this medical center?',
                            confirmButtonText: 'Yes, Delete',
                            cancelButtonText: 'No, Cancel',
                            successMessage: 'Medical center deleted successfully',
                            successFunction: function (response) {
                                let responseData = response.data;

                                if (responseData.status) {
                                    toastSuccess(responseData.message);
                                    reloadThisPage(1000);
                                }
                            },
                            errorFunction: function (error) {
                                let errorMsg = error.response.data.message;
                                toastError(errorMsg);
                            }
                        })
                    });
                });
            </script>
@endsection
