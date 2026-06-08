<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; background: #007bff; color: white; padding: 20px; border-radius: 5px; }
        .invoice { background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .details { background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 20px; }
        .footer { text-align: center; margin-top: 30px; font-size: 0.9em; color: #666; }
        .button { display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .invoice-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .invoice-table th, .invoice-table td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        .invoice-table th { background: #f8f9fa; }
        .total { font-weight: bold; font-size: 1.1em; background: #007bff; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img alt="Youth Central" src="{{ asset('assets_public/images/logo.png') }}" style="width: 70px !important;"/>
            <h2>🎉 Subscription Activated!</h2>
            <p>Welcome to {{ $plan->name }} - Your subscription is now active</p>
        </div>

        <div class="details">
            <img alt="Youth Central" src="{{ asset('assets_public/images/logo.png') }}" style="width: 70px !important;"/>
            <h3>Subscription Details</h3>
            <p><strong>Plan:</strong> {{ $plan->name }}</p>
            <p><strong>Duration:</strong> 
                @if($plan->duration_type == 'one-time')
                    One-time access
                @else
                    {{ $plan->duration_value }} {{ ucfirst($plan->duration_type) }}
                @endif
            </p>
            <p><strong>Started:</strong> {{ $subscription->started_at->format('F j, Y g:i A') }}</p>
            @if($subscription->ends_at)
                <p><strong>Expires:</strong> {{ $subscription->ends_at->format('F j, Y g:i A') }}</p>
            @endif
            <p><strong>Status:</strong> <span style="color: #28a745; font-weight: bold;">{{ ucfirst($subscription->status) }}</span></p>
        </div>

        <div class="invoice">
            <h3>📄 Invoice Receipt</h3>
            <p><strong>Invoice ID:</strong> SUB-{{ $subscription->id }}</p>
            <p><strong>Transaction ID:</strong> {{ $subscription->transaction_id }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($subscription->payment_method) }}</p>
            <p><strong>Date:</strong> {{ $subscription->created_at->format('F j, Y g:i A') }}</p>

            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Duration</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $plan->name }}</td>
                        <td>
                            @if($plan->duration_type == 'one-time')
                                One-time
                            @else
                                {{ $plan->duration_value }} {{ ucfirst($plan->duration_type) }}
                            @endif
                        </td>
                        <td>₹{{ number_format($subscription->amount_paid, 2) }}</td>
                    </tr>
                    <tr class="total">
                        <td colspan="2"><strong>Total Paid</strong></td>
                        <td><strong>₹{{ number_format($subscription->amount_paid, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="details">
            <h3>What's Next?</h3>
            <ul>
                <li>✅ You can now add up to {{ $plan->max_businesses ?? 'unlimited' }} businesses</li>
                <li>📊 Access to advanced analytics and reporting</li>
                <li>🎯 Enhanced business listing features</li>
                <li>📞 Priority customer support</li>
            </ul>
            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ route('vendor.dashboard') }}" class="button">Access Your Dashboard</a>
            </div>
        </div>

        <div class="footer">
            <p><strong>Need Help?</strong> Contact our support team at {{ config('mail.from.address') }}</p>
            <p>This is an automated message. Please save this email as your invoice receipt.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 