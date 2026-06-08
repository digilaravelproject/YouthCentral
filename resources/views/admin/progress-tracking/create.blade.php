@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5 class="mb-0">Configure New Progress Activity</h5>
                    <p class="text-sm mb-0 text-muted">Set up a tracking target and percentage weight for a student activity.</p>
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

                    <form action="{{ route('admin.progress-tracking.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="activity_type" class="form-control-label">Select Student Activity Type <span class="text-danger">*</span></label>
                            @if(empty($availableTypes))
                                <div class="alert alert-warning text-white" role="alert">
                                    All supported activities have already been configured. You can edit existing activities on the main dashboard page.
                                </div>
                                <select id="activity_type" name="activity_type" class="form-control" disabled>
                                    <option value="">No available activity types</option>
                                </select>
                            @else
                                <select id="activity_type" name="activity_type" class="form-control" required>
                                    <option value="">-- Choose Activity --</option>
                                    @foreach($availableTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('activity_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <div class="form-group">
                                    <label for="percentage" class="form-control-label">Percentage Weight (%) <span class="text-danger">*</span></label>
                                    <input type="number" id="percentage" name="percentage" class="form-control" min="1" placeholder="e.g. 20" value="{{ old('percentage') }}" required>
                                    <span class="text-xs text-secondary mt-1 d-block">Contribution this activity makes to their overall progress.</span>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-12 mb-3">
                                <div class="form-group">
                                    <label for="max_limit" class="form-control-label">Limit / Target Action Count <span class="text-danger">*</span></label>
                                    <input type="number" id="max_limit" name="max_limit" class="form-control" min="1" placeholder="e.g. 5" value="{{ old('max_limit') }}" required>
                                    <span class="text-xs text-secondary mt-1 d-block">The maximum number of times the student can perform this action. Reaching this limit unlocks 100% of this activity's weight, and blocks them from performing it further.</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.progress-tracking.index') }}" class="btn btn-light me-2 mb-0">Cancel</a>
                            <button type="submit" class="btn btn-primary mb-0" {{ empty($availableTypes) ? 'disabled' : '' }}>Create Rule</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
