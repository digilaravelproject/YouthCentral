@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 col-md-12 mx-auto">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Reject Review</h6>
                        <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-outline-secondary btn-sm">Back to Review</a>
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
                                    <h6 class="mb-0">Review Details</h6>
                                    <p class="text-sm text-secondary mb-0">Review for {{ $review->business->name }} by {{ $review->user->name }}</p>
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
                            <div class="review-content">
                                @if($review->comment)
                                    <p>{{ $review->comment }}</p>
                                @else
                                    <p class="text-muted">No additional comments provided.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="form-group mb-4">
                            <label for="rejection_reason" class="form-control-label">Rejection Reason</label>
                            <textarea name="rejection_reason" id="rejection_reason" rows="4" class="form-control @error('rejection_reason') is-invalid @enderror" required>{{ old('rejection_reason') }}</textarea>
                            @error('rejection_reason')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted">
                                Please provide a clear reason why this review is being rejected. This information may be shared with the reviewer.
                            </small>
                        </div>
                        
                        <div class="form-group mb-0">
                            <div class="d-flex">
                                <button type="submit" class="btn btn-danger me-2">
                                    <i class="fas fa-times-circle me-2"></i> Confirm Rejection
                                </button>
                                
                                <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 