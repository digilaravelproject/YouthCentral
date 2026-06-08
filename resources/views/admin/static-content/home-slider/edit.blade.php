@extends('layouts.user_type.auth')

@section('title', 'Edit Homepage Slider Item')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Edit Slider Item: <span class="text-info">{{ $item->title }}</span></h6>
                    <a href="{{ route('admin.static-content.home-slider.index') }}" class="btn btn-sm btn-outline-secondary mb-0">
                        <i class="fas fa-arrow-left me-1"></i> Back to Slider
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

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-header bg-gradient-secondary">
                                    <h5 class="text-white mb-0">Current Preview</h5>
                                </div>
                                <div class="card-body text-center">
                                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="img-fluid rounded mb-3" style="max-height: 250px; width: 100%; object-fit: cover;">
                                    <div class="d-flex justify-content-center gap-2">
                                        <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $item->is_active ? 'Active' : 'Inactive' }}</span>
                                        @if($item->color)
                                            <span class="badge" style="background: {{ $item->color }}; color: #000;">{{ $item->color }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <form action="{{ route('admin.static-content.home-slider.update', $item) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label" for="title">Title</label>
                                        <input class="form-control" id="title" name="title" value="{{ old('title', $item->title) }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label" for="subtitle">Subtitle</label>
                                        <input class="form-control" id="subtitle" name="subtitle" value="{{ old('subtitle', $item->subtitle) }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label" for="icon_class">Icon Class</label>
                                        <input class="form-control" id="icon_class" name="icon_class" value="{{ old('icon_class', $item->icon_class) }}" placeholder="e.g. fi fi-rr-book-alt">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-control-label" for="color">Color</label>
                                        <input class="form-control" id="color" name="color" value="{{ old('color', $item->color) }}" placeholder="#FF6B6B">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-control-label" for="sort_order">Sort Order</label>
                                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $item->sort_order) }}" min="0" max="9999">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-control-label" for="link_url">Link URL</label>
                                    <input class="form-control" id="link_url" name="link_url" value="{{ old('link_url', $item->link_url) }}" placeholder="/search?query=music or /events">
                                </div>

                                <div class="mb-3">
                                    <label class="form-control-label" for="image">Banner Image (optional)</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/webp,image/jpeg,image/jpg,image/png">
                                    <small class="text-muted d-block mt-2">Max size: 5MB. If not selected, existing image stays.</small>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.static-content.home-slider.index') }}" class="btn btn-sm bg-gradient-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-sm bg-gradient-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

