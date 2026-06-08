@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Subscription Plans</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success mx-4 mt-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger mx-4 mt-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('info'))
                        <div class="alert alert-info mx-4 mt-4" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif
                    
                    <!-- Current Subscription Alert -->
                    @if($currentSubscription)
                        <div class="alert alert-success mx-4 mt-4" role="alert">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-white mb-1">You are currently subscribed to <strong>{{ $currentSubscription->plan->name }}</strong></h5>
                                    <p class="mb-0">
                                        @if($currentSubscription->ends_at)
                                            Your subscription is valid until {{ $currentSubscription->ends_at->format('M d, Y') }}
                                        @else
                                            Your subscription does not expire
                                        @endif
                                    </p>
                                </div>
                                <a href="{{ route('vendor.subscriptions.current') }}" class="btn btn-sm btn-white">View Details</a>
                            </div>
                        </div>
                    @endif
                    
                    <div class="row px-4 mt-4">
                        @forelse($plans as $plan)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card {{ $currentPlanId == $plan->id ? 'border border-success shadow-lg' : '' }}">
                                    <div class="card-header pb-0 px-3">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h5 class="mb-0">{{ $plan->name }}</h5>
                                                @if($currentPlanId == $plan->id)
                                                    <span class="badge bg-gradient-success">Current Plan</span>
                                                @endif
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <h4 class="mb-0 text-primary">₹{{ number_format($plan->price, 2) }}</h4>
                                                <p class="text-xs text-muted mb-0">
                                                    @if($plan->duration_type == 'one-time')
                                                        One-time
                                                    @else
                                                        / {{ $plan->duration_value }} {{ Str::ucfirst($plan->duration_type) }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        @if($plan->description)
                                            <p class="mb-3">{{ $plan->description }}</p>
                                        @endif
                                        
                                        <ul class="list-group">
                                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon icon-shape icon-sm me-3 bg-gradient-primary shadow text-center">
                                                        <i class="ni ni-building text-white opacity-10"></i>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-1 text-dark text-sm">Businesses</h6>
                                                        <span class="text-xs">Up to {{ $plan->max_businesses }} businesses</span>
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
                                                        <span class="text-xs">{{ $plan->max_images }} images per business</span>
                                                    </div>
                                                </div>
                                            </li>
                                            
                                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon icon-shape icon-sm me-3 bg-gradient-warning shadow text-center">
                                                        <i class="ni ni-tag text-white opacity-10"></i>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-1 text-dark text-sm">Featured Listing</h6>
                                                        <span class="text-xs">
                                                            @if($plan->featured_listing)
                                                                <span class="text-success">Included</span>
                                                            @else
                                                                <span class="text-danger">Not included</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                        
                                        <div class="d-flex justify-content-between mt-4">
                                            @if($currentPlanId == $plan->id)
                                                <button class="btn btn-outline-success w-100" disabled>Current Plan</button>
                                            @else
                                                <a href="{{ route('vendor.subscriptions.checkout', $plan->id) }}" class="btn btn-primary w-100">
                                                    @if($currentSubscription)
                                                        Switch Plan
                                                    @else
                                                        Subscribe
                                                    @endif
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning" role="alert">
                                    No subscription plans are available at the moment. Please check back later.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 