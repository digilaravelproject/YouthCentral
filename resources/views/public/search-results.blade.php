@extends('layouts.app-public')

@section('title', 'Search Results - Youth Central')

@push('styles')
<style>
  /* Business images */
  .listing-item-link {
    position: relative;
    display: block;
    overflow: hidden;
  }
  
  .listing-item-link img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    transition: transform 0.3s ease;
  }
  
  .listing-item:hover .listing-item-link img {
    /* transform: scale(1.05); */
  }
  
  /* Grid/List view */
  .grid-icon {
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
    margin-right: 10px !important;
    font-size: 20px !important;
    color: #333 !important;
    cursor: pointer;
  }
  
  .grid-icon.active {
    color: #2ecc71 !important;
  }
  
  /* List view */
  .list-view .listing-item {
    display: flex;
    margin-bottom: 20px;
  }
  
  .list-view .listing-item-link {
    width: 300px;
    flex-shrink: 0;
  }
  
  .list-view .listing-item-data {
    flex-grow: 1;
    padding: 20px;
  }
  
  .list-view .col-sm-3 {
    width: 100%;
  }
  
  /* Search highlight */
  .search-highlight {
    background-color: #FFF8E1;
    padding: 2px 4px;
    border-radius: 2px;
  }
  
  /* Search header */
  .search-header {
    margin-top: 80px;
    padding: 100px 0;
    background-color: #f8f9fa;
  }
  
  .search-header h1 {
    margin-bottom: 15px;
    color: #333;
  }
  
  /* Limit width, center, and style the hero search bar */
  .search-header .hero-search {
    max-width: 80%;
    margin: 0 auto 20px 0;
    position: relative; /* Ensure positioning context */
    transition: none !important; /* Disable transitions */
    animation: none !important; /* Disable animations */
    height: auto !important;
    padding: 10px 15px !important;
  }
  .search-header .hero-search form {
    display: flex;
    align-items: center; /* Vertically align input and button */
    width: 100%;
  }
  .search-header .hero-search fieldset {
    flex-grow: 1; /* Allow input field to take available space */
    margin: 0; /* Reset margin */
    height: 50px !important;
  }
  .search-header .hero-search .form-control {
    height: 50px; /* Match button height */
    border-radius: 30px 0 0 30px;
    border: 1px solid #ced4da; /* Add border for consistency */
    border-right: none; /* Remove right border where button joins */
  }
  /* Hide redundant city elements */
  .search-header .hero-search .search-cities-toggle,
  .search-header .hero-search .search-cities {
    display: none !important;
  }
  .search-header .hero-search .search-submit {
    flex-shrink: 0; /* Prevent button from shrinking */
  }
  .search-header .hero-search .search-submit button {
    height: 50px;
    width: 60px;
    padding: 0;
    border: 1px solid var(--primary-color); /* Match input border color? */
    border-left: none; /* Remove left border */
    background: var(--primary-color);
    color: white;
    border-radius: 0 30px 30px 0;
    display: flex; /* Center icon within button */
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.2s ease;
  }
  .search-header .hero-search .search-submit {
    position: relative;
    display: inline-block;
    width: auto;
    height: auto;
  }
  .search-header .hero-search .search-submit button {
    height: 50px;
    width: 60px;
    padding: 0;
    border: 1px solid var(--primary-color);
    border-left: none;
    background: var(--primary-color);
    color: white;
    border-radius: 0 30px 30px 0;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.2s ease;
    position: relative;
  }
  .search-header .hero-search .hero-search-icon {
    position: static !important;
    width: auto !important;
    height: auto !important;
    font-size: 18px !important;
    color: #ffffff !important;
    padding: 0 !important;
    margin: 0 !important;
    text-align: center !important;
    display: inline-block !important;
  }
  .search-header .hero-search .hero-search-icon:before {
    font-size: 18px !important;
    margin: 0 !important;
    text-shadow: none !important;
  }

  .premium-badge {
            position: absolute;
            bottom: 70px;
            left: 215px;

            width: 42px;
            height: 42px;
            border-radius: 14px;

            background: #111111;
            
            display: flex;
            align-items: center;
            justify-content: center;

            box-shadow: #111111 0px 0px 0px 2px, #ffffff 0px 0px 0px 4px, #ffffff 0px 0px 10px 4px;

            border: 2px solid rgba(255,255,255,0.15);

            z-index: 10;

            transition: all 0.3s ease;
        }

        .premium-badge:hover {
            transform: scale(1.08);
            box-shadow: #111111 0px 0px 0px 2px, #ffffff 0px 0px 0px 4px, #ffffff 0px 0px 15px 6px;
        }

        .premium-badge i {
            color: #fff;
            font-size: 18px;
        }
