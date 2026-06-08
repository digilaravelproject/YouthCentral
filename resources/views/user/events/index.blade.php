@extends('layouts.user_type.auth')

@section('title', 'My Events')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">My General Events</h6>
                    <a href="{{ route('user.my-events.create') }}" class="btn btn-sm bg-gradient-primary mb-0">
                        <i class="fas fa-plus me-1"></i> Create New Event
                    </a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                         <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Event</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Venue</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events as $event)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    @if($event->banners->isNotEmpty())
                                                        <img src="{{ asset('storage/' . $event->banners->first()->image_path) }}" 
                                                             class="avatar avatar-sm me-3" alt="{{ $event->title }}">
                                                    @else 
                                                        <div class="avatar avatar-sm me-3 bg-light d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-calendar-alt text-secondary"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $event->title }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $event->start_date->format('M d, Y') }}
                                            </p>
                                            <p class="text-xs text-secondary mb-0">
                                                {{ $event->start_date->format('h:i A') }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $event->venue }}</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-gradient-{{ $event->status === 'approved' ? 'success' : ($event->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($event->status) }}
                                            </span>
                                            @if($event->status === 'rejected' && $event->rejection_reason)
                                                <i class="fas fa-info-circle ms-1 text-danger" data-bs-toggle="tooltip" 
                                                   title="Reason: {{ $event->rejection_reason }}"></i>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if($event->isPending() || $event->isApproved()) {{-- Allow edit for pending/approved --}}
                                                <a href="{{ route('user.my-events.edit', $event) }}" 
                                                   class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" title="Edit Event">
                                                   <i class="fas fa-edit"></i>
                                                </a>
                                            @endif

                                                                        @if($event->isApproved())
                                <a href="{{ route('events.show', $event) }}" class="text-info font-weight-bold text-xs ms-2" 
                                   data-bs-toggle="tooltip" title="View Public Page">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endif
                                            
                                            {{-- Show registrations link only if approved --}}
                                            {{-- @if($event->isApproved())
                                                <a href="{{ route('user.my-events.registrations', $event->id) }}" class="text-primary font-weight-bold text-xs ms-2" data-bs-toggle="tooltip" title="View Registrations">
                                                    <i class="fas fa-users"></i>
                                                </a>
                                            @endif --}}

                                            @if($event->isApproved()) {{-- Allow delete only for approved events --}}
                                                <a href="#" class="text-danger font-weight-bold text-xs ms-2" data-bs-toggle="tooltip" title="Delete Event"
                                                   onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this event?')) document.getElementById('delete-form-{{ $event->id }}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <form id="delete-form-{{ $event->id }}" 
                                                      action="{{ route('user.my-events.destroy', $event) }}" 
                                                      method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            You haven't created any events yet.
                                            <a href="{{ route('user.my-events.create') }}" class="btn btn-sm btn-link mb-0">Create one now!</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add toast functionality for event status
    document.querySelectorAll('.view-public-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const status = this.dataset.status;
            if (status === 'pending' || status === 'failed') {
                e.preventDefault();
                showToast(status);
            }
        });
    });
    
    function showToast(status) {
        const toastContainer = document.getElementById('toast-container') || createToastContainer();
        const toastId = 'toast-' + Date.now();
        
        let message, bgColor;
        if (status === 'pending') {
            message = 'The event is under review';
            bgColor = 'bg-warning';
        } else if (status === 'failed') {
            message = 'Your event has been rejected by the admin';
            bgColor = 'bg-danger';
        }
        
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white ${bgColor} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
        
        // Remove toast element after it's hidden
        toastElement.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    }
    
    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'position-fixed top-0 end-0 p-3';
        container.style.zIndex = '1055';
        document.body.appendChild(container);
        return container;
    }
});
</script>
@endpush