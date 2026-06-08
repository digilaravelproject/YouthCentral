@extends('layouts.user_type.auth')

@section('title', 'Registrations for ' . $event->title)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Event Registrations</h6>
                        <p class="text-sm mb-0">For: <span class="text-info">{{ $event->title }}</span></p>
                    </div>
                    <a href="{{ route('vendor.events.index') }}" class="btn btn-sm btn-outline-secondary mb-0">
                        <i class="fas fa-arrow-left me-1"></i> Back to My Events
                    </a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    
                    @if($registrations->count() > 0)
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Registrant Name</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Registration Date</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($registrations as $registration)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $registration->first_name }} {{ $registration->last_name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $registration->email }}</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $registration->created_at->format('Y-m-d H:i') }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                {{-- General events are marked 'completed' upon registration --}}
                                                <span class="badge badge-sm bg-gradient-success">
                                                    Completed
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('vendor.events.registrations.show', [$event->id, $registration->id]) }}" 
                                                   class="text-info font-weight-bold text-xs" 
                                                   data-bs-toggle="tooltip" data-bs-title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                         <div class="card-footer clearfix">
                            {{ $registrations->links() }}
                        </div>
                    @else
                         <div class="text-center py-5 px-3">
                            <p class="text-muted mb-0">No registrations found for this event yet.</p>
                        </div>
                    @endif
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 