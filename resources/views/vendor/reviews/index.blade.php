@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Business Reviews</h6>
                        
                        @if(count($businesses) > 0)
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="businessFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $selectedBusiness ? $selectedBusiness->business_name : 'All Businesses' }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="businessFilterDropdown">
                                    <li><a class="dropdown-item {{ !$selectedBusiness ? 'active' : '' }}" href="{{ route('vendor.reviews.index') }}">All Businesses</a></li>
                                    @foreach($businesses as $business)
                                        <li><a class="dropdown-item {{ $selectedBusiness && $selectedBusiness->id == $business->id ? 'active' : '' }}" href="{{ route('vendor.reviews.index', ['business_id' => $business->id]) }}">{{ $business->business_name }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success mx-4 mt-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger mx-4 mt-3">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(count($reviews) > 0)
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Business</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Reviewer</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Comment</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rating</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reviews as $review)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $review->business->business_name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $review->business->subcategory->name }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $review->user->name }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $review->user->email }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ \Illuminate\Support\Str::limit($review->comment, 50) }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex justify-content-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $review->created_at->format('M d, Y') }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge badge-sm bg-gradient-{{ $review->status == 'approved' ? 'success' : ($review->status == 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($review->status) }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="{{ route('vendor.reviews.show', $review->id) }}" class="btn btn-link text-dark px-3 mb-0">
                                                    <i class="fas fa-eye text-dark me-2"></i>View
                                                </a>
                                                
                                                @if($review->status == 'approved' && !$review->vendor_response)
                                                    <a href="{{ route('vendor.reviews.respond', $review->id) }}" class="btn btn-link text-primary px-3 mb-0">
                                                        <i class="fas fa-reply text-primary me-2"></i>Respond
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-3">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center mb-3">
                                <i class="fas fa-star-half-alt opacity-10"></i>
                            </div>
                            <h5>No Reviews Found</h5>
                            <p class="text-secondary">
                                @if($selectedBusiness)
                                    There are no reviews for {{ $selectedBusiness->business_name }} yet.
                                @else
                                    There are no reviews for any of your businesses yet.
                                @endif
                            </p>
                            <p class="text-sm mt-3">
                                Encourage your customers to leave reviews to improve your business visibility!
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        @if(count($businesses) > 0)
            <div class="col-lg-12">
                <div class="card z-index-2">
                    <div class="card-header pb-0">
                        <h6>Reviews Overview</h6>
                        <p class="text-sm">
                            <i class="fa fa-arrow-up text-success"></i>
                            <span class="font-weight-bold">Review performance</span> for your businesses
                        </p>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            @foreach($businesses as $business)
                                <div class="col-lg-4 col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-1">{{ $business->business_name }}</h6>
                                                    <h5 class="font-weight-bolder mb-0">
                                                        @php
                                                            $avgRating = $business->reviews()->where('status', 'approved')->avg('rating') ?? 0;
                                                        @endphp
                                                        {{ number_format($avgRating, 1) }}
                                                        <span class="text-sm">/5</span>
                                                    </h5>
                                                </div>
                                                <div>
                                                    <div class="d-flex">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= round($avgRating))
                                                                <i class="fas fa-star text-warning me-1"></i>
                                                            @else
                                                                <i class="far fa-star text-warning me-1"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    @php
                                                        $reviewCount = $business->reviews()->where('status', 'approved')->count();
                                                    @endphp
                                                    <p class="text-sm mb-0 text-end">{{ $reviewCount }} reviews</p>
                                                </div>
                                            </div>
                                            
                                            <div class="progress-wrapper mt-3">
                                                <div class="progress-info">
                                                    <div class="progress-percentage">
                                                        <span class="text-xs font-weight-bold">Positive Reviews</span>
                                                    </div>
                                                </div>
                                                <div class="progress">
                                                    @php 
                                                        $positiveCount = $business->reviews()->where('status', 'approved')->whereIn('rating', [4, 5])->count();
                                                        $positivePercentage = $reviewCount > 0 
                                                            ? ($positiveCount / $reviewCount) * 100 
                                                            : 0;
                                                    @endphp
                                                    <div class="progress-bar bg-gradient-success" role="progressbar" 
                                                         aria-valuenow="{{ $positivePercentage }}" aria-valuemin="0" 
                                                         aria-valuemax="100" style="width: {{ $positivePercentage }}%;">
                                                    </div>
                                                </div>
                                                <p class="mt-1 mb-0 text-xs text-end">
                                                    {{ number_format($positivePercentage, 0) }}% (4-5 star ratings)
                                                </p>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between mt-3">
                                                <a href="{{ route('vendor.businesses.show', $business->id) }}" class="btn btn-sm btn-primary">View Business</a>
                                                <a href="{{ route('vendor.businesses.edit', $business->id) }}" class="btn btn-sm btn-info">Edit Business</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 