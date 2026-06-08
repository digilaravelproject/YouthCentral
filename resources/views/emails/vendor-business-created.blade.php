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
        <img src="{{ asset('assets_public/images/logo.png') }}" style="width:70px" alt="Youth Central">
        <h2>🏢 Business Created Successfully</h2>
    </div>

    <p>Your business has been created successfully. Below are the details:</p>

    <div class="item"><strong>Business Name:</strong> {{ $data['business_name'] }}</div>
    <div class="item"><strong>Email:</strong> {{ $data['email'] }}</div>
    <div class="item"><strong>Phone:</strong> {{ $data['phone'] }}</div>
    <div class="item"><strong>Website:</strong> {{ $data['website'] ?? 'N/A' }}</div>
    <div class="item"><strong>Address:</strong> {{ $data['street_address'] }}</div>
    <div class="item"><strong>Created On:</strong> {{ $data['created_at'] }}</div>

    @if($data['status'] === 'pending')
        <div class="details" style="background:#fff3cd; border-color:#ffeaa7;">
            <h3>⏳ Business Under Review</h3>
            <p>Your business is under admin review. Approval usually takes 24–48 hours.</p>
        </div>
    @elseif($data['status'] === 'active')
        <div class="details" style="background:#d1edff; border-color:#74b9ff;">
            <h3>✅ Business Approved</h3>
            <p>Your business is approved and now live.</p>
        </div>
    @else
        <div class="details" style="background:#f8d7da; border-color:#f5c6cb;">
            <h3>❌ Business Status: {{ ucfirst($data['status']) }}</h3>
            <p>Please contact support at {{ config('mail.from.address') }}</p>
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
