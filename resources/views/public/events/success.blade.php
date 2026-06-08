@extends('layouts.app-minimal')
@section('title', 'Registration Successful')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-10 col-md-8 col-lg-7 col-xl-6">
                <div class="card minimal-card">
                    <div class="card-header text-center py-3 border-0" style="background-color: #198754;">
                        <h4 class="mb-0 text-white"><i class="fas fa-check-circle me-2"></i>Registration Successful!</h4>
                    </div>
                    <div class="card-body text-center p-4 p-md-5">
                        <h5 class="mb-3 text-dark">Thank you for registering!</h5>
                        <p class="text-secondary mb-4">
                            A confirmation email has been sent to <strong>{{ $registration->email }}</strong>.
                            Your reference is <strong>{{ $registration->reference_number ?? $registration->id }}</strong>.
                        </p>

                        {{-- Event Summary --}}
                        <div class="card bg-light border mb-4">
                            <div class="card-body p-3">
                                <h6 class="card-title text-dark mb-3 text-center">Registration Details</h6>
                                <div class="row text-start text-sm">
                                    <div class="col-12 mb-2"><strong>Event:</strong> {{ $registration->event->title }}</div>
                                    <div class="col-12 mb-2"><strong>Venue:</strong> {{ $registration->event->venue }}</div>
                                    <div class="col-6 mb-2"><strong>Date:</strong>
                                        {{ $registration->event->start_date->format('M d, Y') }}</div>
                                    <div class="col-6 mb-2"><strong>Time:</strong>
                                        {{ $registration->event->start_date->format('h:i A') }}</div>
                                    <div class="col-6 mb-2"><strong>Amount Paid:</strong>
                                        ₹{{ number_format($registration->amount_paid ?? $registration->amount, 2) }}</div>
                                    <div class="col-6 mb-2"><strong>Payment ID:</strong>
                                        <small>{{ $registration->razorpay_payment_id }}</small></div>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="mt-4 d-grid gap-2 d-sm-flex justify-content-sm-center">
                            <a href="{{ route('user.events.receipt.download', $registration->id) }}" id="downloadReceiptBtn"
                                class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-download me-1"></i> Download Receipt
                            </a>

                            @auth
                                <a href="{{ route('user.events.my-registrations') }}" class="btn btn-lg px-4 text-white"
                                    style="background-color: var(--primary-color); color: #fff;!important;">
                                    <i class="fas fa-list-alt me-1"></i> My Registrations
                                </a>
                            @else
                                <a href="{{ route('events.index') }}" class="btn btn-lg px-4 text-white"
                                    style="background-color: var(--primary-color);">
                                    <i class="fas fa-calendar-alt me-1"></i> Browse Events
                                </a>
                            @endauth
                        </div>

                        <div class="mt-4 text-muted" id="redirectNotice" style="display:none;">
                            <small>Redirecting to home page in <span id="countdown">3</span> seconds...</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const downloadBtn = document.getElementById('downloadReceiptBtn');
            const redirectNotice = document.getElementById('redirectNotice');
            const countdownElement = document.getElementById('countdown');

            downloadBtn.addEventListener('click', function(e) {
                e.preventDefault(); // stop default navigation

                // Trigger the file download manually
                window.open(downloadBtn.href, '_blank');

                // Show redirect message
                redirectNotice.style.display = 'block';

                let seconds = 3;
                const countdown = setInterval(function() {
                    seconds--;
                    countdownElement.textContent = seconds;

                    if (seconds <= 0) {
                        clearInterval(countdown);
                        window.location.href = "{{ url('/') }}";
                    }
                }, 1000);
            });
        });
    </script>
@endpush