@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Edit Category</h6>
                </div>
                <div class="card-body px-4 pt-0 pb-2">
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name" class="form-control-label">Category Name</label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="image" class="form-control-label">Category Image</label>
                            @if($category->image)
                                <div class="mb-2">
                                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="img-fluid" style="max-height: 100px;">
                                </div>
                            @endif
                            <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image" accept="image/webp">
                            <small class="form-text text-muted">Upload an image (WEBP) - Maximum size 2MB</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mt-3">
                            <x-icon-picker name="icon_class" :value="old('icon_class', $category->icon_class)" />
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-light me-3">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 