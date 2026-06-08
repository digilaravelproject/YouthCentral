@extends('layouts.app-minimal')

@section('title', 'Payment Failed')

@push('styles')
<style>
    .plan-card {
        background-color: #ffffff;
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s ease-in-out, box-shadow 0.3s ease-in-out;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .plan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    .plan-card .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        padding: 1rem 1.25rem;
    }
    .plan-card .card-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .icon-shape {
        width: 32px;
        height: 32px;
        background-position: center;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .list-group-item {
        padding: 0.5rem 0;
    }
    .back-button {
        margin-bottom: 1.5rem;
        display: inline-flex;
        align-items: center;
    }
    .back-button i {
        margin-right: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="card plan-card shadow-lg border-0 rounded-lg overflow-hidden">
                <div class="card-header text-center py-3 border-0" style="background-color: #dc3545;"> {{-- Danger Red --}}
                    <h4 class="mb-0 text-white"><i class="fas fa-times-circle me-2"></i>Payment Failed</h4>
                </div>
                <div class="card-body text-center p-4 p-md-5">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-triangle text-danger" style="font-size: 50px;"></i>
                    </div>
                    
                    <h5 class="mb-3 text-dark">Sorry, your payment could not be processed.</h5>
                    
                    <div class="alert alert-danger text-white font-weight-bold mb-4" style="background-color: #dc3545; border-color: #dc3545; color: white !important;">
                        @if(session('error'))
                            {{ session('error') }}
                        @else
                            There was an issue completing your payment.
                        @endif
                    </div>
                    
                    <div class="card bg-light border mb-4">
                        <div class="card-body p-3">
                            <h6 class="card-title text-dark mb-3 text-center">Common Reasons</h6>
                            <ul class="text-start text-sm text-secondary ps-3" style="list-style: none;">
                                <li class="mb-1"><i class="fas fa-times-circle text-danger me-2"></i>Insufficient funds</li>
                                <li class="mb-1"><i class="fas fa-times-circle text-danger me-2"></i>Incorrect card details</li>
                                <li class="mb-1"><i class="fas fa-times-circle text-danger me-2"></i>Bank decline</li>
                                <li class="mb-1"><i class="fas fa-times-circle text-danger me-2"></i>Connection issue</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mt-4 d-grid gap-2 d-sm-flex justify-content-sm-center">
                         {{-- Determine the correct 'Try Again' route based on context if possible --}}
                         {{-- For now, just going back --}}
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-lg px-4"><i class="fas fa-redo me-1"></i> Try Again</a>
                        <a href="{{ route('contact') }}" class="btn btn-lg px-4 text-dark" style="background-color: var(--primary-color); color: #000;"><i class="fas fa-headset me-1"></i> Contact Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

