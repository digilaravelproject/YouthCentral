@extends('layouts.user_type.auth')

@section('title', 'Homepage Slider')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Homepage Slider Items</h6>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.static-content.home-slider.create') }}" class="btn btn-sm bg-gradient-primary">
                            <i class="fas fa-plus me-1"></i> Add Slider Item
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary mb-0" style="height: 10% !important;">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        These items render in the homepage carousel (JD Slider). Only <strong>Active</strong> items show on the homepage.
                    </p>

                    <div class="table-responsive">
                        <table class="table table-hover align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Order</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Preview</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Title</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subtitle</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Link</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td class="align-middle">
                                            <span class="text-sm">{{ $item->sort_order }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="rounded" style="width: 90px; height: 52px; object-fit: cover;">
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex flex-column">
                                                <span class="text-sm font-weight-bold">{{ $item->title }}</span>
                                                @if($item->icon_class)
                                                    <span class="text-xs text-muted">Icon: {{ $item->icon_class }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-sm">{{ $item->subtitle }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-xs text-muted">{{ $item->link_url }}</span>
                                        </td>
                                        <td class="align-middle">
                                            @if($item->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                                <form action="{{ route('admin.static-content.home-slider.toggle', $item) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $item->is_active ? 'btn-warning' : 'btn-success' }}">
                                                        {{ $item->is_active ? 'Disable' : 'Enable' }}
                                                    </button>
                                                </form>

                                                <a href="{{ route('admin.static-content.home-slider.edit', $item) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>

                                                <form action="{{ route('admin.static-content.home-slider.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this slider item?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <p class="text-secondary mb-0">No slider items found.</p>
                                        </td>
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

