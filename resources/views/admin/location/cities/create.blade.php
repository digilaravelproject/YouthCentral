@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Add New City</h6>
                </div>
                <div class="card-body px-4 pt-0 pb-2">
                    <form action="{{ route('admin.cities.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="state_id" class="form-control-label">State</label>
                            <select class="form-control @error('state_id') is-invalid @enderror" id="state_id" name="state_id" required>
                                <option value="">Select a state</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('state_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="name" class="form-control-label">City Name</label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.cities.index') }}" class="btn btn-light me-3">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create City</button>
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
        if (window.jQuery && jQuery().select2) {
            $('#state_id').select2({
                theme: 'bootstrap-5',
                width: '100%',
                sorter: function(data) {
                    var term = $('.select2-search__field').val();
                    if (term && term.trim() !== '') {
                        term = term.trim().toLowerCase();
                        return data.sort(function(a, b) {
                            var aText = (a.text || '').toLowerCase();
                            var bText = (b.text || '').toLowerCase();
                            var aStarts = aText.indexOf(term) === 0;
                            var bStarts = bText.indexOf(term) === 0;
                            if (aStarts && !bStarts) return -1;
                            if (!aStarts && bStarts) return 1;
                            return aText.localeCompare(bText);
                        });
                    }
                    return data;
                }
            });
        }
    });
</script>
@endpush
@endsection 