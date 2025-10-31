<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Card for {{ $member->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card-container {
            width: 350px;
            height: 200px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background-color: #fff;
            position: relative;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .card-header {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .profile-photo {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #007bff;
        }
        .info-section {
            display: flex;
            flex-direction: column;
            gap: 5px;
            flex-grow: 1;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9em;
            color: #555;
        }
        .info-row span {
            font-weight: bold;
            color: #222;
        }
        .qr-section {
            text-align: right;
        }
        .qr-code {
            width: 80px;
            height: 80px;
        }
        .barcode {
            width: 100%;
            height: 30px;
            margin-top: 10px;
        }
        .print-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="card-container">
        <!-- Photo and Info -->
        <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
            <img src="{{ $member->image ? Storage::url($member->image) : 'https://placehold.co/70x70/E2E8F0/A0AEC0?text=Photo' }}" alt="Member Photo" class="profile-photo">
            <div style="font-size: 0.8em; font-weight: bold; text-align: center;">{{ $member->name }}</div>
        </div>

        <!-- Details -->
        <div class="info-section">
            <div class="info-row">
                <span>Roll No:</span>
                <span>{{ $member->roll_no }}</span>
            </div>
            <div class="info-row">
                <span>Major:</span>
                <span>{{ $member->major }}</span>
            </div>
            <div class="info-row">
                <span>Year:</span>
                <span>{{ $member->year_string }}</span>
            </div>
            <div class="info-row">
                <span>Reg. Date:</span>
                <span>{{ \Carbon\Carbon::parse($member->registration_date)->format('d-m-Y') }}</span>
            </div>
            <div class="info-row">
                <span>Exp. Date:</span>
                <span>{{ \Carbon\Carbon::parse($member->expired_at)->format('d-m-Y') }}</span>
            </div>
        </div>

        <!-- QR Code -->
        <div class="qr-section">
            {!! $qrCode !!}
        </div>
    </div>

    <button onclick="printCard()" class="print-button">Print Card</button>

    <script>
        function printCard() {
            window.print();
        }
    </script>
</body>
</html>
