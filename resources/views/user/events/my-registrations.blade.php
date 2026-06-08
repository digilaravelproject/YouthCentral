@extends('layouts.user_type.auth')

@section('title', 'My Event Registrations')

@section('content')
<div class="container my-registrations">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">My Event Registrations</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($registrations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Registration Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($registrations as $registration)
                                        <tr>
                                            <td>
                                                <a href="{{ route('events.show', $registration->event) }}" class="text-decoration-none">
                                                    {{ $registration->event->title }}
                                                </a>
                                                <small class="d-block text-muted">
                                                    {{ $registration->event->start_date->format('M d, Y h:i A') }}
                                                </small>
                                            </td>
                                            <td>{{ $registration->created_at->format('M d, Y') }}</td>
                                            <td>₹{{ number_format($registration->amount, 2) }}</td>
                                            <td>
                                                @if($registration->payment_status == 'paid' || $registration->payment_status == 'completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($registration->payment_status == 'pending')
                                                    <span class="badge bg-warning text-dark">Pending Payment</span>
                                                @else
                                                    <span class="badge bg-danger">Failed</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($registration->payment_status == 'paid')
                                                    <a href="{{ route('user.events.receipt.download', $registration->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Download Receipt">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                @elseif($registration->payment_status == 'completed')
                                                     <span class="text-muted text-xs">N/A (Free Event)</span>
                                                @elseif($registration->payment_status == 'pending')
                                                    <a href="{{ route('events.payment.show', $registration->id) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Complete Payment">
                                                        <i class="fa fa-credit-card"></i> Pay
                                                    </a>
                                                @else
                                                    <a href="{{ route('events.show', $registration->event) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="View Event & Register Again">
                                                        <i class="fa fa-refresh"></i> Retry
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $registrations->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <i class="fa fa-calendar-check-o fa-4x text-muted mb-3"></i>
                                <h3>No Registrations Found</h3>
                                <p class="text-muted">You haven't registered for any events yet.</p>
                                <a href="{{ route('events.index') }}" class="btn btn-primary mt-3">
                                    <i class="fa fa-calendar me-1"></i> Browse Events
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.my-registrations {
    padding: 40px 0;
}
.empty-state {
    padding: 30px 15px;
}
.badge {
    padding: 0.5em 0.8em;
}
</style>
@endsection 