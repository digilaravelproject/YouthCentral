<form action="{{ route('events.book.process', $event) }}" method="POST" id="event-registration-form">
    @csrf
    <input type="hidden" name="event_id" value="{{ $event->id }}">
    <input type="hidden" name="other_event" value="{{ $event->id }}">

    <!-- First Name -->
    <div class="form-group mb-3">
        <label for="first_name" class="form-label">First Name *</label>
        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $user ? $user->name : '') }}" required>
        @error('first_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <!-- Middle Name -->
    <div class="form-group mb-3">
        <label for="middle_name" class="form-label">Middle Name</label>
        <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
        @error('middle_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <!-- Last Name -->
    <div class="form-group mb-3">
        <label for="last_name" class="form-label">Last Name *</label>
        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
        @error('last_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <!-- Email -->
    <div class="form-group mb-3">
        <label for="email" class="form-label">Email *</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user ? $user->email : '') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <!-- Mobile Number -->
    <div class="form-group mb-3">
        <label for="mobile" class="form-label">Mobile Number *</label>
        <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ old('mobile', $user ? $user->phone : '') }}" required>
        @error('mobile')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <!-- Date of Birth -->
    <div class="form-group mb-3">
        <label for="dob" class="form-label">Date of Birth *</label>
        <input type="date" class="form-control @error('dob') is-invalid @enderror" id="dob" name="dob" value="{{ old('dob') }}" required>
        @error('dob')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <!-- Address -->
    <div class="form-group mb-3">
        <label for="address" class="form-label">Address *</label>
        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    @if($event->category === 'yc')
        <!-- School Type (formerly Age Category) -->
        <div class="form-group mb-3">
            <label for="age_category" class="form-label">School Type *</label>
            <select class="form-control @error('age_category') is-invalid @enderror" id="age_category" name="age_category"
                required>
                <option value="">Select School Type</option>
                @foreach ($schoolTypes as $school)
                    <option value="{{ $school->id }}" data-price="{{ $school->price }}"
                        {{ $school->school_type === 'Private School' ? 'selected' : '' }}>
                        {{ $school->school_type }} (₹{{ $school->price }})
                    </option>
                @endforeach
            </select>
            @error('age_category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <!-- School/Institution -->
        <div class="form-group mb-3">
            <label for="school" class="form-label">School/Institution *</label>
            <input type="text" class="form-control @error('school') is-invalid @enderror" id="school" name="school" value="{{ old('school') }}" required>
            @error('school')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <!-- Grade/Class -->
        <div class="form-group mb-3">
            <label for="grade" class="form-label">Grade/Class *</label>
            <select class="form-control @error('grade') is-invalid @enderror" id="grade" name="grade" required>
                <option value="">Select Grade/Class</option>
                <option value="6th" {{ old('grade') == '5th' ? 'selected' : '' }}>5th</option>
                <option value="6th" {{ old('grade') == '6th' ? 'selected' : '' }}>6th</option>
                <option value="6th" {{ old('grade') == '7th' ? 'selected' : '' }}>7th</option>
                <option value="6th" {{ old('grade') == '8th' ? 'selected' : '' }}>8th</option>
                <option value="9th" {{ old('grade') == '9th' ? 'selected' : '' }}>9th</option>
            </select>
            @error('grade')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <!-- Sport/Event Participation -->
        <div class="form-group mb-3">
            <label for="sport_event" class="form-label">Sport/Event Participation *</label>
            <select class="form-control @error('sport_event') is-invalid @enderror" id="sport_event" name="sport_event" required>
                <option value="">Select Sport/Event</option>
                <option value="YC Events" {{ old('sport_event') == 'YC Events' ? 'selected' : '' }}>YC Events</option>
                {{-- <option value="Football" {{ old('sport_event') == 'Football' ? 'selected' : '' }}>Football</option>
                <option value="Basketball" {{ old('sport_event') == 'Basketball' ? 'selected' : '' }}>Basketball</option>
                <option value="Volleyball" {{ old('sport_event') == 'Volleyball' ? 'selected' : '' }}>Volleyball</option>
                <option value="Athletics" {{ old('sport_event') == 'Athletics' ? 'selected' : '' }}>Athletics</option>
                <option value="Swimming" {{ old('sport_event') == 'Swimming' ? 'selected' : '' }}>Swimming</option>
                <option value="Chess" {{ old('sport_event') == 'Chess' ? 'selected' : '' }}>Chess</option>
                <option value="Table Tennis" {{ old('sport_event') == 'Table Tennis' ? 'selected' : '' }}>Table Tennis</option>
                <option value="Badminton" {{ old('sport_event') == 'Badminton' ? 'selected' : '' }}>Badminton</option> --}}
            </select>
            @error('sport_event')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <?php /*@if($event->registration_amount > 0)
        <div class="alert alert-info mb-3">
            <i class="fas fa-info-circle me-2"></i> <strong>Note:</strong> Government school students get 20% cashback after verification of school ID or related document.
        </div>
        @endif */?>
        
        <!-- Parent/Guardian Consent for YC SPARK events -->
        <div class="form-group mb-3">
            <div class="card border">
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input @error('parent_consent') is-invalid @enderror" type="checkbox" id="parent_consent" name="parent_consent" required {{ old('parent_consent') ? 'checked' : '' }}>
                        <label class="form-check-label" for="parent_consent" style="font-size:12px;">
                            I am the parent/guardian of the student registering for YC SPARK 2027. I give my consent for their participation in this online event and agree that Youth Central may collect and securely use the required student information for registration and event communication.
                        </label>
                        @error('parent_consent')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input @error('terms_conditions') is-invalid @enderror" type="checkbox" id="terms_conditions" name="terms_conditions" required {{ old('terms_conditions') ? 'checked' : '' }}>
                        <label class="form-check-label" for="terms_conditions" style="font-size:12px;">
                            By registering, you acknowledge and agree to the above Terms & Conditions.
                            I have read and understood the <a href="{{ route('events.parent-guardian-consent') }}" target="_blank">Terms & Conditions of YC SPARK 2027</a> and agree on behalf of the student.
                        </label>
                        @error('terms_conditions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($event->category === 'general')
        <div class="form-group mb-3">
            <div class="card border">
                <div class="card-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input @error('terms_conditions') is-invalid @enderror" type="checkbox" id="terms_conditions" name="terms_conditions" required {{ old('terms_conditions') ? 'checked' : '' }}>
                        <label class="form-check-label" for="terms_conditions" style="font-size:12px;">
                            I understand that YouthCentral is only a listing platform and is not responsible or liable in any manner for my event. I accept full responsibility. <a href="{{ route('events.general-parent-guardian-consent') }}" target="_blank">Terms & Conditions</a>
                        </label>
                        @error('terms_conditions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <div class="form-group mt-4">
        @if($event->registration_amount > 0)
            <button type="submit" class="btn btn-primary w-100">Proceed to Payment</button>
        @else
            <button type="submit" class="btn btn-primary w-100">Book Now</button>
        @endif
    </div>
</form> 