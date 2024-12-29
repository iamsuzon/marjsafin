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
                                        <select class="form-control" name="city_id" id="city_id">
                                            <option value="">Select City</option>
                                            @foreach($cityList as $key => $city)
                                                <option value="{{$key}}">{{$city['title']}}</option>
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
                        <table class="center-list-table table-color-col table-head-border table-td-border">
                            <thead>
                            <tr>
                                <th class="mw-45">#SL</th>
                                <th>City</th>
                                <th>Center Name</th>
                                <th>Rate</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                </tr>
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
            let centerList = @json($cityList);
            let rateList = [];

            $.each(centerList, function (city_id, center) {
                $.each(center.list, function (slug, title) {
                    if (center.rates[slug].rate === '0.00')
                    {
                        return;
                    }

                    rateList.push({
                        city_id: city_id,
                        center: title,
                        city: center.title,
                        rate: center.rates[slug].rate,
                        discount: center.rates[slug].discount,
                    });
                });
            });

            $(document).on('click', '.search_btn', function (e) {
                e.preventDefault();

                let form = $('#search-from');
                let city = form.find('select[name=city_id]');
                let center = form.find('input[name=center_name]');

                let city_id = city.val();
                let center_name = center.val();

                let centerList = [];
                if (city_id !== '')
                {
                    centerList = rateList.filter(rate => rate.city_id === city_id);
                }
                else if ((city_id !== '' && center_name !== '') || center_name !== '')
                {
                    let centerName = center_name.toLowerCase().trim();
                    centerList = rateList.filter(rate => rate.center.toLowerCase().includes(centerName));
                }
                else
                {
                    toastError('Please select city or enter center name');
                    return;
                }

                city.val('');
                center.val('');

                let html = '';
                $.each(centerList, function (index, rate) {
                    html += `<tr>
                        <td>${index + 1}</td>
                        <td>${rate.city}</td>
                        <td>${rate.center}</td>
                        <td>${rate.rate}</td>
                    </tr>`;
                });

                let table =  $('.center-list-table tbody');

                if (centerList.length === 0) {
                    table.html(`<tr><td colspan="4" class="text-center">No Data Found</td></tr>`);
                    return;
                }
                table.html(html);
            });

            $(document).on('click', '.reset_btn', function () {
                location.href = `{{route('user.slip.medical-center.rates')}}`;
            });
        });
    </script>
@endsection
