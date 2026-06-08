@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Change Password</h5>
                        </div>
                        <a href="{{ route('profile') }}" class="btn bg-gradient-primary btn-sm mb-0">Back to Profile</a>
                    </div>
                </div>
                <div class="card-body px-4 pt-4 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.password.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="current_password" class="form-control-label">Current Password</label>
                            <input class="form-control @error('current_password') is-invalid @enderror" id="current_password" 
                                   name="current_password" type="password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="password" class="form-control-label">New Password</label>
                            <input class="form-control @error('password') is-invalid @enderror" id="password" 
                                   name="password" type="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirmation" class="form-control-label">Confirm New Password</label>
                            <input class="form-control" id="password_confirmation" 
                                   name="password_confirmation" type="password" required>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn bg-gradient-primary">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 