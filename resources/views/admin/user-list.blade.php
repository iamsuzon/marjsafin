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
    </style>
@endsection

@section('contents')
    <div class="page-content">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h2>User List</h2>

                @hasanyrole('super-admin|admin')
                <a href="{{route('admin.new.user')}}" class="btn-primary-fill">Create New User</a>
                @endhasanyrole
            </div>

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
                        <th>#</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Refer</th>

                        @hasrole('super-admin')
                        <th>Score</th>
                        <th>Ban Status</th>
                        <th>Action</th>
                        @endhasrole
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($userList as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->refer_by }}</td>

                            @hasrole('super-admin')
                            <td>{{$user->balance}}</td>
                            <td>
                                @if($user->banned)
                                    <span class="badge bg-danger">Banned</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td class="btn-set">
                                <a href="javascript:void(0)" data-href="{{ route('admin.user.ban', $user->id) }}"
                                   class="ban-unban-btn {{$user->banned ? 'btn-primary-fill' : 'btn-danger-fill'}}">
                                    {{ $user->banned ? 'Unban' : 'Ban' }} Customer
                                </a>

                                <a href="javascript:void(0)" class="balance-edit-btn btn-secondary-fill"
                                   data-bs-target="#score-modal" data-bs-toggle="modal"
                                   data-id="{{$user->id}}" data-name="{{$user->name}}"
                                   data-balance="{{$user->balance}}"
                                >Edit Score</a>

                                <a href="javascript:void(0)" class="btn-pdf btn-secondary-fill"
                                   data-bs-target="#pdf-modal" data-bs-toggle="modal"
                                   data-id="{{$user->id}}" data-name="{{$user->name}}">
                                    <i class="ri-file-pdf-line"></i> PDF
                                </a>
                            </td>
                            @endhasrole
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="score-modal" tabindex="-1" aria-labelledby="scoreModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="#" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="scoreModalLabel"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <input type="hidden" name="id">

                        <div class="modal-body">
                            <div class="contact-form">
                                <label for="score">Name</label>
                                <input class="form-control" type="text" name="name" disabled>
                            </div>

                            <div class="contact-form mt-15">
                                <label for="score">Current Score</label>
                                <input class="form-control" type="text" name="balance">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="pdf-modal" tabindex="-1" aria-labelledby="scoreModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="#" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="scoreModalLabel">Generate PDF</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" name="id">
                            <div class="contact-form mb-15">
                                <label class="contact-label">Start Date </label>
                                <div class="d-flex justify-content-between date-pic-icon">
                                    <input type="text" class="contact-input single-date-picker start_date"
                                           placeholder="Choose Date">
                                    <span> <b class="caret"></b></span>
                                    <i class="ri-calendar-line"></i>
                                </div>
                            </div>
                            <div class="contact-form mb-15">
                                <label class="contact-label">end Date </label>
                                <div class="d-flex justify-content-between date-pic-icon">
                                    <input type="text" class="contact-input single-date-picker end_date"
                                           placeholder="Choose Date">
                                    <span> <b class="caret"></b></span>
                                    <i class="ri-calendar-line"></i>
                                </div>
                            </div>

                            <small class="text-info">টোটাল PDF করতে কোন ডেট সিলেক্ট করার প্রয়োজন নেই । আজকের PDF করতে স্টার্ট ডেট সিলেক্ট করুন</small>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="pdf-generate-btn btn btn-primary">Generate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endsection

        @section('scripts')
            <script>
                $(document).ready(function () {
                    $(document).on('click', '.ban-unban-btn', function () {
                        let el = $(this);
                        let href = el.data('href');

                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You want to change the ban status of this user!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33333',
                            confirmButtonText: 'Yes, ban it!',
                            cancelButtonText: 'No, cancel!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = href;
                            }
                        });
                    });

                    $(document).on('click', '.balance-edit-btn', function () {
                        let el = $(this);
                        let id = el.data('id');
                        let name = el.data('name');
                        let balance = el.data('balance');

                        let modal = $("#score-modal");
                        modal.find('.modal-title').text('Edit Score for ' + name);
                        modal.find('input[name="id"]').val(id);
                        modal.find('input[name="name"]').val(name);
                        modal.find('input[name="balance"]').val(balance);
                    });

                    $(document).on('click', '#score-modal button[type="submit"]', function (e) {
                        e.preventDefault();
                        let modal = $("#score-modal");
                        let id = modal.find('input[name="id"]').val();
                        let balance = modal.find('input[name="balance"]').val();

                        $.ajax({
                            url: '{{ route('admin.user.balance.update') }}',
                            method: 'POST',
                            data: {
                                id: id,
                                balance: balance,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                if (response.success) {
                                    modal.modal('hide');
                                    toastSuccess(response.success);
                                    reloadThisPage();
                                }
                            }
                        });
                    });

                    $(document).on('click', '.btn-pdf', function () {
                        let el = $(this);
                        let id = el.data('id');
                        let name = el.data('name');

                        let modal = $("#pdf-modal");
                        modal.find('.modal-title').text('Generate PDF for ' + name);
                        modal.find('input[name="id"]').val(id);
                    });

                    $(document).on('click', '.pdf-generate-btn', function () {
                        let modal = $("#pdf-modal");
                        let id = modal.find('input[name="id"]').val();
                        let start_date = modal.find('.start_date').val();
                        let end_date = modal.find('.end_date').val();

                        window.location.href = `{{route('admin.user.pdf.generate')}}?id=${id}&start_date=${start_date}&end_date=${end_date}`;
                    });
                });
            </script>
@endsection
