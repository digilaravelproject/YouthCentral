@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>My Business Claims</h6>
                        <a href="{{ route('vendor.claims.index') }}" class="btn btn-sm bg-gradient-primary">
                            <i class="fas fa-plus me-2"></i>Claim New Business
                        </a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Business</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Location</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Submitted</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($claims as $claim)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $claim->business ? $claim->business->business_name : 'Business no longer exists' }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($claim->business)
                                        <p class="text-xs font-weight-bold mb-0">{{ $claim->business->subcategory->category->name }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $claim->business->subcategory->name }}</p>
                                        @else
                                        <p class="text-xs text-secondary mb-0">Not available</p>
                                        @endif
                                    </td>
                                    <td>
                                        @if($claim->business)
                                        <p class="text-xs font-weight-bold mb-0">{{ $claim->business->area->city->state->name }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $claim->business->area->city->name }} - {{ $claim->business->area->name }}</p>
                                        @else
                                        <p class="text-xs text-secondary mb-0">Not available</p>
                                        @endif
                                    </td>
                                    <td>
                                        @if($claim->status == 'pending')
                                            <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                        @elseif($claim->status == 'approved')
                                            <span class="badge badge-sm bg-gradient-success">Approved</span>
                                        @elseif($claim->status == 'rejected')
                                            <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $claim->created_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $claim->created_at->format('h:i A') }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('vendor.claims.show', $claim->id) }}" class="btn btn-sm bg-gradient-info">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-sm mb-0">You haven't submitted any business claims yet.</p>
                                        <a href="{{ route('vendor.claims.index') }}" class="btn btn-sm bg-gradient-primary mt-3">
                                            Find a Business to Claim
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-4 pt-4">
                        {{ $claims->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 