@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 col-md-12 mx-auto">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Create New Subscription</h6>
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
                    
                    <form method="POST" action="{{ route('admin.subscriptions.store') }}">
                        @csrf
                        
                        <!-- User Selection -->
                        <div class="form-group">
                            <label for="user_id" class="form-control-label">Select User</label>
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">-- Select a user --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Plan Selection -->
                        <div class="form-group mt-3">
                            <label for="plan_id" class="form-control-label">Select Plan</label>
                            <select class="form-control @error('plan_id') is-invalid @enderror" id="plan_id" name="plan_id" required>
                                <option value="">-- Select a plan --</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}
                                        data-price="{{ $plan->price }}"
                                        data-duration-type="{{ $plan->duration_type }}"
                                        data-duration-value="{{ $plan->duration_value }}">
                                        {{ $plan->name }} - ₹{{ number_format($plan->price, 2) }} / 
                                        @if($plan->duration_type == 'one-time')
                                            One-time
                                        @else
                                            {{ $plan->duration_value }} {{ Str::ucfirst($plan->duration_type) }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="plan-details" class="mt-2 d-none">
                                <div class="card bg-gray-100 p-3">
                                    <h6>Plan Details</h6>
                                    <div id="plan-info"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="form-group mt-3">
                            <label for="status" class="form-control-label">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Start Date -->
                        <div class="form-group mt-3">
                            <label for="started_at" class="form-control-label">Start Date</label>
                            <input class="form-control @error('started_at') is-invalid @enderror" type="datetime-local" 
                                id="started_at" name="started_at" value="{{ old('started_at', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('started_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- End Date -->
                        <div class="form-group mt-3">
                            <label for="ends_at" class="form-control-label">End Date</label>
                            <input class="form-control @error('ends_at') is-invalid @enderror" type="datetime-local" 
                                id="ends_at" name="ends_at" value="{{ old('ends_at') }}">
                            <small class="text-muted">Leave empty for unlimited subscription</small>
                            @error('ends_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Payment Information -->
                        <div class="card mt-4 p-3">
                            <h6>Payment Information</h6>
                            
                            <div class="form-group mt-3">
                                <label for="amount_paid" class="form-control-label">Amount Paid</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input class="form-control @error('amount_paid') is-invalid @enderror" type="number" 
                                        id="amount_paid" name="amount_paid" value="{{ old('amount_paid') }}" step="0.01">
                                </div>
                                @error('amount_paid')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group mt-3">
                                <label for="payment_method" class="form-control-label">Payment Method</label>
                                <select class="form-control @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method">
                                    <option value="">-- Select Payment Method --</option>
                                    <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="paypal" {{ old('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="manual" {{ old('payment_method') == 'manual' ? 'selected' : '' }}>Manual</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group mt-3">
                                <label for="transaction_id" class="form-control-label">Transaction ID</label>
                                <input class="form-control @error('transaction_id') is-invalid @enderror" type="text" 
                                    id="transaction_id" name="transaction_id" value="{{ old('transaction_id') }}">
                                @error('transaction_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Notes -->
                        <div class="form-group mt-3">
                            <label for="notes" class="form-control-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" onclick="window.history.back()" class="btn btn-secondary me-2">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create Subscription</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const planSelect = document.getElementById('plan_id');
        const planDetails = document.getElementById('plan-details');
        const planInfo = document.getElementById('plan-info');
        const amountPaid = document.getElementById('amount_paid');
        const startedAt = document.getElementById('started_at');
        const endsAt = document.getElementById('ends_at');
        
        planSelect.addEventListener('change', function() {
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                const durationType = selectedOption.getAttribute('data-duration-type');
                const durationValue = selectedOption.getAttribute('data-duration-value');
                
                // Update plan details display
                let planInfoHtml = `<p><strong>Price:</strong> ₹${parseFloat(price).toFixed(2)}</p>`;
                planInfoHtml += `<p><strong>Duration:</strong> `;
                
                if (durationType === 'one-time') {
                    planInfoHtml += `One-time payment</p>`;
                } else {
                    planInfoHtml += `${durationValue} ${durationType.charAt(0).toUpperCase() + durationType.slice(1)}</p>`;
                    
                    // Set end date based on duration if it's not one-time
                    if (!endsAt.value) {
                        const startDate = new Date(startedAt.value);
                        if (durationType === 'days') {
                            startDate.setDate(startDate.getDate() + parseInt(durationValue));
                        } else if (durationType === 'months') {
                            startDate.setMonth(startDate.getMonth() + parseInt(durationValue));
                        } else if (durationType === 'years') {
                            startDate.setFullYear(startDate.getFullYear() + parseInt(durationValue));
                        }
                        
                        // Format for datetime-local input
                        const endDateStr = startDate.toISOString().slice(0, 16);
                        endsAt.value = endDateStr;
                    }
                }
                
                planInfo.innerHTML = planInfoHtml;
                planDetails.classList.remove('d-none');
                
                // Set default amount paid
                if (!amountPaid.value) {
                    amountPaid.value = parseFloat(price).toFixed(2);
                }
                
            } else {
                planDetails.classList.add('d-none');
            }
        });
        
        // Also update end date when start date changes
        startedAt.addEventListener('change', function() {
            if (planSelect.value) {
                const selectedOption = planSelect.options[planSelect.selectedIndex];
                const durationType = selectedOption.getAttribute('data-duration-type');
                const durationValue = selectedOption.getAttribute('data-duration-value');
                
                if (durationType !== 'one-time') {
                    const startDate = new Date(this.value);
                    if (durationType === 'days') {
                        startDate.setDate(startDate.getDate() + parseInt(durationValue));
                    } else if (durationType === 'months') {
                        startDate.setMonth(startDate.getMonth() + parseInt(durationValue));
                    } else if (durationType === 'years') {
                        startDate.setFullYear(startDate.getFullYear() + parseInt(durationValue));
                    }
                    
                    // Format for datetime-local input
                    const endDateStr = startDate.toISOString().slice(0, 16);
                    endsAt.value = endDateStr;
                }
            }
        });
        
        // Trigger change on page load if a plan is already selected
        if (planSelect.value) {
            planSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush 