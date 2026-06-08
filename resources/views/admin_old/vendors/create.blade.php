@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Create New Vendor</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="p-4">
                        <form method="POST" action="{{ route('admin.vendors.store') }}">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">Owner Name</label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" id="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-control-label">Email</label>
                                        <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" id="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-control-label">Phone Number</label>
                                        <input class="form-control @error('phone') is-invalid @enderror" type="text" name="phone" id="phone" value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="business_name" class="form-control-label">Business Name</label>
                                        <input class="form-control @error('business_name') is-invalid @enderror" type="text" name="business_name" id="business_name" value="{{ old('business_name') }}" required>
                                        @error('business_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="business_address" class="form-control-label">Business Address</label>
                                        <textarea class="form-control @error('business_address') is-invalid @enderror" name="business_address" id="business_address" rows="3" required>{{ old('business_address') }}</textarea>
                                        @error('business_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gst_number" class="form-control-label">GST Number (Optional)</label>
                                        <input class="form-control @error('gst_number') is-invalid @enderror" type="text" name="gst_number" id="gst_number" value="{{ old('gst_number') }}">
                                        @error('gst_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="form-control-label">Password</label>
                                        <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" id="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation" class="form-control-label">Confirm Password</label>
                                        <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="auto_approve" id="auto_approve" checked>
                                <label class="form-check-label" for="auto_approve">Auto-approve vendor (otherwise will require manual approval)</label>
                            </div>
                            
                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-light me-3">Cancel</a>
                                <button type="submit" class="btn bg-gradient-primary">Create Vendor</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 