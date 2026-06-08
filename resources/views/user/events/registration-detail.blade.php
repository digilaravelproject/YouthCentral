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
                        <p class="text-sm mb-0">For Your Event: 
                            <a href="{{ route('events.show', $event) }}" target="_blank" class="text-info">{{ $event->title }}</a>
                        </p>
                    </div>
                    {{-- Link back to the list of registrations for this specific event --}}
                    <a href="{{ route('user.my-events.registrations', $event->id) }}" class="btn btn-sm btn-outline-secondary mb-0">
                        <i class="fas fa-arrow-left me-1"></i> Back to Registrations List
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

                    <h6 class="mb-3 text-primary">Registration Status</h6>
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
                             {{-- General events are always free/completed status --}}
                        </div>
                    </div>
                     {{-- No payment details needed for user-created (General) events --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 