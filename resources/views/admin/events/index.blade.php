@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Events</h6>
                        <div>
                            <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm">Create Event</a>
                            <a href="{{ route('admin.events.pending') }}" class="btn btn-info btn-sm">Pending Events</a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success mx-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Event</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Created By</th>
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
                                                        <div class="avatar avatar-sm me-3 bg-gradient-secondary rounded-circle d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-calendar text-white"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $event->title }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ Str::limit($event->description, 50) }}</p>
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
                                            <span class="badge badge-sm bg-gradient-{{ $event->status === 'approved' ? 'success' : ($event->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($event->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $event->creator->name }}
                                            </p>
                                            <p class="text-xs text-secondary mb-0">
                                                {{ $event->creator->email }}
                                            </p>
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('admin.events.edit', $event->slug) }}" class="text-secondary font-weight-bold text-xs me-3" data-toggle="tooltip" data-original-title="Edit event">
                                                Edit
                                                </a>
                                                @if($event->status === 'pending')
                                                <form action="{{ route('admin.events.approve', $event->slug) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="text-success font-weight-bold text-xs border-0 bg-transparent me-3" onclick="return confirm('Approve this event?')">Approve</button>
                                                </form>
                                                @endif
                                            <form action="{{ route('admin.events.destroy', $event->slug) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-secondary font-weight-bold text-xs border-0 bg-transparent" onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>


                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No events found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Infinite scroll indicators will be added by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-infinite-scroll.js') }}"></script>
@endpush 