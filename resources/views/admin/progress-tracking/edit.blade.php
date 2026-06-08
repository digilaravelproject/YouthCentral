@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5 class="mb-0">Edit Progress Activity Config</h5>
                    <p class="text-sm mb-0 text-muted">Update targets or weight values for the configured activity rule.</p>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger text-white alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('admin.progress-tracking.update', $progressActivity->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label class="form-control-label text-secondary">Student Activity Type</label>
                            <input type="text" class="form-control bg-light" value="{{ $activityName }}" readonly disabled>
                            <span class="text-xs text-secondary mt-1 d-block">The activity type is locked and cannot be changed. Delete and create a new rule if you need a different activity type.</span>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <div class="form-group">
                                    <label for="percentage" class="form-control-label">Percentage Weight (%) <span class="text-danger">*</span></label>
                                    <input type="number" id="percentage" name="percentage" class="form-control" min="1" placeholder="e.g. 20" value="{{ old('percentage', $progressActivity->percentage) }}" required>
                                    <span class="text-xs text-secondary mt-1 d-block">Contribution this activity makes to their overall progress.</span>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-12 mb-3">
                                <div class="form-group">
                                    <label for="max_limit" class="form-control-label">Limit / Target Action Count <span class="text-danger">*</span></label>
                                    <input type="number" id="max_limit" name="max_limit" class="form-control" min="1" placeholder="e.g. 5" value="{{ old('max_limit', $progressActivity->max_limit) }}" required>
                                    <span class="text-xs text-secondary mt-1 d-block">The maximum number of times the student can perform this action. Reaching this limit unlocks 100% of this activity's weight, and blocks them from performing it further.</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.progress-tracking.index') }}" class="btn btn-light me-2 mb-0">Cancel</a>
                            <button type="submit" class="btn btn-primary mb-0">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
