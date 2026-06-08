@extends('layouts.user_type.auth')

@section('title', 'Create Event')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Create Event</h6>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-outline-secondary mb-0">
                        <i class="fas fa-arrow-left me-1"></i> Back to Events
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

                    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="form-control-label">Event Title</label>
                                    <input type="text" class="form-control form-control-sm" id="title" name="title" 
                                           value="{{ old('title') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="venue" class="form-control-label">Venue</label>
                                    <input type="text" class="form-control form-control-sm" id="venue" name="venue" 
                                           value="{{ old('venue') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state_id" class="form-control-label">State</label>
                                    <select class="form-control" id="state_id" name="state_id">
                                        <option value="">Select State</option>
                                        @foreach(\App\Models\State::orderBy('name')->get() as $state)
                                            <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city_id" class="form-control-label">City</label>
                                    <select class="form-control" id="city_id" name="city_id" {{ old('state_id') ? '' : 'disabled' }}>
                                        <option value="">Select City</option>
                                        @if(old('state_id'))
                                            @foreach(\App\Models\City::where('state_id', old('state_id'))->orderBy('name')->get() as $city)
                                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="area_id" class="form-control-label">Area</label>
                                    <select class="form-control" id="area_id" name="area_id" {{ old('city_id') ? '' : 'disabled' }}>
                                        <option value="">Select Area</option>
                                        @if(old('city_id'))
                                            @foreach(\App\Models\Area::where('city_id', old('city_id'))->orderBy('name')->get() as $area)
                                                <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                                    {{ $area->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date" class="form-control-label">Start Date & Time</label>
                                    <input type="datetime-local" class="form-control form-control-sm" id="start_date" 
                                           name="start_date" value="{{ old('start_date') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- GPS Coordinates Section is handled by the partial -->
                        @include('partials.event-map')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date" class="form-control-label">End Date & Time</label>
                                    <input type="datetime-local" class="form-control form-control-sm" id="end_date" 
                                           name="end_date" value="{{ old('end_date') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category" class="form-control-label">Event Category</label>
                                    <select class="form-select form-select-sm" id="category" name="category" required>
                                        <option value="" disabled selected>Select Category</option>
                                        @foreach($categories as $key => $value)
                                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="seat_limit" class="form-control-label">Seat Limit</label>
                                    <input type="number" class="form-control form-control-sm" id="seat_limit" 
                                           name="seat_limit" value="{{ old('seat_limit') }}" min="1" placeholder="Optional">
                                </div>
                            </div>
                            <div class="col-md-6" id="registration_amount_group">
                                <div class="form-group">
                                    <label for="registration_amount" class="form-control-label">Registration Amount</label>
                                    <input type="number" class="form-control form-control-sm" id="registration_amount" 
                                           name="registration_amount" value="{{ old('registration_amount', 0) }}" 
                                           min="0" step="0.01">
                                    <!-- <small class="form-text text-muted d-block mt-2" id="registration_note" style="display: none;">
                                        <strong>Note:</strong> Registration fees for paid events are released 24–48 hours after event completion
                                    </small> -->
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-control-label">Description</label>
                            <textarea class="form-control form-control-sm" id="description" name="description" 
                                      rows="4" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="banner_images" class="form-control-label">Event Images</label>
                            <input type="file" class="form-control form-control-sm" id="banner_images" name="banner_images[]" 
                                   accept="image/webp" multiple required>
                            <small class="form-text text-muted">
                                You can select multiple images. The first image will be set as the primary banner.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="featured_banner" class="form-control-label">Featured Banner</label>
                            <input type="file" class="form-control form-control-sm" id="featured_banner" name="featured_banner" 
                                   accept="image/webp,image/jpeg,image/jpg,image/png">
                            <small class="form-text text-muted">
                                This image will be displayed prominently on the event details page. Supported formats: WEBP, JPEG, JPG, PNG. Max size: 5MB.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="gallery_images" class="form-control-label">Additional Gallery Images</label>
                            <input type="file" class="form-control form-control-sm" id="gallery_images" name="gallery_images[]" 
                                   accept="image/webp,image/jpeg,image/jpg,image/png" multiple>
                            <small class="form-text text-muted">Optional gallery images for the event details page. You can upload multiple images.</small>
                        </div>
                        <div id="gallery-preview" class="d-flex flex-wrap mt-2"></div>

                        <div class="form-group">
                            <label for="long_description" class="form-control-label">Long Description</label>
                            <textarea class="form-control form-control-sm" id="long_description" name="long_description" rows="6">{{ old('long_description') }}</textarea>
                            <small class="form-text text-muted">Use this for longer, rich content about the event.</small>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <a href="{{ route('admin.events.index') }}" class="btn btn-sm bg-gradient-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-sm bg-gradient-primary">Create Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- The event-map partial handles its own scripts. We only need non-map scripts here. --}}
