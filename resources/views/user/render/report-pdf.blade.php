<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 10px;
        }
        .receipt-container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 40px;
        }
        .receipt-header, .receipt-info {
            display: flex;
            justify-content: space-between;
        }
        .receipt-header {
            margin-bottom: 20px;
            text-align: center;
        }
        .receipt-info div {
            margin-bottom: 10px;
        }
        .receipt-details {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .receipt-details td, .receipt-details th {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }
        .official-signature {
            text-align: right;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <div class="receipt-header">
        <div>{{$pdf_code}}</div>
        <h1>Money Receipt</h1>
    </div>

    <div class="receipt-info">
        <div>
            <strong>Country:</strong> {{$country}}<br>
            <strong>Date:</strong> {{$created_at->format('d/m/Y')}}
        </div>
        <div>
            <strong>Delivery Date:</strong> {{$delivery_date->format('d/m/Y')}}<br>
        </div>
    </div>

    <div class="receipt-info">
        <div>
            <strong>Name:</strong> {{$passenger}}<br>
            <strong>Passport No:</strong> {{$passport_no}}
        </div>
    </div>

    <table class="receipt-details">
        <tr>
            <th>MEDICAL FEES</th>
            <td>8500/-</td>
        </tr>
        <tr>
            <th>TOTAL</th>
            <td>8500/-</td>
        </tr>
    </table>

    <div class="official-signature">
        _______________________________<br>
        Official Signature
    </div>
</div>

</body>
</html>
