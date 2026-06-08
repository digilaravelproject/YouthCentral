@extends('layouts.app')

@section('content')
<div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('directory.index') }}" class="text-secondary text-sm">
                                <i class="fas fa-arrow-left me-1"></i> Back to Directory
                            </a>
                            <h3 class="mt-2 mb-0">{{ $business->business_name }}</h3>
                            <p class="text-sm mb-0">
                                <i class="fas fa-tag text-primary me-1"></i> 
                                <a href="{{ route('listings', $business->subcategory) }}">{{ $business->subcategory->name }}</a>
                                <span class="mx-2">|</span>
                                <i class="fas fa-map-marker-alt text-primary me-1"></i> 
                                {{ $business->area->name }}, {{ $business->area->city->name }}, {{ $business->area->city->state->name }}
                            </p>
                        </div>
                        <div>
                            @if($business->claimed_by)
                                <span class="badge bg-gradient-success">
                                    <i class="fas fa-check-circle me-1"></i> Verified Business
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Business Images Slider -->
                    @if($business->images && count($business->images) > 0)
                    <div class="business-images-section mb-4">
                        <h5 class="mb-3">Business Photos</h5>
                        <div class="row g-2">
                            @foreach($business->images as $image)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="business-image-item">
                                        <a href="{{ asset('storage/' . $image->path) }}" data-lightbox="business-gallery" data-title="{{ $business->business_name }}">
                                            <img src="{{ asset('storage/' . $image->path) }}" class="img-fluid rounded" alt="{{ $business->business_name }}" style="width: 100%; height: 150px; object-fit: cover;">
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <h4>Business Information</h4>
                                <div class="info-item mb-3">
                                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-1">Address</h6>
                                    <p class="mb-0">{{ $business->street_address }}</p>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-1">Phone</h6>
                                            <p class="mb-0">
                                                <a href="tel:{{ $business->phone }}" class="text-primary font-weight-bold">
                                                    {{ $business->phone }}
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    @if($business->whatsapp_number)
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-1">WhatsApp</h6>
                                            <p class="mb-0">
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp_number) }}" class="text-success font-weight-bold" target="_blank">
                                                    {{ $business->whatsapp_number }} <i class="fab fa-whatsapp ms-1"></i>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($business->email)
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-1">Email</h6>
                                            <p class="mb-0">
                                                <a href="mailto:{{ $business->email }}" class="text-primary font-weight-bold">
                                                    {{ $business->email }}
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                @if($business->website)
                                <div class="info-item mb-3">
                                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-1">Website</h6>
                                    <p class="mb-0">
                                        <a href="{{ $business->website }}" class="text-primary font-weight-bold" target="_blank">
                                            {{ $business->website }} <i class="fas fa-external-link-alt ms-1 text-xs"></i>
                                        </a>
                                    </p>
                                </div>
                                @endif
                                
                                <!-- Social Media Links -->
                                @if($business->facebook_link || $business->instagram_link || $business->twitter_link || $business->pinterest_link)
                                <div class="info-item mb-3">
                                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-1">Follow Us</h6>
                                    <div class="d-flex gap-2 mt-2">
                                        @if($business->facebook_link)
                                            <a href="{{ $business->facebook_link }}" class="btn btn-facebook btn-simple rounded p-2" target="_blank">
                                                <i class="fab fa-facebook fa-lg"></i>
                                            </a>
                                        @endif
                                        
                                        @if($business->instagram_link)
                                            <a href="{{ $business->instagram_link }}" class="btn btn-instagram btn-simple rounded p-2" target="_blank">
                                                <i class="fab fa-instagram fa-lg"></i>
                                            </a>
                                        @endif
                                        
                                        @if($business->twitter_link)
                                            <a href="{{ $business->twitter_link }}" class="btn btn-twitter btn-simple rounded p-2" target="_blank">
                                                <i class="fab fa-twitter fa-lg"></i>
                                            </a>
                                        @endif
                                        
                                        @if($business->pinterest_link)
                                            <a href="{{ $business->pinterest_link }}" class="btn btn-pinterest btn-simple rounded p-2" target="_blank">
                                                <i class="fab fa-pinterest fa-lg"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Business Description -->
                                @if($business->description)
                                <div class="info-item mb-3">
                                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-1">About This Business</h6>
                                    <p class="mb-0">{{ $business->description }}</p>
                                </div>
                                @endif
                                
                                <!-- Business Hours -->
                                <div class="info-item mb-3">
                                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-1">Business Hours</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <tbody>
                                                @php
                                                    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                                    $businessHours = $business->businessHours->keyBy('day');
                                                    $defaultHours = $business->getDefaultBusinessHours();
                                                    $today = strtolower(date('l'));
                                                @endphp
                                                
                                                @foreach($days as $day)
                                                    @php
                                                        $hours = $businessHours->get($day);
                                                        $isOpen = $hours ? !$hours->is_closed : true;
                                                        $openingTime = $hours ? $hours->opening_time : $defaultHours[$day]['opening_time'];
                                                        $closingTime = $hours ? $hours->closing_time : $defaultHours[$day]['closing_time'];
                                                        $isToday = $day === $today;
                                                    @endphp
                                                    <tr class="{{ $isToday ? 'bg-light' : '' }}">
                                                        <td style="width: 120px;" class="text-capitalize {{ $isToday ? 'fw-bold' : '' }}">
                                                            {{ $day }} {{ $isToday ? '(Today)' : '' }}
                                                        </td>
                                                        <td>
                                                            @if($isOpen)
                                                                {{ date('g:i A', strtotime($openingTime)) }} - {{ date('g:i A', strtotime($closingTime)) }}
                                                            @else
                                                                <span class="text-danger">Closed</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3">
                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('directory.show', $business)) }}" 
                                               class="btn btn-outline-primary btn-icon-only" target="_blank">
                                                <i class="fab fa-facebook"></i>
                                            </a>
                                        </div>
                                        <div class="me-3">
                                            <a href="https://twitter.com/intent/tweet?text=Check out {{ urlencode($business->business_name) }} on Youth Central Directory&url={{ urlencode(route('directory.show', $business)) }}" 
                                               class="btn btn-outline-info btn-icon-only" target="_blank">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <a href="mailto:?subject=Check out {{ urlencode($business->business_name) }} on Youth Central Directory&body=I found this business on Youth Central Directory: {{ route('directory.show', $business) }}" 
                                               class="btn btn-outline-dark btn-icon-only">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                                <div class="card card-plain">
                                    <div class="card-header pb-0">
                                        <h4>Location</h4>
                                    </div>
                                    <div class="card-body px-0">
                                        <div class="map-container border rounded" style="height: 250px; position: relative; overflow: hidden;">
                                            @if($business->latitude && $business->longitude)
                                            <iframe 
                                                width="100%" 
                                                height="100%" 
                                                frameborder="0" 
                                                scrolling="no" 
                                                marginheight="0" 
                                                marginwidth="0" 
                                                src="https://maps.google.com/maps?q={{ $business->latitude }},{{ $business->longitude }}&z=15&output=embed"
                                            ></iframe>
                                            @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                                <p>Map location not available</p>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        <div class="mt-3">
                                            <a href="https://maps.google.com/?q={{ $business->latitude }},{{ $business->longitude }}" 
                                               class="btn btn-sm btn-outline-primary w-100" target="_blank">
                                                <i class="fas fa-directions me-1"></i> Get Directions
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                @if(!$business->claimed_by)
                                <div class="card card-plain mt-4">
                                    <div class="card-body px-0">
                                        <div class="info-item text-center p-3 border rounded">
                                            <h6>Is this your business?</h6>
                                            <p class="text-sm mb-3">Claim this listing to update business information and manage your presence on Youth Central.</p>
                                            <a href="{{ route('login') }}" class="btn btn-primary">
                                                <i class="fas fa-store me-1"></i> Claim This Business
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Reviews Section -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h4>Customer Reviews</h4>
                        @auth
                            @if(Auth::user()->role == 'user')
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                    <i class="fas fa-star me-1"></i> Write a Review
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-star me-1"></i> Login to Write a Review
                            </a>
                        @endauth
                    </div>
                    <div class="card-body">
                        @if($business->reviews()->approved()->count() > 0)
                            <div class="row mb-4">
                                <div class="col-lg-3 col-md-6">
                                    <div class="ratings-summary text-center p-3 border rounded">
                                        <h2 class="display-4 font-weight-bolder mb-0">{{ number_format($business->average_rating, 1) }}</h2>
                                        <div class="rating-stars mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= round($business->average_rating))
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <p class="text-sm mb-0">Based on {{ $business->reviews_count }} reviews</p>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-6">
                                    <div class="ratings-bars">
                                        @php
                                            $approvedReviews = $business->reviews()->approved()->get();
                                            $totalCount = $approvedReviews->count();
                                            $ratingCounts = [
                                                5 => $approvedReviews->where('rating', 5)->count(),
                                                4 => $approvedReviews->where('rating', 4)->count(),
                                                3 => $approvedReviews->where('rating', 3)->count(),
                                                2 => $approvedReviews->where('rating', 2)->count(),
                                                1 => $approvedReviews->where('rating', 1)->count(),
                                            ];
                                        @endphp
                                        
                                        @for($i = 5; $i >= 1; $i--)
                                            @php
                                                $percentage = $totalCount > 0 ? ($ratingCounts[$i] / $totalCount) * 100 : 0;
                                            @endphp
                                            <div class="rating-bar mb-3">
                                                <div class="d-flex align-items-center mb-1">
                                                    <div class="col-2">
                                                        <span class="text-sm font-weight-bold">{{ $i }} stars</span>
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="progress">
                                                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%" 
                                                                aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-2 text-end">
                                                        <span class="text-sm font-weight-bold">{{ $ratingCounts[$i] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="horizontal dark">
                            
                            <div class="reviews-list">
                                @foreach($business->reviews()->approved()->latest()->limit(5)->get() as $review)
                                    <div class="review-item mb-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <div>
                                                <h6 class="mb-0">{{ $review->user->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $review->created_at->format('M d, Y') }}</p>
                                            </div>
                                            <div class="ms-auto">
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
                                        <div class="review-content">
                                            @if($review->comment)
                                                <p class="mb-0">{{ $review->comment }}</p>
                                            @else
                                                <p class="text-muted mb-0">No additional comments provided.</p>
                                            @endif
                                        </div>
                                        
                                        @if($review->vendor_response)
                                            <div class="vendor-response mt-2 ms-4 p-3 bg-light rounded">
                                                <div class="d-flex align-items-center mb-1">
                                                    <h6 class="mb-0 text-sm">Business Response</h6>
                                                    <p class="text-xs text-secondary mb-0 ms-2">
                                                        {{ isset($review->vendor_response_date) ? $review->vendor_response_date->format('M d, Y') : '' }}
                                                    </p>
                                                </div>
                                                <p class="mb-0 text-sm">{{ $review->vendor_response }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if(!$loop->last)
                                        <hr class="horizontal dark">
                                    @endif
                                @endforeach
                            </div>
                            
                            @if($business->reviews_count > 5)
                                <div class="text-center mt-4">
                                    <a href="#" class="btn btn-outline-primary btn-sm">
                                        View All {{ $business->reviews_count }} Reviews
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                <h5>No Reviews Yet</h5>
                                <p class="text-muted">Be the first to review this business!</p>
                                @auth
                                    @if(Auth::user()->role == 'user')
                                        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                            <i class="fas fa-star me-1"></i> Write a Review
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-star me-1"></i> Login to Write a Review
                                    </a>
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Review Modal -->
            @auth
                @if(Auth::user()->role == 'user')
                <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reviewModalLabel">Write a Review for {{ $business->business_name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('user.reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="business_id" value="{{ $business->id }}">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Rating</label>
                                        <div class="rating-input">
                                            <input type="radio" name="rating" value="5" id="star5" required>
                                            <label for="star5"><i class="fas fa-star"></i></label>
                                            <input type="radio" name="rating" value="4" id="star4">
                                            <label for="star4"><i class="fas fa-star"></i></label>
                                            <input type="radio" name="rating" value="3" id="star3">
                                            <label for="star3"><i class="fas fa-star"></i></label>
                                            <input type="radio" name="rating" value="2" id="star2">
                                            <label for="star2"><i class="fas fa-star"></i></label>
                                            <input type="radio" name="rating" value="1" id="star1">
                                            <label for="star1"><i class="fas fa-star"></i></label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="comment" class="form-label">Your Review</label>
                                        <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Submit Review</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <style>
                    .rating-input {
                        display: flex;
                        flex-direction: row-reverse;
                        justify-content: flex-end;
                    }
                    .rating-input input {
                        display: none;
                    }
                    .rating-input label {
                        cursor: pointer;
                        font-size: 1.5rem;
                        color: #ddd;
                        padding: 0 0.2rem;
                    }
                    .rating-input input:checked ~ label,
                    .rating-input label:hover,
                    .rating-input label:hover ~ label {
                        color: #ffd700;
                    }
                </style>
                @endif
            @endauth
            
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4>Similar Businesses</h4>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if(count($similarBusinesses) > 0)
                        <div class="row p-3">
                            @foreach($similarBusinesses as $similarBusiness)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title mb-1">{{ $similarBusiness->business_name }}</h5>
                                        <p class="card-text text-sm mb-0">
                                            <i class="fas fa-tag text-primary me-1"></i> 
                                            {{ $similarBusiness->subcategory->name }}
                                        </p>
                                        <p class="card-text text-sm mb-0">
                                            <i class="fas fa-map-marker-alt text-primary me-1"></i> 
                                            {{ $similarBusiness->area->name }}, {{ $similarBusiness->area->city->name }}
                                        </p>
                                        <p class="card-text text-sm mb-0">
                                            <i class="fas fa-phone text-primary me-1"></i> {{ $similarBusiness->phone }}
                                        </p>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <a href="{{ route('directory.show', $similarBusiness) }}" class="btn btn-sm btn-outline-primary w-100">View Details</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-5">
                            <p>No similar businesses found.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'albumLabel': "Image %1 of %2",
        'fadeDuration': 300
    });
</script>
@endsection

<style>
.btn-facebook {
    color: #3b5998;
    border-color: #3b5998;
}
.btn-instagram {
    color: #e4405f;
    border-color: #e4405f;
}
.btn-twitter {
    color: #1da1f2;
    border-color: #1da1f2;
}
.btn-pinterest {
    color: #bd081c;
    border-color: #bd081c;
}
.btn-simple {
    background-color: transparent;
    box-shadow: none;
}
</style> 