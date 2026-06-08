@extends('layouts.app-minimal')

@section('title', 'Checkout - ' . $plan->name)

@push('styles')
<style>
    .checkout-card {
        background-color: #ffffff; /* Ensure white background */
        border: none; /* Remove default border */
        box-shadow: 0 4px 12px rgba(0,0,0,0.1); /* Subtle shadow */
    }
    /* Style for the back button */
    .back-button {
        margin-bottom: 1.5rem; /* Space below back button */
        display: inline-flex; /* Align icon and text */
        align-items: center;
    }
    .back-button i {
        margin-right: 0.5rem;
    }
    /* Optional: Style the placeholder button slightly */
    #rzp-placeholder-button {
        font-weight: 600;
    }
    /* Hide the default Razorpay payment button */
    .razorpay-payment-button {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-md-5"> {{-- Added vertical padding --}}
    {{-- Back Button Row --}}
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5 mb-3">
            <a href="javascript:history.back()" class="btn btn-outline-dark back-button">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- Main Content Row --}}
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12 col-xl-5">
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div><strong>Error!</strong> {{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            <div class="card checkout-card rounded-lg overflow-hidden"> {{-- Applied custom class --}}
                <div class="card-header text-center py-3 border-0" style="background-color: var(--primary-color);">
                    <h4 class="mb-0 text-white"><i class="fas fa-shopping-cart me-2"></i>Subscription Checkout</h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row mb-4">
                        <div class="col-md-6 border-end mb-3 mb-md-0"> {{-- Added margin bottom for small screens --}}
                            <h6 class="text-dark mb-3 fw-bold">Plan Details</h6> {{-- Added fw-bold --}}
                            <p class="mb-1 text-sm"><strong>Plan:</strong> {{ $plan->name }}</p>
                            <p class="mb-1 text-sm"><strong>Price:</strong> ₹{{ number_format($plan->price, 2) }}</p>
                            <p class="mb-1 text-sm">
                                <strong>Duration:</strong> 
                                {{ $plan->duration_in_months }} {{ Str::plural('month', $plan->duration_in_months) }}
                                {{-- Simplified - Assuming duration is always months based on previous context --}}
                                {{-- @if($plan->duration_type == 'one-time') --}}
                                {{--     One-time --}}
                                {{-- @else --}}
                                {{--     {{ $plan->duration_value }} {{ Str::ucfirst($plan->duration_type) }} --}}
                                {{-- @endif --}}
                            </p>
                            {{-- Removed description as it might be long --}}
                            {{-- <p class="text-xs text-muted">{{ $plan->description }}</p> --}}
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <h6 class="text-dark mb-3 fw-bold">Your Information</h6> {{-- Added fw-bold --}}
                            <p class="mb-1 text-sm"><strong>Name:</strong> {{ $user->name }}</p>
                            <p class="mb-1 text-sm"><strong>Email:</strong> {{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="alert alert-light border text-dark mb-4 text-center py-3">
                         You will be charged <strong class="fw-bold">₹{{ number_format($plan->price, 2) }}</strong>.
                    </div>

                    <div id="payment-error" class="alert alert-danger text-white font-weight-bold d-flex align-items-center" style="display: none;">
                        <i class="fas fa-times-circle me-2"></i>
                        <span id="payment-error-message"></span> {{-- Added span for message --}}
                    </div>

                    <form action="{{ route('razorpay.callback') }}" method="POST" id="payment-form">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <input type="hidden" name="razorpay_order_id" value="{{ $orderId }}"> {{-- Ensure order ID is submitted --}}
                        <div class="text-center">
                            <!-- Replace the script tag with our own button that will trigger Razorpay -->
                            <button type="button" id="rzp-placeholder-button" class="btn btn-lg px-5 rounded-pill text-white" style="background-color: var(--primary-color);">
                                <i class="fas fa-shield-alt me-2"></i> Pay ₹{{ number_format($plan->price, 2) }} Securely
                            </button>
                        </div>
                        <div class="mt-3 text-center">
                            <a href="{{ route('vendor.subscription.required') }}" class="btn btn-link text-secondary me-2">Cancel</a>
                        </div>
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
        const placeholderButton = document.getElementById('rzp-placeholder-button');
        const errorDiv = document.getElementById('payment-error');
        const errorMessageSpan = document.getElementById('payment-error-message');
        const orderId = "{{ $orderId ?? '' }}";
        const razorpayKey = "{{ $razorpayId ?? '' }}";
        const amount = "{{ $amount ?? 0 }}";
        const currency = "{{ $currency ?? 'INR' }}";

        function showPaymentError(message) {
            console.error("Payment Error Displayed:", message);
            if (errorMessageSpan) errorMessageSpan.innerText = message;
            if (errorDiv) errorDiv.style.display = "flex";
            if (placeholderButton) {
                placeholderButton.innerText = 'Payment Failed';
                placeholderButton.disabled = true;
                placeholderButton.style.backgroundColor = '#dc3545';
            }
        }

        // Basic validation before setting up button click
        if (!orderId) {
            showPaymentError("Payment initialization failed (Missing Order ID). Please go back and try again or contact support.");
            return;
        }
        if (!razorpayKey) {
            showPaymentError("Payment initialization failed (Missing API Key). Please contact support.");
            return;
        }
        if (!amount || amount <= 0) {
            showPaymentError("Payment initialization failed (Invalid Amount). Please contact support.");
            return;
        }

        // Initialize Razorpay on button click
        placeholderButton.addEventListener('click', function() {
            placeholderButton.disabled = true;
            placeholderButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
            if (errorDiv) errorDiv.style.display = 'none'; // Hide previous errors

            var options = {
                "key": razorpayKey,
                "amount": amount, // Already in paise from controller
                "currency": currency,
                "name": "{{ config('app.name', 'Youth Central') }}",
                "description": "{{ addslashes($plan->name) }} Subscription",
                "image": "{{ asset('assets_public/images/logo.png') }}",
                "order_id": orderId,
                "handler": function (response){
                    console.log('Razorpay Success Response:', response);
                    // Add hidden fields to form before submitting
                    document.getElementById('payment-form').insertAdjacentHTML('beforeend', `<input type="hidden" name="razorpay_payment_id" value="${response.razorpay_payment_id}">`);
                    document.getElementById('payment-form').insertAdjacentHTML('beforeend', `<input type="hidden" name="razorpay_signature" value="${response.razorpay_signature}">`);
                    document.getElementById('payment-form').submit();
                },
                "prefill": {
                    "name": "{{ addslashes($user->name) }}",
                    "email": "{{ addslashes($user->email) }}"
                },
                "notes": { // Optional notes
                     "plan_id": "{{ $plan->id }}",
                     "user_id": "{{ $user->id }}"
                },
                "theme": {
                    "color": "var(--primary-color)"
                },
                "modal": {
                    "ondismiss": function() {
                        console.log('Checkout form closed by user.');
                        placeholderButton.disabled = false;
                        placeholderButton.innerHTML = `<i class="fas fa-shield-alt me-2"></i> Pay ₹{{ number_format($plan->price, 2) }} Securely`; // Reset button text
                    }
                }
            };

            try {
                var rzp = new Razorpay(options);

                rzp.on('payment.failed', function (response){
                    // Log the detailed error response from Razorpay
                    console.error('Razorpay Payment Failed Response:', response);
                    
                    let detailedMessage = 'Payment failed.';
                    if (response.error) {
                        detailedMessage = `Payment failed: ${response.error.description || response.error.reason || 'Unknown Razorpay error'}.`;
                        if (response.error.metadata && response.error.metadata.order_id) {
                            detailedMessage += ` (Order ID: ${response.error.metadata.order_id})`;
                        }
                        // Optionally log more details if available
                        console.error('Error Code:', response.error.code);
                        console.error('Error Source:', response.error.source);
                        console.error('Error Step:', response.error.step);
                    }
                    // Display the error to the user
                    showPaymentError(detailedMessage + ' Please try again or contact support.');
                     // IMPORTANT: Do NOT redirect here. Let the user see the error.
                });

                rzp.open();

            } catch (error) {
                 console.error("Error initializing Razorpay object:", error);
                 showPaymentError("Could not initialize payment gateway. Please refresh the page or contact support.");
            }
        });
    });
</script>
@endpush 