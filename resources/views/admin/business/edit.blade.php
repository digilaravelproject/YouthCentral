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
                                    <div class="input-group position-relative">
                                        <input type="text" class="form-control @error('street_address') is-invalid @enderror" 
                                               id="street_address" name="street_address" value="{{ old('street_address', $business->street_address) }}" required autocomplete="off">
                                        <div id="address-suggestions" class="list-group position-absolute" style="z-index:1050; width:100%; display:none;"></div>
                                    </div>
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
                                           id="latitude" name="latitude" value="{{ old('latitude', $business->latitude) }}" required readonly>
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitude" class="form-control-label">Longitude</label>
                                    <input type="text" class="form-control @error('longitude') is-invalid @enderror" 
                                           id="longitude" name="longitude" value="{{ old('longitude', $business->longitude) }}" required readonly>
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
                                    <select class="form-control" id="state_id" name="state_id">
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
                                    <select class="form-control" id="city_id" name="city_id">
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
        
        // Setup visual loaders helper
        function showLoader(elementId, loaderClass) {
            const existing = document.querySelector('.' + loaderClass);
            if (existing) existing.remove();
            
            const label = document.querySelector(`label[for="${elementId}"]`);
            if (label) {
                const loader = document.createElement('span');
                loader.className = loaderClass + ' ms-2 text-primary';
                loader.style.fontSize = '0.8rem';
                loader.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                label.appendChild(loader);
            }
            
            const select = document.getElementById(elementId);
            if (select) select.disabled = true;
        }
        
        function hideLoader(elementId, loaderClass) {
            const loader = document.querySelector('.' + loaderClass);
            if (loader) loader.remove();
            
            const select = document.getElementById(elementId);
            if (select) select.disabled = false;
        }

        // Category -> Subcategory dependency
        categoryDropdown.addEventListener('change', function() {
            const categoryId = this.value;
            if (categoryId) {
                // Clear and set loading state
                subcategoryDropdown.innerHTML = '<option value="">Loading...</option>';
                
                showLoader('subcategory_id', 'subcategory-loader');
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
                        subcategoryDropdown.innerHTML = '<option value="">Error loading</option>';
                    })
                    .finally(() => {
                        hideLoader('subcategory_id', 'subcategory-loader');
                    });
            } else {
                subcategoryDropdown.innerHTML = '<option value="">Select Subcategory</option>';
            }
        });
        
        // State -> City -> Area dependency - handled by location-dropdown.js
    });
</script>
<script src="{{ asset('assets/js/location-dropdown.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- Geocoding & Coordinate Toggling Logic ---
        function checkCoordinates() {
            const latField = document.getElementById('latitude');
            const lngField = document.getElementById('longitude');
            const lat = latField.value;
            const lng = lngField.value;
            
            if (lat && lng && lat.trim() !== '' && lng.trim() !== '') {
                latField.readOnly = false;
                latField.style.backgroundColor = '';
                latField.style.cursor = '';
                lngField.readOnly = false;
                lngField.style.backgroundColor = '';
                lngField.style.cursor = '';
            } else {
                latField.readOnly = true;
                latField.style.backgroundColor = '#e9ecef';
                latField.style.cursor = 'not-allowed';
                lngField.readOnly = true;
                lngField.style.backgroundColor = '#e9ecef';
                lngField.style.cursor = 'not-allowed';
            }
        }

        // Initialize coordinates state on load
        checkCoordinates();

        // Also check whenever inputs are changed manually
        document.getElementById('latitude').addEventListener('input', checkCoordinates);
        document.getElementById('longitude').addEventListener('input', checkCoordinates);
        // --- Address Autocomplete (Nominatim) for Admin Edit Form ---
        (function() {
            const inputEl = document.getElementById('street_address');
            const suggestionsEl = document.getElementById('address-suggestions');
            let debounce = null;

            function hideSuggestions() {
                suggestionsEl.style.display = 'none';
                suggestionsEl.innerHTML = '';
            }

            function showSuggestions(items) {
                suggestionsEl.innerHTML = '';
                if (!items || items.length === 0) {
                    hideSuggestions();
                    return;
                }
                items.forEach(function(item) {
                    const a = document.createElement('a');
                    a.href = '#';
                    a.className = 'list-group-item list-group-item-action';
                    a.textContent = item.display_name;
                    a.dataset.lat = item.lat;
                    a.dataset.lon = item.lon;
                    a.addEventListener('click', function(e) {
                        e.preventDefault();
                        inputEl.value = item.display_name;
                        document.getElementById('latitude').value = item.lat;
                        document.getElementById('longitude').value = item.lon;
                        checkCoordinates();
                        tryPopulateLocationFromNominatim(item.address || {});
                        hideSuggestions();
                    });
                    suggestionsEl.appendChild(a);
                });
                suggestionsEl.style.display = 'block';
            }

            function tryPopulateLocationFromNominatim(address) {
                function matchAndSelect(selectId, candidates) {
                    if (!candidates || candidates.length === 0) return;
                    const sel = document.getElementById(selectId.substring(1));
                    if (!sel) return;
                    const lower = candidates.map(c => (c||'').toLowerCase());
                    for (let i=0;i<sel.options.length;i++) {
                        const txt = (sel.options[i].text||'').toLowerCase();
                        if (lower.indexOf(txt) !== -1) {
                            sel.value = sel.options[i].value;
                            if (window.jQuery) {
                                $(sel).trigger('change');
                            } else {
                                sel.dispatchEvent(new Event('change'));
                            }
                            return;
                        }
                    }
                }
                matchAndSelect('#state_id', [address.state, address.state_district, address.region]);
                matchAndSelect('#city_id', [address.city, address.town, address.village, address.county]);
                matchAndSelect('#area_id', [address.suburb, address.neighbourhood, address.city_district]);
            }

            function tryDetectGoogleMaps(text) {
                try {
                    if (/maps.app.goo.gl/.test(text)) {
                        alert('Short Google Maps links (maps.app.goo.gl) cannot be resolved automatically. Please open the short link in your browser, copy the full URL (which contains @latitude,longitude) and paste it here.');
                        return null;
                    }
                    const atMatch = text.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/);
                    if (atMatch) return { lat: atMatch[1], lon: atMatch[2], clean: null };
                    const placeMatch = text.match(/\/place\/([^\/]+)/);
                    if (placeMatch) return { place: decodeURIComponent(placeMatch[1].replace(/\+/g,' ')) };
                    const plainCoords = text.match(/(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/);
                    if (plainCoords) return { lat: plainCoords[1], lon: plainCoords[2], clean: null };
                } catch(e) { console.error(e); }
                return null;
            }

            inputEl.addEventListener('input', function() {
                const q = inputEl.value.trim();
                clearTimeout(debounce);
                if (!q) { hideSuggestions(); return; }

                const gm = tryDetectGoogleMaps(q);
                if (gm) {
                    if (gm.lat && gm.lon) {
                        document.getElementById('latitude').value = gm.lat;
                        document.getElementById('longitude').value = gm.lon;
                        checkCoordinates();
                        return;
                    }
                    if (gm.place) {
                        fetch('https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=5&q=' + encodeURIComponent(gm.place), { headers: { 'Accept-Language': 'en' } })
                        .then(r => r.json()).then(data => {
                            if (data && data.length > 0) {
                                showSuggestions(data);
                            } else {
                                hideSuggestions();
                            }
                        }).catch(() => hideSuggestions());
                        return;
                    }
                }

                debounce = setTimeout(function() {
                    fetch('https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=5&q=' + encodeURIComponent(q), {
                        headers: { 'Accept-Language': 'en' }
                    })
                    .then(r => r.json())
                    .then(data => showSuggestions(data))
                    .catch(() => hideSuggestions());
                }, 300);
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('#street_address') && !e.target.closest('#address-suggestions')) {
                    hideSuggestions();
                }
            });
        })();
    });
</script>
@endpush 