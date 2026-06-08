@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Subscription Details</h6>
                        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
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
                    
                    <!-- Subscription Overview Card -->
                    <div class="card bg-gradient-info mb-4">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="text-white mb-0">{{ $subscription->plan->name }}</h5>
                                    <p class="text-white text-sm mb-0">
                                        @if($subscription->status == 'active')
                                            <span class="badge bg-gradient-success">Active</span>
                                        @elseif($subscription->status == 'cancelled')
                                            <span class="badge bg-gradient-warning">Cancelled</span>
                                        @else
                                            <span class="badge bg-gradient-danger">Expired</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <h4 class="text-white mb-0">₹{{ number_format($subscription->amount_paid ?? $subscription->plan->price, 2) }}</h4>
                                    <p class="text-white text-sm mb-0">
                                        @if($subscription->plan->duration_type == 'one-time')
                                            One-time
                                        @else
                                            {{ $subscription->plan->duration_value }} {{ Str::ucfirst($subscription->plan->duration_type) }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Subscription Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Subscriber Information</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item border-0 ps-0 pt-0">
                                            <strong>Name:</strong> {{ $subscription->user->name }}
                                        </li>
                                        <li class="list-group-item border-0 ps-0">
                                            <strong>Email:</strong> {{ $subscription->user->email }}
                                        </li>
                                        <li class="list-group-item border-0 ps-0">
                                            <strong>Role:</strong> {{ ucfirst($subscription->user->role) }}
                                        </li>
                                        <li class="list-group-item border-0 ps-0">
                                            <strong>Joined:</strong> {{ $subscription->user->created_at->format('M d, Y') }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Plan Details</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item border-0 ps-0 pt-0">
                                            <strong>Plan:</strong> {{ $subscription->plan->name }}
                                        </li>
                                        <li class="list-group-item border-0 ps-0">
                                            <strong>Price:</strong> ₹{{ number_format($subscription->plan->price, 2) }}
                                        </li>
                                        <li class="list-group-item border-0 ps-0">
                                            <strong>Duration:</strong> 
                                            @if($subscription->plan->duration_type == 'one-time')
                                                One-time
                                            @else
                                                {{ $subscription->plan->duration_value }} {{ Str::ucfirst($subscription->plan->duration_type) }}
                                            @endif
                                        </li>
                                        <li class="list-group-item border-0 ps-0">
                                            <strong>Features:</strong> 
                                            <div class="d-flex mt-2">
                                                <span class="badge bg-gradient-primary me-2">{{ $subscription->plan->max_businesses }} Businesses</span>
                                                <span class="badge bg-gradient-info me-2">{{ $subscription->plan->max_images }} Images</span>
                                                @if($subscription->plan->featured_listing)
                                                    <span class="badge bg-gradient-success">Featured</span>
                                                @endif
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Subscription Period</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item border-0 ps-0 pt-0">
                                            <strong>Started:</strong> {{ $subscription->started_at->format('M d, Y h:i A') }}
                                        </li>
                                        <li class="list-group-item border-0 ps-0">
                                            <strong>Ends:</strong> 
                                            @if($subscription->ends_at)
                                                {{ $subscription->ends_at->format('M d, Y h:i A') }}
                                                <p class="text-sm text-muted mb-0">
                                                    ({{ now()->diffForHumans($subscription->ends_at, ['parts' => 1]) }})
                                                </p>
                                            @else
                                                Never
                                            @endif
                                        </li>
                                        <li class="list-group-item border-0 ps-0">
                                            <strong>Status:</strong> 
                                            @if($subscription->status == 'active')
                                                <span class="badge bg-gradient-success">Active</span>
                                            @elseif($subscription->status == 'cancelled')
                                                <span class="badge bg-gradient-warning">Cancelled</span>
                                            @else
                                                <span class="badge bg-gradient-danger">Expired</span>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Payment Information</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item border-0 ps-0 pt-0">
                                            <strong>Amount Paid:</strong> 
                                            @if($subscription->amount_paid)
                                                ₹{{ number_format($subscription->amount_paid, 2) }}
                                            @else
                                                N/A
                                            @endif
                                        </li>
                                        <li class="list-group-item border-0 ps-0">
                                            <strong>Payment Method:</strong> {{ $subscription->payment_method ?? 'N/A' }}
                                        </li>
                                        <li class="list-group-item border-0 ps-0">
                                            <strong>Transaction ID:</strong> {{ $subscription->transaction_id ?? 'N/A' }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <div>
                            <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="btn btn-info me-2">Edit</a>
                            @if($subscription->status == 'active')
                                <button type="button" class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                    Cancel Subscription
                                </button>
                                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#extendModal">
                                    Extend Subscription
                                </button>
                            @endif
                        </div>
                        <form action="{{ route('admin.subscriptions.destroy', $subscription->id) }}" method="POST" id="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-12">
            <!-- Subscription Actions Card -->
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="timeline timeline-one-side">
                        <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="fas fa-user text-primary"></i>
                            </span>
                            <div class="timeline-content">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">View User</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">View subscriber's full profile</p>
                                <div class="mt-3">
                                    <a href="#" class="btn btn-primary btn-sm">User Profile</a>
                                </div>
                            </div>
                        </div>
                        
                        @if($subscription->status == 'active')
                        <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="fas fa-calendar-alt text-success"></i>
                            </span>
                            <div class="timeline-content">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">Manage Subscription</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Change status or extend period</p>
                                <div class="mt-3 d-flex">
                                    <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#extendModal">
                                        Extend
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="timeline-block">
                            <span class="timeline-step">
                                <i class="fas fa-building text-info"></i>
                            </span>
                            <div class="timeline-content">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">View User's Businesses</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                    {{ $subscription->user->businesses()->count() }} businesses associated
                                </p>
                                <div class="mt-3">
                                    <a href="#" class="btn btn-info btn-sm">View Businesses</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Subscription Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this subscription?</p>
                <p class="text-muted">The user will maintain access until the end of their current billing period, but the subscription will not renew.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form action="{{ route('admin.subscriptions.cancel', $subscription->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warning">Cancel Subscription</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Extend Subscription Modal -->
<div class="modal fade" id="extendModal" tabindex="-1" role="dialog" aria-labelledby="extendModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="extendModalLabel">Extend Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.subscriptions.extend', $subscription->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="duration_type">Duration Type</label>
                        <select class="form-control" id="duration_type" name="duration_type" required>
                            <option value="days">Days</option>
                            <option value="months">Months</option>
                            <option value="years">Years</option>
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <label for="duration_value">Duration Value</label>
                        <input type="number" class="form-control" id="duration_value" name="duration_value" value="1" min="1" required>
                    </div>
                    <p class="text-muted mt-3">
                        Current end date: 
                        @if($subscription->ends_at)
                            {{ $subscription->ends_at->format('M d, Y') }}
                        @else
                            Never
                        @endif
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Extend Subscription</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirm delete
        const deleteForm = document.getElementById('delete-form');
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete this subscription? This action cannot be undone.')) {
                this.submit();
            }
        });
    });
</script>
@endpush 