</style>
@endpush

@section('content')
      <!-- Start Search Popup -->
      <div class="search-popup container-fluid hero-header">
        <!-- Start Header Centralizer -->
        <div class="header-centralizer">
          <div class="content-centralized">
            <!-- Start Hero Search -->
            <div class="hero-search">
              <form action="{{ route('search') }}" method="GET">
                <fieldset>
                  <input type="text" name="query" class="form-control" data-placeholder="Explore and Enjoy..." value="{{ $query ?? '' }}">
                  <span class="typingEffect" data-title="Explore Opportunities//Explore Your Passion//Explore Yourself"></span>
                </fieldset>
                <!-- Start Search Cities -->
                <div class="search-cities-toggle"></div>
                <div class="search-cities">
                  <div class="cities-list">
                    @foreach($popularCities as $index => $city)
                      <a href="#" style="background-image:url('{{ asset('assets_public/images/cities/thumbs/' . (($index % 5) + 1) . '.jpg') }}')" data-city-id="{{ $city->id }}" {{ $index === 0 ? 'class="current"' : '' }}><span>{{ $city->name }}</span></a>
                    @endforeach
                    <input class="chosen-city" type="hidden" name="city" value="">
                  </div>
                </div>
                <!-- End Search Cities -->
                <div class="search-submit">
                  <input type="submit" value=" ">
                  <i class="hero-search-icon"></i>
                </div>
              </form>
            </div>
            <h3 style="color: #fff; text-align: left; font-size: 20px; margin-left: 10%;">Dream Bigger, Start Here</h3>
            <!-- End Hero Search -->
            <!-- Start Search Categories -->
            <div class="search-categories">
              <div class="categories">
                @php
                  // This is the standardized logic for custom categories
                  $customCategoryMappings = [
                      'Tuitions' => ['icon' => 'fi fi-rr-book-alt', 'search_terms' => ['tuition', 'tutoring', 'tutor']],
                      'Football/Soccer' => ['icon' => 'fi fi-rr-football-player', 'search_terms' => ['football', 'soccer']],
                      'Cricket' => ['icon' => 'fi fi-rs-cricket', 'search_terms' => ['cricket']],
                      'Swimming' => ['icon' => 'fi fi-rs-swimmer', 'search_terms' => ['swimming', 'swim']],
                      'Coaching Classes' => ['icon' => 'fi fi-rr-workshop', 'search_terms' => ['coaching', 'classes', 'training']],
                      'Computers/AI' => ['icon' => 'fi fi-rs-computer', 'search_terms' => ['computer', 'ai', 'programming', 'coding']],
                      'Theatre/Acting' => ['icon' => 'fi fi-ts-theater-masks', 'search_terms' => ['theatre', 'acting', 'drama']],
                      'Music' => ['icon' => 'fi fi-rs-music-alt', 'search_terms' => ['music', 'musical']],
                      'Day Care' => ['icon' => 'fi fi-rr-child', 'search_terms' => ['daycare', 'day care', 'childcare']],
                      'Chess' => ['icon' => 'fi fi-ts-chess', 'search_terms' => ['chess']],
                      'Table Tennis' => ['icon' => 'fi fi-rr-ping-pong', 'search_terms' => ['table tennis', 'ping pong']],
                      'Martial Arts/Karate' => ['icon' => 'fi fi-tr-uniform-martial-arts', 'search_terms' => ['martial arts', 'karate', 'taekwondo']],
                      'Foundational Stem' => ['icon' => 'fi fi-rr-microscope', 'search_terms' => ['stem', 'science', 'foundational']],
                      'Maths/Science' => ['icon' => 'fi fi-sr-calculator-simple', 'search_terms' => ['math', 'science', 'mathematics']],
                      'Library' => ['icon' => 'fi fi-rr-book-alt', 'search_terms' => ['library', 'libraries']],
                      'Pediatrician' => ['icon' => 'fi fi-rr-stethoscope', 'search_terms' => ['pediatrician', 'pediatric']],
                      'Counselling' => ['icon' => 'fi fi-rr-meeting', 'search_terms' => ['counselling', 'counseling', 'therapy']],
                      'Painting/Sketching' => ['icon' => 'fi fi-rr-palette', 'search_terms' => ['painting', 'sketching', 'art']]
                  ];
                  
                  $customCategories = [];
                  foreach ($customCategoryMappings as $categoryName => $mapping) {
                      $subcategory = null;
                      foreach ($mapping['search_terms'] as $term) {
                          $subcategory = \App\Models\Subcategory::where('name', 'LIKE', '%' . $term . '%')->first();
                          if ($subcategory) break;
                      }
                      
                      if ($subcategory) {
                          $customCategories[] = ['name' => $categoryName, 'icon' => $subcategory->icon_class ? $subcategory->getFormattedIconClass() : $mapping['icon'], 'route' => route('listings', $subcategory)];
                      } else {
                          $customCategories[] = ['name' => $categoryName, 'icon' => $mapping['icon'], 'route' => route('search') . '?query=' . urlencode($categoryName)];
                      }
                  }
                @endphp
                @foreach($customCategories as $category)
                  <a class="child" href="{{ $category['route'] }}">
                    <i class="{{ $category['icon'] }}"></i>
                    <span>{{ $category['name'] }}</span>
                  </a>
                @endforeach
                <a href="{{ route('categories.all') }}">
                  <i class="fi fi-rr-apps"></i>
                  <span>More Options</span>
                </a>
              </div>
            </div>
            <!-- End Search Categories -->
          </div>
        </div>
      </div>
      <!-- End Search Popup -->
      
      <!-- Start Search Header -->
      <div class="container-fluid search-header">
        <div class="container">
          <div class="row">
            <div class="col-md-12 text-center">
              {{-- <h1>Search Results for "{{ $query }}"</h1> --}} {{-- Title moved below search --}}
              
              {{-- Start Hero Search (Replaced from index.blade.php) --}}
              <div class="hero-search" style="left: 0% !important;"> {{-- Removed inline style --}}
                <form action="{{ route('search') }}" method="GET">
                  <fieldset>
                    <input type="text" name="query" class="form-control" data-placeholder="Explore and Enjoy..." placeholder="Search businesses..." value="{{ $query }}">
                  </fieldset>
                  <!-- Start Search cities Toggle (Will be hidden by CSS) -->
                  <div class="search-cities-toggle"></div>
                  <!-- End Search cities Toggle -->
                  <!-- Start Search Cities (Will be hidden by CSS) -->
                  <div class="search-cities">
                    <div class="cities-list">
                      @foreach($cities as $index => $c)
                        <a href="#" style="background-image:url('{{ asset('assets_public/images/cities/thumbs/' . (($index % 5) + 1) . '.jpg') }}')" data-city-id="{{ $c->id }}" {{ $c->id == $cityId ? 'class="current"' : '' }}><span>{{ $c->name }}</span></a>
                      @endforeach
                      {{-- <a href="#" style="background-image:url('{{ asset('assets_public/images/cities/thumbs/5.jpg') }}')" data-city-id="more" class="go-more-cities"><span>More Cities</span></a> --}}
                      <input class="chosen-city" type="hidden" name="city_id" value="{{ $cityId ?? 0 }}">
                    </div>
                  </div>
                  <!-- End Search Cities -->
                  <div class="search-submit">
                    {{-- Removed value=" " from button --}}
                    <button type="submit">
                       <i class="hero-search-icon"></i>
                    </button>
                  </div>
                  {{-- Hidden field for sorting --}}
                  <input type="hidden" name="sort" id="sort-field" value="{{ request('sort') }}">
                </form>
              </div>
              {{-- End Hero Search --}}

              <h1>Search Results for "{{ $query }}"</h1>
              @if($cityId && $cities->find($cityId))
                <p>Filtered by city: {{ $cities->find($cityId)->name }}</p>
              @endif

            </div>
          </div>
        </div>
      </div>
      <!-- End Search Header -->

