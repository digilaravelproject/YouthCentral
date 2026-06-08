@extends('layouts.app-minimal')

@section('title', 'Complete Payment - ' . $event->title)

@push('styles')
<style>
    .payment-card {
        background-color: #ffffff; /* Ensure white background */
        border: none; /* Remove default border */
        box-shadow: 0 4px 12px rgba(0,0,0,0.1); /* Subtle shadow */
        border-radius: 8px; /* Match other cards */
        overflow: hidden; /* Ensure content respects border radius */
    }
    .back-button {
        margin-bottom: 1.5rem; /* Space below back button */
        display: inline-flex; /* Align icon and text */
        align-items: center;
    }
    .back-button i {
        margin-right: 0.5rem;
    }
    .event-summary {
        background-color: #f8f9fa !important; /* Ensure light background */
        border-radius: 6px; /* Slightly rounded corners */
    }
    .payment-details {
        background-color: #f8f9fa !important; /* Ensure light background */
        border-radius: 6px; /* Slightly rounded corners */
    }
    #rzp-button, #mock-payment-button {
        font-weight: 600;
    }
    .mock-payment-info {
        background-color: #fff8e1;
        border: 1px solid #ffe082;
        border-radius: 6px;
        padding: 10px 15px;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-md-5"> {{-- Added vertical padding --}}
    {{-- Back Button Row --}}
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12 col-xl-5 mb-3">
            <a href="javascript:history.back()" class="btn btn-outline-dark back-button">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- Main Content Row --}}
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12 col-xl-5">
            <div class="card payment-card"> {{-- Applied custom class and removed default classes like rounded-lg --}}
                <div class="card-header text-center py-3 border-0" style="background-color: var(--primary-color);">
                    <h4 class="mb-0 text-white" style="color: #fff;!important;"><i class="fas fa-credit-card me-2"></i>Payment Required</h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="event-summary mb-4 border p-3 text-center"> {{-- Removed bg-light to inherit from style --}}
                        <h6 class="mb-2 text-dark fw-bold text-center">{{ $event->title }}</h6> {{-- Added fw-bold --}}
                        <p class="text-sm text-muted mb-0 text-center">
                            <i class="fas fa-map-marker-alt fa-fw me-1"></i> {{ $event->venue }}
                            <span class="mx-2 d-none d-sm-inline">|</span> {{-- Hide separator on very small screens --}}
                            <br class="d-sm-none"> {{-- Break line on very small screens --}}
                            <i class="far fa-clock fa-fw me-1"></i> {{ $event->start_date->format('M d, Y h:i A') }}
                        </p>
                    </div>

                    <div class="payment-details p-3 mb-4 border rounded text-center"> {{-- Removed bg-light to inherit from style --}}
                        <div class="row text-sm">
                            <div class="col-6 text-start text-secondary">Registration Fee:</div>
                            <div class="col-6 text-end text-dark fw-bold">₹{{ number_format($registration->amount, 2) }}</div>
                        </div>
                        <hr class="my-2">
                        <div class="row fw-bold text-dark">
                            <div class="col-6 text-start">Total Amount:</div>
                            <div class="col-6 text-end">₹{{ number_format($registration->amount, 2) }}</div>
                        </div>
                    </div>

                    {{-- Mock Payment Notice (only shown for mock orders) --}}
                    @php
                        $isMockOrder = strpos($registration->razorpay_order_id ?? '', 'order_mock_') === 0;
                    @endphp
                    
                    @if($isMockOrder)
                    <div class="mock-payment-info text-center">
                        <p class="mb-1"><strong>Test Environment Detected</strong></p>
                        <p class="mb-0 small">This is a test order. You can complete the registration without actual payment processing.</p>
                    </div>
                    @endif

                    {{-- Payment Error Placeholder --}}
                    {{-- <div id="payment-error-alert" class="alert alert-danger d-none d-flex align-items-center" role="alert">
                         <i class="fas fa-exclamation-triangle me-2"></i>
                         <span id="payment-error-message"></span>
                         <button type="button" class="btn-close ms-auto" onclick="this.parentElement.classList.add('d-none')" aria-label="Close"></button>
                    </div> --}}

                    <div class="payment-actions text-center mt-4">
                        @if($isMockOrder)
                            <button id="mock-payment-button" class="btn btn-lg px-5 rounded-pill text-white" style="background-color: var(--primary-color); color: #fff;!important;">
                                <i class="fas fa-shield-alt me-2"></i> Complete Test Payment (₹{{ number_format($registration->amount, 2) }})
                            </button>
                            <p class="text-muted small mt-2 mb-0">
                                <i class="fas fa-info-circle me-1"></i> Test mode - no actual payment will be processed
                            </p>
                        @else
                            <button id="rzp-button" class="btn btn-lg px-5 rounded-pill text-white" style="background-color: var(--primary-color); color: #fff;!important;">
                                <i class="fas fa-shield-alt me-2"></i> Pay ₹{{ number_format($registration->amount, 2) }} Securely
                            </button>
                            <p class="text-muted small mt-2 mb-0">
                                <i class="fas fa-lock me-1"></i> Secure payment via Razorpay
                            </p>
                        @endif
                    </div>

                    <form id="payment-form" action="{{ route('user.events.payment.handle') }}" method="POST" class="d-none">
                        @csrf
                        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="{{ $isMockOrder ? 'pay_mock_'.time() : '' }}">
                        <input type="hidden" name="razorpay_order_id" value="{{ $registration->razorpay_order_id }}">
                        <input type="hidden" name="razorpay_signature" id="razorpay_signature" value="{{ $isMockOrder ? 'sig_mock_'.time() : '' }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('payment-form');
    const paymentIdInput = document.getElementById('razorpay_payment_id');
    const signatureInput = document.getElementById('razorpay_signature');
    const errorAlert = document.getElementById('payment-error-alert');
    const errorMessageSpan = document.getElementById('payment-error-message');
    
    // Check if this is a mock order
    const isMockOrder = {{ $isMockOrder ? 'true' : 'false' }};
    
    function showPaymentError(message) {
        if (errorMessageSpan) errorMessageSpan.textContent = message || 'An unknown error occurred.';
        if (errorAlert) errorAlert.classList.remove('d-none');
        
        // Re-enable the appropriate button
        if (isMockOrder) {
            document.getElementById('mock-payment-button').disabled = false;
        } else {
            document.getElementById('rzp-button').disabled = false;
        }
    }
    
    // For mock orders, handle the test payment button
    if (isMockOrder) {
        const mockPaymentButton = document.getElementById('mock-payment-button');
        
        mockPaymentButton.onclick = function(e) {
            e.preventDefault();
            mockPaymentButton.disabled = true;
            
            // Generate mock payment ID and signature
            const mockPaymentId = 'pay_mock_' + Math.floor(Math.random() * 1000000);
            const mockSignature = 'sig_mock_' + Math.floor(Math.random() * 1000000);
            
            paymentIdInput.value = mockPaymentId;
            signatureInput.value = mockSignature;
            
            // Submit the form after a short delay to simulate processing
            setTimeout(function() {
                paymentForm.submit();
            }, 1000);
        };
    } else {
        // For real orders, use the Razorpay checkout
        const rzpButton = document.getElementById('rzp-button');
        
        rzpButton.onclick = function(e) {
            e.preventDefault();
            rzpButton.disabled = true; // Disable button during payment process
            if (errorAlert) errorAlert.classList.add('d-none'); // Hide previous errors

            var options = {
                "key": "{{ config('services.razorpay.key') }}",
                "amount": "{{ $registration->amount * 100 }}", // Amount in paise
                "currency": "INR",
                "name": "{{ config('app.name', 'Youth Central') }}",
                "description": "Registration for {{ addslashes($event->title) }}",
                "image": "{{ asset('assets_public/images/logo.png') }}", // Check path
                "order_id": "{{ $registration->razorpay_order_id }}",
                "handler": function (response) {
                    console.log('Razorpay Success Response:', response);
                    if (response.razorpay_payment_id) {
                        paymentIdInput.value = response.razorpay_payment_id;
                    }
                    if (response.razorpay_signature) {
                        signatureInput.value = response.razorpay_signature;
                    }
                    paymentForm.submit();
                },
                "prefill": {
                    "name": "{{ addslashes($registration->first_name . ' ' . $registration->last_name) }}",
                    "email": "{{ $registration->email }}",
                    "contact": "{{ $registration->mobile }}"
                },
                "notes": {
                    "event_registration_id": "{{ $registration->id }}"
                },
                "theme": {
                    "color": "var(--primary-color)"
                },
                "modal": {
                    "ondismiss": function() {
                        console.log('Checkout form closed');
                        rzpButton.disabled = false; // Re-enable button if user closes modal
                    }
                }
            };

            try {
                var rzp = new Razorpay(options);

                rzp.on('payment.failed', function (response) {
                    console.error('Razorpay Payment Failed:', response);
                    let detailedMessage = 'Payment failed.';
                    if (response.error) {
                        detailedMessage = `Payment failed: ${response.error.description || response.error.reason || 'Unknown reason'}.`;
                        if (response.error.metadata && response.error.metadata.order_id) {
                            detailedMessage += ` (Order ID: ${response.error.metadata.order_id})`;
                        }
                    }
                    showPaymentError(detailedMessage + ' Please try again or contact support.');
                });

                rzp.open();

            } catch (error) {
                console.error("Error initializing Razorpay:", error);
                showPaymentError("Could not initialize payment gateway. Please refresh the page or contact support.");
            }
        };
    }
});
</script>
@endpush