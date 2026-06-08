@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Registration Details</h6>
                        <a href="{{ route('admin.event-registrations.index') }}" class="btn btn-sm btn-primary">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3 text-uppercase text-xs">Event Information</h6>
                            <div class="card card-plain">
                                <div class="card-body p-3">
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Event Title:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->event->title }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Date & Time:</div>
                                        <div class="col-md-8 text-sm">
                                            {{ $registration->event->start_date->format('M d, Y h:i A') }} to {{ $registration->event->end_date->format('h:i A') }}
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Venue:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->event->venue }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 text-sm font-weight-bold">Registration Fee:</div>
                                        <div class="col-md-8 text-sm">₹{{ number_format($registration->event->registration_amount, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="mb-3 text-uppercase text-xs">Participant Information</h6>
                            <div class="card card-plain">
                                <div class="card-body p-3">
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Name:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->first_name }} {{ $registration->last_name }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Email:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->email }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Mobile:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->mobile }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Address:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->address }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">User Type:</div>
                                        <div class="col-md-8 text-sm">
                                            {{ $registration->user_id ? 'Registered User' : 'Guest' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="mb-3 text-uppercase text-xs">Registration Details</h6>
                            <div class="card card-plain">
                                <div class="card-body p-3">
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Reference Number:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->reference_number ?? $registration->id }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Registration Date:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->created_at->format('M d, Y h:i A') }}</div>
                                    </div>
                                    @if($registration->sport_event)
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Sport/Event:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->sport_event }}</div>
                                    </div>
                                    @endif
                                    @if($registration->age_category)
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">School Type:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->age_category }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="mb-3 text-uppercase text-xs">Payment Information</h6>
                            <div class="card card-plain">
                                <div class="card-body p-3">
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Status:</div>
                                        <div class="col-md-8 text-sm">
                                            <span class="badge badge-sm {{ $registration->payment_status === 'paid' ? 'bg-gradient-success' : ($registration->payment_status === 'pending' ? 'bg-gradient-warning' : 'bg-gradient-danger') }}">
                                                {{ ucfirst($registration->payment_status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Amount:</div>
                                        <div class="col-md-8 text-sm">₹{{ number_format($registration->amount_paid ?? $registration->amount, 2) }}</div>
                                    </div>
                                    @if($registration->payment_status === 'paid')
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Payment ID:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->razorpay_payment_id }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Payment Date:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->updated_at->format('M d, Y h:i A') }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        @if($registration->payment_status === 'paid')
                        <a href="{{ route('admin.event-registrations.receipt.download', $registration->id) }}" class="btn btn-sm btn-success">
                            <i class="fa fa-download me-2"></i> Download Receipt
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
 
 
 
 
 