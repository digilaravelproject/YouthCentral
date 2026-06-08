@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ url()->previous() }}" class="text-secondary text-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                        <h3 class="mt-2 mb-0">Vendor Profile</h3>
                    </div>
                    <div>
                        <span class="badge bg-gradient-{{ $vendor->status === 'approved' ? 'success' : ($vendor->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($vendor->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header p-3 pb-0">
                                    <h5 class="mb-0">Basic Information</h5>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Name</div>
                                        <div class="col-md-8">{{ $vendor->name }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Email</div>
                                        <div class="col-md-8">{{ $vendor->email }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Phone</div>
                                        <div class="col-md-8">{{ $vendor->phone ?? 'Not provided' }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Status</div>
                                        <div class="col-md-8">
                                            <span class="badge bg-gradient-{{ $vendor->status === 'approved' ? 'success' : ($vendor->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($vendor->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Registered On</div>
                                        <div class="col-md-8">{{ $vendor->created_at->format('F d, Y') }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            @if ($vendor->status === 'pending')
                            <div class="card mt-4">
                                <div class="card-header p-3 pb-0">
                                    <h5 class="mb-0">Approval Actions</h5>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <form action="{{ route('admin.vendors.approve', $vendor->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Are you sure you want to approve this vendor?')">
                                                    <i class="fas fa-check me-1"></i> Approve Vendor
                                                </button>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                            <form action="{{ route('admin.vendors.reject', $vendor->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to reject this vendor?')">
                                                    <i class="fas fa-times me-1"></i> Reject Vendor
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header p-3 pb-0">
                                    <h5 class="mb-0">Contact Information</h5>
                                </div>
                                <div class="card-body p-3">
                                    @if($vendor->email)
                                    <a href="mailto:{{ $vendor->email }}" class="btn btn-info mb-3">
                                        <i class="fas fa-envelope me-1"></i> Send Email
                                    </a>
                                    @endif
                                    
                                    @if($vendor->phone)
                                    <a href="tel:{{ $vendor->phone }}" class="btn btn-primary mb-3 ms-2">
                                        <i class="fas fa-phone me-1"></i> Call
                                    </a>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card mt-4">
                                <div class="card-header p-3 pb-0">
                                    <h5 class="mb-0">Businesses</h5>
                                </div>
                                <div class="card-body p-3">
                                    @php
                                        $businesses = App\Models\Business::where('claimed_by', $vendor->id)->get();
                                    @endphp
                                    
                                    @if(count($businesses) > 0)
                                        <div class="list-group">
                                            @foreach($businesses as $business)
                                                <div class="list-group-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0">{{ $business->business_name }}</h6>
                                                        <span class="badge bg-gradient-{{ $business->status === 'active' ? 'success' : ($business->status === 'pending' ? 'warning' : 'secondary') }}">
                                                            {{ ucfirst($business->status) }}
                                                        </span>
                                                    </div>
                                                    <small class="text-muted">{{ $business->subcategory->name }} | {{ $business->area->name }}</small>
                                                    <div class="mt-2">
                                                        <a href="{{ route('admin.businesses.show', $business) }}" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye me-1"></i> View
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">This vendor does not have any businesses yet.</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card mt-4">
                                <div class="card-header p-3 pb-0">
                                    <h5 class="mb-0">Claims</h5>
                                </div>
                                <div class="card-body p-3">
                                    @php
                                        $claims = App\Models\BusinessClaim::where('user_id', $vendor->id)->get();
                                    @endphp
                                    
                                    @if(count($claims) > 0)
                                        <div class="list-group">
                                            @foreach($claims as $claim)
                                                <div class="list-group-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0">{{ $claim->business->business_name }}</h6>
                                                        <span class="badge bg-gradient-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'pending' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($claim->status) }}
                                                        </span>
                                                    </div>
                                                    <small class="text-muted">Submitted on {{ $claim->created_at->format('M d, Y') }}</small>
                                                    <div class="mt-2">
                                                        <a href="{{ route('admin.claims.show', $claim) }}" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye me-1"></i> View Claim
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">This vendor has not submitted any claims.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 