@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 col-md-12 mx-auto">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Review Details</h6>
                        <a href="{{ route('vendor.reviews.index', ['business_id' => $review->business_id]) }}" class="btn btn-outline-secondary btn-sm">Back to Reviews</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="card border mb-4">
                        <div class="card-header bg-light py-3">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="mb-0">{{ $review->business->name }}</h6>
                                    <p class="text-sm text-secondary mb-0">
                                        {{ $review->business->subcategory->name }} in {{ $review->business->area->name }}, {{ $review->business->area->city->name }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <span class="mb-0 badge 
                                        @if($review->status === 'pending') 
                                            bg-gradient-warning
                                        @elseif($review->status === 'approved') 
                                            bg-gradient-success
                                        @else 
                                            bg-gradient-danger
                                        @endif">
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="reviewer-info">
                                        <h6 class="text-sm">Reviewer</h6>
                                        <p class="text-xs text-secondary mb-1">{{ $review->user->name }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $review->user->email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <div class="review-date">
                                        <h6 class="text-sm">Review Date</h6>
                                        <p class="text-xs text-secondary mb-0">
                                            {{ $review->created_at->format('F d, Y') }} at {{ $review->created_at->format('h:i A') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="review-content mb-4">
                                <div class="rating mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rating-stars me-3">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-sm font-weight-bold">
                                            @switch($review->rating)
                                                @case(1)
                                                    Terrible
                                                    @break
                                                @case(2)
                                                    Poor
                                                    @break
                                                @case(3)
                                                    Average
                                                    @break
                                                @case(4)
                                                    Good
                                                    @break
                                                @case(5)
                                                    Excellent
                                                    @break
                                                @default
                                                    {{ $review->rating }} / 5
                                            @endswitch
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="review-text">
                                    <h6 class="text-sm mb-2">Review Comment</h6>
                                    <div class="p-3 bg-light rounded">
                                        @if($review->comment)
                                            <p class="mb-0">{{ $review->comment }}</p>
                                        @else
                                            <p class="text-muted mb-0">No additional comments provided.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            @if($review->status === 'rejected')
                                <div class="alert alert-danger mb-4">
                                    <h6 class="text-white mb-1">This review was rejected</h6>
                                    @if($review->rejection_reason)
                                        <p class="text-white mb-0"><strong>Reason:</strong> {{ $review->rejection_reason }}</p>
                                    @else
                                        <p class="text-white mb-0">No specific reason was provided by the admin.</p>
                                    @endif
                                </div>
                            @endif
                            
                            @if($review->vendor_response)
                                <div class="vendor-response mb-4">
                                    <h6 class="text-sm mb-2">Your Response</h6>
                                    <div class="p-3 bg-light rounded">
                                        <p class="mb-0">{{ $review->vendor_response }}</p>
                                        <p class="text-xs text-secondary mt-2 mb-0">
                                            Responded on {{ $review->vendor_response_date->format('F d, Y') }}
                                        </p>
                                    </div>
                                </div>
                                
                                @if($review->status === 'approved')
                                    <div class="text-end">
                                        <a href="{{ route('vendor.reviews.respond', $review->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit me-2"></i> Edit Response
                                        </a>
                                    </div>
                                @endif
                            @elseif($review->status === 'approved')
                                <div class="respond-section">
                                    <h6 class="text-sm mb-2">Respond to this Review</h6>
                                    <div class="alert alert-info">
                                        <p class="mb-0">
                                            <i class="fas fa-info-circle me-2"></i> Responding to reviews shows that you value customer feedback and are committed to improving your business.
                                        </p>
                                    </div>
                                    
                                    <a href="{{ route('vendor.reviews.respond', $review->id) }}" class="btn btn-primary">
                                        <i class="fas fa-reply me-2"></i> Add Response
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <a href="{{ route('vendor.reviews.index', ['business_id' => $review->business_id]) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to All Reviews
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 