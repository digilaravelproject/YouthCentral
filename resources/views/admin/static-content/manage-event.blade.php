@extends('layouts.user_type.auth')

@section('title', 'Manage Event Featured Banner')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Manage Event Featured Banner</h6>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-outline-secondary mb-0">
                        <i class="fas fa-arrow-left me-1"></i> Back to Events
                    </a>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        This banner is displayed above the events listing page (at <a href="{{ route('events.index') }}" target="_blank">{{ route('events.index') }}</a>), replacing the default gray banner.
                    </p>

                    <div class="table-responsive">
                        <table class="table table-hover align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Banner</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($banner->image_path)
                                                <img src="{{ asset('storage/' . $banner->image_path) }}" alt="Events Listing Banner" class="rounded me-3" style="width: 120px; height: 70px; object-fit: cover;">
                                                <span class="text-sm">Events Listing Featured Banner</span>
                                            @else
                                                <div class="rounded me-3 d-flex align-items-center justify-content-center bg-light" style="width: 120px; height: 70px;">
                                                    <i class="fas fa-image text-muted fa-2x"></i>
                                                </div>
                                                <span class="text-sm text-muted">No banner uploaded</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        @if($banner->image_path)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-warning">Not Set</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('admin.static-content.edit-featured-banner') }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> Edit Banner
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
