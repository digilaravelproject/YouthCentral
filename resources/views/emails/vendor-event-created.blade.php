<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial; background: #f4f6f8; }
        .container { max-width: 600px; margin:auto; background:#fff; padding:20px; }
        .header { text-align: center; margin-bottom: 30px; background: #0A0F1F; color: white; padding: 20px; border-radius: 5px; }
        .item { background:#f8f9fa; padding:10px; margin-bottom:8px; }
        .footer { text-align:center; color:#777; margin-top:20px; font-size:13px; }
        .details { background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img alt="Youth Central" src="{{ asset('assets_public/images/logo.png') }}" style="width: 70px !important;"/>
        <h2>🎉 Event Created Successfully</h2>
    </div>

    <p>Your event has been created successfully.</p>

    <div class="item"><strong>Event:</strong> {{ $data['event_title'] }}</div>
    <div class="item"><strong>Date:</strong> {{ $data['event_date'] }}</div>
    <div class="item"><strong>Type:</strong> {{ $data['event_type'] }}</div>
    <div class="item"><strong>Price:</strong> {{ $data['price'] }}</div>

    @if($data['status'] === 'pending')
        <div class="details" style="background: #fff3cd; border-color: #ffeaa7;">
            <h3>⏳ Event Under Review</h3>
            <p>Your event is currently under review by our admin team. This process typically takes 24-48 hours.</p>
        </div>
    @elseif($data['status'] === 'approved')
        <div class="details" style="background: #d1edff; border-color: #74b9ff;">
            <h3>✅ Event Approved!</h3>
            <p>Congratulations! Your event has been approved and is ready to go live.</p>
        </div>
    @else
        <div class="details" style="background: #f8979fff; border-color: #f5c6cb;">
            <h3>❌ Event Status: {{ ucfirst($data['status']) }}</h3>
            <p>Please contact our support team at {{ config('mail.from.address') }} for more information regarding your event status.</p>
        </div>
    @endif

    <div class="footer">
        <p><strong>Need Help?</strong> Our vendor support team is here to help at {{ config('mail.from.address') }}</p>
        <p><strong>Resources:</strong> Check out our vendor guides and best practices</p>
        <p>This is an automated message from {{ config('app.name') }} vendor services.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</div>
</body>
</html>
