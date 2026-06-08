@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 col-md-12 mx-auto">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Review Details</h6>
                        <a href="{{ route('user.reviews.index') }}" class="btn btn-outline-secondary btn-sm">Back to Reviews</a>
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
                    
                    <div class="business-info-card mb-4">
                        <div class="card bg-gradient-info">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <h5 class="text-white mb-0">{{ $review->business->name }}</h5>
                                        <p class="text-white text-sm mb-0 opacity-8">{{ $review->business->subcategory->name }} in {{ $review->business->area->name }}, {{ $review->business->area->city->name }}</p>
                                    </div>
                                    <div class="col-4 text-end">
                                        <a href="#" class="btn btn-sm btn-outline-white">View Business</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border mb-4">
                        <div class="card-header bg-light py-3">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="mb-0">Your Review</h6>
                                    <p class="text-sm text-secondary mb-0">Submitted on {{ $review->created_at->format('M d, Y') }} at {{ $review->created_at->format('h:i A') }}</p>
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
                            <div class="mb-3">
                                <div class="rating-stars mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                    
                                    <span class="ms-2 text-sm font-weight-bold">
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
                                
                                <div class="review-content">
                                    @if($review->comment)
                                        <p>{{ $review->comment }}</p>
                                    @else
                                        <p class="text-muted">No additional comments provided.</p>
                                    @endif
                                </div>
                            </div>
                            
                            @if($review->status === 'rejected' && $review->rejection_reason)
                                <div class="alert alert-danger mb-0">
                                    <h6 class="text-white">Rejection Reason:</h6>
                                    <p class="text-white mb-0">{{ $review->rejection_reason }}</p>
                                </div>
                            @endif
                        </div>
                        
                        @if($review->vendor_response)
                            <div class="card-footer bg-light py-3">
                                <h6 class="mb-2">Business Response:</h6>
                                <div class="p-3 bg-white rounded border">
                                    <p class="mb-0">{{ $review->vendor_response }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <div>
                            @if($review->status === 'pending')
                                <a href="{{ route('user.reviews.edit', $review->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i> Edit Review
                                </a>
                                
                                <form action="{{ route('user.reviews.destroy', $review->id) }}" method="POST" class="d-inline ms-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this review?')">
                                        <i class="fas fa-trash me-2"></i> Delete Review
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <a href="{{ route('user.reviews.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to All Reviews
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 