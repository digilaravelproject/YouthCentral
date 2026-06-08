@extends('layouts.app')

@section('content')
<div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
    <!-- Header -->
    <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('{{ asset('assets/img/curved-images/curved0.jpg') }}'); background-position-y: 50%;">
        <span class="mask bg-gradient-primary opacity-6"></span>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center mx-auto my-auto">
                    <h1 class="text-white">Business Directory</h1>
                    <p class="lead mb-4 text-white opacity-8">Find local businesses and services in your area</p>
                    <form action="{{ route('directory.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-8 mx-auto">
                                <div class="input-group mb-3">
                                    <input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search for businesses..." type="text">
                                    <button class="btn bg-white" type="submit"><i class="fas fa-search text-primary"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid py-4">
        <div class="row mt-n6">
            <div class="col-lg-3">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Categories</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group">
                            @foreach($categories as $cat)
                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape icon-sm me-3 bg-gradient-primary shadow text-center">
                                            <i class="fas fa-tag text-white opacity-10"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('directory.category', $cat) }}" class="mb-1 text-dark text-sm">{{ $cat->name }}</a>
                                            <span class="text-xs">{{ $cat->subcategories->count() }} subcategories</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Filter by Area</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('directory.index') }}" method="GET">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            
                            <div class="form-group">
                                <label for="subcategory" class="form-control-label">Subcategory</label>
                                <select name="subcategory" id="subcategory" class="form-control">
                                    <option value="">All Subcategories</option>
                                    @foreach($subcategories as $sub)
                                        <option value="{{ $sub->id }}" {{ request('subcategory') == $sub->id ? 'selected' : '' }}>
                                            {{ $sub->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="area" class="form-control-label">Area</label>
                                <select name="area" id="area" class="form-control">
                                    <option value="">All Areas</option>
                                    @foreach($areas as $a)
                                        <option value="{{ $a->id }}" {{ request('area') == $a->id ? 'selected' : '' }}>
                                            {{ $a->name }} ({{ $a->city->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="sort" class="form-control-label">Sort By</label>
                                <select name="sort" id="sort" class="form-control">
                                    <option value="name" {{ request('sort') == 'name' || !request('sort') ? 'selected' : '' }}>Name</option>
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-9">
                @if(isset($error))
                    <div class="alert alert-warning" role="alert">
                        {{ $error }}
                    </div>
                @endif
                
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>
                            @if(isset($category))
                                {{ $category->name }} Businesses
                            @elseif(isset($subcategory))
                                {{ $subcategory->name }} Businesses
                            @elseif(isset($area))
                                Businesses in {{ $area->name }}
                            @else
                                All Businesses
                            @endif
                        </h6>
                        <span class="badge bg-gradient-primary">{{ $businesses->total() }} {{ \Illuminate\Support\Str::plural('result', $businesses->total()) }}</span>
                    </div>
                    
                    <div class="card-body px-0 pt-0 pb-2">
                        @if($businesses->count() > 0)
                            <div class="row p-3">
                                @foreach($businesses as $business)
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title mb-1">{{ $business->business_name }}</h5>
                                                <p class="card-text text-sm mb-0">
                                                    <i class="fas fa-tag text-primary me-1"></i> 
                                                    <a href="{{ route('listings', $business->subcategory) }}">{{ $business->subcategory->name }}</a>
                                                </p>
                                                <p class="card-text text-sm mb-0">
                                                    <i class="fas fa-map-marker-alt text-primary me-1"></i> 
                                                    <a href="{{ route('directory.area', $business->area) }}">{{ $business->area->name }}</a>, {{ $business->area->city->name }}
                                                </p>
                                                <p class="card-text text-sm mb-0">
                                                    <i class="fas fa-phone text-primary me-1"></i> {{ $business->phone }}
                                                </p>
                                                @if($business->email)
                                                    <p class="card-text text-sm mb-0">
                                                        <i class="fas fa-envelope text-primary me-1"></i> {{ $business->email }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="card-footer bg-white">
                                                <a href="{{ route('directory.show', $business) }}" class="btn btn-sm btn-outline-primary w-100">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="d-flex justify-content-center mt-4">
                                {{ $businesses->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-search fa-3x text-primary mb-3"></i>
                                <h4>No businesses found</h4>
                                <p class="text-muted">Try adjusting your search criteria or browsing all businesses.</p>
                                <a href="{{ route('directory.index') }}" class="btn btn-primary">View All Businesses</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 