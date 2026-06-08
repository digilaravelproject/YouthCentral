@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Event Registrations</h6>
                    </div>
                </div>
                
                <div class="card-body pt-3 pb-2">
                    <form action="{{ route('admin.event-registrations.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="event_id" class="form-control-label text-sm">Filter by Event</label>
                                    <select class="form-control" id="event_id" name="event_id">
                                        <option value="">All Events</option>
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                                {{ $event->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="payment_status" class="form-control-label text-sm">Payment Status</label>
                                    <select class="form-control" id="payment_status" name="payment_status">
                                        <option value="">All Statuses</option>
                                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date_from" class="form-control-label text-sm">Date From</label>
                                    <input class="form-control" type="date" id="date_from" name="date_from" value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date_to" class="form-control-label text-sm">Date To</label>
                                    <input class="form-control" type="date" id="date_to" name="date_to" value="{{ request('date_to') }}">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm mb-0 w-100">Apply Filters</button>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Event</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Participant</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contact</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Payment</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($registrations as $registration)
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                @if($registration->event)
                                                <h6 class="mb-0 text-sm">{{ $registration->event->title }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $registration->event->start_date->format('M d, Y') }}</p>
                                                @else
                                                    <h6 class="mb-0 text-sm text-danger">Event not found</h6>
                                                    <p class="text-xs text-secondary mb-0">Associated event may have been deleted</p>
                    @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $registration->first_name }} {{ $registration->last_name }}</h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    {{ $registration->user_id ? 'Registered User' : 'Guest' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $registration->email }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $registration->mobile }}</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm {{ $registration->payment_status === 'paid' ? 'bg-gradient-success' : ($registration->payment_status === 'pending' ? 'bg-gradient-warning' : 'bg-gradient-danger') }}">
                                            {{ ucfirst($registration->payment_status) }}
                                        </span>
                                        <p class="text-xs text-secondary mb-0">₹{{ number_format($registration->amount_paid ?? $registration->amount, 2) }}</p>
                                    </td>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ $registration->created_at->format('M d, Y H:i') }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('admin.event-registrations.show', $registration->id) }}" class="btn btn-link text-info text-gradient px-3 mb-0">
                                            <i class="fas fa-eye me-2"></i>View
                                        </a>
                                        @if($registration->payment_status === 'paid')
                                        <a href="{{ route('admin.event-registrations.receipt.download', $registration->id) }}" class="btn btn-link text-dark px-3 mb-0">
                                            <i class="fas fa-download text-dark me-2"></i>Receipt
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-sm text-secondary mb-0">No registrations found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($registrations->hasPages())
                    <div class="px-3 pt-4">
                        {{ $registrations->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 