@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">User Management</h5>
                        </div>
                    </div>
                </div>
                
                <div class="card-body px-0 pt-0 pb-2">
                    <!-- User Counts Summary Cards -->
                    <div class="row px-4 mt-3">
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Users</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $userCounts['total'] }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                                <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
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
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Admin Users</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $userCounts['admin'] }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                                <i class="ni ni-key-25 text-lg opacity-10" aria-hidden="true"></i>
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
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Regular Users</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $userCounts['user'] }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                                <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Vendors</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $userCounts['vendor'] }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                                <i class="ni ni-shop text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Search and Filter Controls -->
                    <div class="px-4 pt-4">
                        <form action="{{ route('admin.users.index') }}" method="GET" class="d-md-flex justify-content-between">
                            <div class="d-flex mb-3 mb-md-0">
                                <div class="me-2">
                                    <select name="role" class="form-select" onchange="this.form.submit()" style="    padding: .5rem 3rem .5rem 2.75rem;">
                                        <option value="">All User Types</option>
                                        <option value="admin" {{ isset($filters['role']) && $filters['role'] === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="user" {{ isset($filters['role']) && $filters['role'] === 'user' ? 'selected' : '' }}>User</option>
                                        <option value="vendor" {{ isset($filters['role']) && $filters['role'] === 'vendor' ? 'selected' : '' }}>Vendor</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="d-flex">
                                <input type="text" name="search" class="form-control" placeholder="Search by name, email, business..." value="{{ $filters['search'] ?? '' }}" style="width: 327px;">
                                <button type="submit" class="btn bg-gradient-primary ms-2" style="  margin-bottom: 0rem;">Search</button>
                                @if(isset($filters['role']) || isset($filters['search']))
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary ms-2">Clear</a>
                                @endif
                            </div>
                        </form>
                    </div>
                    
                    <!-- Users Table -->
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Business</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Registered</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div>
                                                <div class="avatar avatar-sm me-3 bg-gradient-{{ $user->role === 'admin' ? 'dark' : ($user->role === 'vendor' ? 'warning' : 'success') }} rounded-circle">
                                                    <span class="text-white text-uppercase">{{ substr($user->name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                                                @if($user->phone)
                                                    <p class="text-xs text-secondary mb-0">{{ $user->phone }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-gradient-{{ $user->role === 'admin' ? 'dark' : ($user->role === 'vendor' ? 'warning' : 'success') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->role === 'vendor')
                                            <span class="badge bg-gradient-{{ $user->status === 'approved' ? 'success' : ($user->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-gradient-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->business_name)
                                            <p class="text-xs font-weight-bold mb-0">{{ $user->business_name }}</p>
                                        @else
                                            <span class="text-xs text-secondary">Not a business</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <p class="text-xs font-weight-bold mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $user->created_at->diffForHumans() }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-link text-dark px-2 mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                            <i class="fas fa-eye text-dark me-2"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-link text-dark px-2 mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit User">
                                            <i class="fas fa-pencil-alt text-dark me-2"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger px-2 mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete User">
                                                <i class="fas fa-trash text-danger me-2"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-sm text-secondary mb-0">No users found matching your criteria.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Infinite scroll indicators will be added by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin-infinite-scroll.js') }}"></script>
@endpush 