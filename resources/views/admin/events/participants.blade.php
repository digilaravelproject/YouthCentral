@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Event Participants - {{ $event->title }}</h6>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-primary">Back to Events</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Participant</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contact</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Details</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Payment</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Registration Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($registrations as $registration)
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $registration->first_name }} {{ $registration->last_name }}</h6>
                                                <p class="text-xs text-secondary mb-0">School Type: {{ $registration->age_category }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $registration->email }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $registration->mobile }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $registration->school }}</p>
                                        <p class="text-xs text-secondary mb-0">Grade: {{ $registration->grade }}</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm {{ $registration->payment_status === 'paid' ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                            {{ ucfirst($registration->payment_status) }}
                                        </span>
                                        @if($registration->payment_status === 'paid')
                                        <p class="text-xs text-secondary mb-0">ID: {{ $registration->razorpay_payment_id }}</p>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ $registration->created_at->format('M d, Y H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($registration->payment_status === 'paid')
                                        <a href="{{ route('admin.events.receipt.download', $registration->id) }}" class="btn btn-link text-dark px-3 mb-0">
                                            <i class="fas fa-download text-dark me-2"></i>Receipt
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-sm text-secondary mb-0">No participants registered yet.</p>
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