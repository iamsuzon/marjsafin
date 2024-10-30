@extends('admin.layout.user-master')

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
                                    @if($user->banned)
                                        <span class="badge bg-danger">Banned</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.user.ban', $user->id) }}" class="{{$user->banned ? 'btn-primary-fill' : 'btn-danger-fill'}} btn-sm">
                                        {{ $user->banned ? 'Unban' : 'Ban' }} Customer
                                    </a>
                                </td>
                            @endhasrole
                        </tr>
                    @endforeach
                    </tbody>
                </table>
        </div>
    </div>
@endsection
