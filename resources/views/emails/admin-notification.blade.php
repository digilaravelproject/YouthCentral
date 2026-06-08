<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; background: #6c757d; color: white; padding: 20px; border-radius: 5px; }
        .notification { background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .details { background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 20px; }
        .footer { text-align: center; margin-top: 30px; font-size: 0.9em; color: #666; }
        .button { display: inline-block; padding: 12px 25px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 15px 0; }
        .info-item { margin: 10px 0; padding: 8px; background: #f8f9fa; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🔔 Admin Notification</h2>
            <p>{{ config('app.name') }} - Administrative Alert</p>
        </div>

        <div class="notification">
            @if(isset($type))
                <h3>
                    @if($type === 'subscription')
                        💳 New Subscription Purchase
                    @elseif($type === 'user_registration')
                        👤 New User Registration
                    @elseif($type === 'vendor_registration')
                        🏢 New Vendor Registration
                    @elseif($type === 'event_registration')
                        🎫 New Event Registration
                    @else
                        📋 System Notification
                    @endif
                </h3>
            @endif

            @if(isset($message))
                <p>{{ $message }}</p>
            @endif
        </div>

        @if(isset($details) && is_array($details))
        <div class="details">
            <h3>Details</h3>
            @foreach($details as $key => $value)
                <div class="info-item">
                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> 
                    @if(is_array($value))
                        {{ json_encode($value) }}
                    @else
                        {{ $value }}
                    @endif
                </div>
            @endforeach
        </div>
        @endif

        @if(isset($action_url))
        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ $action_url }}" class="button">{{ $action_text ?? 'View Details' }}</a>
        </div>
        @endif

        <div class="footer">
            <p>This is an automated administrative notification from {{ config('app.name') }}.</p>
            <p><strong>Timestamp:</strong> {{ now()->format('F j, Y g:i A') }}</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 