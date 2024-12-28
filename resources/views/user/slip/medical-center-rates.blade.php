@extends('user.layout.common-master')
@section('title', 'Medical Center Rates')

@section('sidebar')
    @include('user.partials.medical-sidebar')
@endsection

@section('styles')
    <style>
        .manage__title {
            border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
        }
    </style>
@endsection

@section('contents')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <h2 class="manage__title">Medical Centers</h2>

                        <form id="search-from">
                            <div class="row d-flex justify-content-center mt-25">
                                <div class="col-md-2">
                                    <!-- Date Picker -->
                                    <div class="contact-form">
                                        <label class="contact-label">City</label>
                                        <select class="form-control" name="city_slug" id="city_slug">
                                            <option value="">Select City</option>
                                            @foreach($cityList as $city)
                                                <option value="{{$city['slug']}}">{{$city['title']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="contact-form">
                                        <label class="contact-label">Center Name</label>
                                        <input type="text" class="contact-input center_name"
                                               placeholder="Search By Center Name" name="center_name">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <div class="contact-form d-flex gap-10">
                                        <button class="btn-primary-fill search_btn" type="submit">Search
                                        </button>
                                        <button class="btn-danger-fill reset_btn" type="reset">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row mt-50">
                    <div class="table-responsives max-height-100vh scroll-active">
                        <table class="table-color-col table-head-border table-td-border">
                            <thead>
                            <tr>
                                <th class="mw-45">#SL</th>
                                <th>City</th>
                                <th>Center Name</th>
                                <th>Rate</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($slipList ?? [] as $item)
                                <tr>
                                    <td class="mw-45 d-flex align-items-center">{{listSerialNumber($slipList, $loop)}}</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click', '.search_btn', function (e) {
                e.preventDefault();

                let form = $('#search-from');
                let city_slug = form.find('select[name=city_slug]').val();

                let centers = @json($cityList);
                console.log(centers);
            });

            $(document).on('click', '.reset_btn', function () {
                location.href = `{{route('user.slip.list')}}`;
            });
        });
    </script>
@endsection
