@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Register for {{ $event->title }}</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.events.register', $event) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="age_category" class="form-label">School Type</label>
                                <select class="form-select" id="age_category" name="age_category" required>
                                    <option value="">Select School Type</option>
                                    <option value="Government School" {{ old('age_category') == 'Government School' ? 'selected' : '' }}>Government School</option>
                                    <option value="Private School" {{ old('age_category') == 'Private School' ? 'selected' : '' }}>Private School</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mobile" class="form-label">Mobile Number</label>
                                <input type="tel" class="form-control" id="mobile" name="mobile" value="{{ old('mobile') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="school" class="form-label">School</label>
                                <input type="text" class="form-control" id="school" name="school" value="{{ old('school') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="grade" class="form-label">Grade</label>
                                <input type="text" class="form-control" id="grade" name="grade" value="{{ old('grade') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="sport_event" class="form-label">Sport/Event</label>
                            <input type="text" class="form-control" id="sport_event" name="sport_event" value="{{ old('sport_event') }}" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Proceed to Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 