<!-- Start Listings Counter and Filter -->
<div class="container-fluid listing-block">
  <div class="row listing-results">
    <div class="results-count">
      <span class="counter">{{ $businesses->total() }}</span> results found
    </div>
    
  </div>
</div>
<!-- End Listings Counter and Filter -->

<!-- Start Listings Container -->
<div class="container-fluid listing-block">
  <div class="row listing white grid-view">
    @forelse($businesses as $index => $business)
    <!-- Start Listing Item Col -->
    <div class="col-sm-3">
      <div class="listing-item">
        <a href="#" class="category-icon">
          <i class="{{ $business->subcategory->getFormattedIconClass() ?? 'fas fa-bookmark' }}"></i>
        </a>
        <div class="listing-item-rating">{{ number_format($business->average_rating ?? 0, 1) }}</div>
        <a href="{{ route('public.business.show', $business) }}" class="listing-item-link">
          <div class="listing-item-title-centralizer">
            <div class="listing-item-title">
              {{ $business->business_name }}
              @if($business->description)
                <div class="ribbon">{{ Str::limit($business->description, 25) }}</div>
              @endif
              @if($business->has_active_subscription)
                <!-- <div class="ribbon premium-ribbon"><i class="fas fa-star"></i></div> -->
                <div class="premium-badge">
                      <i class="fas fa-crown"></i>
                  </div>
              @endif
            </div>
          </div>
          <div class="image-wrapper">
            @if($business->images && $business->images->isNotEmpty())
              @php
                $primaryImage = $business->images->where('is_primary', true)->first();
                $fallbackImage = $business->images->first();
                $imagePath = '';
                
                if ($primaryImage && isset($primaryImage->path)) {
                    $imagePath = $primaryImage->path;
                } elseif ($fallbackImage && isset($fallbackImage->path)) {
                    $imagePath = $fallbackImage->path;
                }
              @endphp
              
              @if(!empty($imagePath))
                <img alt="{{ $business->business_name }}" src="{{ asset('storage/' . $imagePath) }}" />
              @else
                <img alt="{{ $business->business_name }}" src="{{ asset('assets_public/images/listings/' . ($index % 8 + 1) . '.jpg') }}" />
              @endif
            @else
              <img alt="{{ $business->business_name }}" src="{{ asset('assets_public/images/listings/' . ($index % 8 + 1) . '.jpg') }}" />
            @endif
          </div>
        </a>
        <div class="listing-item-data">
          <a class="listing-item-address" href="#">
            {{ $business->street_address ?? $business->area->name ?? 'Location not specified' }}, {{ $business->area->city->name ?? '' }}
          </a>
          <div class="listing-item-excerpt">
            {{ Str::limit($business->description, 50) ?? 'Visit our business for the best experience' }}
          </div>
        </div>
        <div class="listing-category-name">
          <a href="{{ route('listings', $business->subcategory) }}">{{ $business->subcategory->name ?? 'Uncategorized' }}</a>
        </div>
      </div>
    </div>
    <!-- End Listing Item Col -->
    @empty
    <div class="col-12 text-center p-5">
      <h3>No results found</h3>
      <p>We couldn't find any businesses matching your search query.</p>
      <div class="back-to-home">
        <a href="{{ url('/') }}" class="btn btn-outline-primary">
          <i class="fa-solid fa-arrow-left"></i> Back to Home
        </a>
      </div>
    </div>
    @endforelse
  </div>
  
  <!-- Pagination -->
  <div class="row">
    <div class="col-12 text-center">
      {{ $businesses->appends(['query' => $query])->links() }}
    </div>
  </div>
