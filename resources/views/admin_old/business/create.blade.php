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
                                    <input class="form-control" type="text" id="street_address" name="street_address" value="{{ old('street_address') }}" required>
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
                                    <input class="form-control" type="text" id="latitude" name="latitude" value="{{ old('latitude') }}">
                                    @error('latitude')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitude" class="form-control-label">Longitude</label>
                                    <input class="form-control" type="text" id="longitude" name="longitude" value="{{ old('longitude') }}">
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
        if(categoryId) {
            $.ajax({
                url: '{{ route("admin.businesses.subcategories") }}',
                type: 'GET',
                data: { category_id: categoryId },
                success: function(data) {
                    $('#subcategory_id').empty();
                    $('#subcategory_id').append('<option value="">Select Subcategory</option>');
                    $.each(data, function(key, value) {
                        $('#subcategory_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        } else {
            $('#subcategory_id').empty();
            $('#subcategory_id').append('<option value="">Select Subcategory</option>');
        }
    });
    
    // State -> City -> Area dependency
    $('#state_id').change(function() {
        var stateId = $(this).val();
        if(stateId) {
            $.ajax({
                url: '{{ route("admin.businesses.cities") }}',
                type: 'GET',
                data: { state_id: stateId },
                success: function(data) {
                    $('#city_id').empty();
                    $('#city_id').append('<option value="">Select a city</option>');
                    $.each(data, function(key, value) {
                        $('#city_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                    $('#area_id').empty();
                    $('#area_id').append('<option value="">Select an area</option>');
                }
            });
        } else {
            $('#city_id').empty();
            $('#city_id').append('<option value="">Select a city</option>');
            $('#area_id').empty();
            $('#area_id').append('<option value="">Select an area</option>');
        }
    });

    $('#city_id').change(function() {
        var cityId = $(this).val();
        if(cityId) {
            $.ajax({
                url: '{{ route("admin.businesses.areas") }}',
                type: 'GET',
                data: { city_id: cityId },
                success: function(data) {
                    $('#area_id').empty();
                    $('#area_id').append('<option value="">Select an area</option>');
                    $.each(data, function(key, value) {
                        $('#area_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        } else {
            $('#area_id').empty();
            $('#area_id').append('<option value="">Select an area</option>');
        }
    });
</script>
@endpush

@endsection 