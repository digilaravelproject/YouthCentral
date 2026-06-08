@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">User Details</h5>
                        </div>
                        <div>
                            <a href="{{ route('admin.users.index') }}" class="btn bg-gradient-primary btn-sm mb-0">Back to Users</a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn bg-gradient-dark btn-sm mb-0">Edit User</a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body px-4 pt-4 pb-2">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Basic Information -->
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <h6 class="mb-0 text-sm text-dark">Full Name:</h6>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="mb-0 text-sm">{{ $user->name }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <h6 class="mb-0 text-sm text-dark">Email Address:</h6>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="mb-0 text-sm">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <h6 class="mb-0 text-sm text-dark">Phone Number:</h6>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="mb-0 text-sm">{{ $user->phone ?? 'Not provided' }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <h6 class="mb-0 text-sm text-dark">Location:</h6>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="mb-0 text-sm">{{ $user->location ?? 'Not provided' }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <h6 class="mb-0 text-sm text-dark">About:</h6>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="mb-0 text-sm">{{ $user->about_me ?? 'No information provided' }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <h6 class="mb-0 text-sm text-dark">Registered On:</h6>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="mb-0 text-sm">{{ $user->created_at->format('F d, Y \a\t h:i A') }}</p>
                                            <p class="mb-0 text-xs text-secondary">{{ $user->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h6 class="mb-0 text-sm text-dark">Last Updated:</h6>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="mb-0 text-sm">{{ $user->updated_at->format('F d, Y \a\t h:i A') }}</p>
                                            <p class="mb-0 text-xs text-secondary">{{ $user->updated_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Business Information (for vendors) -->
                            @if($user->isVendor())
                            <div class="card mt-4">
                                <div class="card-header pb-0">
                                    <h6>Business Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <h6 class="mb-0 text-sm text-dark">Business Name:</h6>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="mb-0 text-sm">{{ $user->business_name ?? 'Not provided' }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <h6 class="mb-0 text-sm text-dark">Business Address:</h6>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="mb-0 text-sm">{{ $user->business_address ?? 'Not provided' }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h6 class="mb-0 text-sm text-dark">GST Number:</h6>
                                        </div>
                                        <div class="col-md-8">
                                            <p class="mb-0 text-sm">{{ $user->gst_number ?? 'Not provided' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Account Status -->
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Account Status</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3">
                                            <div class="avatar avatar-xl bg-gradient-{{ $user->role === 'admin' ? 'dark' : ($user->role === 'vendor' ? 'warning' : 'success') }} rounded-circle">
                                                <span class="text-white text-uppercase" style="font-size: 24px">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <h6 class="mb-0 text-sm text-dark">Role:</h6>
                                        </div>
                                        <div class="col-md-7">
                                            <span class="badge bg-gradient-{{ $user->role === 'admin' ? 'dark' : ($user->role === 'vendor' ? 'warning' : 'success') }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    @if($user->isVendor())
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <h6 class="mb-0 text-sm text-dark">Status:</h6>
                                        </div>
                                        <div class="col-md-7">
                                            <span class="badge bg-gradient-{{ $user->status === 'approved' ? 'success' : ($user->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($user->isVendor() && $user->businesses()->count() > 0)
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <h6 class="mb-0 text-sm text-dark">Businesses:</h6>
                                        </div>
                                        <div class="col-md-7">
                                            <span class="badge bg-gradient-info">{{ $user->businesses()->count() }}</span>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($user->hasActiveSubscription())
                                    <div class="row">
                                        <div class="col-md-5">
                                            <h6 class="mb-0 text-sm text-dark">Subscription:</h6>
                                        </div>
                                        <div class="col-md-7">
                                            <span class="badge bg-gradient-success">Active</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="card mt-4">
                                <div class="card-header pb-0">
                                    <h6>Actions</h6>
                                </div>
                                <div class="card-body">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn bg-gradient-dark w-100 mb-2">Edit User</a>
                                    
                                    @if($user->isVendor() && $user->status === 'pending')
                                    <form action="{{ route('admin.vendors.approve', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn bg-gradient-success w-100 mb-2">Approve Vendor</button>
                                    </form>
                                    
                                    <form action="{{ route('admin.vendors.reject', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn bg-gradient-danger w-100 mb-2">Reject Vendor</button>
                                    </form>
                                    @endif
                                    
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger w-100">Delete User</button>
                                    </form>
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