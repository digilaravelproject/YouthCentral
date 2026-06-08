@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>My Businesses</h6>
                    <a href="{{ route('vendor.businesses.create') }}" class="btn btn-primary btn-sm">Add New Business</a>
                </div>
                
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form action="{{ route('vendor.businesses.index') }}" method="GET" class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="subcategory" class="form-control">
                                        <option value="">All Subcategories</option>
                                        @foreach($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}" {{ request('subcategory') == $subcategory->id ? 'selected' : '' }}>
                                                {{ $subcategory->name }} ({{ $subcategory->category->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="area" class="form-control">
                                        <option value="">All Areas</option>
                                        @foreach($areas as $area)
                                            <option value="{{ $area->id }}" {{ request('area') == $area->id ? 'selected' : '' }}>
                                                {{ $area->name }} ({{ $area->city->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-info w-100">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Business</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subcategory</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Location</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($businesses) > 0)
                                    @foreach($businesses as $business)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $business->business_name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $business->phone }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $business->subcategory->name }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $business->subcategory->category->name }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $business->area->name }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $business->area->city->name }}, {{ $business->area->city->state->name }}</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm {{ $business->status === 'active' ? 'bg-gradient-success' : ($business->status === 'pending' ? 'bg-gradient-warning' : 'bg-gradient-secondary') }}">
                                                {{ ucfirst($business->status) }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex">
                                                <a href="{{ route('vendor.businesses.show', $business) }}" class="btn btn-link text-info px-1 mb-0" data-toggle="tooltip" title="View details">
                                                    <i class="fas fa-eye text-info me-2"></i>
                                                </a>
                                                <a href="{{ route('vendor.businesses.edit', $business) }}" class="btn btn-link text-dark px-1 mb-0" data-toggle="tooltip" title="Edit">
                                                    <i class="fas fa-pencil-alt text-dark me-2"></i>
                                                </a>
                                                <a href="{{ route('public.business.show', $business) }}" class="btn btn-link text-primary px-1 mb-0" data-toggle="tooltip" title="View in Directory" target="_blank">
                                                    <i class="fas fa-external-link-alt text-primary me-2"></i>
                                                </a>
                                                <form action="{{ route('vendor.businesses.destroy', $business) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger px-1 mb-0" data-toggle="tooltip" title="Delete">
                                                        <i class="fas fa-trash text-danger me-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-sm mb-0">You haven't claimed or created any businesses yet.</p>
                                            <a href="{{ route('vendor.claims.index') }}" class="btn btn-sm btn-primary mt-3">
                                                <i class="fas fa-search me-1"></i> Browse Businesses to Claim
                                            </a>
                                            <span class="mx-2">or</span>
                                            <a href="{{ route('vendor.businesses.create') }}" class="btn btn-sm btn-info mt-3">
                                                <i class="fas fa-plus me-1"></i> Create New Business
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $businesses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirm delete
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Always prevent default first
                if (confirm('Are you sure you want to delete this business? This action cannot be undone.')) {
                    // Submit the form if confirmed
                    this.submit();
                }
            });
        });
    });
</script>
@endpush 