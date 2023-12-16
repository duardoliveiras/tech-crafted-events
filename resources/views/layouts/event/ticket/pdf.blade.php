<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $event->name }} - Event Ticket</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif; /* Good for unicode characters */
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }
        .card {
            background: #fff;
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            max-width: 600px;
            padding: 20px;
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        h3 {
            border-bottom: 2px solid #007bff;
            color: #007bff;
            padding-bottom: 10px;
        }
        .card-body h4 {
            color: #007bff;
            margin-bottom: 15px;
        }
        p {
            margin-bottom: 10px;
        }
        .qr-code-container {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
            display: inline-block;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.8em;
            color: #666;
        }
    </style>
</head>
<body>
<div class="card">
    <h3 class="text-center">{{ $event->name }} - Event Ticket</h3>
    <div class="card-body">
        <h4>Event Details:</h4>
        <p>{{ $event->description }}</p>
        <p><strong>Start Time:</strong> {{ $event->start_date->format('d M Y, H:i') }}</p>
        <p><strong>End Time:</strong> {{ $event->end_date->format('d M Y, H:i') }}</p>
        <p><strong>Location:</strong> {{ $event->address }}</p>

        <div class="text-center">
            <img src="{{ $qrCodePath }}">
        </div>
    </div>
</div>
<div class="footer">
    Thank you for choosing {{ $event->name }}. Enjoy the event!
</div>
</body>
</html>
