@extends('layouts.user_type.auth') {{-- Or your main authenticated layout --}}

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">My Profile</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 pt-4 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Name</label>
                                    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                                           type="text" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-control-label">Email</label>
                                    <input class="form-control" id="email" type="email" value="{{ $user->email }}" readonly disabled>
                                    <small class="text-muted">Email address cannot be changed</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-control-label">Phone Number</label>
                                    <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" 
                                           type="text" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="location" class="form-control-label">Location</label>
                                    <input class="form-control @error('location') is-invalid @enderror" id="location" name="location" 
                                           type="text" value="{{ old('location', $user->location) }}">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="about_me" class="form-control-label">About Me</label>
                            <textarea class="form-control @error('about_me') is-invalid @enderror" id="about_me" name="about_me" 
                                      rows="4">{{ old('about_me', $user->about_me) }}</textarea>
                            @error('about_me')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        @if($user->isVendor())
                            <hr>
                            <h6>Business Information</h6>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="business_name" class="form-control-label">Business Name</label>
                                        <input class="form-control @error('business_name') is-invalid @enderror" id="business_name" name="business_name" 
                                               type="text" value="{{ old('business_name', $user->business_name) }}" required>
                                        @error('business_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gst_number" class="form-control-label">GST Number</label>
                                        <input class="form-control @error('gst_number') is-invalid @enderror" id="gst_number" name="gst_number" 
                                               type="text" value="{{ old('gst_number', $user->gst_number) }}">
                                        @error('gst_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="business_address" class="form-control-label">Business Address</label>
                                <textarea class="form-control @error('business_address') is-invalid @enderror" id="business_address" name="business_address" 
                                          rows="3" required>{{ old('business_address', $user->business_address) }}</textarea>
                                @error('business_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn bg-gradient-primary">Save Changes</button>
                        </div>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Security</h6>
                        <a href="{{ route('profile.password.edit') }}" class="btn bg-gradient-dark btn-sm mb-0">Change Password</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 