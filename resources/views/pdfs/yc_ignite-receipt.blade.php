<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Event Registration Receipt</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.6; font-size: 14px; padding-top: 20px; }
        .receipt { max-width: 800px; margin: 0 auto; }
        .header { text-align: center; border-bottom: 2px solid #3399cc; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { max-width: 200px; margin-bottom: 20px; }
        .receipt-title { color: #3399cc; font-size: 24px; font-weight: bold; margin-bottom: 5px; }
        .receipt-subtitle { color: #666; font-size: 16px; margin-top: 0; }
        .company-info, .customer-info { margin-bottom: 30px; }
        .info-title { font-weight: bold; color: #3399cc; font-size: 16px; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .info-row { margin-bottom: 5px; }
        .info-label { font-weight: bold; width: 150px; display: inline-block; }
        .event-details { margin-bottom: 30px; }
        .receipt-box { border: 1px solid #ddd; padding: 15px; border-radius: 5px; background-color: #f9f9f9; margin-bottom: 30px; }
        .receipt-id { font-size: 18px; font-weight: bold; color: #3399cc; margin-bottom: 10px; }
        .receipt-date { color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { text-align: left; padding: 10px; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .amount { text-align: right; }
        .total-row { font-weight: bold; border-top: 2px solid #ddd; }
        .footer { border-top: 1px solid #ddd; padding-top: 20px; font-size: 12px; color: #666; text-align: center; }
        .terms { margin-top: 30px; font-size: 12px; color: #777; }
        .thank-you { margin-top: 30px; text-align: center; color: #3399cc; font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <img src="{{ public_path('assets_public/images/logo.png') }}" class="logo" alt="{{ $company_name }}">
            <h1 class="receipt-title">Payment Receipt</h1>
            <p class="receipt-subtitle">Event Registration</p>
        </div>
        
        <div class="receipt-box">
            <div class="receipt-id">Receipt #: {{ $receipt_id }}</div>
            <div class="receipt-date">Issue Date: {{ $issued_date }}</div>
        </div>

        <div class="row">
            <div class="company-info">
                <div class="info-title">From</div>
                <div class="info-row">{{ $company_name }}</div>
                <div class="info-row">{{ $company_address }}</div>
                <div class="info-row">Email: {{ $company_email }}</div>
            </div>
            
            <div class="customer-info">
                <div class="info-title">To</div>
                <div class="info-row">{{ $registration->first_name }} {{ $registration->last_name }}</div>
                <div class="info-row">{{ $registration->address ?? 'N/A' }}</div>
                <div class="info-row">Email: {{ $registration->email }}</div>
                <div class="info-row">Phone: {{ $registration->mobile }}</div>
            </div>
        </div>
        
        <div class="event-details">
            <div class="info-title">Event Details</div>
            <div class="info-row"><span class="info-label">Event Name:</span> {{ $event->title }}</div>
            <div class="info-row"><span class="info-label">Date:</span> {{ $event->start_date->format('D, M d, Y') }}</div>
            <div class="info-row"><span class="info-label">Time:</span> {{ $event->start_date->format('h:i A') }} - {{ $event->end_date->format('h:i A') }}</div>
            <div class="info-row"><span class="info-label">Venue:</span> {{ $event->venue }}</div>
            <div class="info-row"><span class="info-label">Sport/Event:</span> {{ $registration->sport_event }}</div>
            
            @if($registration->age_category)
            <div class="info-row">
                <span class="info-label">School Type:</span> 
                {{ \DB::table('school_types')->where('id', $registration->age_category)->value('school_type') ?? 'N/A' }}
            </div>
            @endif
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th width="150" class="amount">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Registration Fee for {{ $event->title }}</td>
                    <td class="amount">₹{{ \DB::table('school_types')->where('id', $registration->age_category)->value('price') ?? 'N/A' }}</td>
                </tr>
                {{-- <tr class="total-row">
                    <td>Total</td>
                    <td class="amount">₹{{ number_format($registration->amount_paid ?? $registration->amount, 2) }}</td>
                </tr> --}}
            </tbody>
        </table>
        
        {{-- <div class="payment-info">
            <div class="info-title">Payment Information</div>
            <div class="info-row"><span class="info-label">Payment Status:</span> Paid</div>
            <div class="info-row"><span class="info-label">Payment Date:</span> {{ $registration->updated_at->format('M d, Y h:i A') }}</div>
            <div class="info-row"><span class="info-label">Payment Method:</span> Razorpay</div>
            <div class="info-row"><span class="info-label">Transaction ID:</span> {{ $registration->razorpay_payment_id ?? 'N/A' }}</div>
        </div> --}}
        
        <div class="thank-you">Thank you for your registration!</div>
        
        <div class="terms">
            <p><strong>Terms and Conditions:</strong></p>
            <ul>
                <li>This receipt serves as proof of your registration and payment.</li>
                <li>Please bring a digital or printed copy of this receipt to the event.</li>
                <li>Registration fees are non-refundable unless the event is cancelled by the organizer.</li>
                <li>For any inquiries, please contact us at {{ $company_email }}.</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>This is a computer-generated receipt and does not require a signature.</p>
            <p>&copy; {{ date('Y') }} {{ $company_name }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