<script src="{{ asset('assets/js/location-dropdown.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category');
        const amountGroup = document.getElementById('registration_amount_group');
        const amountInput = document.getElementById('registration_amount');
        const registrationNote = document.getElementById('registration_note');

        // function toggleAmountField() {
        //     if (categorySelect.value === 'general') {
        //         amountGroup.style.display = 'none';
        //         amountInput.required = false; 
        //         amountInput.value = null; // Clear the value
        //     } else {
        //         amountGroup.style.display = 'block';
        //         amountInput.required = true; 
        //     }
        // }

        // Initial check on page load
        // toggleAmountField();

        // Add event listener for changes
        // categorySelect.addEventListener('change', toggleAmountField);

        // Function to toggle registration note visibility
        function toggleRegistrationNote() {
            if (amountInput && registrationNote) {
                const amountValue = parseFloat(amountInput.value) || 0;
                if (amountValue > 0) {
                    registrationNote.style.display = 'block';
                } else {
                    registrationNote.style.display = 'none';
                }
            }
        }

        // Initial check on page load
        toggleRegistrationNote();

        // Add event listener for registration amount changes
        if (amountInput) {
            amountInput.addEventListener('input', toggleRegistrationNote);
            amountInput.addEventListener('change', toggleRegistrationNote);
        }
        
        // Add client-side validation for end date being after start date
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        
        if (endDateInput && startDateInput) {
             endDateInput.addEventListener('change', function() {
                if (startDateInput.value && endDateInput.value) {
                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);
                    
                    if (endDate <= startDate) {
                        alert('End date must be after start date');
                        endDateInput.value = '';
                    }
                }
            });
        }
    });
</script>

    <script>
{{-- Initialize CKEditor for long_description --}}
@include('partials.rich_text_editor')

        document.addEventListener('DOMContentLoaded', function() {
            const bannerInput = document.getElementById('banner_images');

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

            bannerInput.addEventListener('change', function() {
                clearErrors(bannerInput);

                for (const file of bannerInput.files) {
                    const {
                        valid,
                        typeValid,
                        sizeValid
                    } = validateFile(file, 2);

                    if (!valid) {
                        if (!typeValid) {
                            showError(bannerInput, 'Only .webp images are allowed for banner.');
                        } else if (!sizeValid) {
                            showError(bannerInput, 'Each banner image must be less than 2MB.');
                        }
                        bannerInput.value = ''; // Clear the invalid files
                        break;
                    }
                }
            });
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const preview = document.getElementById('gallery-preview');
        const input = document.getElementById('gallery_images');
        const removeContainer = document.getElementById('remove-gallery-inputs');
        let newFiles = [];

        function refreshPreview() {
            preview.innerHTML = '';
            newFiles.forEach((file, idx) => {
                const reader = new FileReader();
                reader.onload = e => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'position-relative me-2 mb-2';
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.height = '80px';
                    img.className = 'rounded';
                    const remove = document.createElement('span');
                    remove.innerHTML = '&times;';
                    remove.className = 'position-absolute top-0 start-100 translate-middle p-1 bg-danger text-white rounded-circle cursor-pointer';
                    remove.style.fontSize = '1rem';
                    remove.style.lineHeight = '1';
                    remove.style.width = '1.4rem';
                    remove.style.height = '1.4rem';
                    remove.addEventListener('click', () => {
                        newFiles.splice(idx, 1);
                        updateInputFiles();
                        refreshPreview();
                    });
                    wrapper.appendChild(img);
                    wrapper.appendChild(remove);
                    preview.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });
        }

        function updateInputFiles() {
            const data = new DataTransfer();
            newFiles.forEach(f => data.items.add(f));
            input.files = data.files;
        }

        if (input && preview) {
            input.addEventListener('change', function() {
                newFiles = Array.from(input.files);
                refreshPreview();
            });
        }

        // existing images removal (none on create but safe to keep)
        const existingGallery = document.getElementById('existing-gallery');
        if (existingGallery) {
            existingGallery.querySelectorAll('.remove-existing').forEach(btn => {
                btn.addEventListener('click', function() {
                    const wrapper = this.closest('.existing-img-wrapper');
                    const path = wrapper.getAttribute('data-path');
                    if (path && removeContainer) {
                        const hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = 'remove_gallery[]';
                        hidden.value = path;
                        removeContainer.appendChild(hidden);
                    }
                    wrapper.remove();
                });
            });
        }
    });
</script>
@endpush 