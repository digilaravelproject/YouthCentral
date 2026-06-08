@extends('layouts.user_type.auth')

@section('title', 'Subscription Required')

@push('styles')
<style>
    .plan-card {
        background-color: #ffffff;
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s ease-in-out, box-shadow 0.3s ease-in-out;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .plan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    .plan-card .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        padding: 1rem 1.25rem;
    }
    .plan-card .card-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .plan-card .card-price {
        color: #344767;
        margin-bottom: 0.5rem;
    }
    .plan-card .price-duration {
        color: #6c757d;
        margin-bottom: 1.5rem;
    }
    .icon-shape {
        width: 32px;
        height: 32px;
        background-position: center;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .list-group-item {
        padding: 0.5rem 0;
    }
    .back-button {
        margin-bottom: 1.5rem;
        display: inline-flex;
        align-items: center;
    }
    .back-button i {
        margin-right: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <a href="javascript:history.back()" class="btn btn-outline-dark back-button">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center bg-gradient-warning border-0">
                    <h3 class="mb-0 text-white"><i class="fas fa-exclamation-triangle me-2"></i>Subscription Required</h3>
                </div>
                <div class="card-body p-4 p-md-5">
                    <p class="text-center lead mb-4">To access all vendor features, including managing your businesses and events, please purchase a subscription plan.</p>
                    
                    @if(session('warning'))
                        <div class="alert alert-warning text-white font-weight-bold d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <div>{{ session('warning') }}</div>
                        </div>
                    @endif

                    <h5 class="text-center mb-4 fw-bold">Available Plans</h5>
                    
                    @if($plans->isEmpty())
                        <p class="text-center text-muted mt-5">No subscription plans are currently available. Please contact support.</p>
                    @else
                        <div class="row justify-content-center">
                            @foreach($plans as $plan)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card plan-card">
                                        <div class="card-header pb-0 px-3">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h5 class="mb-0">{{ $plan->name }}</h5>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <h4 class="mb-0 text-primary">₹{{ number_format($plan->price, 0) }}</h4>
                                                    <p class="text-xs text-muted mb-0">
                                                        @if($plan->duration_type == 'one-time')
                                                            One-time payment
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
                                                
                                                @if($plan->features)
                                                    @foreach(explode("\n", $plan->features) as $feature)
                                                        @if(trim($feature))
                                                            <li class="list-group-item border-0 d-flex ps-0 mb-2 border-radius-lg">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="icon icon-shape icon-sm me-3 bg-gradient-success shadow text-center" >
                                                                        <i class="fas fa-check text-white opacity-10"  style="top: 5px !important;"></i>
                                                                    </div>
                                                                    <span class="text-sm">{{ trim($feature) }}</span>
                                                                </div>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </ul>
                                            
                                            <div class="d-flex justify-content-between mt-4">
                                                <a href="{{ route('vendor.subscriptions.checkout', $plan->id) }}" class="btn btn-primary w-100">Choose Plan</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="card-footer text-center border-top bg-light py-3">
                    <p class="text-muted mb-0 small">Need help? <a href="{{ route('contact') }}">Contact Support</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 