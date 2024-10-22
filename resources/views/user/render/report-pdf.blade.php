<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-Medical Slip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 700px;
            margin: 15px auto;
            padding: 15px;
            text-align: center;
        }
        .header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: capitalize;
        }
        .content {
            text-align: left;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .content p {
            margin: 5px 0;
        }
        .divider {
            border-bottom: 1px solid #000;
            margin: 15px 0;
        }
        .footer {
            font-size: 13px;
            text-align: left;
            margin-top: 10px;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">{{$center_name}}</div>
    <div class="divider"></div>
    <div class="content">
        <p><strong>From:</strong> {{ $agent }}</p>
        <p><strong>Passport No:</strong> {{ $passport_no }}</p>
        <p><strong>NID No:</strong> {{ $nid_no }}</p>
        <p><strong>Passenger:</strong> {{ $passenger }}</p>
        <p><strong>Country:</strong> {{ $country }}</p>
        <p><strong>Date of Birth:</strong> {{ $date_of_birth->format('d/m/Y') }}</p>
        <p><strong>Passport Expire Date:</strong> {{ $passport_expiry_date->format('d/m/Y') }}</p>
    </div>
    <div class="divider"></div>
    <div class="footer">
        <p><strong>CreatedBy:</strong> {{ $created_by }}, For: {{ $for }}</p>
        <p>Notes: Passengers are advised to contact Agent only for Medical Test Report and all other official procedures, Not at the Medical Center. Thanks</p>
    </div>
</div>
</body>
</html>
