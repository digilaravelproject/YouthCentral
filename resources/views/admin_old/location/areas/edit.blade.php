@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Edit Area</h6>
                </div>
                <div class="card-body px-4 pt-0 pb-2">
                    <form action="{{ route('admin.areas.update', $area) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="city_id" class="form-control-label">City</label>
                            <select class="form-control @error('city_id') is-invalid @enderror" id="city_id" name="city_id" required>
                                <option value="">Select a city</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ (old('city_id', $area->city_id) == $city->id) ? 'selected' : '' }}>
                                        {{ $city->name }} ({{ $city->state->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('city_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="name" class="form-control-label">Area Name</label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ old('name', $area->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.areas.index') }}" class="btn btn-light me-3">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Area</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 