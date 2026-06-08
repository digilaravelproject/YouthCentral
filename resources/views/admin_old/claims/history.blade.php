@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Processed Business Claims</h6>
                    <div>
                        <a href="{{ route('admin.claims.index') }}" class="btn btn-primary btn-sm me-2">View Pending Claims</a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Business</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Claimed By</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Processed By</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Processed Date</th>
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
                                                <p class="text-xs text-secondary mb-0">{{ $claim->business->subcategory->name }}</p>
                                            </div>
                                        </div>
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
                                        @if($claim->status == 'approved')
                                            <span class="badge badge-sm bg-gradient-success">Approved</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $claim->admin->name }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs text-secondary mb-0">{{ $claim->processed_at->format('M d, Y H:i') }}</p>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('admin.claims.show', $claim->id) }}" class="btn btn-link text-info px-1 mb-0" data-toggle="tooltip" title="View details">
                                            <i class="fas fa-eye text-info me-2"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p>No processed business claims found.</p>
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