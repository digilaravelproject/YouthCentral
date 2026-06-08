@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Your Subscription</h6>
                        <a href="{{ route('vendor.subscriptions.plans') }}" class="btn btn-primary btn-sm">View Plans</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success text-white" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger text-white" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('info'))
                        <div class="alert alert-info text-white" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif
                    
                    @if(!$subscription)
                        <div class="alert alert-warning text-white" role="alert">
                            <h5 class="text-white">You don't have an active subscription</h5>
                            <p class="mb-0">Subscribe to a plan to manage your businesses more effectively.</p>
                            <div class="mt-3">
                                <a href="{{ route('vendor.subscriptions.plans') }}" class="btn btn-sm btn-white">View Available Plans</a>
                            </div>
                        </div>
                    @else
                        <!-- Current Plan -->
                        <div class="card bg-gradient-info mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="text-white mb-0">{{ $subscription->plan->name }}</h5>
                                        @if($subscription->plan->description)
                                            <p class="text-white text-sm mb-3">{{ $subscription->plan->description }}</p>
                                        @endif
                                        <div class="mb-2">
                                            <span class="badge bg-gradient-light text-dark me-2">
                                                {{ $subscription->plan->max_businesses }} businesses
                                            </span>
                                            <span class="badge bg-gradient-light text-dark me-2">
                                                {{ $subscription->plan->max_images }} images
                                            </span>
                                            @if($subscription->plan->featured_listing)
                                                <span class="badge bg-gradient-success">
                                                    Featured Listing
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <h4 class="text-white mb-0">₹{{ number_format($subscription->plan->price, 2) }}</h4>
                                        <p class="text-white text-sm mb-0">
                                            @if($subscription->plan->duration_type == 'one-time')
                                                One-time
                                            @else
                                                per {{ $subscription->plan->duration_value }} {{ Str::ucfirst($subscription->plan->duration_type) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Subscription Details -->
                        <div class="card shadow-none border mb-4">
                            <div class="card-body">
                                <h6 class="mb-3">Subscription Details</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="text-sm mb-1">
                                            <strong>Status:</strong> 
                                            <span class="badge bg-gradient-success">{{ ucfirst($subscription->status) }}</span>
                                        </p>
                                        <p class="text-sm mb-1">
                                            <strong>Started:</strong> {{ $subscription->started_at->format('M d, Y') }}
                                        </p>
                                        @if($subscription->ends_at)
                                            <p class="text-sm mb-1">
                                                <strong>Expires:</strong> {{ $subscription->ends_at->format('M d, Y') }}
                                                ({{ now()->diffForHumans($subscription->ends_at, ['parts' => 1]) }})
                                            </p>
                                        @else
                                            <p class="text-sm mb-1">
                                                <strong>Expires:</strong> Never
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-sm mb-1">
                                            <strong>Amount Paid:</strong> 
                                            @if($subscription->amount_paid)
                                                ${{ number_format($subscription->amount_paid, 2) }}
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                        <p class="text-sm mb-1">
                                            <strong>Payment Method:</strong> {{ $subscription->payment_method ?? 'N/A' }}
                                        </p>
                                        @if($subscription->transaction_id)
                                            <p class="text-sm mb-1">
                                                <strong>Transaction ID:</strong> {{ $subscription->transaction_id }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Usage Stats -->
                        <div class="card shadow-none border mb-4">
                            <div class="card-body">
                                <h6 class="mb-3">Usage Statistics</h6>
                                
                                <!-- Businesses Usage -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-sm">Businesses: {{ Auth::user()->businesses()->count() }} of {{ $subscription->plan->max_businesses }}</span>
                                        <span class="text-sm text-primary">{{ number_format((Auth::user()->businesses()->count() / $subscription->plan->max_businesses) * 100, 0) }}%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-gradient-primary" role="progressbar" 
                                            style="width: {{ (Auth::user()->businesses()->count() / $subscription->plan->max_businesses) * 100 }}%" 
                                            aria-valuenow="{{ Auth::user()->businesses()->count() }}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="{{ $subscription->plan->max_businesses }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Actions you can take -->
                                <div class="mt-4">
                                    <h6 class="text-sm">Available Actions</h6>
                                    <div class="d-flex">
                                        @if(Auth::user()->businesses()->count() < $subscription->plan->max_businesses)
                                            <a href="{{ route('vendor.dashboard') }}" class="btn btn-sm btn-primary me-2">
                                                Add Business
                                            </a>
                                        @endif
                                        <a href="{{ route('vendor.subscriptions.plans') }}" class="btn btn-sm btn-outline-primary me-2">
                                            Change Plan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cancel Subscription -->
                        <div class="card bg-gradient-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Cancel Subscription</h6>
                                        <p class="text-sm mb-0">
                                            Cancelling your subscription will prevent automatic renewal, but you'll maintain access until the end of your billing period.
                                        </p>
                                    </div>
                                    <form action="{{ route('vendor.subscriptions.cancel') }}" method="POST" id="cancel-form">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Cancel Subscription</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Subscription History</h6>
                </div>
                <div class="card-body">
                    <div class="timeline timeline-one-side">
                        @forelse(Auth::user()->subscriptions()->latest()->take(5)->get() as $sub)
                        <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="ni ni-circle-08 text-{{ $sub->status == 'active' ? 'success' : ($sub->status == 'cancelled' ? 'warning' : 'danger') }}"></i>
                            </span>
                            <div class="timeline-content">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">{{ $sub->plan->name }}</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $sub->started_at->format('M d, Y') }}</p>
                                <p class="text-sm mt-3 mb-0">
                                    Status: <span class="badge bg-gradient-{{ $sub->status == 'active' ? 'success' : ($sub->status == 'cancelled' ? 'warning' : 'danger') }}">{{ ucfirst($sub->status) }}</span>
                                </p>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm mb-0">No subscription history available.</p>
                        @endforelse
                        
                        @if(Auth::user()->subscriptions()->count() > 5)
                            <div class="text-center mt-4">
                                <a href="{{ route('vendor.subscriptions.history') }}" class="btn btn-sm btn-primary">View All History</a>
                            </div>
                        @endif
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
        // Confirm subscription cancellation
        const cancelForm = document.getElementById('cancel-form');
        if (cancelForm) {
            cancelForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (confirm('Are you sure you want to cancel your subscription? You will maintain access until the end of your current billing period.')) {
                    this.submit();
                }
            });
        }
    });
</script>
@endpush 