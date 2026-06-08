@extends('layouts.app-minimal')

@section('title', 'Payment Failed')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                <div class="card-header text-center py-3 border-0" style="background-color: #dc3545;"> {{-- Danger Red --}}
                    <h4 class="mb-0 text-white"><i class="fas fa-times-circle me-2"></i>Payment Failed</h4>
                </div>
                <div class="card-body text-center p-4 p-md-5">
                    
                    <h5 class="mb-3 text-dark">We couldn't process the payment for <strong>{{ $registration->event->title }}</strong>.</h5>
                    
                    @if(session('error'))
                        <div class="alert alert-danger text-white font-weight-bold mb-4" style="background-color: #dc3545; border-color: #dc3545;">
                            {{ session('error') }}
                        </div>
                    @else
                         <p class="text-secondary mb-4">This could be due to several reasons. Please check the details below or try again.</p>
                    @endif
                    
                    <div class="card bg-light border mb-4">
                        <div class="card-body p-3">
                            <h6 class="card-title text-dark mb-3 text-center">Common Reasons</h6>
                            <ul class="text-start text-sm text-secondary ps-3" style="list-style: none;">
                                <li class="mb-1"><i class="fas fa-times-circle text-danger me-2"></i>Insufficient funds</li>
                                <li class="mb-1"><i class="fas fa-times-circle text-danger me-2"></i>Incorrect card details</li>
                                <li class="mb-1"><i class="fas fa-times-circle text-danger me-2"></i>Bank decline</li>
                                <li class="mb-1"><i class="fas fa-times-circle text-danger me-2"></i>Connection issue</li>
                                <li class="mb-1"><i class="fas fa-times-circle text-danger me-2"></i>Security verification failed</li>
                            </ul>
                        </div>
                    </div>
                    
                    @if(isset($registration))
                    <p class="text-sm text-muted">Registration Reference: <strong>{{ $registration->reference_number ?? $registration->id }}</strong></p>
                    @endif
                    
                    <div class="mt-4 d-grid gap-2 d-sm-flex justify-content-sm-center">
                         {{-- Link back to the specific event payment page --}}
                        <a href="{{ route('events.payment.show', $registration->id) }}" class="btn btn-outline-secondary btn-lg px-4"><i class="fas fa-redo me-1"></i> Try Payment Again</a>
                        <a href="{{ route('events.index') }}" class="btn btn-lg px-4 text-white" style="background-color: var(--primary-color);"><i class="fas fa-calendar-alt me-1"></i> Browse Events</a>
                    </div>
                    
                    <div class="mt-4 text-muted">
                        <small>Redirecting to home page in <span id="countdown">3</span> seconds...</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-redirect to home page after 3 seconds
    document.addEventListener('DOMContentLoaded', function() {
        let seconds = 3;
        const countdownElement = document.getElementById('countdown');
        
        const countdown = setInterval(function() {
            seconds--;
            countdownElement.textContent = seconds;
            
            if (seconds <= 0) {
                clearInterval(countdown);
                window.location.href = "{{ url('/') }}";
            }
        }, 1000);
    });
</script>
@endsection 