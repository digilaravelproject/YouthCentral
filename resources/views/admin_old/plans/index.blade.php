@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Plans Management</h6>
                        <a href="{{ route('admin.plans.create') }}" class="btn btn-primary btn-sm ms-auto">Add New Plan</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <!-- Stats Cards -->
                    <div class="row px-4 mt-3">
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Plans</p>
                                                <h5 class="font-weight-bolder">{{ count($plans) }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                                <i class="ni ni-collection text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Active Plans</p>
                                                <h5 class="font-weight-bolder">{{ $activePlansCount }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                                <i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Subscribers</p>
                                                <h5 class="font-weight-bolder">{{ $subscribersCount }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                                <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(session('success'))
                        <div class="alert alert-success mx-4 mt-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger mx-4 mt-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="table-responsive p-0 mx-4 mt-4">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Priority</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Plan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Price</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Duration</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Features</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subscribers</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plans as $plan)
                                <tr>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 ps-3">{{ $plan->priority }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $plan->name }}</h6>
                                                @if($plan->description)
                                                    <p class="text-xs text-secondary mb-0">{{ Str::limit($plan->description, 50) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">${{ number_format($plan->price, 2) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            @if($plan->duration_type == 'one-time')
                                                One-time
                                            @else
                                                {{ $plan->duration_value }} {{ Str::ucfirst($plan->duration_type) }}
                                            @endif
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-xs text-secondary mb-0">Max Businesses: {{ $plan->max_businesses }}</p>
                                        <p class="text-xs text-secondary mb-0">Max Images: {{ $plan->max_images }}</p>
                                        <p class="text-xs text-secondary mb-0">
                                            Featured: 
                                            @if($plan->featured_listing)
                                                <span class="badge badge-sm bg-gradient-success">Yes</span>
                                            @else
                                                <span class="badge badge-sm bg-gradient-secondary">No</span>
                                            @endif
                                        </p>
                                    </td>
                                    <td class="align-middle">
                                        @if($plan->is_active)
                                            <span class="badge badge-sm bg-gradient-success">Active</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.plans.subscriptions', $plan->id) }}" class="text-xs font-weight-bold mb-0">
                                            {{ $plan->subscriptions()->where('status', 'active')->count() }} Active
                                        </a>
                                    </td>
                                    <td class="align-middle">
                                        <div class="ms-auto">
                                            <a href="{{ route('admin.plans.edit', $plan->id) }}" class="btn btn-link text-dark px-1 mb-0">
                                                <i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>
                                            </a>
                                            
                                            <form action="{{ route('admin.plans.toggle-status', $plan->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-link text-dark px-1 mb-0" title="{{ $plan->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas fa-power-off text-{{ $plan->is_active ? 'danger' : 'success' }} me-2" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger text-gradient px-1 mb-0" title="Delete">
                                                    <i class="far fa-trash-alt me-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No plans found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirm delete
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (confirm('Are you sure you want to delete this plan? All associated subscriptions will be cancelled.')) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush 