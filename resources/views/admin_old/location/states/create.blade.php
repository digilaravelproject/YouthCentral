@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Add New State</h6>
                </div>
                <div class="card-body px-4 pt-0 pb-2">
                    <form action="{{ route('admin.states.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="form-control-label">State Name</label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.states.index') }}" class="btn btn-light me-3">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create State</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 