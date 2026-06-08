<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; background: #0A0F1F; color: white; padding: 20px; border-radius: 5px; }
        .details { background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .footer { text-align: center; margin-top: 30px; font-size: 0.9em; color: #666; }
        .button { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img alt="Youth Central" src="{{ asset('assets_public/images/logo.png') }}" style="width: 70px !important;"/>
            <h2>Registration Confirmed!</h2>
            <p>Thank you for registering for {{ $registration->event->title }}</p>
        </div>

        <div class="details">
            <h3>Event Details</h3>
            <p><strong>Event:</strong> {{ $registration->event->title }}</p>
            <p><strong>Date:</strong> {{ $registration->event->start_date->format('F j, Y g:i A') }}</p>
            <p><strong>Venue:</strong> {{ $registration->event->venue }}</p>
            
            <h3>Registration Information</h3>
            <p><strong>Participant:</strong> {{ $registration->first_name }} {{ $registration->last_name }}</p>
            <p><strong>Registration ID:</strong> {{ $registration->id }}</p>
            <p><strong>Payment ID:</strong> {{ $registration->razorpay_payment_id }}</p>
            <p><strong>Amount Paid:</strong> ₹{{ number_format($registration->amount, 2) }}</p>
        </div>

        <div class="important-info">
            <h3>Important Information</h3>
            <ul>
                <li>Please arrive 30 minutes before the event starts</li>
                <li>Bring a copy of this email or your registration ID</li>
                <li>For any queries, contact us at {{ config('mail.from.address') }}</li>
            </ul>
        </div>

        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 