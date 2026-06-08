@extends('layouts.user_type.auth')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Edit Business</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.businesses.update', $business) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_name" class="form-control-label">Business Name</label>
                                    <input type="text" class="form-control @error('business_name') is-invalid @enderror" 
                                           id="business_name" name="business_name" value="{{ old('business_name', $business->business_name) }}">
                                    @error('business_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-control-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $business->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-control-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $business->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="website" class="form-control-label">Website</label>
                                    <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                           id="website" name="website" value="{{ old('website', $business->website) }}">
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                        <div class="form-group">
                            <label for="street_address" class="form-control-label">Street Address</label>
                            <input type="text" class="form-control @error('street_address') is-invalid @enderror" 
                                   id="street_address" name="street_address" value="{{ old('street_address', $business->street_address) }}">
                            @error('street_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitude" class="form-control-label">Latitude</label>
                                    <input type="text" class="form-control @error('latitude') is-invalid @enderror" 
                                           id="latitude" name="latitude" value="{{ old('latitude', $business->latitude) }}">
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitude" class="form-control-label">Longitude</label>
                                    <input type="text" class="form-control @error('longitude') is-invalid @enderror" 
                                           id="longitude" name="longitude" value="{{ old('longitude', $business->longitude) }}">
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id" class="form-control-label">Category</label>
                                    <select class="form-control" id="category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $business->subcategory->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subcategory_id" class="form-control-label">Subcategory</label>
                                    <select class="form-control @error('subcategory_id') is-invalid @enderror" id="subcategory_id" name="subcategory_id">
                                        <option value="">Select Subcategory</option>
                                        @foreach($business->subcategory->category->subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}" {{ $business->subcategory_id == $subcategory->id ? 'selected' : '' }}>
                                                {{ $subcategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subcategory_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="state_id" class="form-control-label">State</label>
                                    <select class="form-control" id="state_id">
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ $business->area->city->state_id == $state->id ? 'selected' : '' }}>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city_id" class="form-control-label">City</label>
                                    <select class="form-control" id="city_id">
                                        <option value="">Select City</option>
                                        @foreach($business->area->city->state->cities as $city)
                                            <option value="{{ $city->id }}" {{ $business->area->city_id == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="area_id" class="form-control-label">Area</label>
                                    <select class="form-control @error('area_id') is-invalid @enderror" id="area_id" name="area_id">
                                        <option value="">Select Area</option>
                                        @foreach($business->area->city->areas as $area)
                                            <option value="{{ $area->id }}" {{ $business->area_id == $area->id ? 'selected' : '' }}>
                                                {{ $area->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('area_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="claimed_by" class="form-control-label">Claimed By (Vendor)</label>
                                    <select class="form-control @error('claimed_by') is-invalid @enderror" id="claimed_by" name="claimed_by">
                                        <option value="">Not Claimed</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" {{ old('claimed_by', $business->claimed_by) == $vendor->id ? 'selected' : '' }}>
                                                {{ $vendor->name }} ({{ $vendor->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('claimed_by')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-control-label">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="active" {{ old('status', $business->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $business->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="pending" {{ old('status', $business->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description" class="form-control-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $business->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <hr class="horizontal dark mt-4">
                        
                        <h6 class="text-uppercase text-sm">Business Hours</h6>
                        <div class="row">
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                @php
                                    $openField = $day . '_open';
                                    $closeField = $day . '_close';
                                    $closedField = $day . '_closed';
                                @endphp
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label text-capitalize">{{ $day }}</label>
                                        <div class="d-flex align-items-center">
                                            <input type="time" class="form-control me-2 @error($openField) is-invalid @enderror" 
                                                   name="{{ $openField }}" 
                                                   value="{{ old($openField, optional($business->$openField)->format('H:i')) ?? '09:30' }}"
                                                   {{ old($closedField, $business->$closedField) ? 'disabled' : '' }}>
                                            <span class="mx-2">to</span>
                                            <input type="time" class="form-control me-2 @error($closeField) is-invalid @enderror" 
                                                   name="{{ $closeField }}" 
                                                   value="{{ old($closeField, optional($business->$closeField)->format('H:i')) ?? '18:30' }}"
                                                   {{ old($closedField, $business->$closedField) ? 'disabled' : '' }}>
                                            <div class="form-check">
                                                <input class="form-check-input business-hour-closed-checkbox @error($closedField) is-invalid @enderror" 
                                                       type="checkbox" 
                                                       name="{{ $closedField }}" 
                                                       value="1" 
                                                       id="{{ $closedField }}" 
                                                       {{ old($closedField, $business->$closedField) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="{{ $closedField }}">
                                                    Closed
                                                </label>
                                            </div>
                                        </div>
                                        @error($openField) <div class="text-danger text-xs d-block">{{ $message }}</div> @enderror
                                        @error($closeField) <div class="text-danger text-xs d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <hr class="horizontal dark mt-4">
                        
                        {{-- Logo & Gallery Section --}}
                        <h6 class="text-uppercase text-sm">Logo & Gallery</h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo" class="form-control-label">Business Logo</label>
                                    @if($business->logo_path)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $business->logo_path) }}" alt="Current Logo" class="img-thumbnail" style="max-width: 150px;">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="delete_logo" id="delete_logo" value="1">
                                                <label class="form-check-label" for="delete_logo">Delete current logo</label>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/webp">
                                    <small class="text-muted">Recommended size: 200x200px. Max 1MB.</small>
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gallery_images" class="form-control-label">Add Gallery Images</label>
                                    <input type="file" class="form-control @error('gallery_images.*') is-invalid @enderror" id="gallery_images" name="gallery_images[]" multiple accept="image/webp">
                                    <small class="text-muted">You can select multiple images. Max 2MB per image.</small>
                                    @error('gallery_images')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    @error('gallery_images.*')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Display Current Gallery Images --}}
                        @if($business->images && $business->images->count() > 0)
                            <h6 class="text-uppercase text-sm">Current Gallery Images</h6>
                            <div class="row mb-4">
                                @foreach($business->images as $image)
                                    <div class="col-md-3 col-sm-4 mb-3">
                                        <div class="card">
                                            <img src="{{ asset('storage/' . $image->path) }}" class="card-img-top" alt="Gallery Image" style="height: 150px; object-fit: cover;">
                                            <div class="card-body p-2 text-center">
                                                @if($image->is_primary)
                                                    <span class="badge bg-gradient-success mb-2">Primary Image</span>
                                                @else
                                                    <form action="#" method="POST" class="d-inline mb-2">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-primary">Set as Primary</button>
                                                    </form>
                                                @endif
                                                <form action="#" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn bg-gradient-primary">Save Changes</button>
                            <button type="button" class="btn bg-gradient-secondary ms-2" onclick="history.back()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const logoInput = document.getElementById('logo');
    const galleryInput = document.getElementById('gallery_images');

    const showError = (input, message) => {
        input.classList.add('is-invalid');
        let errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback d-block';
        errorDiv.innerText = message;
        input.parentNode.appendChild(errorDiv);
    };

    const clearErrors = (input) => {
        input.classList.remove('is-invalid');
        let existingErrors = input.parentNode.querySelectorAll('.invalid-feedback');
        existingErrors.forEach(el => el.remove());
    };

    const validateFile = (file, maxSizeMB) => {
        const isWebP = file.type === 'image/webp';
        const isUnderSize = file.size <= maxSizeMB * 1024 * 1024;
        return {
            valid: isWebP && isUnderSize,
            typeValid: isWebP,
            sizeValid: isUnderSize
        };
    };

    if (logoInput) {
        logoInput.addEventListener('change', function () {
            clearErrors(logoInput);
            if (logoInput.files.length > 0) {
                const file = logoInput.files[0];
                const { valid, typeValid, sizeValid } = validateFile(file, 1);

                if (!valid) {
                    if (!typeValid) {
                        showError(logoInput, 'Only .webp format is allowed for the logo.');
                    } else if (!sizeValid) {
                        showError(logoInput, 'Logo must be less than 1MB.');
                    }
                    logoInput.value = ''; // Reset invalid file
                }
            }
        });
    }

    if (galleryInput) {
        galleryInput.addEventListener('change', function () {
            clearErrors(galleryInput);
            for (const file of galleryInput.files) {
                const { valid, typeValid, sizeValid } = validateFile(file, 2);

                if (!valid) {
                    if (!typeValid) {
                        showError(galleryInput, 'Only .webp format is allowed for gallery images.');
                    } else if (!sizeValid) {
                        showError(galleryInput, 'Each gallery image must be less than 2MB.');
                    }
                    galleryInput.value = ''; // Reset all files if any invalid
                    break;
                }
            }
        });
    }
});
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // AJAX setup for categories, cities, etc.
        const categoryDropdown = document.getElementById('category_id');
        const subcategoryDropdown = document.getElementById('subcategory_id');
        const stateDropdown = document.getElementById('state_id');
        const cityDropdown = document.getElementById('city_id');
        const areaDropdown = document.getElementById('area_id');
        
        // Category -> Subcategory dependency
        categoryDropdown.addEventListener('change', function() {
            const categoryId = this.value;
            if (categoryId) {
                // Clear and set loading state
                subcategoryDropdown.innerHTML = '<option value="">Loading subcategories...</option>';
                
                fetch(`/admin/businesses/subcategories?category_id=${categoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate subcategories
                        subcategoryDropdown.innerHTML = '<option value="">Select Subcategory</option>';
                        data.forEach(subcategory => {
                            const option = document.createElement('option');
                            option.value = subcategory.id;
                            option.textContent = subcategory.name;
                            subcategoryDropdown.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading subcategories:', error);
                        subcategoryDropdown.innerHTML = '<option value="">Error loading subcategories</option>';
                    });
            } else {
                subcategoryDropdown.innerHTML = '<option value="">Select Subcategory</option>';
            }
        });
        
        // State -> City dependency
        stateDropdown.addEventListener('change', function() {
            const stateId = this.value;
            if (stateId) {
                // Clear city and area dropdowns
                cityDropdown.innerHTML = '<option value="">Loading cities...</option>';
                areaDropdown.innerHTML = '<option value="">Select Area</option>';
                
                fetch(`/admin/businesses/cities?state_id=${stateId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate cities
                        cityDropdown.innerHTML = '<option value="">Select City</option>';
                        data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.id;
                            option.textContent = city.name;
                            cityDropdown.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading cities:', error);
                        cityDropdown.innerHTML = '<option value="">Error loading cities</option>';
                    });
            } else {
                cityDropdown.innerHTML = '<option value="">Select City</option>';
                areaDropdown.innerHTML = '<option value="">Select Area</option>';
            }
        });
        
        // City -> Area dependency
        cityDropdown.addEventListener('change', function() {
            const cityId = this.value;
            if (cityId) {
                // Clear and set loading state
                areaDropdown.innerHTML = '<option value="">Loading areas...</option>';
                
                fetch(`/admin/businesses/areas?city_id=${cityId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate areas
                        areaDropdown.innerHTML = '<option value="">Select Area</option>';
                        data.forEach(area => {
                            const option = document.createElement('option');
                            option.value = area.id;
                            option.textContent = area.name;
                            areaDropdown.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading areas:', error);
                        areaDropdown.innerHTML = '<option value="">Error loading areas</option>';
                    });
            } else {
                areaDropdown.innerHTML = '<option value="">Select Area</option>';
            }
        });
    });
</script>
@endpush 