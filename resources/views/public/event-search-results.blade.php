@extends('layouts.app-public')

@section('title', 'Event Search Results - Youth Central')

@push('styles')
<style>
  /* Event cards */
  .event-card {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 30px;
    height: 100%;
    display: flex;
    flex-direction: column;
    background-color: #fff;
  }

  .event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  }

  .event-img {
    height: 200px;
    position: relative;
    overflow: hidden;
  }

  .event-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
  }

  .event-card:hover .event-img img {
    transform: scale(1.05);
  }

  .event-category {
    position: absolute;
    top: 15px;
    right: 15px;
    background-color: #3399cc;
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
  }

  .event-date {
    position: absolute;
    top: 15px;
    left: 15px;
    background-color: rgba(255,255,255,0.9);
    color: #333;
    padding: 8px 15px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 600;
  }

  .event-content {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
  }

  .event-title {
    margin-bottom: 10px;
    font-size: 18px;
    font-weight: 700;
    color: #333;
    line-height: 1.4;
  }

  .event-venue {
    margin-bottom: 15px;
    color: #666;
    display: flex;
    align-items: center;
  }

  .event-venue i {
    margin-right: 8px;
    color: #3399cc;
  }

  .event-excerpt {
    margin-bottom: 20px;
    color: #666;
    font-size: 14px;
    line-height: 1.6;
    flex-grow: 1;
  }

  .event-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #eee;
  }

  .event-price {
    font-weight: 600;
    color: var(--primary-color);
  }

  .event-btn {
    padding: 8px 20px;
    background-color: var(--primary-color);
    color: white;
    border-radius: 4px;
    font-weight: 600;
    font-size: 14px;
    transition: background-color 0.3s ease;
    display: inline-block;
    text-decoration: none;
  }

  .event-btn:hover {
    background-color: var(--primary-color);
    color: white;
    text-decoration: none;
  }

  /* Search highlight */
  .search-highlight {
    background-color: #e6f3f9;
    padding: 2px 4px;
    border-radius: 2px;
  }

  /* Search header */
  .search-header {
    margin-top: 80px;
    padding: 80px 0;
    background: linear-gradient(135deg, var(--primary-color) 0%, #0a0f1f 100%);
    color: white;
  }

  .search-header h1 {
    margin-bottom: 15px;
    color: white;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

  /* Modern search bar with cities dropdown */
  .event-search-container {
    max-width: 800px;
    margin: 0 auto 30px;
    position: relative;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    padding: 25px;
  }

  .event-search-form {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
  }

  .event-search-input {
    flex: 1;
    position: relative;
  }

  .event-search-input input {
    width: 100%;
    color:var(--primary-color);
    height: 55px;
    border: 1px solid #e1e1e1;
    border-radius: 10px;
    padding: 10px 20px;
    font-size: 16px;
    transition: all 0.3s;
  }

  .event-search-input input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(51, 153, 204, 0.15);
    outline: none;
  }

  .event-search-input i {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
  }

  .event-search-city {
    width: 220px;
  }

  .event-search-city select {
    width: 100%;
    height: 55px;
    color:var(--primary-color);
    border: 1px solid #e1e1e1;
    border-radius: 10px;
    padding: 10px 20px;
    font-size: 16px;
    appearance: none;
    background: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E%3Cpath d='M7 10l5 5 5-5z' fill='%23555'/%3E%3C/svg%3E") no-repeat;
    background-position: right 10px center;
    background-size: 20px;
    transition: all 0.3s;
  }

  .event-search-city select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(51, 153, 204, 0.15);
    outline: none;
  }

  .event-search-submit {
    width: 120px;
  }

  .event-search-submit button {
    width: 100%;
    height: 55px;
    background: linear-gradient(135deg, var(--primary-color) 0%, #0a0f1f 100%);
    border: none;
    border-radius: 10px;
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .event-search-submit button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(51, 153, 204, 0.3);
  }

  .event-search-submit button i {
    font-size: 18px;
  }

  @media (max-width: 767px) {
    .event-search-city {
      width: 100%;
    }

    .event-search-submit {
      width: 100%;
    }
  }
</style>
@endpush

@section('content')
<!-- Start Search Header -->
<div class="container-fluid search-header">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        <h1>Event Search Results for "{{ $query }}"</h1>
        @if($cityId && isset($cities) && $cities->find($cityId))
          <p class="text-white mb-4">Location: {{ $cities->find($cityId)->name }}</p>
        @endif
        
        <!-- Modern Search Form -->
        <div class="event-search-container">
          <form action="{{ route('search') }}" method="GET" class="event-search-form">
            <input type="hidden" name="search_type" value="events">
            
            <div class="event-search-input">
              <input type="text" name="query" placeholder="Search events..." value="{{ $query }}">
              <i class="fas fa-search"></i>
            </div>
            
            <div class="event-search-city">
              <select name="city_id" id="city_id">
                <option value="">All Cities</option>
                @foreach(\App\Models\City::orderBy('name')->get() as $city)
                  <option value="{{ $city->id }}" {{ $cityId == $city->id ? 'selected' : '' }}>
                    {{ $city->name }}
                  </option>
                @endforeach
              </select>
            </div>
            
            <div class="event-search-submit">
              <button type="submit">
                <i class="fas fa-search"></i> Search
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Search Header -->

<!-- Start Results Counter and Filter -->
<div class="container-fluid listing-block">
  <div class="row listing-results">
    <div class="results-count">
      <span class="counter">{{ $events->total() }}</span> events found
    </div>
    
    <!-- Sort options -->
    {{-- <div class="results-filter">
      <span>Sort by:</span>
      <ul>
        <li>
          <a href="#" class="sort-option {{ request('sort') == 'date' || !request('sort') ? 'active' : '' }}" data-sort="date">Date</a>
        </li>
        <li>
          <a href="#" class="sort-option {{ request('sort') == 'title' ? 'active' : '' }}" data-sort="title">Title</a>
        </li>
      </ul>
    </div> --}}
  </div>
</div>
<!-- End Results Counter and Filter -->

<!-- Start Events Container -->
<div class="container mt-5">
  <div class="row">
    @forelse($events as $event)
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="event-card">
          <div class="event-img">
            @php
              $bannerPath = $event->banners->where('is_primary', true)->first()?->image_path 
                          ?? $event->banners->first()?->image_path 
                          ?? 'assets_public/images/backgrounds/8.jpg'; // Fallback image
              $bannerUrl = asset(str_starts_with($bannerPath, 'assets') ? $bannerPath : 'storage/' . $bannerPath);
            @endphp
            <img src="{{ $bannerUrl }}" alt="{{ $event->title }}">
            <div class="event-category">{{ ucfirst($event->category) }}</div>
            <div class="event-date">
              {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
            </div>
          </div>
          <div class="event-content">
            <h3 class="event-title" style="color: #fff !important;">{{ $event->title }}</h3>
            <div class="event-meta">
              <i class="fa-solid fa-location-dot"></i> <span style="color: #fff !important;">{{ $event->venue }}</span>
            </div>
            <div class="event-excerpt">
              <span style="color: #fff !important;">{{ Str::limit(strip_tags($event->description), 100) }}</span>
            </div>
            <div class="event-footer">
              <div class="event-price">
                <span style="color: #fff !important;">
                @if($event->registration_amount > 0)
                  ₹{{ number_format($event->registration_amount, 2) }}
                @else
                  Free
                @endif
                </span>
              </div>
              <a href="{{ route('events.show', $event) }}" class="event-btn">View Details</a>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center py-5">
        <i class="fa-solid fa-calendar-xmark fa-4x text-muted mb-4"></i>
        <h3 class="text-muted">No events found matching your search</h3>
        <p class="text-muted">Try adjusting your search terms or filters</p>
      </div>
    @endforelse
  </div>

  <!-- Pagination -->
  <div class="row mt-4 mb-5">
    <div class="col-md-12 d-flex justify-content-center">
      {{ $events->links() }}
    </div>
  </div>
</div>
<!-- End Events Container -->
@endsection

@push('scripts')
<script src="{{ asset('assets/js/location-dropdown.js') }}"></script>
<script>
  $(document).ready(function() {
    // Sort functionality
    $('.sort-option').click(function(e) {
      e.preventDefault();
      const sortVal = $(this).data('sort');
      $('#sort-field').val(sortVal);
      $(this).closest('form').submit();
    });
  });
</script>
@endpush 