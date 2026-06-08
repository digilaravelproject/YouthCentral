@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Review Business Claim</h6>
                        <a href="{{ route('admin.claims.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Claims
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header p-3 pb-0">
                                    <h6 class="mb-0">Business Information</h6>
                                </div>
                                <div class="card-body p-3">
                                    <p><strong>Business Name:</strong> {{ $claim->business->business_name }}</p>
                                    <p><strong>Phone:</strong> {{ $claim->business->phone }}</p>
                                    @if($claim->business->email)
                                        <p><strong>Email:</strong> {{ $claim->business->email }}</p>
                                    @endif
                                    @if($claim->business->website)
                                        <p><strong>Website:</strong> {{ $claim->business->website }}</p>
                                    @endif
                                    <p><strong>Address:</strong> {{ $claim->business->street_address }}</p>
                                    <p><strong>Category:</strong> {{ $claim->business->subcategory->category->name }} / {{ $claim->business->subcategory->name }}</p>
                                    <p><strong>Location:</strong> {{ $claim->business->area->name }}, {{ $claim->business->area->city->name }}, {{ $claim->business->area->city->state->name }}</p>
                                    
                                    <div class="alert alert-info text-white">
                                        <strong>Note:</strong> If approved, this business will be assigned to the vendor.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header p-3 pb-0">
                                    <h6 class="mb-0">Vendor Information</h6>
                                </div>
                                <div class="card-body p-3">
                                    <p><strong>Name:</strong> {{ $claim->vendor->name }}</p>
                                    <p><strong>Email:</strong> {{ $claim->vendor->email }}</p>
                                    <p><strong>Phone:</strong> {{ $claim->vendor->phone ?? 'Not provided' }}</p>
                                    <p><strong>Account Created:</strong> {{ $claim->vendor->created_at->format('F d, Y') }}</p>
                                    
                                    @if($claim->vendor->email)
                                    <div class="mb-3">
                                        <a href="mailto:{{ $claim->vendor->email }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-envelope me-1"></i> Contact Vendor
                                        </a>
                                    </div>
                                    @endif
                                    
                                    <div class="mt-3">
                                        <h6 class="mb-2">Vendor's Businesses ({{ $vendorBusinesses->count() }})</h6>
                                        @if($vendorBusinesses->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Business Name</th>
                                                            <th>Category</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($vendorBusinesses as $business)
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <strong>{{ $business->business_name }}</strong><br>
                                                                    <small class="text-muted">{{ $business->area->name }}, {{ $business->area->city->name }}</small>
                                                                </div>
                                                            </td>
                                                            <td>{{ $business->subcategory->category->name }} / {{ $business->subcategory->name }}</td>
                                                            <td>
                                                                <a href="{{ route('public.business.show', $business) }}" class="btn btn-outline-primary btn-xs" target="_blank" title="View Public Page">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('admin.businesses.edit', $business) }}" class="btn btn-outline-secondary btn-xs" title="Edit Business">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted">No businesses claimed yet.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header p-3 pb-0">
                                    <h6 class="mb-0">Claim Information</h6>
                                </div>
                                <div class="card-body p-3">
                                    <p><strong>Submitted On:</strong> {{ $claim->created_at->format('F d, Y H:i') }}</p>
                                    
                                    <p><strong>Proof Description:</strong></p>
                                    <div class="alert alert-secondary">
                                        {{ $claim->proof_description }}
                                    </div>
                                    
                                    @if($claim->document_path)
                                        <p><strong>Supporting Document:</strong></p>
                                        <a href="{{ Storage::url($claim->document_path) }}" class="btn btn-info btn-sm mb-3" target="_blank">
                                            <i class="fas fa-file me-1"></i> View Document
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-header p-3 pb-0">
                                    <h6 class="mb-0">Make Decision</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <form action="{{ route('admin.claims.approve', $claim->id) }}" method="POST" id="approveForm">
                                                @csrf
                                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Are you sure you want to approve this claim? The business will be assigned to this vendor.')">
                                                    <i class="fas fa-check me-1"></i> Approve Claim
                                                </button>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                                <i class="fas fa-times me-1"></i> Reject Claim
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Other claims for this business, if any -->
                            @if($claim->business->claims->where('id', '!=', $claim->id)->where('status', 'pending')->count() > 0)
                                <div class="card">
                                    <div class="card-header p-3 pb-0">
                                        <h6 class="mb-0">Other Pending Claims for this Business</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="alert alert-warning text-white">
                                            <strong>Warning:</strong> There are other pending claims for this business. Approving this claim will automatically reject all other claims.
                                        </div>
                                        
                                        <div class="list-group">
                                            @foreach($claim->business->claims->where('id', '!=', $claim->id)->where('status', 'pending') as $otherClaim)
                                                <a href="{{ route('admin.claims.show', $otherClaim->id) }}" class="list-group-item list-group-item-action">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h6 class="mb-1">{{ $otherClaim->vendor->name }}</h6>
                                                        <small>{{ $otherClaim->created_at->format('M d, Y') }}</small>
                                                    </div>
                                                    <small>{{ $otherClaim->vendor->email }}</small>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.claims.reject', $claim->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Claim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="admin_notes" class="form-control-label">Reason for Rejection</label>
                        <textarea class="form-control @error('admin_notes') is-invalid @enderror" id="admin_notes" name="admin_notes" rows="5" placeholder="Please provide a reason for rejecting this claim. This will be visible to the vendor.">{{ old('admin_notes') }}</textarea>
                        @error('admin_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Minimum 5 characters required.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Claim</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 