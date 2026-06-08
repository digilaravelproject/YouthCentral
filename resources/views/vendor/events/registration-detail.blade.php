@extends('layouts.user_type.auth')

@section('title', 'Registration Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Registration Details</h6>
                        <p class="text-sm mb-0">For Event: 
                            <a href="{{ route('events.show', $event) }}" target="_blank" class="text-info">{{ $event->title }}</a>
                        </p>
                    </div>
                    <a href="{{ route('vendor.events.registrations', $event->id) }}" class="btn btn-sm btn-outline-secondary mb-0">
                        <i class="fas fa-arrow-left me-1"></i> Back to Registrations
                    </a>
                </div>
                <div class="card-body pt-3">
                    <h6 class="mb-3 text-primary">Registrant Information</h6>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong class="text-dark">Name:</strong></div>
                        <div class="col-md-8">{{ $registration->first_name }} {{ $registration->last_name }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong class="text-dark">Email:</strong></div>
                        <div class="col-md-8">{{ $registration->email }}</div>
                    </div>
                    @if($registration->phone)
                    <div class="row mb-2">
                        <div class="col-md-4"><strong class="text-dark">Phone:</strong></div>
                        <div class="col-md-8">{{ $registration->phone }}</div>
                    </div>
                    @endif
                    @if($registration->user)
                     <div class="row mb-2">
                        <div class="col-md-4"><strong class="text-dark">Registered Account:</strong></div>
                        <div class="col-md-8">{{ $registration->user->name }} (ID: {{ $registration->user_id }})</div>
                    </div>
                    @else
                     <div class="row mb-2">
                        <div class="col-md-4"><strong class="text-dark">Registered Account:</strong></div>
                        <div class="col-md-8 text-muted">Guest Registration</div>
                    </div>
                    @endif

                    <hr class="horizontal dark my-4">

                    <h6 class="mb-3 text-primary">Registration Details</h6>
                     <div class="row mb-2">
                        <div class="col-md-4"><strong class="text-dark">Registration ID:</strong></div>
                        <div class="col-md-8">{{ $registration->id }}</div>
                    </div>
                     <div class="row mb-2">
                        <div class="col-md-4"><strong class="text-dark">Registration Date:</strong></div>
                        <div class="col-md-8">{{ $registration->created_at->format('M d, Y H:i A') }}</div>
                    </div>
                     <div class="row mb-2">
                        <div class="col-md-4"><strong class="text-dark">Status:</strong></div>
                        <div class="col-md-8">
                            <span class="badge badge-sm bg-gradient-success">
                                Completed
                            </span>
                             {{-- General events don't have payment status other than 'completed' --}}
                        </div>
                    </div>
                    
                    {{-- Display Payment Info if YC event was registered (unlikely for vendor events, but good structure) --}}
                    @if($event->category === 'yc' && $registration->payment_status)
                        <hr class="horizontal dark my-4">
                        <h6 class="mb-3 text-primary">Payment Information</h6>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong class="text-dark">Payment Status:</strong></div>
                            <div class="col-md-8">
                                <span class="badge badge-sm bg-gradient-{{ $registration->payment_status == 'paid' ? 'success' : ($registration->payment_status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($registration->payment_status) }}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong class="text-dark">Amount Paid:</strong></div>
                            <div class="col-md-8">₹{{ number_format($registration->amount, 2) }}</div>
                        </div>
                        @if($registration->transaction_id)
                        <div class="row mb-2">
                            <div class="col-md-4"><strong class="text-dark">Transaction ID:</strong></div>
                            <div class="col-md-8">{{ $registration->transaction_id }}</div>
                        </div>
                        @endif
                         @if($registration->payment_method)
                        <div class="row mb-2">
                            <div class="col-md-4"><strong class="text-dark">Payment Method:</strong></div>
                            <div class="col-md-8">{{ $registration->payment_method }}</div>
                        </div>
                        @endif
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 