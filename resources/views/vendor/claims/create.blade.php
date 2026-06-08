@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Claim Business: {{ $business->business_name }}</h6>
                        <a href="{{ route('vendor.claims.index') }}" class="btn btn-sm bg-gradient-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Available Businesses
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
                                    <div class="mb-3">
                                        <strong>Business Name:</strong> {{ $business->business_name }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Category:</strong> {{ $business->subcategory->category->name }} - {{ $business->subcategory->name }}
                                    </div>
                                    @if($business->phone)
                                    <div class="mb-3">
                                        <strong>Phone:</strong> {{ $business->phone }}
                                    </div>
                                    @endif
                                    @if($business->email)
                                    <div class="mb-3">
                                        <strong>Email:</strong> {{ $business->email }}
                                    </div>
                                    @endif
                                    @if($business->website)
                                    <div class="mb-3">
                                        <strong>Website:</strong> <a href="{{ $business->website }}" target="_blank">{{ $business->website }}</a>
                                    </div>
                                    @endif
                                    <div class="mb-3">
                                        <strong>Address:</strong> {{ $business->street_address }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Location:</strong> {{ $business->area->name }}, {{ $business->area->city->name }}, {{ $business->area->city->state->name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-gradient-light pb-0">
                                    <h6 class="mb-0">Claim Form</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('vendor.claims.store', $business->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        
                                        <div class="alert alert-info text-white" role="alert">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Important:</strong> To claim this business, you must provide proof of ownership or management authority. Your claim will be reviewed by our administrators.
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="proof_description" class="form-control-label">Proof Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('proof_description') is-invalid @enderror" id="proof_description" name="proof_description" rows="5" required>{{ old('proof_description') }}</textarea>
                                            <small class="form-text text-muted">
                                                Explain your relationship to this business and why you should be granted ownership. Include details such as your position, how long you've been associated with the business, etc.
                                            </small>
                                            @error('proof_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group mt-3">
                                            <label for="document" class="form-control-label">Supporting Document (Optional)</label>
                                            <input type="file" class="form-control @error('document') is-invalid @enderror" id="document" name="document">
                                            <small class="form-text text-muted">
                                                Upload a document supporting your claim (business card, letter, ID, etc.). Accepted formats: PDF, JPG, PNG. Max size: 2MB.
                                            </small>
                                            @error('document')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="d-flex justify-content-end mt-4">
                                            <button type="submit" class="btn bg-gradient-primary">Submit Claim</button>
                                        </div>
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