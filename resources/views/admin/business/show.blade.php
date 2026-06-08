@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-lg mx-4">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        {{-- Display Logo --}}
                        <img src="{{ $business->logo_path ? Storage::url($business->logo_path) : asset('assets/img/default-business.png') }}" alt="Business Logo" class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">{{ $business->business_name }}</h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{ $business->subcategory->name ?? 'N/A' }} / {{ $business->subcategory->category->name ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        {{-- Action buttons if needed --}}
                        <a href="{{ route('admin.businesses.edit', $business->id) }}" class="btn btn-sm btn-dark mb-0 me-2">Edit Business</a>
                        <a href="{{ route('admin.businesses.index') }}" class="btn btn-sm btn-outline-secondary mb-0">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Business Details</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-uppercase text-sm">Contact Information</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Phone Number</label>
                                    <p class="form-control-static">{{ $business->phone }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">WhatsApp Number</label>
                                    <p class="form-control-static">{{ $business->whatsapp_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Email address</label>
                                    <p class="form-control-static">{{ $business->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Website</label>
                                    <p class="form-control-static"><a href="{{ $business->website }}" target="_blank" rel="noopener noreferrer">{{ $business->website ?? 'N/A' }}</a></p>
                                </div>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <p class="text-uppercase text-sm">Location Information</p>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label">Street Address</label>
                                    <p class="form-control-static">{{ $business->street_address }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Area</label>
                                    <p class="form-control-static">{{ $business->area->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">City</label>
                                    <p class="form-control-static">{{ $business->area->city->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">State</label>
                                    <p class="form-control-static">{{ $business->area->city->state->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Latitude</label>
                                    <p class="form-control-static">{{ $business->latitude ?? 'N/A' }}</p>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Longitude</label>
                                    <p class="form-control-static">{{ $business->longitude ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <p class="text-uppercase text-sm">Social Media</p>
                        <div class="row">
                            <div class="col-md-6">Facebook: <a href="{{ $business->facebook_link }}" target="_blank">{{ $business->facebook_link ?? 'N/A' }}</a></div>
                            <div class="col-md-6">Instagram: <a href="{{ $business->instagram_link }}" target="_blank">{{ $business->instagram_link ?? 'N/A' }}</a></div>
                            <div class="col-md-6">Twitter: <a href="{{ $business->twitter_link }}" target="_blank">{{ $business->twitter_link ?? 'N/A' }}</a></div>
                            <div class="col-md-6">Pinterest: <a href="{{ $business->pinterest_link }}" target="_blank">{{ $business->pinterest_link ?? 'N/A' }}</a></div>
                        </div>
                         <hr class="horizontal dark">
                        <p class="text-uppercase text-sm">About Business</p>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label">Description</label>
                                    <p class="form-control-static">{{ $business->description ?? 'No description provided.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-profile">
                    <img src="../assets/img/bg-profile.jpg" alt="Image placeholder" class="card-img-top">
                    <div class="row justify-content-center">
                        <div class="col-4 col-lg-4 order-lg-2">
                            <div class="mt-n4 mt-lg-n6 mb-4 mb-lg-0">
                                <a href="javascript:;">
                                    {{-- Re-use logo here --}}
                                    <img src="{{ $business->logo_path ? Storage::url($business->logo_path) : asset('assets/img/default-business.png') }}" class="rounded-circle img-fluid border border-2 border-white">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-0 pt-lg-2 pb-4 pb-lg-3">
                        <span class="badge badge-sm bg-gradient-{{ $business->status == 'active' ? 'success' : ($business->status == 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($business->status) }}</span>
                        @if($business->owner)
                            <span class="badge badge-sm bg-gradient-info">Claimed by: {{ $business->owner->name }}</span>
                        @else
                             <span class="badge badge-sm bg-gradient-secondary">Unclaimed</span>
                        @endif
                    </div>
                    <div class="card-body pt-0">
                        {{-- Maybe add business hours or other summary info here --}}
                    </div>
                </div>

                {{-- Gallery Images Section --}}
                <div class="card mt-4">
                    <div class="card-header pb-0">
                        <p class="mb-0">Gallery Images</p>
                    </div>
                    <div class="card-body">
                        @if($business->images && $business->images->count() > 0)
                            <div class="row gx-2 gy-2">
                                @foreach($business->images as $image)
                                    <div class="col-md-4 col-6">
                                        <img src="{{ Storage::url($image->path) }}" alt="Gallery Image" class="img-fluid border-radius-lg shadow-sm mb-2">
                                        {{-- Optional: Add delete button/form per image --}}
                                        {{-- <form action="{{ route('admin.businesses.images.delete', $image->id) }}" method="POST"> @csrf @method('DELETE') <button type="submit" class="btn btn-sm btn-danger">Delete</button> </form> --}}
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-muted">No gallery images uploaded.</p>
                        @endif
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
        // Confirm delete
        document.querySelector('.delete-form').addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this business? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
        
        // Confirm unclaim
        if (document.querySelector('.unclaim-form')) {
            document.querySelector('.unclaim-form').addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to remove ownership from this business?')) {
                    e.preventDefault();
                }
            });
        }
    });
</script>
@endpush 