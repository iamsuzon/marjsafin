@extends('admin.layout.user-master')

@section('contents')
    <div class="page-content">
        <div class="card">
            <div class="d-flex justify-content-between">
                <div>
                    <h2>{{$medical_center_details->name}} - Allocated Medical Centers List</h2>
                    <p>
                        <strong>Unapproved Application:</strong> {{$unapproved_applications}}
                    </p>
                </div>

                <a href="{{route('admin.application-list.allocations')}}" class="btn-primary-fill">
                    <i class="ri-arrow-left-line"></i>
                </a>
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

            <div class="row mt-50">
                <div class="table-responsives max-height-100vh scroll-active">
                    <table class="table-color-col table-head-border table-td-border">
                        <thead>
                        <tr>
                            <th class="mw-45">#SL</th>
                            <th>Date</th>
                            <th>Registration</th>
                            <th>Passport</th>
                            @hasrole('super-admin')
                            <th>Reference</th>
                            @endhasrole
                            <th>Traveling To</th>
                            <th>Center</th>
                            <th>Comment</th>
                            <th>Allocated Center</th>

                            @hasanyrole('super-admin')
                            <th>Status</th>
                            <th>Action</th>
                            @endhasanyrole
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($applications ?? [] as $item)
                            <tr>
                                <td class="mw-45 d-flex align-items-center">{{ ($applications->currentPage() - 1) * $applications->perPage() + $loop->index + 1 }}</td>
                                <td>
                                    <p>Drft: {{$item->created_at->format('d/m/Y')}}</p>
                                    <p>Crte: {{$item->updated_at->format('d/m/Y')}}</p>
                                </td>
                                <td>
                                    <p>{{$item->serial_number}}</p>
                                    <p>{{$item->ems_number}}</p>
                                </td>
                                <td>
                                    <p>{{$item->passport_number}}</p>
                                    <p>{{$item->given_name}}</p>
                                    <p>NID: {{$item->nid_no}}</p>
                                </td>
                                @hasrole('super-admin')
                                <td>
                                    <p>{{$item->ref_ledger}}</p>
                                    <p>User: {{$item->user->username}}, Ref: {{$item->ref_no}}</p>
                                </td>
                                @endhasrole
                                <td>
                                    <p class="text-capitalize">{{$item->gender}}</p>
                                    <p>{{travelingToName($item->traveling_to)}}</p>
                                </td>
                                <td>
                                    @php
                                        $center = centerName($item->center_name);
                                        $center_name = explode(' - ', $center)[0] ?? '';
                                        $center_address = explode(' - ', $center)[1] ?? '';
                                    @endphp

                                    <p class="text-capitalize">{{$center_name}}</p>
                                    <p class="text-capitalize">{{$center_address}}</p>
                                </td>
                                <td>
                                    <p @class([
                                            'text-capitalize',
                                            'text-success' => $item->health_status == 'fit',
                                            'text-danger' => $item->health_status == 'unfit',
                                            'text-warning' => $item->health_status == 'held-up',
                                        ])>{{$item->health_status}}</p>
                                    <p>C: {{$item->health_status_details}}</p>

                                    @hasrole('super-admin')
                                    <p>A: {{$item->applicationCustomComment?->health_condition}}</p>
                                    @endhasrole
                                </td>
                                <td>
                                    <p>{{getAllocatedMedicalCenterName($item)}}</p>
                                </td>

                                @hasanyrole('super-admin')
                                <td class="td-{{$item->id}}">
                                    @php
                                        $approve_class = $item->allocatedMedicalCenter?->status ? 'text-success' : 'text-danger';
                                    @endphp
                                    <p class="{{$approve_class}}">{{$item->allocatedMedicalCenter?->status ? 'Approved' : 'Not Approved'}}</p>
                                </td>

                                    <td>
                                        <a href="{{route('admin.application-list.allocations.approve', $item->id)}}"
                                           class="approve-btn btn-primary-fill" data-id="{{$item->id}}">Approve</a>
                                        <a href="{{route('admin.application-list.allocations.disapprove', $item->id)}}"
                                           class="approve-btn btn-danger-fill" data-id="{{$item->id}}">Disapprove</a>
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

                <div class="d-flex justify-content-start">
                    {{ $applications->links() }}
                </div>
            </div>
        </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('.approve-btn').on('click', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');
                let id = $(this).data('id');

                changeStatus(url, id);
            });

            let changeStatus = function (url, id) {
                axios.get(url)
                    .then((response) => {
                        let data = response.data;

                        let status = data.status;
                        let message = data.message;
                        let medical_center = data.medical_center;

                        if (status)
                        {
                            medical_center.status ? toastSuccess(message) : toastError(message);

                            let className = medical_center.status ? 'text-success' : 'text-danger';
                            let text = medical_center.status ? 'Approved' : 'Not Approved';

                            $('.td-' + id).html(`<p class="${className}">${text}</p>`);
                        }
                    })
                    .catch(() => {
                        alert('Something went wrong');
                    });
            }
        });
    </script>
@endsection
