@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 col-md-12 mx-auto">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>{{ $review->vendor_response ? 'Edit Response' : 'Respond to Review' }}</h6>
                        <a href="{{ route('vendor.reviews.show', $review->id) }}" class="btn btn-outline-secondary btn-sm">Back to Review</a>
                    </div>
                </div>
                <div class="card-body">
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
                                    <p class="text-sm text-secondary mb-0">Review from {{ $review->user->name }} on {{ $review->created_at->format('F d, Y') }}</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="review-content mb-4">
                                <h6 class="text-sm mb-2">Customer's Review</h6>
                                <div class="p-3 bg-light rounded">
                                    @if($review->comment)
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    @else
                                        <p class="text-muted mb-0">No additional comments provided.</p>
                                    @endif
                                </div>
                            </div>
                            
                            <form action="{{ route('vendor.reviews.respond.submit', $review->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                
                                <div class="form-group mb-4">
                                    <label for="vendor_response" class="form-control-label">Your Response</label>
                                    <textarea name="vendor_response" id="vendor_response" rows="5" class="form-control @error('vendor_response') is-invalid @enderror" required>{{ old('vendor_response', $review->vendor_response) }}</textarea>
                                    @error('vendor_response')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="alert alert-info">
                                    <h6 class="text-white mb-1">Tips for responding to reviews:</h6>
                                    <ul class="text-white mb-0 ps-4">
                                        <li>Thank the customer for their feedback</li>
                                        <li>Address specific points in the review</li>
                                        <li>Maintain a professional and courteous tone</li>
                                        <li>If addressing a negative review, acknowledge concerns and explain any corrective actions</li>
                                        <li>Keep responses concise and to the point</li>
                                    </ul>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('vendor.reviews.show', $review->id) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-{{ $review->vendor_response ? 'edit' : 'reply' }} me-2"></i> 
                                        {{ $review->vendor_response ? 'Update Response' : 'Submit Response' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 