@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Reviews Management</h6>
                        <a href="{{ route('admin.reviews.dashboard') }}" class="btn btn-primary btn-sm">Reviews Dashboard</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success mx-4 mt-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger mx-4 mt-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="mx-4 mt-4 mb-4">
                        <ul class="nav nav-tabs" id="review-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="nav-link {{ $status === 'pending' ? 'active' : '' }}">
                                    Pending 
                                    <span class="badge bg-warning ms-1">{{ $pendingCount }}</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="nav-link {{ $status === 'approved' ? 'active' : '' }}">
                                    Approved 
                                    <span class="badge bg-success ms-1">{{ $approvedCount }}</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="{{ route('admin.reviews.index', ['status' => 'rejected']) }}" class="nav-link {{ $status === 'rejected' ? 'active' : '' }}">
                                    Rejected 
                                    <span class="badge bg-danger ms-1">{{ $rejectedCount }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    @if(count($reviews) === 0)
                        <div class="alert alert-info mx-4">
                            No {{ $status }} reviews found.
                        </div>
                    @else
                        <div class="table-responsive p-0 mx-4">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Business</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Reviewer</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Rating</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reviews as $review)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $review->business->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $review->business->subcategory->name }} - {{ $review->business->area->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $review->user->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $review->user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="rating-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $review->created_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $review->created_at->format('h:i A') }}</p>
                                        </td>
                                        <td class="align-middle">
                                            <div class="ms-auto">
                                                <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-link text-dark px-1 mb-0" title="View Details">
                                                    <i class="fas fa-eye text-dark me-2" aria-hidden="true"></i>
                                                </a>
                                                
                                                @if($status === 'pending')
                                                    <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-link text-success px-1 mb-0" title="Approve Review">
                                                            <i class="fas fa-check-circle text-success me-2"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <a href="{{ route('admin.reviews.reject-form', $review->id) }}" class="btn btn-link text-danger px-1 mb-0" title="Reject Review">
                                                        <i class="fas fa-times-circle text-danger me-2"></i>
                                                    </a>
                                                @endif
                                                
                                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger px-1 mb-0" title="Delete Review" onclick="return confirm('Are you sure you want to delete this review?')">
                                                        <i class="far fa-trash-alt text-danger me-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 