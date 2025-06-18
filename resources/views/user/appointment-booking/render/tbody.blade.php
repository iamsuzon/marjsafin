@forelse($linkList ?? [] as $item)
    <tr>
        <td>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="serial-id" name="serial-id[]" data-id="{{ $item->id }}">
                <label class="form-check-label" for="serial-id">
                    {{ $item->id }}
                </label>
            </div>
        </td>
        <td>{{ $item->note }}</td>
        <td>
            <p>Date: {{ $item->created_at->format('d/m/Y') }}</p>
            <p>Reference: {{ $item->reference }}</p>
        </td>
        <td>
            <p class="timer text-danger"></p>
            <p>{{ucwords(str_replace('_',' ',$item->type))}}</p>
        </td>
        <td>
            <p>PP: {{ $item->passport_number }}</p>
            <p>FN: {{ $item->first_name }}</p>
            <p>LS: {{ $item->last_name }}</p>
            <p>NID: {{$item->nid_number }}</p>
            <p class="text-capitalize">Gender: {{ $item->gender }}</p>
        </td>
        <td class="medical-center-names">
            <p class="text-capitalize choice-medical">
                {{ str_replace('-',' ',$item->center_name) }}
            </p>
            <p>-------</p>
            @foreach($item->links ?? [] as $link)
                <p class="link-medical">{{$loop->iteration}}. {{ $link->medical_center }}</p>
            @endforeach
        </td>
        <td>
            @foreach($item->links ?? [] as $link)
                <div class="my-2 d-flex justify-content-between align-items-center gap-5">
                    @if($link->ready_data)
                        <p class="badge badge-success text-white pay-btn" data-pay-id="{{ $link->ready_data['link_id'] }}" data-id="{{ $link->id }}" data-appointment-url="{{ $link->url }}" data-url="{{ $link->url }}">
                            <a href="javascript:void(0)" class="text-white">{{$loop->iteration}}. Pay</a>
                        </p>
                    @elseif($link->type)
                        <p class="badge badge-success text-white pay-btn">
                            <a href="javascript:void(0)" class="text-white">{{$loop->iteration}}. Paying..</a>
                        </p>
                    @elseif($link->medical_center)
                        <p class="badge badge-success text-white pay-btn">
                            <a href="{{ $link->url }}" class="text-white" target="_blank">{{$loop->iteration}}. Slip</a>
                        </p>
                    @else
                        <p class="badge badge-success text-white pay-btn" data-id="{{ $link->id }}" data-appointment-url="{{ $link->url }}" data-url="{{ $link->url }}">
                            <a href="javascript:void(0)" class="text-white">{{$loop->iteration}}. Link</a>
                        </p>
                    @endif
                </div>
            @endforeach
        </td>
        <td>
            @php
                if ($item->type === 'normal') {
                    $status = $item->links()->count() === 1 ? 'Submitted' : 'Pending';
                } elseif ($item->type === 'normal_plus') {
                    $status = $item->links()->count() === 4 ? 'Submitted' : 'Pending';
                } elseif ($item->type === 'special') {
                    $status = $item->links()->count() === 5 ? 'Submitted' : 'Pending';
                } elseif ($item->type === 'special_plus') {
                    $status = $item->links()->count() === 7 ? 'Submitted' : 'Pending';
                }
            @endphp

            {{ $status }}
        </td>
        <td>
            <div class="action-dropdown dropdown">
                <button class="btn btn-primary btm-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    Actions
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="{{ route('user.appointment.booking.edit.registration', $item->passport_number) }}">Edit</a></li>
                    <li><a class="dropdown-item ready-payment-btn" href="javascript:void(0)" data-id="{{ $item->id }}">Ready Payment Processing</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0)">Complete</a></li>
                </ul>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center">No Data Found</td>
    </tr>
@endforelse
