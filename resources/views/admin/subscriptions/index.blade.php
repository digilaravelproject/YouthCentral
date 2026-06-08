@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Subscriptions Management</h6>
                        <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary btn-sm ms-auto">Add New Subscription</a>
                    </div>
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
                    
                    <!-- Filters -->
                    <div class="mx-4 mt-4">
                        <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="row">
                            <div class="col-md-3 mb-3">
                                <select name="status" class="form-control">
                                    <option value="">All Statuses</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select name="plan_id" class="form-control">
                                    <option value="">All Plans</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input type="text" name="search" class="form-control" placeholder="Search by user..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary">Reset</a>
                            </div>
                        </form>
                    </div>
                    
                    <div class="table-responsive p-0 mx-4 mt-4">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subscriber</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Plan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Started</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Expires</th>
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
                                                <h6 class="mb-0 text-sm">{{ $subscription->user->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $subscription->user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $subscription->plan->name }}</p>
                                        <p class="text-xs text-secondary mb-0">
                                            @if($subscription->plan->duration_type == 'one-time')
                                                One-time
                                            @else
                                                {{ $subscription->plan->duration_value }} {{ Str::ucfirst($subscription->plan->duration_type) }}
                                            @endif
                                        </p>
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
                                                
                                                <button type="button" class="btn btn-link text-primary px-1 mb-0" title="Extend Subscription" data-bs-toggle="modal" data-bs-target="#extendModal" data-subscription-id="{{ $subscription->id }}">
                                                    <i class="fas fa-calendar-plus me-2"></i>
                                                </button>
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
                                    <td colspan="7" class="text-center">No subscriptions found</td>
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
            <form id="extendForm" action="" method="POST">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Extend</button>
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
        
        // Handle extend subscription
        const extendModal = document.getElementById('extendModal');
        const extendForm = document.getElementById('extendForm');
        
        extendModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const subscriptionId = button.getAttribute('data-subscription-id');
            extendForm.action = `/admin/subscriptions/${subscriptionId}/extend`;
        });
    });
</script>
@endpush 