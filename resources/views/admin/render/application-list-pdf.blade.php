<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Table</title>
    <style>
        /* Styling for the PDF table */
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h4>{{$center_name}}</h4>

<table>
    <thead>
    <tr>
        <th>#SL</th>
        <th>ID</th>
        <th>Date</th>
        <th>Registration</th>
        <th>Passport</th>
        <th>Reference</th>
        <th>Traveling To</th>
        <th>Amount</th>
        <th>Discount</th>
        <th>Total</th>
        <th>Center</th>
        <th>Comment</th>
        <th>Allocate Center</th>
        <th>Medical Status</th>
    </tr>
    </thead>
    <tbody>
    @forelse($applicationList ?? [] as $item)
        <tr>
            <td class="align-items-center">{{$loop->iteration}}</td>
            <td>{{$item->pdf_code}}</td>
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
            <td>
                <p>From Raju</p>
            </td>
            <td>
                <p class="text-capitalize">{{$item->gender}}</p>
                <p>{{travelingToName($item->traveling_to)}}</p>
            </td>
            <td>
                <p>{{$item->applicationPayment?->admin_amount}}</p>
            </td>
            <td>
                <p>{{$item->applicationPayment?->discount_amount}}</p>
            </td>
            <td>
                <p>{{$item->applicationPayment?->admin_amount - $item->applicationPayment?->discount_amount}}</p>
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
                                        ])>{{strtoupper($item->health_status)}}</p>
                <p>{{$item->health_status_details}}</p>
            </td>
            <td>
                <p>{{getAllocatedMedicalCenterName($item) ?? ''}}</p>
            </td>
            <td>
                @php
                    $medical_status = $item->medical_status;

                    $class = 'info';
                    if ($medical_status == 'new') {
                        $class = 'info';
                    } elseif ($medical_status == 'in-progress' || $medical_status == 'under-review') {
                        $class = 'warning';
                    } elseif ($medical_status == 'fit') {
                        $class = 'success';
                    }
                @endphp

                <p class="mb-10 text-{{$class}}">{{getMedicalStatusName($item->medical_status ?? '')}}</p>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan=11" class="text-center">No Data Found</td>
        </tr>
    @endforelse
    </tbody>
</table>

</body>
</html>



