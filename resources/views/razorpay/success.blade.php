@extends('layouts.app-minimal')

@section('title', 'Payment Successful')

@push('styles')
<style>
    body {
        /* background: linear-gradient(135deg, var(--primary-color) 0%, #f4c430 100%); */
        font-family: 'Roboto', sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .success-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .success-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        overflow: hidden;
        position: relative;
        animation: slideInUp 0.6s ease-out;
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .success-header {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        text-align: center;
        padding: 40px 20px 30px;
        position: relative;
        overflow: hidden;
    }
    
    .success-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .success-icon {
        font-size: 80px;
        margin-bottom: 20px;
        display: inline-block;
        animation: bounceIn 1s ease-out 0.3s both;
    }
    
    @keyframes bounceIn {
        0% { transform: scale(0.3); opacity: 0; }
        50% { transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); opacity: 1; }
    }
    
    .success-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .success-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    .success-body {
        padding: 40px 30px;
        text-align: center;
    }
    
    .celebration-message {
        background: linear-gradient(135deg, var(--primary-color), #f4c430);
        color: white;
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(10, 15, 31, 0.3);
    }
    
    .celebration-message h4 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
    }
    
    .details-card {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        border: 1px solid #e9ecef;
        text-align: left;
    }
    
    .details-card h5 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 20px;
        text-align: center;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary-color);
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        font-weight: 600;
        color: #6c757d;
    }
    
    .detail-value {
        color: #495057;
        font-weight: 500;
    }
    
    .badge-success {
        background: linear-gradient(135deg, #28a745, #20c997);
        border-radius: 20px;
        padding: 5px 12px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .btn-custom {
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-color), #f4c430);
        color: white;
        box-shadow: 0 5px 15px rgba(10, 15, 31, 0.3);
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(10, 15, 31, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .btn-secondary-custom {
        background: white;
        color: #6c757d;
        border: 2px solid #e9ecef;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .btn-secondary-custom:hover {
        transform: translateY(-2px);
        border-color: var(--primary-color);
        color: var(--primary-color);
        text-decoration: none;
    }
    
    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        background: var(--primary-color);
        animation: confetti-fall 3s linear infinite;
    }
    
    .confetti:nth-child(1) { left: 10%; animation-delay: 0s; background: #28a745; }
    .confetti:nth-child(2) { left: 20%; animation-delay: 0.5s; background: var(--primary-color); }
    .confetti:nth-child(3) { left: 30%; animation-delay: 1s; background: #20c997; }
    .confetti:nth-child(4) { left: 40%; animation-delay: 1.5s; background: #28a745; }
    .confetti:nth-child(5) { left: 50%; animation-delay: 2s; background: var(--primary-color); }
    .confetti:nth-child(6) { left: 60%; animation-delay: 0.3s; background: #20c997; }
    .confetti:nth-child(7) { left: 70%; animation-delay: 0.8s; background: #28a745; }
    .confetti:nth-child(8) { left: 80%; animation-delay: 1.3s; background: var(--primary-color); }
    .confetti:nth-child(9) { left: 90%; animation-delay: 1.8s; background: #20c997; }
    
    @keyframes confetti-fall {
        0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
        100% { transform: translateY(100vh) rotate(360deg); opacity: 0; }
    }
    
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn-custom {
            justify-content: center;
        }
        
        .success-title {
            font-size: 2rem;
        }
        
        .success-body {
            padding: 30px 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="success-container">
    <!-- Confetti Animation -->
    <div class="confetti"></div>
    <div class="confetti"></div>
    <div class="confetti"></div>
    <div class="confetti"></div>
    <div class="confetti"></div>
    <div class="confetti"></div>
    <div class="confetti"></div>
    <div class="confetti"></div>
    <div class="confetti"></div>
    
    <div class="success-card">
        <div class="success-header">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="success-title">Payment Successful!</h1>
            <p class="success-subtitle">Welcome to Youth Central Premium</p>
        </div>
        
        <div class="success-body">
            <div class="celebration-message">
                <h4 style="color: #fff;">🎉 Congratulations! Your subscription is now active!</h4>
            </div>
            
            <div class="details-card">
                <h5><i class="fas fa-receipt me-2"></i>Subscription Details</h5>
                
                <div class="detail-row">
                    <span class="detail-label">Plan Name:</span>
                    <span class="detail-value">{{ $subscription->plan->name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Amount Paid:</span>
                    <span class="detail-value">₹{{ number_format($subscription->amount_paid, 2) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="badge badge-success">{{ ucfirst($subscription->status) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Started Date:</span>
                    <span class="detail-value">{{ $subscription->started_at->format('M d, Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Valid Until:</span>
                    <span class="detail-value">
                        @if($subscription->ends_at)
                            {{ $subscription->ends_at->format('M d, Y') }}
                        @else
                            Lifetime Access
                        @endif
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Transaction ID:</span>
                    <span class="detail-value"><small>{{ $subscription->transaction_id }}</small></span>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('vendor.dashboard') }}" class="btn-custom btn-primary-custom">
                    <i class="fas fa-tachometer-alt"></i>
                    Go to Dashboard
                </a>
                <a href="{{ route('vendor.subscriptions.plans') }}" class="btn-custom btn-secondary-custom">
                    <i class="fas fa-receipt"></i>
                    View All Plans
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Add some interactive celebration effects
document.addEventListener('DOMContentLoaded', function() {
    // Create more confetti dynamically
    setTimeout(function() {
        for(let i = 0; i < 15; i++) {
            let confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.left = Math.random() * 100 + '%';
            confetti.style.animationDelay = Math.random() * 3 + 's';
            confetti.style.backgroundColor = ['var(--primary-color)', '#28a745', '#20c997'][Math.floor(Math.random() * 3)];
            document.querySelector('.success-container').appendChild(confetti);
            
            // Remove confetti after animation
            setTimeout(function() {
                confetti.remove();
            }, 3000);
        }
    }, 1000);
});
</script>
@endsection

