@extends('layouts.user_type.auth')

@section('title', 'My YC Ignite Registrations')

@section('content')
<div class="container my-registrations">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h3 class="mb-0">My YC Ignite Registrations</h3>
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
                                        <th>Sport Event</th>
                                        <th>Registration Date</th>
                                        <th>Receipt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($registrations as $registration)
                                        <tr>
                                            <td>
                                                @if($registration->event)
                                                    <a href="{{ route('events.show', $registration->event) }}" class="text-decoration-none">
                                                        {{ $registration->event->title }}
                                                    </a>
                                                    <small class="d-block text-muted">
                                                        {{ $registration->event->start_date->format('M d, Y h:i A') }}
                                                    </small>
                                                @else
                                                    <span class="text-danger">Event not found</span>
                                                @endif
                                            </td>
                                            <td>{{ $registration->sport_event }}</td>
                                            <td>{{ $registration->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('yc-ignites.receipt', $registration->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   target="_blank" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Download Receipt">
                                                    <i class="fa fa-download"></i>
                                                </a>
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
                                <i class="fa fa-fire fa-4x text-muted mb-3"></i>
                                <h3>No YC Ignite Registrations Found</h3>
                                <p class="text-muted">You haven’t registered for YC Ignite yet.</p>
                                <a href="{{ route('events.index') }}" class="btn btn-warning mt-3">
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
