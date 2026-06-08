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
        .features { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 20px 0; }
        .feature { background: #f8f9fa; padding: 15px; border-radius: 5px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img alt="Youth Central" src="{{ asset('assets_public/images/logo.png') }}" style="width: 70px !important;"/>
            <h2>🎉 Welcome to Youth Central!</h2>
            <p>Your account has been successfully created</p>
        </div>

        <div class="welcome">
            <h3>Hello {{ $user->name }}! 👋</h3>
            <p>Thank you for joining Youth Central! We're excited to have you as part of our community.</p>
        </div>

        <div class="details">
            <h3>Your Account Details</h3>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p>
                <strong>Password:</strong>
                <span style="color: #d9534f; font-weight: bold;">
                    {{ $plainPassword }}
                </span>
            </p>
            @if($user->phone)
                <p><strong>Phone:</strong> {{ $user->phone }}</p>
            @endif
            @if($user->location)
                <p><strong>Location:</strong> {{ $user->location }}</p>
            @endif
            <p><strong>Account Created:</strong> {{ $user->created_at->format('F j, Y g:i A') }}</p>
        </div>

        <div class="details">
            <h3>What You Can Do</h3>
            <div class="features">
                <div class="feature">
                    <h4>🔍 Discover</h4>
                    <p>Find amazing businesses and services in your area</p>
                </div>
                <div class="feature">
                    <h4>⭐ Review</h4>
                    <p>Share your experiences and help others</p>
                </div>
                <div class="feature">
                    <h4>🎫 Events</h4>
                    <p>Register for exciting events and activities</p>
                </div>
                <div class="feature">
                    <h4>📱 Connect</h4>
                    <p>Connect with local businesses and community</p>
                </div>
            </div>
        </div>

        <div class="details">
            <h3>Get Started</h3>
            <p>Ready to explore? Here are some things you can do right now:</p>
            <ul>
                <li>🏠 Complete your profile with more details</li>
                <li>📍 Set your preferred location for better recommendations</li>
                <li>🔍 Start discovering businesses in your area</li>
                <li>📅 Check out upcoming events</li>
            </ul>
            <p>Login Link : <a href="{{ route('user.login') }}" target="_blank">{{ route('user.login') }}</a></p>
            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ route('user.dashboard') }}" class="button">Go to Your Dashboard</a>
            </div>
        </div>

        <div class="footer">
            <p><strong>Need Help?</strong> Our support team is here to help at {{ config('mail.from.address') }}</p>
            <p><strong>Stay Connected:</strong> Follow us on social media for the latest updates</p>
            <p>This is an automated welcome message from {{ config('app.name') }}.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 