@extends('layouts.user_type.auth')

@section('title', 'Add Homepage Slider Item')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Add Slider Item</h6>
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

                    <form action="{{ route('admin.static-content.home-slider.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-control-label" for="title">Title</label>
                                <input class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-control-label" for="subtitle">Subtitle</label>
                                <input class="form-control" id="subtitle" name="subtitle" value="{{ old('subtitle') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-control-label" for="icon_class">Icon Class</label>
                                <input class="form-control" id="icon_class" name="icon_class" value="{{ old('icon_class') }}" placeholder="e.g. fi fi-rr-book-alt">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-control-label" for="color">Color</label>
                                <input class="form-control" id="color" name="color" value="{{ old('color') }}" placeholder="#FF6B6B">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-control-label" for="sort_order">Sort Order</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $nextOrder ?? 1) }}" min="0" max="9999">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-control-label" for="link_url">Link URL</label>
                            <input class="form-control" id="link_url" name="link_url" value="{{ old('link_url') }}" placeholder="/search?query=music or /events">
                        </div>

                        <div class="mb-3">
                            <label class="form-control-label" for="image">Banner Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/webp,image/jpeg,image/jpg,image/png">
                            <small class="text-muted d-block mt-2">Max size: 5MB. If no image is uploaded, a default background may be used.</small>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.static-content.home-slider.index') }}" class="btn btn-sm bg-gradient-secondary">Cancel</a>
                            <button type="submit" class="btn btn-sm bg-gradient-primary">Create Slider Item</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

