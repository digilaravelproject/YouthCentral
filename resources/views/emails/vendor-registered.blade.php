<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; background: #0A0F1F; color: white; padding: 20px; border-radius: 5px; }
        .welcome { background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .details { background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 20px; }
        .footer { text-align: center; margin-top: 30px; font-size: 0.9em; color: #666; }
        .button { display: inline-block; padding: 12px 25px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 15px 0; }
        .status-badge { padding: 5px 10px; border-radius: 15px; font-size: 0.9em; font-weight: bold; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #d1edff; color: #0c5460; }
        .features { margin: 20px 0; }
        .feature { margin: 10px 0; padding: 10px; background: #f8f9fa; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img alt="Youth Central" src="{{ asset('assets_public/images/logo.png') }}" style="width: 70px !important;"/>
            <h2>🏢 Welcome to Youth Central!</h2>
            <p>Your vendor account has been created</p>
        </div>

        <div class="welcome">
            <h3>Hello {{ $vendor->name }}! 👋</h3>
            <p>Thank you for joining Youth Central as a business vendor! We're excited to help you grow your business.</p>
        </div>

        <div class="details">
            <h3>Your Vendor Account Details</h3>
            <p><strong>Name:</strong> {{ $vendor->name }}</p>
            <p><strong>Email:</strong> {{ $vendor->email }}</p>
            <p><strong>Business Name:</strong> {{ $vendor->business_name }}</p>
            <p><strong>Business Address:</strong> {{ $vendor->business_address }}</p>
            @if($vendor->gst_number)
                <p><strong>GST Number:</strong> {{ $vendor->gst_number }}</p>
            @endif
            @if($vendor->phone)
                <p><strong>Phone:</strong> {{ $vendor->phone }}</p>
            @endif
            <p><strong>Account Status:</strong> 
                <span class="status-badge {{ $vendor->status === 'approved' ? 'status-approved' : 'status-pending' }}">
                    {{ ucfirst($vendor->status) }}
                </span>
            </p>
            <p><strong>Account Created:</strong> {{ $vendor->created_at->format('F j, Y g:i A') }}</p>
        </div>

        @if($vendor->status === 'pending')
        <div class="details" style="background: #fff3cd; border-color: #ffeaa7;">
            <h3>⏳ Account Under Review</h3>
            <p>Your vendor account is currently under review by our admin team. This process typically takes 24-48 hours.</p>
            <p><strong>What happens next?</strong></p>
            <ul>
                <li>Our team will verify your business information</li>
                <li>You'll receive an email notification once approved</li>
                <li>After approval, you can purchase a subscription to start listing your businesses</li>
            </ul>
        </div>
        @elseif($vendor->status === 'approved')
            <div class="details" style="background: #d1edff; border-color: #74b9ff;">
                <h3>✅ Account Approved!</h3>
                <p>Congratulations! Your vendor account has been approved and is ready to use.</p>
                <div style="text-align: center; margin: 20px 0;">
                    <a href="{{ route('vendor.subscriptions.plans') }}" class="button">View Subscription Plans</a>
                </div>
            </div>
        @else
            <div class="details" style="background: #f8979fff; border-color: #f5c6cb;">
                <h3>❌ Account Status: {{ ucfirst($vendor->status) }}</h3>
                <p>Please contact our support team at {{ config('mail.from.address') }} for more information regarding your account status.</p>
            </div>
        @endif

        <div class="details">
            <h3>What You Can Do as a Vendor</h3>
            <div class="features">
                <div class="feature">
                    <h4>📝 List Your Businesses</h4>
                    <p>Add detailed business listings with photos, descriptions, and contact information</p>
                </div>
                <div class="feature">
                    <h4>📊 Analytics & Insights</h4>
                    <p>Track views, leads, and customer engagement with detailed analytics</p>
                </div>
                <div class="feature">
                    <h4>⭐ Manage Reviews</h4>
                    <p>Respond to customer reviews and build your online reputation</p>
                </div>
                <div class="feature">
                    <h4>🎫 Create Events</h4>
                    <p>Organize and promote events to attract more customers</p>
                </div>
                <div class="feature">
                    <h4>📞 Customer Support</h4>
                    <p>Get priority support to help grow your business</p>
                </div>
            </div>
        </div>

        <div class="details">
            <h3>Getting Started</h3>
            <p>Once your account is approved, here's what you should do:</p>
            <ol>
                <li>🔐 Log in to your vendor dashboard</li>
                <li>💳 Choose and purchase a subscription plan</li>
                <li>🏢 Add your first business listing</li>
                <li>📸 Upload high-quality photos</li>
                <li>🎯 Start attracting customers!</li>
            </ol>
            <p>Login Link : <a href="{{ route('vendor.login') }}" target="_blank">{{ route('vendor.login') }}</a></p>
            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ route('vendor.dashboard') }}" class="button">Access Your Dashboard</a>
            </div>
        </div>

        <div class="footer">
            <p><strong>Need Help?</strong> Our vendor support team is here to help at {{ config('mail.from.address') }}</p>
            <p><strong>Resources:</strong> Check out our vendor guides and best practices</p>
            <p>This is an automated message from {{ config('app.name') }} vendor services.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 