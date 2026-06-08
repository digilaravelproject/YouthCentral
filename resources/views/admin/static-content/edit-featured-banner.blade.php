@extends('layouts.user_type.auth')

@section('title', 'Edit Event Featured Banner')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Edit Events Listing Featured Banner</h6>
                    <a href="{{ route('admin.static-content.manage-event') }}" class="btn btn-sm btn-outline-secondary mb-0">
                        <i class="fas fa-arrow-left me-1"></i> Back to Manage Banner
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

                    <p class="text-muted mb-4">
                        This banner will be displayed above the events listing page, replacing the default gray banner. Recommended resolution: 728x90 pixels or higher.
                    </p>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-header bg-gradient-secondary">
                                    <h5 class="text-white mb-0">Current Banner</h5>
                                </div>
                                <div class="card-body text-center">
                                    @if($banner->image_path)
                                        <img src="{{ asset('storage/' . $banner->image_path) }}" alt="Featured Banner" class="img-fluid rounded mb-3" style="max-height: 250px; width: 100%; object-fit: cover;">
                                        <p class="text-sm text-success mb-0"><i class="fas fa-check-circle"></i> Banner Uploaded</p>
                                    @else
                                        <div style="height: 250px; background-color: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                                            <p class="text-muted mb-0">No banner uploaded</p>
                                        </div>
                                        <p class="text-sm text-warning mb-0"><i class="fas fa-exclamation-circle"></i> No Banner Set</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card shadow-sm">
                                <div class="card-header bg-gradient-info">
                                    <h5 class="text-white mb-0">Upload Featured Banner</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.static-content.update-featured-banner') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group mb-3">
                                            <label for="featured_banner" class="form-control-label">Select Featured Banner Image</label>
                                            <input type="file" class="form-control @error('featured_banner') is-invalid @enderror" 
                                                   id="featured_banner" name="featured_banner" accept="image/webp,image/jpeg,image/jpg,image/png" required>
                                            <small class="form-text text-muted d-block mt-2">
                                                <i class="fas fa-info-circle"></i> Supported formats: WEBP, JPEG, JPG, PNG
                                            </small>
                                            <small class="form-text text-muted d-block">
                                                <i class="fas fa-lightbulb"></i> Recommended resolution: 728x90 pixels or higher
                                            </small>
                                            @error('featured_banner')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div id="preview" style="display: none; margin-top: 20px;">
                                            <label class="form-control-label">Preview</label>
                                            <img id="previewImage" src="" alt="Preview" class="img-fluid rounded" style="max-height: 300px; width: 100%; object-fit: cover;">
                                        </div>

                                        <div class="d-flex gap-2 mt-4">
                                            <button type="submit" class="btn btn-sm bg-gradient-primary">
                                                <i class="fas fa-save me-1"></i> Save Featured Banner
                                            </button>
                                            @if($banner->image_path)
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                                                    <i class="fas fa-trash me-1"></i> Delete Banner
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.static-content.manage-event') }}" class="btn btn-sm bg-gradient-secondary">
                                                <i class="fas fa-times me-1"></i> Cancel
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($banner->image_path)
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmLabel">Delete Featured Banner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the events listing featured banner?</p>
                <p class="text-danger mb-0"><i class="fas fa-warning"></i> The gray default banner will be shown until a new one is uploaded.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.static-content.delete-featured-banner') }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Banner</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('featured_banner');
    const preview = document.getElementById('preview');
    const previewImage = document.getElementById('previewImage');

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImage.src = event.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }
});
</script>
@endsection
