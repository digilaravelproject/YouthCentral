@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 col-md-12 mx-auto">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Edit Your Review</h6>
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
                    
                    @if(session('info'))
                        <div class="alert alert-info">
                            {{ session('info') }}
                        </div>
                    @endif
                    
                    <div class="business-info-card mb-4">
                        <div class="card bg-gradient-info">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-white mb-0">{{ $review->business->name }}</h5>
                                        <p class="text-white text-sm mb-0 opacity-8">{{ $review->business->subcategory->name }} in {{ $review->business->area->name }}, {{ $review->business->area->city->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('user.reviews.update', $review->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-4">
                            <label for="rating" class="form-control-label">Your Rating</label>
                            <div class="rating-input d-flex justify-content-start align-items-center">
                                <div class="star-rating">
                                    <div class="stars">
                                        <input type="radio" id="star5" name="rating" value="5" class="d-none" {{ old('rating', $review->rating) == 5 ? 'checked' : '' }}>
                                        <label for="star5" class="star-label"><i class="far fa-star text-warning"></i></label>
                                        
                                        <input type="radio" id="star4" name="rating" value="4" class="d-none" {{ old('rating', $review->rating) == 4 ? 'checked' : '' }}>
                                        <label for="star4" class="star-label"><i class="far fa-star text-warning"></i></label>
                                        
                                        <input type="radio" id="star3" name="rating" value="3" class="d-none" {{ old('rating', $review->rating) == 3 ? 'checked' : '' }}>
                                        <label for="star3" class="star-label"><i class="far fa-star text-warning"></i></label>
                                        
                                        <input type="radio" id="star2" name="rating" value="2" class="d-none" {{ old('rating', $review->rating) == 2 ? 'checked' : '' }}>
                                        <label for="star2" class="star-label"><i class="far fa-star text-warning"></i></label>
                                        
                                        <input type="radio" id="star1" name="rating" value="1" class="d-none" {{ old('rating', $review->rating) == 1 ? 'checked' : '' }}>
                                        <label for="star1" class="star-label"><i class="far fa-star text-warning"></i></label>
                                    </div>
                                </div>
                                <span id="rating-text" class="ms-3 text-sm">Select a rating</span>
                            </div>
                            @error('rating')
                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="comment" class="form-control-label">Your Review</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="5" placeholder="Share your experience with this business...">{{ old('comment', $review->comment) }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <div class="alert alert-info text-white text-sm" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                You can only edit your review while it is in the "pending" state. Once approved or rejected, you cannot modify it.
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('user.reviews.index') }}" class="btn btn-light me-2">Cancel</a>
                            <button type="submit" class="btn bg-gradient-primary">Update Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const starLabels = document.querySelectorAll('.star-label');
        const ratingInputs = document.querySelectorAll('input[name="rating"]');
        const ratingText = document.getElementById('rating-text');
        
        const ratingDescriptions = {
            5: 'Excellent',
            4: 'Good',
            3: 'Average',
            2: 'Poor',
            1: 'Terrible'
        };
        
        // Check if a rating was previously selected (e.g. on form validation error)
        ratingInputs.forEach(input => {
            if (input.checked) {
                updateStars(parseInt(input.value));
                updateRatingText(parseInt(input.value));
            }
        });
        
        // Handle star hover and click
        starLabels.forEach((label, index) => {
            const starValue = 5 - index;
            
            // Hover effect
            label.addEventListener('mouseenter', function() {
                updateStars(starValue);
            });
            
            // Click to select
            label.addEventListener('click', function() {
                const inputId = this.getAttribute('for');
                const input = document.getElementById(inputId);
                input.checked = true;
                updateStars(starValue);
                updateRatingText(starValue);
            });
        });
        
        // Reset stars on mouseleave if no rating is selected
        document.querySelector('.stars').addEventListener('mouseleave', function() {
            const selectedValue = getSelectedRating();
            if (selectedValue) {
                updateStars(selectedValue);
            } else {
                resetStars();
            }
        });
        
        function updateStars(value) {
            starLabels.forEach((label, index) => {
                const starValue = 5 - index;
                const icon = label.querySelector('i');
                
                if (starValue <= value) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                }
            });
        }
        
        function resetStars() {
            starLabels.forEach(label => {
                const icon = label.querySelector('i');
                icon.classList.remove('fas');
                icon.classList.add('far');
            });
        }
        
        function getSelectedRating() {
            for (const input of ratingInputs) {
                if (input.checked) {
                    return parseInt(input.value);
                }
            }
            return null;
        }
        
        function updateRatingText(value) {
            ratingText.textContent = ratingDescriptions[value] || 'Select a rating';
        }
    });
</script>
@endpush 