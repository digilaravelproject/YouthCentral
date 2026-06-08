@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Registration Successful!</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        <h5 class="mt-3">Thank you for registering!</h5>
                    </div>

                    <div class="alert alert-success">
                        <h6>Registration Details:</h6>
                        <p class="mb-1">Event: {{ $registration->event->title }}</p>
                        <p class="mb-1">Participant: {{ $registration->first_name }} {{ $registration->last_name }}</p>
                        <p class="mb-1">Amount Paid: ₹{{ number_format($registration->amount, 2) }}</p>
                        <p class="mb-1">Payment ID: {{ $registration->razorpay_payment_id }}</p>
                    </div>

                    <div class="alert alert-info">
                        <h6>Next Steps:</h6>
                        <p class="mb-1">1. A confirmation email has been sent to {{ $registration->email }}</p>
                        <p class="mb-1">2. Please keep your payment ID for reference</p>
                        <p class="mb-1">3. You will receive event details and updates via email</p>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('events.show', $registration->event) }}" class="btn btn-primary">
                            Back to Event
                        </a>
                        <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                            Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 