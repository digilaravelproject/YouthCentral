@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Subscribe to {{ $plan->name }}</h6>
                        <a href="{{ route('vendor.subscriptions.plans') }}" class="btn btn-secondary btn-sm">Back to Plans</a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger text-white">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('vendor.subscriptions.process', $plan->id) }}" id="payment-form">
                        @csrf
                        
                        <!-- Plan Details -->
                        <div class="card bg-gradient-light mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="mb-0">{{ $plan->name }}</h5>
                                        @if($plan->description)
                                            <p class="text-sm mb-0">{{ $plan->description }}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <h4 class="text-primary mb-0">₹{{ number_format($plan->price, 2) }}</h4>
                                        <p class="text-sm text-muted mb-0">
                                            @if($plan->duration_type == 'one-time')
                                                One-time
                                            @else
                                                {{ $plan->duration_value }} {{ Str::ucfirst($plan->duration_type) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <hr class="horizontal dark my-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="text-sm mb-1">
                                            <strong>Businesses:</strong> {{ $plan->max_businesses }}
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="text-sm mb-1">
                                            <strong>Images:</strong> {{ $plan->max_images }} per business
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="text-sm mb-1">
                                            <strong>Featured:</strong> 
                                            @if($plan->featured_listing)
                                                <span class="text-success">Yes</span>
                                            @else
                                                <span class="text-danger">No</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Method -->
                        <div class="form-group">
                            <label class="form-control-label">Payment Method</label>
                            <select class="form-control" name="payment_method" required>
                                <option value="credit_card">Credit Card</option>
                                <option value="paypal">PayPal</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                            <small class="text-muted">Note: For demo purposes, no actual payment will be processed.</small>
                        </div>
                        
                        <!-- Credit Card Details (demo only) -->
                        <div id="credit-card-details" class="card mt-4 p-3">
                            <h6 class="mb-3">Credit Card Details</h6>
                            <div class="form-group">
                                <label class="form-control-label">Card Number</label>
                                <input type="text" class="form-control" placeholder="1234 5678 9012 3456" disabled>
                                <small class="text-muted">Demo mode - no actual payment will be processed</small>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Expiration Date</label>
                                        <input type="text" class="form-control" placeholder="MM/YY" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">CVC</label>
                                        <input type="text" class="form-control" placeholder="123" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Subscription Details -->
                        <div class="card bg-gradient-default text-white mt-4 mb-4">
                            <div class="card-body p-3">
                                <h5 class="text-white mb-0">Subscription Summary</h5>
                                <hr class="horizontal light my-3">
                                <div class="row">
                                    <div class="col-8">
                                        <p class="text-white mb-1">{{ $plan->name }}</p>
                                        <p class="text-white text-sm mb-1">
                                            @if($plan->duration_type == 'one-time')
                                                One-time payment
                                            @else
                                                {{ $plan->duration_value }} {{ Str::ucfirst($plan->duration_type) }} subscription
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-4 text-end">
                                        <h4 class="text-white mb-0">₹{{ number_format($plan->price, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="form-check form-check-info text-start">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="text-dark font-weight-bolder">Terms and Conditions</a>
                            </label>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('vendor.subscriptions.plans') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Subscribe Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Subscription Benefits</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-success shadow text-center">
                                <i class="ni ni-check-bold text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Manage multiple businesses</h6>
                                <span class="text-xs">Add up to {{ $plan->max_businesses }} businesses to your account</span>
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-success shadow text-center">
                                <i class="ni ni-check-bold text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Showcase with images</h6>
                                <span class="text-xs">Upload up to {{ $plan->max_images }} images for each business</span>
                            </div>
                        </li>
                        @if($plan->featured_listing)
                        <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-success shadow text-center">
                                <i class="ni ni-check-bold text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Featured listings</h6>
                                <span class="text-xs">Your businesses appear prominently in search results</span>
                            </div>
                        </li>
                        @endif
                        <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-success shadow text-center">
                                <i class="ni ni-check-bold text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Priority support</h6>
                                <span class="text-xs">Get assistance whenever you need it</span>
                            </div>
                        </li>
                    </ul>
                    
                    <hr class="horizontal dark my-4">
                    
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape icon-sm me-3 bg-gradient-warning shadow text-center">
                            <i class="ni ni-tag text-white opacity-10"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <h6 class="mb-1 text-dark text-sm">30-Day Money Back Guarantee</h6>
                            <span class="text-xs">Not satisfied? Get a full refund within 30 days</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethodSelect = document.querySelector('select[name="payment_method"]');
        const creditCardDetails = document.getElementById('credit-card-details');
        
        function togglePaymentDetails() {
            if (paymentMethodSelect.value === 'credit_card') {
                creditCardDetails.style.display = 'block';
            } else {
                creditCardDetails.style.display = 'none';
            }
        }
        
        paymentMethodSelect.addEventListener('change', togglePaymentDetails);
        togglePaymentDetails();
    });
</script>
@endpush 