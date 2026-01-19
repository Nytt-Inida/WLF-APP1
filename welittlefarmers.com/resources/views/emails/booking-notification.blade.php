<!DOCTYPE html>
<html>

<head>
    <title>New Booking Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 0 0 5px 5px;
        }

        .booking-details {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .detail-row {
            margin-bottom: 15px;
            padding: 10px;
            border-left: 4px solid #28a745;
            background-color: #f8f9fa;
        }

        .label {
            font-weight: bold;
            color: #495057;
        }

        .value {
            color: #212529;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🌱 We Little Farmer</h1>
            <h2>New Live Session Booking Request</h2>
        </div>
        <div class="content">
            <div class="booking-details">
                <h3>📋 Booking Details:</h3>

                <div class="detail-row">
                    <span class="label">👤 Full Name:</span><br>
                    <span class="value">{{ $bookingData['name'] }}</span>
                </div>

                <div class="detail-row">
                    <span class="label">📧 Email:</span><br>
                    <span class="value">{{ $bookingData['email'] }}</span>
                </div>

                <div class="detail-row">
                    <span class="label">🏫 School Name:</span><br>
                    <span class="value">{{ $bookingData['school_name'] }}</span>
                </div>

                <div class="detail-row">
                    <span class="label">🎂 Age:</span><br>
                    <span class="value">{{ $bookingData['age'] }} years old</span>
                </div>

                <div class="detail-row">
                    <span class="label">📅 Date:</span><br>
                    <span class="value">{{ \Carbon\Carbon::parse($bookingData['date'])->format('F j, Y') }}</span>
                </div>

                <div class="detail-row">
                    <span class="label">⏰ Submitted at:</span><br>
                    <span class="value">{{ now()->format('F j, Y - g:i A') }}</span>
                </div>
            </div>

            <!-- <div
                style="margin-top: 20px; padding: 15px; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px;">
                <strong>📞 Next Steps:</strong> Please contact the student to confirm the session details and schedule.
            </div> -->
        </div>
    </div>
</body>

</html>