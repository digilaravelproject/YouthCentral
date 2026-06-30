@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Add New Business Listing</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.businesses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <h6 class="text-uppercase text-sm">Business Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_name" class="form-control-label">Business Name</label>
                                    <input class="form-control" type="text" id="business_name" name="business_name" value="{{ old('business_name') }}" required>
                                    @error('business_name')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="phone" class="form-control-label">Phone Number</label>
                                    <input class="form-control" type="text" id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="whatsapp_number" class="form-control-label">WhatsApp Number</label>
                                    <input class="form-control" type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number') }}">
                                    @error('whatsapp_number')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-control-label">Email Address</label>
                                    <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="website" class="form-control-label">Website</label>
                                    <input class="form-control" type="url" id="website" name="website" value="{{ old('website') }}">
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
                                    <input class="form-control" type="url" id="facebook_link" name="facebook_link" value="{{ old('facebook_link') }}">
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
                                    <input class="form-control" type="url" id="instagram_link" name="instagram_link" value="{{ old('instagram_link') }}">
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
                                    <input class="form-control" type="url" id="twitter_link" name="twitter_link" value="{{ old('twitter_link') }}">
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
                                    <input class="form-control" type="url" id="pinterest_link" name="pinterest_link" value="{{ old('pinterest_link') }}">
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
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subcategory_id" class="form-control-label">Subcategory</label>
                                    <select class="form-control" id="subcategory_id" name="subcategory_id" required>
                                        <option value="">Select Subcategory</option>
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
                                    <div class="position-relative">
                                        <input class="form-control" type="text" id="street_address" name="street_address" value="{{ old('street_address') }}" required autocomplete="off">
                                        <div id="address-suggestions" class="list-group position-absolute" style="z-index:1050; width:100%; display:none; top: 100%; left: 0;"></div>
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
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city_id" class="form-control-label">City</label>
                                    <select class="form-control" id="city_id" name="city_id">
                                        <option value="">Select a city</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="area_id" class="form-control-label">Area</label>
                                    <select class="form-control" id="area_id" name="area_id" required>
                                        <option value="">Select an area</option>
                                    </select>
                                    @error('area_id')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitude" class="form-control-label">Latitude</label>
                                    <input class="form-control" type="text" id="latitude" name="latitude" value="{{ old('latitude') }}" required>
                                    @error('latitude')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitude" class="form-control-label">Longitude</label>
                                    <input class="form-control" type="text" id="longitude" name="longitude" value="{{ old('longitude') }}" required>
                                    @error('longitude')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="zipcode" class="form-control-label">Zipcode / PIN Code</label>
                                    <input class="form-control" type="text" id="zipcode" name="zipcode" value="{{ old('zipcode') }}" placeholder="e.g. 400001">
                                    <small class="text-muted">Enter the business zipcode/PIN code for better location-based search results</small>
                                    @error('zipcode')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="text-uppercase text-sm mt-3">Business Description</h6>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="form-control-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="text-uppercase text-sm mt-3">Business Hours</h6>
                        <div class="row">
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label text-capitalize">{{ $day }}</label>
                                        <div class="d-flex align-items-center">
                                            <input type="time" class="form-control me-2 @error($day.'_open') is-invalid @enderror" name="{{ $day }}_open" value="{{ old($day.'_open', '09:30') }}">
                                            <span class="mx-2">to</span>
                                            <input type="time" class="form-control me-2 @error($day.'_close') is-invalid @enderror" name="{{ $day }}_close" value="{{ old($day.'_close', '18:30') }}">
                                            <div class="form-check">
                                                <input class="form-check-input @error($day.'_closed') is-invalid @enderror" type="checkbox" name="{{ $day }}_closed" value="1" id="{{ $day }}_closed" {{ old($day.'_closed') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="{{ $day }}_closed">
                                                    Closed
                                                </label>
                                            </div>
                                        </div>
                                        @error($day.'_open') <div class="text-danger text-xs d-block">{{ $message }}</div> @enderror
                                        @error($day.'_close') <div class="text-danger text-xs d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <hr class="horizontal dark mt-4">

                        {{-- Assign Vendor (Optional) --}}
                        <h6 class="text-uppercase text-sm">Assign Vendor (Optional)</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="claimed_by" class="form-control-label">Assign to Vendor</label>
                                    <select class="form-control" id="claimed_by" name="claimed_by">
                                        <option value="">-- None (Unclaimed) --</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" {{ old('claimed_by') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }} ({{ $vendor->email }})</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Select a vendor to assign this business listing to them.</small>
                                    @error('claimed_by')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <hr class="horizontal dark mt-4">
                        
                        {{-- Logo & Gallery --}}
                        <h6 class="text-uppercase text-sm">Logo & Gallery</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo" class="form-control-label">Business Logo</label>
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/webp">
                                    <small class="text-muted">Recommended size: 200x200px. Max 1MB.</small>
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gallery_images" class="form-control-label">Gallery Images</label>
                                    <input type="file" class="form-control @error('gallery_images.*') is-invalid @enderror" id="gallery_images" name="gallery_images[]" multiple accept="image/webp">
                                    <small class="text-muted">You can select multiple images. Max 2MB per image.</small>
                                    @error('gallery_images') {{-- General array error --}}
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    @error('gallery_images.*') {{-- Error for individual files --}}
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="horizontal dark mt-4">

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn bg-gradient-primary">Create Business</button>
                            <button type="button" class="btn bg-gradient-secondary ms-2" onclick="history.back()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
            document.addEventListener('DOMContentLoaded', function() {
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
                    let nextSiblings = input.parentNode.querySelectorAll('.invalid-feedback');
                    nextSiblings.forEach(el => el.remove());
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

                logoInput.addEventListener('change', function() {
                    clearErrors(logoInput);

                    if (logoInput.files.length > 0) {
                        const file = logoInput.files[0];
                        const {
                            valid,
                            typeValid,
                            sizeValid
                        } = validateFile(file, 1);

                        if (!valid) {
                            if (!typeValid) {
                                showError(logoInput, 'Only .webp images are allowed.');
                            } else if (!sizeValid) {
                                showError(logoInput, 'Logo must be less than 1MB.');
                            }
                            logoInput.value = ''; // Clear the invalid file
                        }
                    }
                });

                galleryInput.addEventListener('change', function() {
                    clearErrors(galleryInput);

                    for (const file of galleryInput.files) {
                        const {
                            valid,
                            typeValid,
                            sizeValid
                        } = validateFile(file, 2);

                        if (!valid) {
                            if (!typeValid) {
                                showError(galleryInput, 'Only .webp images are allowed.');
                            } else if (!sizeValid) {
                                showError(galleryInput, `Each gallery image must be less than 2MB.`);
                            }
                            galleryInput.value = ''; // Clear all files
                            break;
                        }
                    }
                });
            });
        </script>

<script>
    // Category -> Subcategory dependency
    $('#category_id').change(function() {
        var categoryId = $(this).val();
        var subcategoryDropdown = $('#subcategory_id');
        
        $('.subcategory-loader').remove();
        subcategoryDropdown.empty().append('<option value="">Loading...</option>');

        if(categoryId) {
            $('label[for="subcategory_id"]').append('<span class="subcategory-loader ms-2 text-primary" style="font-size: 0.8rem;"><i class="fas fa-spinner fa-spin"></i> Loading...</span>');
            subcategoryDropdown.prop('disabled', true);

            $.ajax({
                url: '{{ route("admin.businesses.subcategories") }}',
                type: 'GET',
                data: { category_id: categoryId },
                success: function(data) {
                    subcategoryDropdown.empty();
                    subcategoryDropdown.append('<option value="">Select Subcategory</option>');
                    $.each(data, function(key, value) {
                        subcategoryDropdown.append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                },
                complete: function() {
                    $('.subcategory-loader').remove();
                    subcategoryDropdown.prop('disabled', false);
                }
            });
        } else {
            subcategoryDropdown.empty();
            subcategoryDropdown.append('<option value="">Select Subcategory</option>');
        }
    });
    
    // State -> City -> Area dependency - handled by location-dropdown.js
</script>
<script src="{{ asset('assets/js/location-dropdown.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

    // --- Geocoding & Coordinate Toggling Logic ---
    function checkCoordinates() {
        // keep enabled and editable
    }

    // Initialize coordinates state on load
    checkCoordinates();
    
    // Also check whenever inputs are changed manually
    $('#latitude, #longitude').on('input', function() {
        checkCoordinates();
    });

    // --- Address Autocomplete (Nominatim) ---
    (function() {
        let debounceTimer = null;
        const $input = $('#street_address');
        const $suggestions = $('#address-suggestions');

        function closeSuggestions() {
            $suggestions.hide().empty();
        }

        function renderSuggestions(items) {
            $suggestions.empty();
            if (!items || items.length === 0) {
                closeSuggestions();
                return;
            }
            items.forEach(function(item) {
                const $a = $('<a href="#" class="list-group-item list-group-item-action"></a>');
                $a.text(item.display_name);
                $a.data('details', item);
                $a.on('click', function(e) {
                    e.preventDefault();
                    const d = $(this).data('details');
                    $input.val(d.display_name);
                    $('#latitude').val(d.lat);
                    $('#longitude').val(d.lon);
                    checkCoordinates();
                    tryPopulateLocationFromNominatim(d.address || {});
                    closeSuggestions();
                });
                $suggestions.append($a);
            });
            $suggestions.show();
        }

        function tryPopulateLocationFromNominatim(address) {
            function matchAndSelect(selectId, candidates) {
                if (!candidates || candidates.length === 0) return;
                const $sel = $(selectId);
                if ($sel.length === 0) return;
                const opts = $sel.find('option');
                const lowerCandidates = candidates.map(c=> (c||'').toLowerCase());
                let matched = null;
                opts.each(function() {
                    const txt = ($(this).text()||'').toLowerCase();
                    if (lowerCandidates.indexOf(txt) !== -1) {
                        matched = $(this).val();
                        return false;
                    }
                });
                if (matched) {
                    $sel.val(matched).trigger('change');
                }
            }

            matchAndSelect('#state_id', [address.state, address.state_district, address.region]);
            matchAndSelect('#city_id', [address.city, address.town, address.village, address.county]);
            matchAndSelect('#area_id', [address.suburb, address.neighbourhood, address.city_district]);
        }

        $input.on('input paste', function() {
            const val = $(this).val().trim();
            clearTimeout(debounceTimer);
            if (!val) { closeSuggestions(); return; }

            // detect google maps url or coords
            const gm = detectGoogleMapsCoordinates(val);
            if (gm) {
                if (gm.lat && gm.lon) {
                    $('#latitude').val(gm.lat);
                    $('#longitude').val(gm.lon);
                    if (gm.clean) $input.val(gm.clean);
                    checkCoordinates();
                    return;
                }
            }

            debounceTimer = setTimeout(function() {
                $.ajax({
                    url: 'https://nominatim.openstreetmap.org/search',
                    data: { q: val, format: 'json', addressdetails: 1, limit: 5 },
                    headers: { 'Accept-Language': 'en' },
                    success: function(res) {
                        renderSuggestions(res);
                    },
                    error: function() {
                        closeSuggestions();
                    }
                });
            }, 300);
        });

        function detectGoogleMapsCoordinates(text) {
            try {
                if (/maps.app.goo.gl/.test(text)) {
                    alert('Short Google Maps links (maps.app.goo.gl) cannot be resolved automatically. Please open the short link in your browser, copy the full URL (which contains @latitude,longitude) and paste it here.');
                    return null;
                }
                const atMatch = text.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/);
                if (atMatch) return { lat: atMatch[1], lon: atMatch[2], clean: null };
                const placeMatch = text.match(/\/place\/([^\/]+)/);
                if (placeMatch) {
                    const name = decodeURIComponent(placeMatch[1].replace(/\+/g,' '));
                    var out = null;
                    $.ajax({
                        url: 'https://nominatim.openstreetmap.org/search',
                        data: { q: name, format: 'json', limit: 1 },
                        async: false,
                        success: function(res) { if (res && res.length) out = { lat: res[0].lat, lon: res[0].lon, clean: res[0].display_name }; }
                    });
                    return out;
                }
                const plainCoords = text.match(/(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/);
                if (plainCoords) return { lat: plainCoords[1], lon: plainCoords[2], clean: null };
            } catch(e) { console.error(e); }
            return null;
        }

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#street_address, #address-suggestions').length) {
                closeSuggestions();
            }
        });
    })();
});
</script>
@endpush

@endsection 