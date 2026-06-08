@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{ $business->business_name }}</h5>
                        <p class="text-sm mb-0">
                            <span class="font-weight-bold">Status:</span> 
                            <span class="badge bg-gradient-{{ $business->status == 'active' ? 'success' : ($business->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($business->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('vendor.businesses.index') }}" class="btn btn-sm bg-gradient-primary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Businesses
                        </a>
                        <a href="{{ route('vendor.businesses.edit', $business->id) }}" class="btn btn-sm bg-gradient-info">
                            <i class="fas fa-edit me-1"></i> Edit Business
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="text-uppercase text-sm">Business Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Name</label>
                                        <p class="form-control-static">{{ $business->business_name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Category</label>
                                        <p class="form-control-static">
                                            {{ $business->subcategory->category->name }} &gt; {{ $business->subcategory->name }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Phone</label>
                                        <p class="form-control-static">{{ $business->phone }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Email</label>
                                        <p class="form-control-static">{{ $business->email ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Website</label>
                                        <p class="form-control-static">
                                            @if($business->website)
                                                <a href="{{ $business->website }}" target="_blank">{{ $business->website }}</a>
                                            @else
                                                Not provided
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Social Links</label>
                                        <div class="d-flex gap-2">
                                            @if($business->facebook_link)
                                                <a href="{{ $business->facebook_link }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                    <i class="fab fa-facebook"></i>
                                                </a>
                                            @endif
                                            @if($business->instagram_link)
                                                <a href="{{ $business->instagram_link }}" target="_blank" class="btn btn-outline-danger btn-sm">
                                                    <i class="fab fa-instagram"></i>
                                                </a>
                                            @endif
                                            @if($business->twitter_link)
                                                <a href="{{ $business->twitter_link }}" target="_blank" class="btn btn-outline-info btn-sm">
                                                    <i class="fab fa-twitter"></i>
                                                </a>
                                            @endif
                                            @if($business->pinterest_link)
                                                <a href="{{ $business->pinterest_link }}" target="_blank" class="btn btn-outline-danger btn-sm">
                                                    <i class="fab fa-pinterest"></i>
                                                </a>
                                            @endif
                                            @if($business->whatsapp_number)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp_number) }}" target="_blank" class="btn btn-outline-success btn-sm">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                            @endif
                                            @if(!$business->facebook_link && !$business->instagram_link && !$business->twitter_link && !$business->pinterest_link && !$business->whatsapp_number)
                                                <span class="text-sm text-muted">No social links provided</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark">
                            <h6 class="text-uppercase text-sm">Address Information</h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Street Address</label>
                                        <p class="form-control-static">{{ $business->street_address }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Area</label>
                                        <p class="form-control-static">{{ $business->area->name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">City</label>
                                        <p class="form-control-static">{{ $business->area->city->name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">State</label>
                                        <p class="form-control-static">{{ $business->area->city->state->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <hr class="horizontal dark">
                            <h6 class="text-uppercase text-sm">Business Images</h6>
                            <div class="row">
                                <div class="col-12">
                                    @if($business->images && count($business->images) > 0)
                                        <div class="row">
                                            @foreach($business->images as $image)
                                                <div class="col-md-4 mb-3">
                                                    <div class="card">
                                                        <img src="{{ asset('storage/' . $image->path) }}" class="card-img-top" alt="Business Image">
                                                        <div class="card-body p-2">
                                                            <form action="{{ route('vendor.businesses.images.delete', $image->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">No images uploaded yet.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header pb-0">
                                    <h6>Location</h6>
                                </div>
                                <div class="card-body">
                                    @if($business->latitude && $business->longitude)
                                        <div class="map-container border rounded mb-3" style="height: 300px;">
                                            <iframe 
                                                width="100%" 
                                                height="100%" 
                                                frameborder="0" 
                                                scrolling="no" 
                                                marginheight="0" 
                                                marginwidth="0" 
                                                src="https://maps.google.com/maps?q={{ $business->latitude }},{{ $business->longitude }}&z=15&output=embed">
                                            </iframe>
                                        </div>
                                        <p class="text-xs">
                                            <strong>Coordinates:</strong> {{ $business->latitude }}, {{ $business->longitude }}
                                        </p>
                                    @else
                                        <div class="alert alert-warning text-white">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            No map coordinates available. Please edit your business to add location details.
                                        </div>
                                    @endif
                                    
                                    <a href="{{ route('vendor.businesses.edit', $business->id) }}" class="btn btn-sm btn-primary w-100 mb-2">
                                        <i class="fas fa-edit me-1"></i> Edit Business Details
                                    </a>
                                    
                                    <a href="{{ route('public.business.show', $business->id) }}" class="btn btn-sm btn-outline-primary w-100 mb-2" target="_blank">
                                        <i class="fas fa-external-link-alt me-1"></i> View in Directory
                                    </a>
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