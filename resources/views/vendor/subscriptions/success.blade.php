@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <div class="icon icon-shape icon-xl bg-gradient-success text-white shadow-success mx-auto">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <h2 class="mt-4">Subscription Successful!</h2>
                        <p class="text-secondary">Thank you for subscribing to our {{ $subscription->plan->name }} plan.</p>
                    </div>
                    
                    <div class="card bg-gradient-light mb-4">
                        <div class="card-body p-3">
                            <h5 class="mb-3">Subscription Details</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-sm mb-1">
                                        <strong>Plan:</strong> {{ $subscription->plan->name }}
                                    </p>
                                    <p class="text-sm mb-1">
                                        <strong>Price:</strong> ₹{{ number_format($subscription->plan->price, 2) }}
                                        @if($subscription->plan->duration_type != 'one-time')
                                            per {{ $subscription->plan->duration_value }} {{ Str::ucfirst($subscription->plan->duration_type) }}
                                        @endif
                                    </p>
                                    @if($subscription->transaction_id)
                                        <p class="text-sm mb-1">
                                            <strong>Transaction ID:</strong> {{ $subscription->transaction_id }}
                                        </p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <p class="text-sm mb-1">
                                        <strong>Status:</strong> 
                                        <span class="badge bg-gradient-success">{{ ucfirst($subscription->status) }}</span>
                                    </p>
                                    <p class="text-sm mb-1">
                                        <strong>Start Date:</strong> {{ $subscription->started_at->format('M d, Y') }}
                                    </p>
                                    @if($subscription->ends_at)
                                        <p class="text-sm mb-1">
                                            <strong>Expires:</strong> {{ $subscription->ends_at->format('M d, Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card shadow-none border mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">Plan Features</h5>
                            <ul class="list-group">
                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape icon-sm me-3 bg-gradient-primary shadow text-center">
                                            <i class="ni ni-building text-white opacity-10"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark text-sm">Businesses</h6>
                                            <span class="text-xs">Up to {{ $subscription->plan->max_businesses }} businesses</span>
                                        </div>
                                    </div>
                                </li>
                                
                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape icon-sm me-3 bg-gradient-info shadow text-center">
                                            <i class="ni ni-image text-white opacity-10"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark text-sm">Images</h6>
                                            <span class="text-xs">{{ $subscription->plan->max_images }} images per business</span>
                                        </div>
                                    </div>
                                </li>
                                
                                @if($subscription->plan->featured_listing)
                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape icon-sm me-3 bg-gradient-warning shadow text-center">
                                            <i class="ni ni-tag text-white opacity-10"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark text-sm">Featured listings</h6>
                                            <span class="text-xs">Your businesses appear prominently in search results</span>
                                        </div>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <h5 class="mb-3">What's next?</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-gradient-primary">
                                    <div class="card-body text-center p-3">
                                        <div class="icon icon-shape bg-white shadow text-center rounded-circle mb-2">
                                            <i class="ni ni-building text-primary"></i>
                                        </div>
                                        <h6 class="text-white mb-1">Add Your Business</h6>
                                        <p class="text-xs text-white opacity-8 mb-0">Create or claim a business listing</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-gradient-info">
                                    <div class="card-body text-center p-3">
                                        <div class="icon icon-shape bg-white shadow text-center rounded-circle mb-2">
                                            <i class="ni ni-image text-info"></i>
                                        </div>
                                        <h6 class="text-white mb-1">Upload Photos</h6>
                                        <p class="text-xs text-white opacity-8 mb-0">Showcase your business with images</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-gradient-dark">
                                    <div class="card-body text-center p-3">
                                        <div class="icon icon-shape bg-white shadow text-center rounded-circle mb-2">
                                            <i class="ni ni-settings text-dark"></i>
                                        </div>
                                        <h6 class="text-white mb-1">Manage Subscription</h6>
                                        <p class="text-xs text-white opacity-8 mb-0">View and manage your subscription</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-5">
                        <a href="{{ route('vendor.subscriptions.current') }}" class="btn btn-info me-2">View Subscription</a>
                        @if(Auth::user()->businesses()->count() < $subscription->plan->max_businesses)
                            <a href="{{ route('vendor.dashboard') }}" class="btn btn-primary me-2">Add New Business</a>
                        @endif
                        <a href="{{ route('vendor.dashboard') }}" class="btn btn-outline-primary">Go to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 