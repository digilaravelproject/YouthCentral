@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Subscriptions for {{ $plan->name }}</h6>
                        <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary btn-sm">Back to Plans</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <!-- Plan Details Card -->
                    <div class="card mx-4 mt-4">
                        <div class="card-body">
                            <h5 class="card-title">Plan Details</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="text-sm mb-1"><strong>Price:</strong> ${{ number_format($plan->price, 2) }}</p>
                                    <p class="text-sm mb-1">
                                        <strong>Duration:</strong> 
                                        @if($plan->duration_type == 'one-time')
                                            One-time
                                        @else
                                            {{ $plan->duration_value }} {{ Str::ucfirst($plan->duration_type) }}
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-sm mb-1"><strong>Max Businesses:</strong> {{ $plan->max_businesses }}</p>
                                    <p class="text-sm mb-1"><strong>Max Images:</strong> {{ $plan->max_images }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-sm mb-1">
                                        <strong>Featured Listing:</strong> 
                                        @if($plan->featured_listing)
                                            <span class="badge badge-sm bg-gradient-success">Yes</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-secondary">No</span>
                                        @endif
                                    </p>
                                    <p class="text-sm mb-1">
                                        <strong>Status:</strong> 
                                        @if($plan->is_active)
                                            <span class="badge badge-sm bg-gradient-success">Active</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-secondary">Inactive</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if($plan->description)
                                <p class="text-sm mt-2"><strong>Description:</strong> {{ $plan->description }}</p>
                            @endif
                        </div>
                    </div>
                    
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
                    
                    <div class="table-responsive p-0 mx-4 mt-4">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subscriber</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Started</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ends</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount Paid</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Payment Method</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscriptions as $subscription)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $subscription->user->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $subscription->user->email }}</p>
                                            </div>
                                        </div>
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
                                        @if($subscription->status == 'active')
                                            <span class="badge badge-sm bg-gradient-success">Active</span>
                                        @elseif($subscription->status == 'cancelled')
                                            <span class="badge badge-sm bg-gradient-warning">Cancelled</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-danger">Expired</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subscription->amount_paid)
                                            <p class="text-xs font-weight-bold mb-0">${{ number_format($subscription->amount_paid, 2) }}</p>
                                        @else
                                            <p class="text-xs text-secondary mb-0">N/A</p>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $subscription->payment_method ?? 'N/A' }}</p>
                                        @if($subscription->transaction_id)
                                            <p class="text-xs text-secondary mb-0">Tx: {{ $subscription->transaction_id }}</p>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="ms-auto">
                                            <a href="{{ route('admin.subscriptions.show', $subscription->id) }}" class="btn btn-link text-dark px-1 mb-0" title="View Details">
                                                <i class="fas fa-eye text-dark me-2" aria-hidden="true"></i>
                                            </a>
                                            
                                            <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="btn btn-link text-dark px-1 mb-0" title="Edit">
                                                <i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>
                                            </a>
                                            
                                            @if($subscription->status == 'active')
                                                <form action="{{ route('admin.subscriptions.cancel', $subscription->id) }}" method="POST" class="d-inline cancel-form">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-link text-warning text-gradient px-1 mb-0" title="Cancel Subscription">
                                                        <i class="fas fa-ban me-2"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('admin.subscriptions.destroy', $subscription->id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger text-gradient px-1 mb-0" title="Delete">
                                                    <i class="far fa-trash-alt me-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No subscriptions found for this plan</td>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirm delete
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (confirm('Are you sure you want to delete this subscription?')) {
                    this.submit();
                }
            });
        });
        
        // Confirm cancel
        const cancelForms = document.querySelectorAll('.cancel-form');
        cancelForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (confirm('Are you sure you want to cancel this subscription?')) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush 