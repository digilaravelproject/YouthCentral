@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Business</h5>
                    <a href="{{ route('vendor.businesses.show', $business->id) }}" class="btn btn-sm bg-gradient-primary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Business
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success text-white">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger text-white">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('vendor.businesses.update', $business->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <h6 class="text-uppercase text-sm">Business Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_name" class="form-control-label">Business Name</label>
                                    <input class="form-control" type="text" id="business_name" name="business_name" value="{{ old('business_name', $business->business_name) }}" required>
                                    @error('business_name')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="phone" class="form-control-label">Phone Number</label>
                                    <input class="form-control" type="text" id="phone" name="phone" value="{{ old('phone', $business->phone) }}" required>
                                    @error('phone')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="whatsapp_number" class="form-control-label">WhatsApp Number</label>
                                    <input class="form-control" type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $business->whatsapp_number) }}">
                                    @error('whatsapp_number')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-control-label">Email Address</label>
                                    <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $business->email) }}">
                                    @error('email')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="website" class="form-control-label">Website</label>
                                    <input class="form-control" type="url" id="website" name="website" value="{{ old('website', $business->website) }}">
                                    @error('website')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="text-uppercase text-sm mt-3">Social Media Links</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="facebook_link" class="form-control-label">
                                        <i class="fab fa-facebook text-primary me-1"></i> Facebook Link
                                    </label>
                                    <input class="form-control" type="url" id="facebook_link" name="facebook_link" value="{{ old('facebook_link', $business->facebook_link) }}">
                                    @error('facebook_link')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="instagram_link" class="form-control-label">
                                        <i class="fab fa-instagram text-danger me-1"></i> Instagram Link
                                    </label>
                                    <input class="form-control" type="url" id="instagram_link" name="instagram_link" value="{{ old('instagram_link', $business->instagram_link) }}">
                                    @error('instagram_link')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="twitter_link" class="form-control-label">
                                        <i class="fab fa-twitter text-info me-1"></i> Twitter Link
                                    </label>
                                    <input class="form-control" type="url" id="twitter_link" name="twitter_link" value="{{ old('twitter_link', $business->twitter_link) }}">
                                    @error('twitter_link')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pinterest_link" class="form-control-label">
                                        <i class="fab fa-pinterest text-danger me-1"></i> Pinterest Link
                                    </label>
                                    <input class="form-control" type="url" id="pinterest_link" name="pinterest_link" value="{{ old('pinterest_link', $business->pinterest_link) }}">
                                    @error('pinterest_link')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <hr class="horizontal dark mt-4">
                        
                        <h6 class="text-uppercase text-sm">Category & Subcategory</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id" class="form-control-label">Category</label>
                                    <select class="form-control" id="category_id" name="category_id">
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ $business->subcategory->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subcategory_id" class="form-control-label">Subcategory</label>
                                    <select class="form-control" id="subcategory_id" name="subcategory_id" required>
                                        @foreach($categories->first(function($cat) use ($business) {
                                            return $cat->id == $business->subcategory->category_id;
                                        })->subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}" 
                                                {{ $business->subcategory_id == $subcategory->id ? 'selected' : '' }}>
                                                {{ $subcategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subcategory_id')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="text-uppercase text-sm mt-3">Location Information</h6>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="street_address" class="form-control-label">Street Address</label>
                                    <div class="input-group position-relative">
                                        <input class="form-control" type="text" id="street_address" name="street_address" value="{{ old('street_address', $business->street_address) }}" required autocomplete="off">
                                        <div id="address-suggestions" class="list-group position-absolute" style="z-index:1050; width:100%; display:none;"></div>
                                    </div>
                                    @error('street_address')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="state_id" class="form-control-label">State</label>
                                    <select class="form-control" id="state_id" name="state_id">
                                        <option value="">Select a state</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" 
                                                {{ $business->area->city->state_id == $state->id ? 'selected' : '' }}>
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
                                        @foreach($states->first(function($s) use ($business) {
                                            return $s->id == $business->area->city->state_id;
                                        })->cities as $city)
                                            <option value="{{ $city->id }}" 
                                                {{ $business->area->city_id == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="area_id" class="form-control-label">Area</label>
                                    <select class="form-control" id="area_id" name="area_id" required>
                                        @foreach($states->first(function($s) use ($business) {
                                            return $s->id == $business->area->city->state_id;
                                        })->cities->first(function($c) use ($business) {
                                            return $c->id == $business->area->city_id;
                                        })->areas as $area)
                                            <option value="{{ $area->id }}" 
                                                {{ $business->area_id == $area->id ? 'selected' : '' }}>
                                                {{ $area->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('area_id')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitude" class="form-control-label">Latitude</label>
                                    <input class="form-control" type="text" id="latitude" name="latitude" value="{{ old('latitude', $business->latitude) }}" required readonly>
                                    @error('latitude')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitude" class="form-control-label">Longitude</label>
                                    <input class="form-control" type="text" id="longitude" name="longitude" value="{{ old('longitude', $business->longitude) }}" required readonly>
                                    @error('longitude')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <hr class="horizontal dark mt-4">
                        
                        <h6 class="text-uppercase text-sm">Business Description</h6>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="form-control-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $business->description) }}</textarea>
                                    @error('description')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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
                                                   value="{{ old($openField, $business->$openField ? \Carbon\Carbon::parse($business->$openField)->format('H:i') : '09:30') }}"
                                                   {{ old($closedField, $business->$closedField) ? 'disabled' : '' }}>
                                            <span class="mx-2">to</span>
                                            <input type="time" class="form-control me-2 @error($closeField) is-invalid @enderror" 
                                                   name="{{ $closeField }}" 
                                                   value="{{ old($closeField, $business->$closeField ? \Carbon\Carbon::parse($business->$closeField)->format('H:i') : '18:30') }}"
                                                   {{ old($closedField, $business->$closedField) ? 'disabled' : '' }}>
                                            <div class="form-check form-switch ps-0">
                                                <input class="form-check-input ms-auto @error($closedField) is-invalid @enderror business-hour-closed-checkbox" 
                                                       type="checkbox" 
                                                       name="{{ $closedField }}" 
                                                       value="1" 
                                                       id="{{ $closedField }}" 
                                                       {{ old($closedField, $business->$closedField) ? 'checked' : '' }}>
                                                <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="{{ $closedField }}">Closed</label>
                                            </div>
                                        </div>
                                        @error($openField) <div class="text-danger text-xs d-block">{{ $message }}</div> @enderror
                                        @error($closeField) <div class="text-danger text-xs d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <hr class="horizontal dark mt-4">
                        
                        <h6 class="text-uppercase text-sm">Business Images</h6>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="business_images" class="form-control-label">Manage Images</label>
                                    
                                    <!-- Current Images Display -->
                                    @if($business->images && count($business->images) > 0)
                                        <div class="row mb-3">
                                            @foreach($business->images as $image)
                                                <div class="col-md-4 col-sm-6 mb-3">
                                                    <div class="card">
                                                        <img src="{{ asset('storage/' . $image->path) }}" class="card-img-top" alt="Business Image" style="height: 160px; object-fit: cover;">
                                                        <div class="card-body p-2">
                                                            <div class="d-flex justify-content-between">
                                                                @if(!$image->is_primary)
                                                                <form action="{{ route('vendor.businesses.images.primary', $image->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                                        <i class="fas fa-star"></i> Set Primary
                                                                    </button>
                                                                </form>
                                                                @else
                                                                <span class="badge bg-gradient-success">Primary</span>
                                                                @endif
                                                                
                                                                <form action="{{ route('vendor.businesses.images.delete', $image->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted mb-3">No images uploaded yet.</p>
                                    @endif
                                    
                                    <!-- Upload New Image -->
                                    <div class="mt-2">
                                        <form action="{{ route('vendor.businesses.images.upload', $business->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="input-group">
                                                <input type="file" class="form-control" name="image" accept="image/*" required>
                                                <button class="btn btn-primary" type="submit">Upload Image</button>
                                            </div>
                                            <small class="text-muted">Max file size: 2MB. Allowed formats: jpg, jpeg, png</small>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('vendor.businesses.show', $business->id) }}" class="btn btn-light m-0">Cancel</a>
                            <button type="submit" class="btn bg-gradient-primary m-0 ms-2">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logo & Gallery Section -->
<div class="card mt-4">
    <div class="card-header">
        <h4>Logo & Gallery</h4>
    </div>
    <div class="card-body">
        <!-- Logo Section -->
        <div class="form-group">
            <label>Business Logo (Recommended size: 200x200px, Max: 1MB)</label>
            <div class="mb-3">
                @if($business->logo_path)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $business->logo_path) }}" alt="Business Logo" class="img-thumbnail" style="max-width: 200px;">
                </div>
                @endif
                
                <form action="{{ route('vendor.businesses.update', $business->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="input-group">
                        <input type="file" name="logo" id="logo" class="form-control" accept="image/jpeg,image/png,image/jpg">
                        <button type="submit" class="btn btn-primary">Upload Logo</button>
                    </div>
                    <small class="text-muted">Max file size: 1MB. Allowed formats: jpg, jpeg, png</small>
                    @error('logo')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </form>
                
                @if($business->logo_path)
                <form action="{{ route('vendor.businesses.update', $business->id) }}" method="POST" class="mt-2">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="delete_logo" value="1">
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Delete Logo
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>    document.addEventListener('DOMContentLoaded', function() {
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

        // Category-Subcategory dropdown dependency
        const categorySelect = document.getElementById('category_id');
        const subcategorySelect = document.getElementById('subcategory_id');
        
        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            
            // Clear the subcategory dropdown
            subcategorySelect.innerHTML = '';
            
            if (categoryId) {
                showLoader('subcategory_id', 'subcategory-loader');
                fetch(`/vendor/get-subcategories?category_id=${categoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(subcategory => {
                            const option = document.createElement('option');
                            option.value = subcategory.id;
                            option.textContent = subcategory.name;
                            subcategorySelect.appendChild(option);
                        });
                    })
                    .finally(() => {
                        hideLoader('subcategory_id', 'subcategory-loader');
                    });
            }
        });
        
        // State-City-Area dropdown dependencies
        const stateSelect = document.getElementById('state_id');
        const citySelect = document.getElementById('city_id');
        const areaSelect = document.getElementById('area_id');
        
        stateSelect.addEventListener('change', function() {
            const stateId = this.value;
            
            // Clear the city and area dropdowns
            citySelect.innerHTML = '';
            areaSelect.innerHTML = '';
            
            if (stateId) {
                showLoader('city_id', 'city-loader');
                fetch(`/vendor/get-cities?state_id=${stateId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.id;
                            option.textContent = city.name;
                            citySelect.appendChild(option);
                        });
                        
                        // Trigger city change to load areas for the first city
                        if (data.length > 0) {
                            citySelect.value = data[0].id;
                            citySelect.dispatchEvent(new Event('change'));
                        }
                    })
                    .finally(() => {
                        hideLoader('city_id', 'city-loader');
                    });
            }
        });
        
        citySelect.addEventListener('change', function() {
            const cityId = this.value;
            
            // Clear the area dropdown
            areaSelect.innerHTML = '';
            
            if (cityId) {
                showLoader('area_id', 'area-loader');
                fetch(`/vendor/get-areas?city_id=${cityId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(area => {
                            const option = document.createElement('option');
                            option.value = area.id;
                            option.textContent = area.name;
                            areaSelect.appendChild(option);
                        });
                    })
                    .finally(() => {
                        hideLoader('area_id', 'area-loader');
                    });
            }
        });

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
        // --- Address Autocomplete (Nominatim) for Edit Form ---
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
                    a.dataset.details = JSON.stringify(item.address || {});
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
                            sel.dispatchEvent(new Event('change'));
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
                        // search nominatim for the place string
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