</div>
<!-- End Listings Container -->
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    // Grid/List view toggle
    $('.grid-view-btn').on('click', function(e) {
      e.preventDefault();
      $('.listing.white').removeClass('list-view').addClass('grid-view');
      $('.grid-view-btn').addClass('active');
      $('.list-view-btn').removeClass('active');
    });

    $('.list-view-btn').on('click', function(e) {
      e.preventDefault();
      $('.listing.white').removeClass('grid-view').addClass('list-view');
      $('.list-view-btn').addClass('active');
      $('.grid-view-btn').removeClass('active');
    });

    // Sorting functionality
    $('.sort-btn').on('click', function(e) {
      e.preventDefault();
      var sortValue = $(this).data('sort');
      $('#sort-field-filter').val(sortValue); // Update the hidden input in the filter form
      $('#filter-form').submit(); // Submit the form containing sort and other filters
    });

    // City selection in search bar
    var cityLinks = document.querySelectorAll('.search-header .hero-search .cities-list a'); // More specific selector
    var cityInput = document.querySelector('.search-header .hero-search .chosen-city'); // More specific selector
    
    if (cityLinks.length > 0 && cityInput) {
        cityLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                cityLinks.forEach(function(el) {
                    el.classList.remove('current');
                });
                
                this.classList.add('current');
                cityInput.value = this.getAttribute('data-city-id');
                
                // Uncomment below to submit the form immediately when a city is clicked
                // $(this).closest('form').submit(); 
            });
        });
    }
    
    // Initialize tooltips (Simplified)
    // Use Bootstrap 5 initialization if available, otherwise fallback to jQuery
    if (typeof bootstrap !== 'undefined' && typeof bootstrap.Tooltip !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], .tooltip'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    } else if ($.fn.tooltip) {
        $('.tooltip').tooltip(); // Fallback for Bootstrap 4 or jQuery UI tooltip
    }

  }); // End document ready
</script>
@endpush 