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

        .balance-td p {
            font-size: 13px;
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
                        <th>Permission</th>
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
                            <td>
                                @if($user->has_medical_permission)
                                    <span class="badge bg-success">Medical</span>
                                @endif
                                @if($user->has_slip_permission)
                                    <span class="badge bg-success">Slip</span>
                                @endif
                                @if($user->has_link_permission)
                                    <span class="badge bg-success">Link</span>
                                @endif
                            </td>
                            <td class="balance-td">
                                <p>MB: {{$user->balance}}</p>
                                <p>SB: {{$user->slip_balance}}</p>
                                <p class="text-primary">
                                    <strong class="text-primary">SN:</strong>
                                    {{ $user->slip_logs_sum_slip_amount ?? 0 }}
                                </p>
                            </td>
                            <td>
                                @if($user->banned)
                                    <span class="badge bg-danger">Banned</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td class="btn-set">
                                <a href="javascript:void(0)" data-href="{{ route('admin.user.ban', $user->id) }}"
                                   class="ban-unban-btn {{$user->banned ? 'btn-primary-fill' : 'btn-warning-fill'}}">
                                    {{ $user->banned ? 'Unban' : 'Ban' }} Customer
                                </a>
                                <a href="javascript:void(0)" data-href="{{ route('admin.user.delete') }}"
                                   data-user-id="{{ $user->id }}"
                                   class="btn-delete btn-danger-fill">
                                    <i class="ri-alert-fill"></i> Delete Customer
                                </a>

                                {{--                                <a href="javascript:void(0)" class="balance-edit-btn btn-secondary-fill"--}}
                                {{--                                   data-bs-target="#score-modal" data-bs-toggle="modal"--}}
                                {{--                                   data-id="{{$user->id}}" data-name="{{$user->name}}"--}}
                                {{--                                   data-balance="{{$user->balance}}"--}}
                                {{--                                >Edit Score</a>--}}

                                <a href="javascript:void(0)" class="btn-permission btn-secondary-fill"
                                   data-bs-target="#permission-modal" data-bs-toggle="modal"
                                   data-id="{{$user->id}}" data-name="{{$user->name}}"
                                   data-has_medical_permission="{{$user->has_medical_permission ? 1 : 0}}"
                                   data-has_slip_permission="{{$user->has_slip_permission ? 1 : 0}}"
                                   data-has_link_permission="{{$user->has_link_permission ? 1 : 0}}"
                                >
                                    <i class="ri-equalizer-3-line"></i> Permissions
                                </a>

                                <a href="javascript:void(0)" class="btn-pdf btn-secondary-fill"
                                   data-bs-target="#pdf-modal" data-bs-toggle="modal"
                                   data-id="{{$user->id}}" data-name="{{$user->name}}">
                                    <i class="ri-file-pdf-line"></i> PDF
                                </a>

                                <a href="javascript:void(0)" class="btn-slip-permission btn btn-primary"
                                   data-bs-target="#slip-amount-permission-modal" data-bs-toggle="modal"
                                   data-user-id="{{$user->id}}" data-name="{{$user->name}}"
                                   data-running-slip-number="{{ current($user->slipLogs) ? $user->slipLogs[0]->slip_amount : 0 }}"
                                   data-running-slip-amount="{{ current($user->slipLogs) ? $user->slipLogs[0]->amount : 0 }}"
                                   data-running-slip-note="{{ current($user->slipLogs) ? $user->slipLogs[0]->note : '' }}">
                                    <i class="ri-ticket-line"></i> Slip Permission
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

                            <small class="text-info">টোটাল PDF করতে কোন ডেট সিলেক্ট করার প্রয়োজন নেই । আজকের PDF করতে
                                স্টার্ট ডেট সিলেক্ট করুন</small>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="pdf-generate-btn btn btn-primary">Generate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="slip-amount-permission-modal" tabindex="-1" aria-labelledby="scoreModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="#" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="scoreModalLabel">Slip Amount Permission</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" name="id">

                            <div class="border border-color-info p-3">
                                <h6>Running</h6>
                                <div class="d-flex justify-content-start align-items-center gap-15">
                                    <p class="running-slip-number">0</p>
                                    :
                                    <p class="running-slip-amount">0</p>
                                </div>
                                <p class="running-slip-note"></p>
                            </div>

                            <div class="border border-color-success p-3 mt-3">
                                <h6>New</h6>
                                <div class="d-flex justify-content-between align-items-center gap-15">
                                    <div class="form-group mb-15">
                                        <label class="contact-label">Slip Number</label>
                                        <input class="form-control new-slip-number" type="number" name="slip_number">
                                    </div>
                                    <div class="form-group mb-15">
                                        <label class="contact-label">Slip Amount</label>
                                        <input class="form-control new-slip-amount" type="number" name="slip_amount">
                                    </div>
                                </div>
                                <div>
                                    <input type="text" class="form-control" name="slip_note" placeholder="Note (Optional)">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="permission-modal" tabindex="-1" aria-labelledby="scoreModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="#" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="scoreModalLabel">User Permissions</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" name="id">
                            <div class="contact-form mb-10">
                                <label class="contact-label">Select Permissions</label>
                                <div class="mt-3">
                                    <div class="check-wrap style-one mb-10">
                                        <input type="checkbox" id="1" name="permissions" value="medical">
                                        <label for="1">Medical</label>
                                    </div>
                                    <div class="check-wrap style-one mb-10">
                                        <input type="checkbox" id="2" name="permissions" value="slip">
                                        <label for="2">Slip</label>
                                    </div>
                                    <div class="check-wrap style-one mb-10">
                                        <input type="checkbox" id="3" name="permissions" value="link">
                                        <label for="3">Link</label>
                                    </div>
                                </div>
                            </div>

                            <small class="text-primary">এখানে যে যে পারমিশনগুলো সিলেক্ট করবেন, ইউজার শুধু মাত্র ঐ অপশনই
                                পাবে</small>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="permission-btn btn btn-primary">Update</button>
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

                    $(document).on('click', '.btn-delete', function () {
                        let el = $(this);
                        let href = el.data('href');
                        let user_id = el.data('user-id');

                        let name = el.closest('tr').find('td:nth-child(2)').text();

                        Swal.fire({
                            title: 'Are you sure?',
                            html: `
                                <h5 class="text-danger">You want to delete <strong>${name}</strong>!</h5><br>
                                <h5 class="text-dark">This action will delete the user permanently!</h5>
                                <h5 class="text-dark">All data related to this user will be deleted!</h5>
                            `,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33333',
                            cancelButtonColor: 'rgba(51,59,70,0.48)',
                            confirmButtonText: 'Yes, delete!',
                            cancelButtonText: 'No, cancel!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: `${href}?uid=${user_id}`,
                                    method: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        user_id: user_id
                                    },
                                    success: function (response) {
                                        if (response.status) {
                                            el.closest('tr').remove();
                                            toastSuccess(response.message);
                                        }
                                    }
                                });
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

                    $(document).on('click', '.btn-slip-permission', function () {
                        let el = $(this);
                        let id = el.data('user-id');
                        let name = el.data('name');

                        let running_slip_number = el.data('running-slip-number');
                        let running_slip_amount = el.data('running-slip-amount');
                        let running_slip_note = el.data('running-slip-note');

                        let modal = $("#slip-amount-permission-modal");
                        setTimeout(() => {
                            modal.find('.modal-title').text('Adding Slips to ' + name);
                        }, 1000)
                        modal.find('input[name="id"]').val(id);

                        modal.find('p.running-slip-number').text(running_slip_number);
                        modal.find('p.running-slip-amount').text(running_slip_amount);
                        modal.find('p.running-slip-note').text(running_slip_note);
                    });

                    $(document).on('submit', '#slip-amount-permission-modal form', function (e) {
                        e.preventDefault();

                        let modal = $("#slip-amount-permission-modal");
                        let id = modal.find('input[name="id"]').val();
                        let slip_number = modal.find('input[name=slip_number]').val();
                        let slip_amount = modal.find('input[name=slip_amount]').val();
                        let slip_note = modal.find('input[name=slip_note]').val();

                        if (slip_number === '' || slip_number === undefined || slip_amount === '' || slip_amount === undefined)
                        {
                            toastError('Kindly fillup the input fields');
                            return ;
                        }

                        $.ajax({
                            url: `{{ route('admin.user.Slip.amount.update') }}`,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: id,
                                slip_number: slip_number,
                                slip_amount: slip_amount,
                                slip_note: slip_note
                            },
                            success: function (response) {
                                if (response.status) {
                                    modal.modal('hide');
                                    toastSuccess(response.message);
                                    reloadThisPage(500);
                                }
                            }
                        });
                    });

                    $(document).on('click', '.btn-permission', function () {
                        let el = $(this);
                        let id = el.data('id');
                        let name = el.data('name');
                        let has_medical_permission = el.data('has_medical_permission');
                        let has_slip_permission = el.data('has_slip_permission');
                        let has_link_permission = el.data('has_link_permission');

                        let modal = $("#permission-modal");
                        modal.find('.modal-title').text('User Permissions for ' + name);
                        modal.find('input[name="id"]').val(id);
                        modal.find('input[name="permissions"]').prop('checked', false);
                        modal.find('input[name="permissions"][value="medical"]').prop('checked', has_medical_permission);
                        modal.find('input[name="permissions"][value="slip"]').prop('checked', has_slip_permission);
                        modal.find('input[name="permissions"][value="link"]').prop('checked', has_link_permission);
                    });

                    $(document).on('click', '#permission-modal button[type="submit"]', function (e) {
                        e.preventDefault();
                        let modal = $("#permission-modal");
                        let id = modal.find('input[name="id"]').val();
                        let medical = modal.find('input[name="permissions"][value="medical"]').is(':checked');
                        let slip = modal.find('input[name="permissions"][value="slip"]').is(':checked');
                        let link = modal.find('input[name="permissions"][value="link"]').is(':checked');

                        $.ajax({
                            url: `{{route('admin.user.permission.update')}}`,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: id,
                                medical_permission: medical,
                                slip_permission: slip,
                                link_permission: link
                            },
                            success: function (response) {
                                if (response.success) {
                                    modal.modal('hide');
                                    toastSuccess(response.success);
                                    reloadThisPage(1000);
                                }
                            }
                        });
                    });
                });
            </script>
@endsection
