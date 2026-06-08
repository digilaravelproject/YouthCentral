@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Pending Business Listings</h6>
                    {{-- Add filter/search form if needed --}}
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Business Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Location</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Submitted By</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Submitted On</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($businesses as $business)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            {{-- Optional: Display logo --}}
                                            {{-- <div>
                                                @if($business->logo_path)
                                                <img src="{{ Storage::url($business->logo_path) }}" class="avatar avatar-sm me-3" alt="{{ $business->business_name }} logo">
                                                @else
                                                <img src="{{ asset('assets/img/default-business.png') }}" class="avatar avatar-sm me-3" alt="Default logo">
                                                @endif
                                            </div> --}}
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $business->business_name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $business->phone }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $business->subcategory->category->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $business->subcategory->name ?? 'N/A' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $business->area->city->name ?? 'N/A' }}, {{ $business->area->city->state->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $business->area->name ?? 'N/A' }}</p>
                                    </td>
                                     <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $business->owner->name ?? 'Admin Created' }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $business->owner->email ?? '-' }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $business->created_at->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <form action="{{ route('admin.businesses.approve', $business->id) }}" method="POST" class="d-inline"> @csrf @method('PATCH') <button type="submit" class="btn btn-link text-success p-0 m-0"><i class="fas fa-check text-xs"></i> Approve</button> </form>
                                        <form action="{{ route('admin.businesses.reject', $business->id) }}" method="POST" class="d-inline"> @csrf @method('PATCH') <button type="submit" class="btn btn-link text-danger p-0 m-0 ms-2"><i class="fas fa-times text-xs"></i> Reject</button> </form>
                                        <a href="{{ route('admin.businesses.show', $business->id) }}" class="btn btn-link text-secondary p-0 m-0 ms-2" data-bs-toggle="tooltip" data-bs-original-title="View Details"><i class="fas fa-eye text-xs"></i> View</a>
                                        {{-- Maybe add edit link too? --}}
                                        {{-- <a href="{{ route('admin.businesses.edit', $business->id) }}" class="btn btn-link text-dark p-0 m-0 ms-2" data-bs-toggle="tooltip" data-bs-original-title="Edit Business"><i class="fas fa-pencil-alt text-dark text-xs"></i> Edit</a> --}}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No pending businesses found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        {{ $businesses->links() }} {{-- Pagination --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
 
 
 
 
 