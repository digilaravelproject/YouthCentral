@extends('layouts.user_type.auth')

@section('title', 'Create Event')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Create General Event</h6>
                    <a href="{{ route('vendor.events.index') }}" class="btn btn-sm btn-outline-secondary mb-0">
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

                    <form action="{{ route('vendor.events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="form-control-label">Event Title</label>
                                    <input type="text" class="form-control form-control-sm" id="title" name="title" value="{{ old('title') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="venue" class="form-control-label">Venue</label>
                                    <input type="text" class="form-control form-control-sm" id="venue" name="venue" value="{{ old('venue') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state_id" class="form-control-label">State</label>
                                    <select class="form-control @error('state_id') is-invalid @enderror" id="state_id" name="state_id">
                                        <option value="">Select a state</option>
                                        @foreach(\App\Models\State::orderBy('name')->get() as $state)
                                            <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('state_id') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city_id" class="form-control-label">City</label>
                                    <select class="form-control @error('city_id') is-invalid @enderror" id="city_id" name="city_id" required>
                                        <option value="">Select State First</option>
                                        @if(old('state_id'))
                                            @foreach(\App\Models\City::where('state_id', old('state_id'))->orderBy('name')->get() as $city)
                                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('city_id') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="area_id" class="form-control-label">Area</label>
                                    <select class="form-control @error('area_id') is-invalid @enderror" id="area_id" name="area_id" required>
                                        <option value="">Select City First</option>
                                        @if(old('city_id'))
                                            @foreach(\App\Models\Area::where('city_id', old('city_id'))->orderBy('name')->get() as $area)
                                                <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                                    {{ $area->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('area_id') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date" class="form-control-label">Start Date & Time</label>
                                    <input type="datetime-local" class="form-control form-control-sm" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- GPS Coordinates Section -->
                        @include('partials.event-map')
                        <!-- End GPS Coordinates Section -->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date" class="form-control-label">End Date & Time</label>
                                    <input type="datetime-local" class="form-control form-control-sm" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category" class="form-control-label">Event Category</label>
                                    <input type="text" class="form-control form-control-sm" id="category" name="category_display" value="General Event" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="seat_limit" class="form-control-label">Seat Limit</label>
                                    <input type="number" class="form-control form-control-sm" id="seat_limit" name="seat_limit" value="{{ old('seat_limit') }}" min="1" placeholder="Optional">
                                </div>
                            </div>
                            <div class="col-md-6">
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
                            <textarea class="form-control form-control-sm" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="banner_images" class="form-control-label">Event Images</label>
                            <input type="file" class="form-control form-control-sm" id="banner_images" name="banner_images[]" 
                                   accept="image/*" multiple required>
                            <small class="form-text text-muted">
                                You can select multiple images. The first image will be set as the primary banner.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="featured_banner" class="form-control-label">Featured Banner</label>
                            <input type="file" class="form-control form-control-sm" id="featured_banner" name="featured_banner" 
                                   accept="image/*">
                            <small class="form-text text-muted">
                                This image will be displayed prominently on the event details page. Supported formats: WEBP, JPEG, JPG, PNG. Max size: 5MB.
                            </small>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <a href="{{ route('vendor.events.index') }}" class="btn btn-sm bg-gradient-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-sm bg-gradient-primary">Submit for Approval</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/location-dropdown.js') }}"></script>
{{-- The event-map partial handles its own scripts. We only need non-map scripts here. --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountInput = document.getElementById('registration_amount');
        const registrationNote = document.getElementById('registration_note');

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
@endpush 