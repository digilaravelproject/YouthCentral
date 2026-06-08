@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Pending Business Claims</h6>
                    <div>
                        <a href="{{ route('admin.claims.history') }}" class="btn btn-info btn-sm me-2">View Processed Claims</a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Business</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category & Location</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Claimed By</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Submitted</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($claims as $claim)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $claim->business->business_name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $claim->business->phone }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $claim->business->subcategory->name }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $claim->business->area->name }}, {{ $claim->business->area->city->name }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $claim->vendor->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $claim->vendor->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs text-secondary mb-0">{{ $claim->created_at->format('M d, Y H:i') }}</p>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('admin.claims.show', $claim->id) }}" class="btn btn-link text-info px-1 mb-0" data-toggle="tooltip" title="Review claim">
                                            <i class="fas fa-eye text-info me-2"></i> Review
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p>No pending business claims at the moment.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $claims->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 