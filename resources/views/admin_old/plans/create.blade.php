@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Create New Plan</h6>
                        <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary btn-sm">Back to Plans</a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger text-white">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.plans.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Plan Name <span class="text-danger">*</span></label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price" class="form-control-label">Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input class="form-control @error('price') is-invalid @enderror" type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price') }}" required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duration_type" class="form-control-label">Duration Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('duration_type') is-invalid @enderror" id="duration_type" name="duration_type" required>
                                        <option value="monthly" {{ old('duration_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="yearly" {{ old('duration_type') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                        <option value="one-time" {{ old('duration_type') == 'one-time' ? 'selected' : '' }}>One Time</option>
                                    </select>
                                    @error('duration_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duration_value" class="form-control-label">Duration Value <span class="text-danger">*</span></label>
                                    <input class="form-control @error('duration_value') is-invalid @enderror" type="number" min="1" id="duration_value" name="duration_value" value="{{ old('duration_value', 1) }}" required>
                                    <small class="form-text text-muted">Number of months/years (not applicable for one-time)</small>
                                    @error('duration_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_businesses" class="form-control-label">Max Businesses <span class="text-danger">*</span></label>
                                    <input class="form-control @error('max_businesses') is-invalid @enderror" type="number" min="1" id="max_businesses" name="max_businesses" value="{{ old('max_businesses', 1) }}" required>
                                    <small class="form-text text-muted">Maximum number of businesses allowed</small>
                                    @error('max_businesses')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_images" class="form-control-label">Max Images Per Business <span class="text-danger">*</span></label>
                                    <input class="form-control @error('max_images') is-invalid @enderror" type="number" min="1" id="max_images" name="max_images" value="{{ old('max_images', 5) }}" required>
                                    <small class="form-text text-muted">Maximum images allowed per business</small>
                                    @error('max_images')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="featured_listing" name="featured_listing" value="1" {{ old('featured_listing') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="featured_listing">Featured Listing</label>
                                    <small class="d-block text-muted">Businesses will appear at the top of search results</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                    <small class="d-block text-muted">Only active plans are visible to users</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-control-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="priority" class="form-control-label">Display Priority <span class="text-danger">*</span></label>
                            <input class="form-control @error('priority') is-invalid @enderror" type="number" min="1" id="priority" name="priority" value="{{ old('priority', 10) }}" required>
                            <small class="form-text text-muted">Lower numbers will display first</small>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Create Plan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const durationType = document.getElementById('duration_type');
        const durationValue = document.getElementById('duration_value');
        
        // Toggle duration value field based on duration type
        function toggleDurationValue() {
            if (durationType.value === 'one-time') {
                durationValue.disabled = true;
                durationValue.value = 1;
            } else {
                durationValue.disabled = false;
            }
        }
        
        durationType.addEventListener('change', toggleDurationValue);
        
        // Initialize on page load
        toggleDurationValue();
    });
</script>
@endpush 