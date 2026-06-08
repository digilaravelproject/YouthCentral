@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Your Subscription History</h6>
                        <div>
                            <a href="{{ route('vendor.subscriptions.current') }}" class="btn btn-info btn-sm me-2">Current Subscription</a>
                            <a href="{{ route('vendor.subscriptions.plans') }}" class="btn btn-primary btn-sm">View Plans</a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0 mx-4 mt-4">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Plan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Started</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ended</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscriptions as $subscription)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $subscription->plan->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    @if($subscription->plan->duration_type == 'one-time')
                                                        One-time
                                                    @else
                                                        {{ $subscription->plan->duration_value }} {{ Str::ucfirst($subscription->plan->duration_type) }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($subscription->status == 'active')
                                            <span class="badge badge-sm bg-gradient-success">Active</span>
                                        @elseif($subscription->status == 'cancelled')
                                            <span class="badge badge-sm bg-gradient-warning">Cancelled</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-danger">Expired</span>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $subscription->started_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $subscription->started_at->format('h:i A') }}</p>
                                    </td>
                                    <td>
                                        @if($subscription->ends_at)
                                            <p class="text-xs font-weight-bold mb-0">{{ $subscription->ends_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $subscription->ends_at->format('h:i A') }}</p>
                                        @else
                                            <p class="text-xs font-weight-bold mb-0">Never</p>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subscription->amount_paid)
                                            <p class="text-xs font-weight-bold mb-0">₹{{ number_format($subscription->amount_paid, 2) }}</p>
                                        @else
                                            <p class="text-xs text-secondary mb-0">N/A</p>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <button type="button" class="btn btn-link text-dark mb-0" data-bs-toggle="modal" data-bs-target="#subscriptionDetails{{ $subscription->id }}">
                                            <i class="fas fa-eye text-dark me-2" aria-hidden="true"></i>
                                            Details
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- Subscription Details Modal -->
                                <div class="modal fade" id="subscriptionDetails{{ $subscription->id }}" tabindex="-1" role="dialog" aria-labelledby="subscriptionDetailsLabel{{ $subscription->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="subscriptionDetailsLabel{{ $subscription->id }}">Subscription Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card bg-gradient-info mb-3">
                                                    <div class="card-body p-3">
                                                        <div class="row">
                                                            <div class="col-8">
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
                                                            <div class="col-4 text-end">
                                                                <h4 class="text-white mb-0">₹{{ number_format($subscription->amount_paid ?? $subscription->plan->price, 2) }}</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <h6 class="mb-3">Plan Details</h6>
                                                <ul class="list-group mb-3">
                                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="mb-1 text-dark text-sm">Duration</h6>
                                                            <span class="text-xs">
                                                                @if($subscription->plan->duration_type == 'one-time')
                                                                    One-time
                                                                @else
                                                                    {{ $subscription->plan->duration_value }} {{ Str::ucfirst($subscription->plan->duration_type) }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="mb-1 text-dark text-sm">Businesses</h6>
                                                            <span class="text-xs">Up to {{ $subscription->plan->max_businesses }} businesses</span>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="mb-1 text-dark text-sm">Images</h6>
                                                            <span class="text-xs">{{ $subscription->plan->max_images }} images per business</span>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="mb-1 text-dark text-sm">Featured Listing</h6>
                                                            <span class="text-xs">
                                                                @if($subscription->plan->featured_listing)
                                                                    <span class="text-success">Included</span>
                                                                @else
                                                                    <span class="text-danger">Not included</span>
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </li>
                                                </ul>
                                                
                                                <h6 class="mb-3">Subscription Period</h6>
                                                <ul class="list-group mb-3">
                                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="mb-1 text-dark text-sm">Started</h6>
                                                            <span class="text-xs">{{ $subscription->started_at->format('M d, Y h:i A') }}</span>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="mb-1 text-dark text-sm">Ended</h6>
                                                            <span class="text-xs">
                                                                @if($subscription->ends_at)
                                                                    {{ $subscription->ends_at->format('M d, Y h:i A') }}
                                                                @else
                                                                    Never
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </li>
                                                </ul>
                                                
                                                <h6 class="mb-3">Payment Details</h6>
                                                <ul class="list-group">
                                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="mb-1 text-dark text-sm">Amount Paid</h6>
                                                            <span class="text-xs">
                                                                @if($subscription->amount_paid)
                                                                    ₹{{ number_format($subscription->amount_paid, 2) }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="mb-1 text-dark text-sm">Payment Method</h6>
                                                            <span class="text-xs">{{ $subscription->payment_method ?? 'N/A' }}</span>
                                                        </div>
                                                    </li>
                                                    @if($subscription->transaction_id)
                                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="mb-1 text-dark text-sm">Transaction ID</h6>
                                                            <span class="text-xs">{{ $subscription->transaction_id }}</span>
                                                        </div>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No subscription history found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $subscriptions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 