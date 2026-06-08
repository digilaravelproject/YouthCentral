@extends('layouts.app-minimal')
@section('title', 'Verify OTP')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">

                {{-- Flash Messages --}}
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card shadow border-0 rounded-4">
                    <div class="card-header bg-primary text-white text-center rounded-top-4">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-shield-lock-fill me-2"></i> Verify OTP
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('events.register.verifyOtp.process', $event) }}">
                            @csrf

                            <div class="mb-3">
                                <label for="otp" class="form-label fw-semibold">Enter the OTP</label>
                                <input type="text" name="otp" id="otp"
                                    class="form-control form-control-lg text-center fw-bold @error('otp') is-invalid @enderror"
                                    placeholder="Enter 6-digit OTP" maxlength="6" required>

                                @error('otp')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg rounded-3">
                                    <i class="bi bi-check-circle me-1"></i> Verify OTP
                                </button>
                            </div>

                            {{-- Resend OTP link --}}
                            {{-- 
                        <div class="text-center mt-3">
                            <a href="{{ route('events.resendOtp', $event->id) }}" class="text-decoration-none">
                                Didn’t get the code? Resend OTP
                            </a>
                        </div>
                        --}}
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
