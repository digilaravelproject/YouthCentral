@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Available Businesses to Claim</h6>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="px-4 py-3">
                        <form action="{{ route('vendor.claims.index') }}" method="GET" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="search" placeholder="Search business name..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="subcategory" id="filter_subcategory" class="form-control select2">
                                    <option value="">All Categories</option>
                                    @foreach(App\Models\Subcategory::with('category')->get()->sortBy('category.name') as $subcategory)
                                        <option value="{{ $subcategory->id }}" {{ request('subcategory') == $subcategory->id ? 'selected' : '' }}>
                                            {{ $subcategory->category->name }} - {{ $subcategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="area" id="filter_area" class="form-control select2">
                                    <option value="">All Areas</option>
                                    @if($selectedArea)
                                        <option value="{{ $selectedArea->id }}" selected>
                                            {{ $selectedArea->name }} ({{ $selectedArea->city->name }}, {{ $selectedArea->city->state->name }})
                                        </option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn bg-gradient-primary w-100 mb-0">Filter</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Business</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Location</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($businesses as $business)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $business->business_name }}</h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    @if($business->email)
                                                        <i class="fas fa-envelope"></i> {{ $business->email }}<br>
                                                    @endif
                                                    @if($business->phone)
                                                        <i class="fas fa-phone"></i> {{ $business->phone }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $business->subcategory->category->name }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $business->subcategory->name }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $business->area->city->state->name }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $business->area->city->name }} - {{ $business->area->name }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('vendor.claims.create', $business->id) }}" class="btn btn-sm bg-gradient-primary">
                                            Claim Business
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <p class="text-sm mb-0">No unclaimed businesses found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-4 pt-4">
                        {{ $businesses->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#filter_subcategory').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'All Categories',
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
        
        $('#filter_area').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'All Areas',
            allowClear: true,
            ajax: {
                url: '{{ route("vendor.businesses.areas-search") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.more
                        }
                    };
                },
                cache: true
            }
        });
    });
</script>
@endpush 