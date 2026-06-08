@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Available Businesses to Claim</h6>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="px-4 py-3">
                        <form action="{{ route('vendor.claims.index') }}" method="GET" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="search" placeholder="Search business name..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="subcategory" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach(App\Models\Subcategory::with('category')->get()->sortBy('category.name') as $subcategory)
                                        <option value="{{ $subcategory->id }}" {{ request('subcategory') == $subcategory->id ? 'selected' : '' }}>
                                            {{ $subcategory->category->name }} - {{ $subcategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="area" class="form-select">
                                    <option value="">All Areas</option>
                                    @foreach(App\Models\Area::with(['city.state'])->get()->sortBy('city.state.name') as $area)
                                        <option value="{{ $area->id }}" {{ request('area') == $area->id ? 'selected' : '' }}>
                                            {{ $area->city->state->name }} - {{ $area->city->name }} - {{ $area->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn bg-gradient-primary w-100 mb-0">Filter</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Business</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Location</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($businesses as $business)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $business->business_name }}</h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    @if($business->email)
                                                        <i class="fas fa-envelope"></i> {{ $business->email }}<br>
                                                    @endif
                                                    @if($business->phone)
                                                        <i class="fas fa-phone"></i> {{ $business->phone }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $business->subcategory->category->name }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $business->subcategory->name }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $business->area->city->state->name }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $business->area->city->name }} - {{ $business->area->name }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('vendor.claims.create', $business->id) }}" class="btn btn-sm bg-gradient-primary">
                                            Claim Business
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <p class="text-sm mb-0">No unclaimed businesses found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-4 pt-4">
                        {{ $businesses->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 