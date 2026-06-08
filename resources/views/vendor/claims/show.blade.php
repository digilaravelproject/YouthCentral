@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Claim Details {{ $claim->business ? 'for ' . $claim->business->business_name : '' }}</h6>
                        <a href="{{ route('vendor.claims.myClaims') }}" class="btn btn-sm bg-gradient-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to My Claims
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-gradient-light pb-0">
                                    <h6 class="mb-0">Business Information</h6>
                                </div>
                                <div class="card-body">
                                    @if($claim->business)
                                    <div class="mb-3">
                                        <strong>Business Name:</strong> {{ $claim->business->business_name }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Category:</strong> {{ $claim->business->subcategory->category->name }} - {{ $claim->business->subcategory->name }}
                                    </div>
                                    @if($claim->business->phone)
                                    <div class="mb-3">
                                        <strong>Phone:</strong> {{ $claim->business->phone }}
                                    </div>
                                    @endif
                                    @if($claim->business->email)
                                    <div class="mb-3">
                                        <strong>Email:</strong> {{ $claim->business->email }}
                                    </div>
                                    @endif
                                    @if($claim->business->website)
                                    <div class="mb-3">
                                        <strong>Website:</strong> <a href="{{ $claim->business->website }}" target="_blank">{{ $claim->business->website }}</a>
                                    </div>
                                    @endif
                                    <div class="mb-3">
                                        <strong>Address:</strong> {{ $claim->business->street_address }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Location:</strong> {{ $claim->business->area->name }}, {{ $claim->business->area->city->name }}, {{ $claim->business->area->city->state->name }}
                                    </div>
                                    @else
                                    <div class="alert alert-warning">
                                        <strong>Note:</strong> The business associated with this claim no longer exists in our system.
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-gradient-light pb-0">
                                    <h6 class="mb-0">Claim Information</h6>
                                </div>
                                <div class="card-body">
                                    <!-- Status Badge -->
                                    <div class="mb-3">
                                        <strong>Status:</strong> 
                                        @if($claim->status == 'pending')
                                            <span class="badge badge-sm bg-gradient-warning">Pending Review</span>
                                        @elseif($claim->status == 'approved')
                                            <span class="badge badge-sm bg-gradient-success">Approved</span>
                                        @elseif($claim->status == 'rejected')
                                            <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Submission Date -->
                                    <div class="mb-3">
                                        <strong>Submitted On:</strong> {{ $claim->created_at->format('F d, Y \a\t h:i A') }}
                                    </div>
                                    
                                    <!-- Processing date if applicable -->
                                    @if($claim->processed_at)
                                    <div class="mb-3">
                                        <strong>Processed On:</strong> {{ $claim->processed_at->format('F d, Y \a\t h:i A') }}
                                    </div>
                                    @endif
                                    
                                    <!-- Proof Description -->
                                    <div class="mb-3">
                                        <strong>Your Proof Description:</strong>
                                        <p class="mt-2">{{ $claim->proof_description }}</p>
                                    </div>
                                    
                                    <!-- Supporting Document -->
                                    @if($claim->document_path)
                                    <div class="mb-3">
                                        <strong>Supporting Document:</strong>
                                        <div class="mt-2">
                                            <a href="{{ Storage::url($claim->document_path) }}" target="_blank" class="btn btn-sm bg-gradient-info">
                                                <i class="fas fa-file me-2"></i>View Document
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <!-- Admin Notes (only shown if rejected) -->
                                    @if($claim->status == 'rejected' && $claim->admin_notes)
                                    <div class="mb-3">
                                        <strong>Reason for Rejection:</strong>
                                        <div class="alert alert-danger mt-2" role="alert">
                                            {{ $claim->admin_notes }}
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <!-- Actions based on status -->
                                    @if($claim->status == 'approved' && $claim->business)
                                    <div class="alert alert-success mt-3" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <strong>Congratulations!</strong> Your claim has been approved. You now have management access to this business listing.
                                    </div>
                                    <div class="d-flex justify-content-end mt-4">
                                        <a href="{{ route('vendor.businesses.edit', $claim->business_id) }}" class="btn bg-gradient-primary">
                                            <i class="fas fa-edit me-2"></i>Manage Business
                                        </a>
                                    </div>
                                    @elseif($claim->status == 'pending')
                                    <div class="alert alert-warning mt-3" role="alert">
                                        <i class="fas fa-clock me-2"></i>
                                        <strong>Your claim is under review.</strong> We'll notify you once our administrators make a decision.
                                    </div>
                                    @elseif($claim->status == 'rejected')
                                    <div class="alert alert-danger mt-3" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <strong>Your claim was rejected.</strong> Please see the reason for rejection above. You may submit a new claim with additional information if you believe this was in error.
                                    </div>
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