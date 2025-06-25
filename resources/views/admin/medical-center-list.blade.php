@extends('admin.layout.user-master')

@section('styles')
    <style>
        .swal2-icon.swal2-info, .swal2-icon.swal2-question, .swal2-icon.swal2-warning {
            font-size: 15px !important;
        }
    </style>
@endsection

@section('contents')
    <div class="page-content">
        <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-15">
            <div class="d-flex align-items-center gap-8">
                <div class="icon text-title text-23">
                    <i class="ri-terminal-line"></i>
                </div>
                <h6 class="card-title text-18">Medical List</h6>
            </div>
            <!-- Sub Menu -->
            <div class="sub-menu-wrapper">
                <ul class="sub-menu-list">
                    <li class="sub-menu-item">
                        <a href="{{route('admin.medical-center.list')}}" class="single {{activeCurrentUrl(route('admin.medical-center.list'))}}">
                            Manage Centers
                        </a>
                    </li>
                    <li class="sub-menu-item">
                        <a href="{{route('admin.medical-center.list.application')}}" class="single {{activeCurrentUrl(route('admin.medical-center.list.application'))}}">
                            All Center Applications
                        </a>
                    </li>
                </ul>
            </div>
            <!-- / Sub Menu -->
        </div>

        <div class="card">
            <div class="d-flex justify-content-between">
                <h2>Medical Center List</h2>

                @hasanyrole('super-admin|admin')
                    <a href="{{route('admin.new.medical-center')}}" class="btn-primary-fill">Create New Medical Center</a>
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

            <div class="table-responsive mt-25">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>#SL</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Note</th>
                        @hasanyrole('super-admin|admin')
                        <th>Action</th>
                        @endhasanyrole
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($medicalCenterList as $user)
                        @role('analyst')
                            @if(in_array(strtolower($user->username), ['altaskhis_markaz', 'yadan_medical', 'malancha_medical', 'kent_medical']))
                                @continue
                            @endif
                        @endrole

                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->address }}</td>
                            <td>{{ $user->note }}</td>

                            @hasanyrole('super-admin|admin|analyst')
                            <td class="text-end px-15 d-flex gap-10">
                                {{--                                <a class="edit-btn view-password-btn" href="javascript:void(0)"--}}
                                {{--                                   data-bs-toggle="modal" data-bs-target="#view-password-modal" data-name="{{$user->username}}">--}}
                                {{--                                    <i class="ri-eye-line" data-bs-toggle="tooltip" data-bs-placement="top"--}}
                                {{--                                       title="View Password"></i>--}}
                                {{--                                </a>--}}
                                @can('change-password-medical-center')
                                    <a class="view-btn change-password-btn" href="javascript:void(0)"
                                       data-bs-toggle="modal" data-bs-target="#change-password-modal"
                                       data-id="{{$user->id}}">
                                        <i class="ri-lock-line"></i>
                                    </a>
                                @endcan

                                @can('update-medical-center')
                                    <a class="edit-btn" href="javascript:void(0)"
                                       data-bs-toggle="modal" data-bs-target="#edit-modal"
                                       data-id="{{$user->id}}" data-name="{{$user->name}}"
                                       data-username="{{$user->username}}" data-email="{{$user->email}}"
                                       data-address="{{$user->address}}">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                @endcan

                                @can('delete-medical-center')
                                    <a class="delete-btn" href="javascript:void(0)" data-id="{{$user->id}}">
                                        <i class="ri-delete-bin-6-line"></i>
                                    </a>
                                @endcan
                            </td>
                            @endhasanyrole
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center py-10" colspan="5">No medical center found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        <div class="modal fade" id="change-password-modal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header mb-10 p-0 pb-10">
                        <div class="d-flex align-items-center gap-8">
                            <div class="icon text-20">
                                <i class="ri-bar-chart-horizontal-line"></i>
                            </div>
                            <h6 class="modal-title">Update Medical Center</h6>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ri-close-line" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div class="modal-body p-0">
                        <div class="row g-10">
                            <div class="col-md-12">
                                <input type="hidden" name="confirm_password_id">

                                <div class="contact-form">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control input" id="password" name="password"
                                           required>
                                </div>
                                <div class="contact-form mt-15">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" class="form-control input" id="password_confirmation"
                                           name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="d-flex align-items-center gap-16 flex-wrap mt-18">
                            <button class="btn-primary-fill confirm-password-btn" type="submit">
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
                            <h6 class="modal-title">Update Medical Center</h6>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ri-close-line" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div class="modal-body p-0">
                        <div class="row g-10">
                            <div class="col-md-12">
                                <input type="hidden" name="medical_center_id">
                                <div class="contact-form mt-15">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control input" id="email" name="email" required>
                                </div>

                                <div class="contact-form mt-15">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control input" id="address" name="address" required>
                                </div>

                                <div class="contact-form mt-15">
                                    <label for="note">Note</label>
                                    <textarea class="form-control" name="note" id="note" rows="5"></textarea>
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
                    $('input[name=username]').on('keypress', function (e) {
                        if (e.which === 32) { // Detect spacebar key
                            $(this).addClass('input-error'); // Add red border and background
                            $('.error').show(); // Show the error message
                            return false; // Prevent space from being added
                        } else {
                            $(this).removeClass('input-error'); // Remove red border if not a space
                            $('.error').hide(); // Hide the error message if no space is pressed
                        }
                    });

                    $(document).on('click', '.edit-btn', function (e) {
                        e.preventDefault();

                        let id = $(this).data('id');
                        let name = $(this).data('name');
                        let username = $(this).data('username');
                        let email = $(this).data('email');
                        let address = $(this).data('address');
                        let note = $(this).data('note');

                        $('input[name=medical_center_id]').val(id);
                        $('#edit-modal input[name=name]').val(name);
                        $('#edit-modal input[name=username]').val(username);
                        $('#edit-modal input[name=email]').val(email);
                        $('#edit-modal input[name=address]').val(address);
                        $('#edit-modal textarea[name=note]').val(note);
                    });

                    $(document).on('click', '.update-btn', function (e) {
                        e.preventDefault();

                        let id = $('input[name=medical_center_id]').val();
                        let name = $('#name').val();
                        let username = $('#username').val();
                        let email = $('#email').val();
                        let address = $('#address').val();
                        let note = $('#note').val();

                        customSwal({
                            route: `{{route('admin.medical-center.update')}}`,
                            data: {
                                id: id,
                                name: name,
                                username: username,
                                email: email,
                                address: address,
                                note: note
                            },
                            title: 'Update Medical Center',
                            text: 'Are you sure you want to update this medical center?',
                            confirmButtonText: 'Yes, Update',
                            cancelButtonText: 'No, Cancel',
                            successMessage: 'Medical center updated successfully',
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

                    $(document).on('click', '.change-password-btn', function (e) {
                        e.preventDefault();

                        let id = $(this).data('id');
                        $('input[name=confirm_password_id]').val(id);
                    });

                    $(document).on('click', '.confirm-password-btn', function (e) {
                        e.preventDefault();

                        let id = $('input[name=confirm_password_id]').val();
                        let password = $('#password').val();
                        let password_confirmation = $('#password_confirmation').val();

                        customSwal({
                            route: `{{route('admin.medical-center.change.password')}}`,
                            data: {
                                id: id,
                                password: password,
                                password_confirmation: password_confirmation
                            },
                            title: 'Change Password',
                            text: 'Are you sure you want to change the password of this medical center?',
                            confirmButtonText: 'Yes, Change Password',
                            cancelButtonText: 'No, Cancel',
                            successMessage: 'Password changed successfully',
                            successFunction: function (response) {
                                let responseData = response.data;

                                if (responseData.status) {
                                    $('#change-password-modal').modal('hide');
                                    toastSuccess(responseData.message);
                                }
                            },
                            errorFunction: function (error) {
                                let errorMsg = error.response.data.message;
                                toastError(errorMsg);
                            }
                        })
                    });

                    $(document).on('click', '.delete-btn', function (e) {
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
