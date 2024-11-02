@extends('admin.layout.user-master')

@section('contents')
    <div class="page-content">
        <div class="card">
            <div class="d-flex justify-content-between">
                <h2>Union List</h2>

                @hasanyrole('super-admin|admin')
                    <a href="{{route('admin.new.union-accounts')}}" class="btn-primary-fill">Create New Account</a>
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
                        <th>Account Type</th>

                        @hasrole('super-admin')
                            <th>Action</th>
                        @endhasrole
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($accounts as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                    $class = $user->account_type === 'user' ? 'badge bg-primary' : 'badge bg-secondary';
                                @endphp

                                <p class="{{$class}} text-15">{{ $user->account_type === 'user' ? 'User' : 'Medical Center' }}</p>
                            </td>

                            @hasrole('super-admin')
                                <td class="d-flex justify-content-around align-items-center gap-0">
                                    <a href="{{route('admin.edit.union-accounts', $user->id)}}" class="btn-primary-fill btn-sm">Edit Accounts</a>
                                    <a href="{{route('admin.assign.union-accounts', $user->id)}}" class="btn-primary-fill btn-sm" style="background: blue;color: white">Assign Accounts</a>
                                </td>
                            @endhasrole
                        </tr>
                    @endforeach
                    </tbody>
                </table>
        </div>
    </div>
@endsection
