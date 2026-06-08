@extends('layouts.app-minimal')
@section('title', 'Verify OTP')

@section('content')
<div class="max-w-md mx-auto mt-12 p-6 bg-white shadow-md rounded-md">
    <h2 class="text-2xl font-semibold mb-4 text-center">Verify OTP</h2>

    @if(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(session('info'))
        <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded mb-4">
            {{ session('info') }}
        </div>
    @endif

    <form method="POST" action="{{ route('yc_ignite.verifyOtp.submit') }}">
        @csrf

        <div class="mb-4">
            <label for="otp" class="block text-gray-700 font-medium mb-1">Enter OTP</label>
            <input type="text" name="otp" id="otp" value="{{ old('otp') }}" 
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                   placeholder="6-digit OTP" maxlength="6" required>
            @error('otp')
                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" 
                class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition-colors">
            Verify OTP
        </button>
    </form>

    {{-- <div class="mt-4 text-center text-sm text-gray-600">
        Didn't receive the OTP? <a href="{{ route('yc-ignite.store') }}" class="text-blue-600 hover:underline">Resend</a>
    </div> --}}
</div>
@endsection
