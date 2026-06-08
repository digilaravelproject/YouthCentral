@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>My Reviews</h6>
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
                    
                    @if(session('info'))
                        <div class="alert alert-info mx-4 mt-4">
                            {{ session('info') }}
                        </div>
                    @endif
                    
                    @if(count($reviews) === 0)
                        <div class="alert alert-info mx-4 mt-4">
                            You haven't written any reviews yet.
                        </div>
                    @else
                        <div class="table-responsive p-0 mx-4 mt-4">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Business</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Rating</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
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
                                                    <p class="text-xs text-secondary mb-0">{{ $review->business->category->name }} - {{ $review->business->subcategory->name }}</p>
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
                                            @if($review->status === 'pending')
                                                <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                            @elseif($review->status === 'approved')
                                                <span class="badge badge-sm bg-gradient-success">Approved</span>
                                            @else
                                                <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $review->created_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $review->created_at->format('h:i A') }}</p>
                                        </td>
                                        <td class="align-middle">
                                            <div class="ms-auto">
                                                <a href="{{ route('user.reviews.show', $review->id) }}" class="btn btn-link text-dark px-1 mb-0" title="View">
                                                    <i class="fas fa-eye text-dark me-2" aria-hidden="true"></i>
                                                </a>
                                                
                                                @if($review->status === 'pending')
                                                    <a href="{{ route('user.reviews.edit', $review->id) }}" class="btn btn-link text-dark px-1 mb-0" title="Edit">
                                                        <i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>
                                                    </a>
                                                    
                                                    <form action="{{ route('user.reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger text-gradient px-1 mb-0" title="Delete" onclick="return confirm('Are you sure you want to delete this review?')">
                                                            <i class="far fa-trash-alt me-2"></i>
                                                        </button>
                                                    </form>
                                                @endif
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