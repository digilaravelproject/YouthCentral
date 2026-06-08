@extends('layouts.app-public')

@section('title', 'Upcoming Events')

@push('styles')
<style>
    .cities-list {
        max-height: 420px;      /* adjust as per your UI */
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }

    .yc-hero-event {
        height: 400px; /* Adjust height as needed */
        background-size: cover;
        background-position: center center;
        position: relative;
        color: #fff;
        display: flex;
        align-items: center; /* Center content vertically */
        text-shadow: 1px 1px 3px rgba(0,0,0,0.6);
    }
    .yc-hero-content {
        position: relative; /* Ensure content is above the overlay */
        z-index: 1;
        max-width: 600px; /* Limit content width */
    }
    .yc-hero-content h2 {
        font-size: 2.8rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    .yc-hero-content p {
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        line-height: 1.6;
         max-height: 4.8em; /* Limit description height (approx 3 lines) */
         overflow: hidden;
         text-overflow: ellipsis;
         display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }
    .yc-hero-content .btn-primary {
        background-color: var(--primary-color); /* Match theme */
        border-color: var(--primary-color);
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }
    .yc-hero-content .btn-primary:hover {
         background-color: #2980b9;
         border-color: #2980b9;
    }
    
    /* New Filter Styles */
    .filter-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(51, 153, 204, 0.1);
        margin-bottom: 2rem;
        background-color: #fdfdfd;
    }
    
    .filter-card .card-header {
        background: linear-gradient(45deg, #0a0f1f, var(--primary-color));
        color: white;
        font-weight: 600;
        border-radius: 15px 15px 0 0;
        border: none;
        padding: 18px 25px;
    }
    
    .filter-card .card-header h5 {
        margin: 0;
        font-size: 1.3rem;
        letter-spacing: 0.5px;
    }
    
    .filter-card .card-body {
        padding: 25px;
    }
    
    .filter-card .form-control,
    .filter-card .form-select {
        border-radius: 10px;
        border: 1px solid #e1e1e1;
        padding: 12px 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: none;
        height: auto;
    }
    
    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: #3399cc;
        box-shadow: 0 0 0 0.25rem rgba(51, 153, 204, 0.2);
    }
    
    .filter-card .form-label {
        font-weight: 600;
        font-size: 1rem;
        color: #444;
        margin-bottom: 0.75rem;
    }
    
    .filter-card .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        border-radius: 10px;
        font-weight: 600;
        padding: 12px 24px;
        font-size: 1.05rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(51, 153, 204, 0.2);
    }
    
    .filter-card .btn-primary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(51, 153, 204, 0.25);
    }
    
    .filter-card .btn-outline-secondary {
        border-radius: 10px;
        font-weight: 500;
        padding: 12px 24px;
        font-size: 1.05rem;
        transition: all 0.3s ease;
        border: 1px solid #ced4da;
    }
    
    .filter-card .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    /* Events Page Specific - Reduce Hero Header Height */
    .hero-header.h-video {
        height: 700px !important; /* Reduced from default height */
        min-height: 460px !important;
        --mask:
    radial-gradient(17.61px at 50% calc(100% - 24.5px),#000 99%,#0000 101%) calc(50% - 20px) 0/40px 100%,
    radial-gradient(17.61px at 50% calc(100% + 14.5px),#0000 99%,#000 101%) 50% calc(100% - 10px)/40px 100% repeat-x;
  -webkit-mask: var(--mask);
          mask: var(--mask);
    }
    
    /* Remove dark overlay and texture from hero banner on events page */
    .hero-header.transparent .overlay {
        display: none !important;
        background: transparent !important;
    }
    
    .hero-header.h-video .hero-texture {
        display: none !important;
    }
    
    .hero-header.h-video .dot-overlay {
        display: none !important;
    }
    
    .hero-header .header-centralizer {
        height: 500px !important; /* Desktop height */
    }
    
    /* Mobile specific height */
    @media (max-width: 768px) {
        .hero-header .header-centralizer {
            height: 630px !important; /* Mobile height */
        }
    }
    
    .hero-header .content-centralized {
        padding-top: 200px !important; /* Reduced padding */
        padding-bottom: 50px !important;
    }
    
    .hero-header .hero-search {
        margin-top: 20px !important; /* Reduced margin */
    }
    
    /* Improved Filter UI */
    .filter-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(51, 153, 204, 0.15);
        margin-bottom: 3rem;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .filter-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(51, 153, 204, 0.2);
    }
    
    .filter-card .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #0a0f1f 100%);
        color: white;
        font-weight: 700;
        border-radius: 0;
        border: none;
        padding: 25px 30px;
        position: relative;
        overflow: hidden;
    }
    
    /* Mobile adjustments for filter card header */
    @media (max-width: 768px) {
        .filter-card .card-header {
            padding: 20px 15px;
        }
        
        .filter-card .card-body {
            padding: 25px 15px !important;
        }
    }
    
    .filter-card .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
        animation: shimmer 3s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .filter-card .card-header h5 {
        margin: 0;
        font-size: 1.6rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        position: relative;
        z-index: 1;
    }
    
    /* Mobile font size adjustment */
    @media (max-width: 768px) {
        .filter-card .card-header h5 {
            font-size: 1.3rem;
        }
    }
    
    .filter-card .card-header i {
        font-size: 1.8rem;
        margin-right: 12px;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
    }
    
    .filter-card .card-body {
        padding: 35px 30px;
        background: white;
    }
    
    .filter-card .form-control,
    .filter-card .form-select {
        border-radius: 15px;
        border: 2px solid #e8ecf0;
        padding: 18px 20px;
        font-size: 1.1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        background: #fafbfc;
        color: #2c3e50;
    }
    
    /* Mobile adjustments for form controls */
    @media (max-width: 768px) {
        .filter-card .form-control,
        .filter-card .form-select {
            padding: 15px 15px;
            font-size: 1rem;
            border-radius: 10px;
            width: 100% !important; /* Full width on mobile */
        }
    }
    
    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.3rem rgba(10, 15, 31, 0.25);
        background: white;
        transform: translateY(-2px);
    }
    
    /* Disable transform on mobile for better UX */
    @media (max-width: 768px) {
        .filter-card .form-control:focus,
        .filter-card .form-select:focus {
            transform: none;
        }
    }
    
    .filter-card .form-label {
        font-weight: 700;
        font-size: 1.2rem;
        color: #2c3e50;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Mobile font size for labels */
    @media (max-width: 768px) {
        .filter-card .form-label {
            font-size: 1rem;
            margin-bottom: 8px;
        }
    }
    
    /* Button container for desktop side-by-side layout */
    .filter-buttons-container {
        display: flex;
        gap: 15px;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }
    
    /* Mobile stacked buttons */
    @media (max-width: 768px) {
        .filter-buttons-container {
            flex-direction: column;
            gap: 10px;
        }
        
        .filter-buttons-container .btn {
            width: 100%;
            max-width: none;
        }
    }
    
    .filter-card .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, #0a0f1f 100%);
        border: none;
        border-radius: 15px;
        font-weight: 700;
        padding: 18px 35px;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(10, 15, 31, 0.3);
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        overflow: hidden;
        min-width: 200px;
    }
    
    /* Mobile button adjustments */
    @media (max-width: 768px) {
        .filter-card .btn-primary {
            padding: 15px 25px;
            font-size: 1rem;
            min-width: auto;
            border-radius: 10px;
        }
    }
    
    .filter-card .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: all 0.3s ease;
    }
    
    .filter-card .btn-primary:hover::before {
        left: 100%;
    }
    
    .filter-card .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(10, 15, 31, 0.4);
    }
    
    /* Disable hover transforms on mobile */
    @media (max-width: 768px) {
        .filter-card .btn-primary:hover {
            transform: none;
        }
    }
    
    .filter-card .btn-outline-secondary {
        border-radius: 15px;
        font-weight: 600;
        padding: 18px 35px;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        border: 2px solid #6c757d;
        color: #6c757d;
        background: transparent;
        text-transform: uppercase;
        letter-spacing: 1px;
        min-width: 200px;
    }
    
    /* Mobile outline button adjustments */
    @media (max-width: 768px) {
        .filter-card .btn-outline-secondary {
            padding: 15px 25px;
            font-size: 1rem;
            min-width: auto;
            border-radius: 10px;
        }
    }
    
    .filter-card .btn-outline-secondary:hover {
        background: #6c757d;
        border-color: #6c757d;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
    }
    
    /* Disable hover transforms on mobile */
    @media (max-width: 768px) {
        .filter-card .btn-outline-secondary:hover {
            transform: none;
        }
        .fltbtn{
            width: 100% !important;
            justify-content: center;
            align-items: center;
        }

        .filter-buttons-container{
            width: 100% !important;
        }
        
        /* Make form controls and buttons 100% width on mobile */
        .filter-card .form-select,
        .filter-card select.form-select,
        .filter-card .location-filter {
            width: 100% !important;
        }
        
        .filter-card .btn-primary,
        .filter-card .btn-outline-secondary,
        .filter-card button.btn-primary,
        .filter-card a.btn-outline-secondary {
            width: 100% !important;
            margin-bottom: 10px !important;
        }
        
        /* Make the button container stack vertically on mobile */
        .filter-card .filter-buttons-container {
            display: flex !important;
            flex-direction: column !important;
            gap: 10px !important;
        }
        
        /* Override Bootstrap grid on mobile for form elements */
        .filter-card .col-md-4 {
            width: 100% !important;
            margin-bottom: 15px !important;
        }
    }
    
    /* Infinite Scroll Loading */
    .infinite-scroll-loading {
        text-align: center;
        padding: 40px 0;
        display: none;
    }
    
    .infinite-scroll-loading .spinner {
        width: 60px;
        height: 60px;
        border: 6px solid #f3f3f3;
        border-top: 6px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }
    
    .infinite-scroll-loading p {
        font-size: 1.2rem;
        color: #666;
        font-weight: 500;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .no-more-events {
        text-align: center;
        padding: 40px 0;
        color: #666;
        font-size: 1.1rem;
        font-weight: 500;
        display: none;
    }
    
    /* Equal Height Cards for Events */
    .upcoming-events .row {
        display: flex;
        flex-wrap: wrap;
    }
    
    .upcoming-events .row > [class*="col-"] {
        display: flex;
        flex-direction: column;
        margin-bottom: 30px;
    }
    
    .upcoming-events .event-card {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* .hero-search {
        height: 36px !important;
    } */
/* Sirf Height aur Alignment Fix - Bina UI chede */
.hero-search {
    height: 45px !important;
    margin-top: 10px !important;
    margin-bottom: 10px !important;
    display: flex !important;
    align-items: center !important;
}

.hero-search form {
    height: 45px !important;
    display: flex !important;
    align-items: center !important;
    width: 100% !important;
}

.hero-search fieldset {
    height: 45px !important;
    margin: 0 !important;
    padding: 0 !important;
    flex: 1 !important;
}

.hero-search input[name="query"] {
    height: 45px !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    font-size: 15px !important; /* Proper readability in small height */
}

/* Typing Effect ko center karne ke liye */
.hero-search .typingEffect {
    line-height: 45px !important;
    height: 45px !important;
    top: -60px !important;
}

/* Search Icon aur Submit Button fix */
.hero-search .search-submit {
    height: 45px !important;
    top: 0 !important;
    display: flex !important;
    align-items: center !important;
}

.hero-search .hero-search-icon {
    top: 50% !important;
    transform: translateY(-50%) !important;
    margin: 0 !important;
    padding-top: 0px;
}

/* City Toggle Icon position */
.hero-search .search-cities-toggle {
    height: 45px !important;
    top: 0 !important;
}

/* City Clear Icon Styles */
.city-clear-icon {
    display: inline-block;
    margin-left: 8px;
    cursor: pointer;
    font-size: 16px;
    color: var(--primary-color);
    transition: all 0.3s ease;
    padding: 2px 6px;
    border-radius: 50%;
}

.city-clear-icon:hover {
    background-color: rgba(51, 153, 204, 0.1);
    transform: scale(1.15);
}

/* Backdrop for closing popup when clicking outside */
.cities-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 29;
    display: none;
    background: transparent;
    pointer-events: auto;
}

.cities-backdrop.active {
    display: block;
}
</style>

<style>
    /* Section Container Styling */
    .filter-section-wrapper {
        padding: 20px 0;
    }

    /* Modern Minimalist Header */
    .filter-title {
        font-size: 1.6rem;
        font-weight: 800;
        letter-spacing: 2px;
        color: #1a1a1a;
        text-transform: uppercase;
        text-align: center;
        margin-bottom: 25px;
        /* Subtle text shadow for prominence without the box */
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    /* Slim Card Design */
    .filter-card-minimal {
        border: none !important;
        background: transparent !important;
    }

    /* Refined Input Fields */
    .filter-card-minimal .form-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        margin-bottom: 5px;
        margin-left: 2px;
    }

    .filter-card-minimal .form-select {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }

    .filter-card-minimal .form-select:focus {
        border-color: #000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    /* Compact Buttons */
    .btn-apply-minimal {
        background-color: #0d1117; /* Dark matching your nav */
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
        transition: transform 0.2s, background-color 0.2s;
    }

    .btn-apply-minimal:hover {
        background-color: #24292f;
        transform: translateY(-1px);
        color: #fff;
    }

    .btn-reset-minimal {
        background-color: transparent;
        color: #6c757d;
        border: 1px solid #dee2e6;
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .btn-reset-minimal:hover {
        background-color: #f8f9fa;
        color: #333;
    }

    /* Reduce vertical height */
    .g-3-compact {
        --bs-gutter-y: 0.5rem;
    }
</style>
@endpush

@section('content')
      {{-- Events Listing Featured Banner (replaces gray banner when set by admin) --}}
      @php
          $heroStyle = '';
          if (isset($eventsListingBanner) && $eventsListingBanner && $eventsListingBanner->image_path) {
              $heroStyle = "background-image: url('" . asset('storage/' . $eventsListingBanner->image_path) . "'); background-size: cover; background-position: center;";
          }
      @endphp
      <!-- Start Search Popup -->
      <div class="search-popup container-fluid hero-header" @if($heroStyle) style="{{ $heroStyle }}" @endif>
        <!-- Start Header Centralizer -->
        <div class="header-centralizer">
          <div class="content-centralized">
            <!-- Start Hero Search -->
            <div class="hero-search">
              <form action="{{ route('search') }}" method="GET">
                <input type="hidden" name="search_type" value="events">
                <fieldset>
                  <input type="text" name="query" class="form-control" data-placeholder="Search for events...">
                  <span class="typingEffect" data-title="Find Events Near You//Discover Local Events//Find Entertainment//Explore Events//Join Community Events"></span>
                </fieldset>
                <!-- Start Search cities Toggle -->
                <div class="search-cities-toggle"></div>
                <!-- End Search cities Toggle -->
                <!-- Start Search Cities -->
                <div class="search-cities">
                  <div class="cities-list">
                    @foreach($popularCities as $index => $city)
                      <a href="#" style="background-image:url('{{ asset('assets_public/images/cities/thumbs/' . (($index % 5) + 1) . '.jpg') }}')" data-city-id="{{ $city->id }}" {{ $index === 0 ? 'class="current"' : '' }}><span>{{ $city->name }}</span><i class="fa fa-times city-clear-icon"></i></a>
                    @endforeach
                    <input class="chosen-city" type="hidden" name="city_id" value="">
                  </div>
                </div>
                <!-- End Search Cities -->
                <div class="search-submit">
                  <input type="submit" value=" ">
                  <i class="hero-search-icon"></i>
                </div>
              </form>
              <!-- Cities Backdrop for closing popup -->
              <div class="cities-backdrop"></div>
            </div>
            <!-- End Hero Search -->
          </div>
        </div>
      </div>
      <!-- End Search Popup -->

      <!-- Start Hero Header with Search -->
      <div class="container-fluid hero-header h-video transparent" @if($heroStyle) style="{{ $heroStyle }}" @endif >
        <!-- Start Header Centralizer -->
        <div class="header-centralizer">
          <div class="content-centralized">
            <!-- Start Hero Search -->
            <div class="hero-search">
              <form action="{{ route('search') }}" method="GET">
                <input type="hidden" name="search_type" value="events">
                <fieldset>
                  <input type="text" name="query" class="form-control" data-placeholder="Search for events...">
                  <span class="typingEffect" data-title="Find Events Near You//Discover Local Events//Find Entertainment//Explore Events//Join Community Events"></span>
                </fieldset>
                <!-- Start Search cities Toggle -->
                <div class="search-cities-toggle"></div>
                <!-- End Search cities Toggle -->
                <!-- Start Search Cities -->
                <div class="search-cities">
                  <div class="cities-list">
                    @foreach($popularCities as $index => $city)
                      <a href="#" style="background-image:url('{{ asset('assets_public/images/cities/thumbs/' . (($index % 5) + 1) . '.jpg') }}')" data-city-id="{{ $city->id }}" {{ $index === 0 ? 'class="current"' : '' }}><span>{{ $city->name }}</span><i class="fa fa-times city-clear-icon"></i></a>
                    @endforeach

                    <input class="chosen-city" type="hidden" name="city_id" value="">
                    <!-- Fallback: Add a visible select for users without JavaScript -->
                    <noscript>
                      <select name="city_id_fallback" class="form-control" style="margin-top: 10px;">
                        <option value="">Select City</option>
                        @foreach($popularCities as $city)
                          <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                      </select>
                    </noscript>
                  </div>
                </div>
                <!-- End Search Cities -->
                <div class="search-submit">
                  <input type="submit"  value=" ">
                  <i class="hero-search-icon"></i>
                </div>
              </form>
              <!-- Cities Backdrop for closing popup -->
              <div class="cities-backdrop"></div>
            </div>
            <!-- End Hero Search -->
          </div>
        </div>
      </div>
      <!-- End Hero Header with Search -->

{{-- Latest YC Event Hero Section --}}
<?php /*@if($latestYcEvent)
    @php
        $bannerPath = $latestYcEvent->banners->where('is_primary', true)->first()?->image_path 
                      ?? $latestYcEvent->banners->first()?->image_path 
                      ?? 'assets_public/images/backgrounds/8.jpg'; // Fallback image
        $bannerUrl = asset(str_starts_with($bannerPath, 'assets') ? $bannerPath : 'storage/' . $bannerPath);
    @endphp
    <div class="yc-hero-event mb-5" style="background-image: url('{{ $bannerUrl }}');">
        <div class="container">
            <div class="yc-hero-content">
                <h2 class="event-title" style="text-align: left; color: #fff !important;">{{ $latestYcEvent->title }}</h2>
                <p class="event-short-desc" style="font-size: 18px;"><span style="color: #fff !important;">{{ Str::limit(strip_tags($latestYcEvent->description), 180) }}</span></p>
                <a href="{{ route('events.show', $latestYcEvent) }}" class="btn btn-primary" style="font-size: 15px;">View Event Details <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
@endif */?>
{{-- End YC Event Hero Section --}}


<!-- Start Upcoming General Events Section -->
<div class="container mt-5 upcoming-events" style="margin-top: 60px;">
    <div class="sec-title text-center mb-5">
        <h2>Upcoming Events</h2>
        <p>Explore general events happening in the community.</p>
    </div>

    <!-- Event Filters -->
    <!-- <div class="row mb-4">
        <div class="col-md-12">
            <div class="card filter-card">
                <div class="card-header">
                    <h5 class="mb-0" style="color:#fff;"><i class="fas fa-filter me-2" style="color: #fff;"></i>Filter Events</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('events.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="state_id" class="form-label">State</label>
                            <select name="state_id" id="state_id" class="form-select location-filter">
                                <option value="">All States</option>
                                @foreach(\App\Models\State::orderBy('name')->get() as $state)
                                    <option value="{{ $state->id }}" {{ isset($stateId) && $stateId == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="city_id" class="form-label">City</label>
                            <select name="city_id" id="city_id" class="form-select location-filter" {{ isset($stateId) && $stateId ? '' : 'disabled' }}>
                                <option value="">All Cities</option>
                                @if(isset($stateId) && $stateId)
                                    @foreach(\App\Models\City::where('state_id', $stateId)->orderBy('name')->get() as $city)
                                        <option value="{{ $city->id }}" {{ isset($cityId) && $cityId == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="area_id" class="form-label">Area</label>
                            <select name="area_id" id="area_id" class="form-select location-filter" {{ isset($cityId) && $cityId ? '' : 'disabled' }}>
                                <option value="">All Areas</option>
                                @if(isset($cityId) && $cityId)
                                    @foreach(\App\Models\Area::where('city_id', $cityId)->orderBy('name')->get() as $area)
                                        <option value="{{ $area->id }}" {{ isset($areaId) && $areaId == $area->id ? 'selected' : '' }}>
                                            {{ $area->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                        <div class="col-md-12 text-center mt-3 fltbtn" style="padding-top: 10px;">
                            <div class="filter-buttons-container">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i> Apply Filters
                            </button>
                            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-undo me-1"></i> Reset Filters
                            </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> -->
    <!-- End Event Filters -->

    <div class="container filter-section-wrapper">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12">
                
                <h5 class="filter-title">
                    <i class="fas fa-sliders-h" style="font-size: 0.9rem;"></i> Filter Events
                </h5>

                <div class="card filter-card-minimal">
                    <div class="card-body p-0">
                        <form action="{{ route('events.index') }}" method="GET">
                            <div class="row g-3 g-3-compact">
                                <div class="col-md-4">
                                    <label for="state_id" class="form-label">State</label>
                                    <select name="state_id" id="state_id" class="form-select location-filter">
                                        <option value="">All States</option>
                                        @foreach(\App\Models\State::orderBy('name')->get() as $state)
                                            <option value="{{ $state->id }}" {{ isset($stateId) && $stateId == $state->id ? 'selected' : '' }}>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="city_id" class="form-label">City</label>
                                    <select name="city_id" id="city_id" class="form-select location-filter" {{ isset($stateId) && $stateId ? '' : 'disabled' }}>
                                        <option value="">All Cities</option>
                                        @if(isset($stateId) && $stateId)
                                            @foreach(\App\Models\City::where('state_id', $stateId)->orderBy('name')->get() as $city)
                                                <option value="{{ $city->id }}" {{ isset($cityId) && $cityId == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="area_id" class="form-label">Area</label>
                                    <select name="area_id" id="area_id" class="form-select location-filter" {{ isset($cityId) && $cityId ? '' : 'disabled' }}>
                                        <option value="">All Areas</option>
                                        @if(isset($cityId) && $cityId)
                                            @foreach(\App\Models\Area::where('city_id', $cityId)->orderBy('name')->get() as $area)
                                                <option value="{{ $area->id }}" {{ isset($areaId) && $areaId == $area->id ? 'selected' : '' }}>
                                                    {{ $area->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                
                                <div class="col-md-12 text-center mt-4">
                                    <div class="d-flex justify-content-center gap-3">
                                        <button type="submit" class="btn btn-apply-minimal">
                                            <i class="fas fa-filter me-1"></i> Apply Filters
                                        </button>
                                        <a href="{{ route('events.index') }}" class="btn btn-reset-minimal">
                                            <i class="fas fa-undo me-1"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($generalEvents->isNotEmpty())
        <div class="row" id="events-container" style="margin-top: 50px;">
            @foreach ($generalEvents as $event)
            <a href="{{ route('events.show', $event) }}">
                <div class="col-lg-4 col-md-6 mb-4">
                    @include('public.events.partials.event-card', ['event' => $event]) {{-- Use the existing partial --}}
                </div>
            </a>
            @endforeach
        </div>

        <!-- Infinite Scroll Loading Indicator -->
        <div class="infinite-scroll-loading" id="loading-indicator">
            <div class="spinner"></div>
            <p>Loading more events...</p>
            </div>

        <!-- No More Events Message -->
        <div class="no-more-events" id="no-more-events">
            <i class="fas fa-check-circle fa-2x mb-3" style="color: var(--primary-color);"></i>
            <p>You've reached the end! No more events to load.</p>
        </div>
    @else
        <div class="col-12 text-center py-5">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
            <p class="text-muted">No upcoming general events found at the moment. Check back soon!</p>
        </div>
    @endif
</div>
<!-- End Upcoming General Events Section -->

@endsection 

@push('scripts')
<script src="{{ asset('assets/js/location-dropdown.js') }}"></script>
<script>
$(document).ready(function() {
    // Infinite Scroll Variables
    let currentPage = {{ $generalEvents->currentPage() }};
    let lastPage = {{ $generalEvents->lastPage() }};
    let isLoading = false;
    let hasMorePages = {{ $generalEvents->hasMorePages() ? 'true' : 'false' }};
    
    // Get current filter parameters
    const getCurrentFilters = () => {
        return {
            state_id: $('#state_id').val(),
            city_id: $('#city_id').val(),
            area_id: $('#area_id').val()
        };
    };
    
    // Infinite Scroll Implementation
    const initInfiniteScroll = () => {
        $(window).on('scroll', function() {
            if (isLoading || !hasMorePages) return;
            
            const scrollTop = $(window).scrollTop();
            const windowHeight = $(window).height();
            const documentHeight = $(document).height();
            
            // Trigger load when user is 200px from bottom
            if (scrollTop + windowHeight >= documentHeight - 200) {
                loadMoreEvents();
            }
        });
    };
    
    // Load More Events Function
    const loadMoreEvents = () => {
        if (isLoading || !hasMorePages) return;
        
        isLoading = true;
        $('#loading-indicator').show();
        
        const nextPage = currentPage + 1;
        const filters = getCurrentFilters();
        
        $.ajax({
            url: '{{ route("events.index") }}',
            method: 'GET',
            data: {
                page: nextPage,
                ...filters
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success && response.html) {
                    // Append new events to container
                    $('#events-container').append(response.html);
                    
                    // Update pagination info
                    currentPage = response.pagination.current_page;
                    lastPage = response.pagination.last_page;
                    hasMorePages = response.pagination.has_more;
                    
                    // Hide loading indicator
                    $('#loading-indicator').hide();
                    
                    // Show "no more events" message if we've reached the end
                    if (!hasMorePages) {
                        $('#no-more-events').show();
                    }
                } else {
                    $('#loading-indicator').hide();
                    $('#no-more-events').show();
                }
                
                isLoading = false;
            },
            error: function(xhr, status, error) {
                $('#loading-indicator').hide();
                
                // Show error message
                const errorHtml = `
                    <div class="col-12 text-center py-4">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Failed to load more events. Please try again.
                        </div>
                    </div>
                `;
                $('#events-container').append(errorHtml);
                
                isLoading = false;
            }
        });
    };
    
    // Reset infinite scroll when filters change
    const resetInfiniteScroll = () => {
        currentPage = 1;
        hasMorePages = true;
        isLoading = false;
        $('#loading-indicator').hide();
        $('#no-more-events').hide();
    };
    
    // Handle filter form submission
    $('.filter-card form').on('submit', function(e) {
        resetInfiniteScroll();
        // Let the form submit normally - this will reload the page with new filters
        return true;
    });
    
    // Initialize infinite scroll
    initInfiniteScroll();
    
    // Multiple attempts to ensure our handler takes precedence
    function initializeEventsPageCitySelection() {
        // Check if elements exist
        if ($('.hero-search .search-cities a').length === 0) {
            return;
        }
        
        // Remove all existing event handlers from main.js
        $('.hero-search .search-cities a').off('click');
        $('body').off('click', '.search-cities a');
        
        // Handle city clear icon click
        $('.hero-search .search-cities a .city-clear-icon').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $cityLink = $(this).closest('a');
            var $cityList = $cityLink.closest('.hero-search');
            
            // Remove current class from the city link
            $cityLink.removeClass('current');
            
            // Clear the city ID in the hidden input
            $cityList.find('.chosen-city').val('');
            
            // Hide the cities dropdown
            $cityList.find('.search-cities').animate({top: '-1000px'}, {duration: 500});
            setTimeout(function() {
                $cityList.closest('.hero-header').removeClass('open-cities-list');
            }, 500);
            
            // Hide backdrop
            $cityList.find('.cities-backdrop').removeClass('active');
            
            return false;
        });
        
        // Add our specific event handler for events page
        $('.hero-search .search-cities a').not('.city-clear-icon').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $this = $(this);
            var cityId = $this.attr('data-city-id');
            
            // No more "More Cities" button - removed as per requirements
            
            // Handle city selection
            if (cityId && cityId !== 'more') {
                // Remove current class from all city links
                $this.closest('.hero-search').find('.search-cities a').removeClass('current');
                
                // Add current class to clicked link
                $this.addClass('current');
                
                // Set the city ID in the hidden input
                $this.closest('.hero-search').find('.chosen-city').val(cityId);
                
                // Close the cities dropdown
                var $heroHeader = $this.closest('.hero-header');
                $this.closest('.hero-search').find('.search-cities').animate({top: '-1000px'}, {duration: 500});
                setTimeout(function() {
                    $heroHeader.removeClass('open-cities-list');
                }, 500);
                
                // Hide backdrop
                $this.closest('.hero-search').find('.cities-backdrop').removeClass('active');
            }
            
            return false;
        });
        
        // Handle cities dropdown toggle - override main.js
        $('.hero-search .search-cities-toggle').off('click').on('click', function(e) {
            e.preventDefault();
            var $heroHeader = $(this).closest('.hero-header');
            var $searchCities = $(this).closest('.hero-search').find('.search-cities');
            var $backdrop = $(this).closest('.hero-search').find('.cities-backdrop');
            
            $heroHeader.addClass('open-cities-list');
            $searchCities.css({top: '-1000px'});
            $searchCities.stop().animate({top: [0, 'easeOutExpo']}, {duration: 1500});
            
            // Show backdrop with active class
            $backdrop.addClass('active');
        });
        
        // Handle click outside dropdown to close it - Robust approach for cities dropdown
        $(document).on('click', function(e) {
            // Find all open cities dropdowns (check both backdrop and open-cities-list class)
            var $openHeaders = $('.hero-header.open-cities-list');
            
            if ($openHeaders.length > 0) {
                $openHeaders.each(function() {
                    var $heroHeader = $(this);
                    var $heroSearch = $heroHeader.find('.hero-search');
                    var $searchCities = $heroSearch.find('.search-cities');
                    var $backdrop = $heroSearch.find('.cities-backdrop');
                    
                    // Check if click is NOT on cities list, toggle button, or search form
                    var $clickTarget = $(e.target);
                    var isClickOnCities = $clickTarget.closest('.search-cities').length > 0;
                    var isClickOnToggle = $clickTarget.closest('.search-cities-toggle').length > 0;
                    var isClickOnForm = $clickTarget.closest('.hero-search').length > 0;
                    var isClickOnIcon = $clickTarget.closest('.city-clear-icon').length > 0;
                    
                    // If click is outside all these elements, close the popup
                    if (!isClickOnCities && !isClickOnToggle && !isClickOnForm && !isClickOnIcon) {
                        // Close the cities dropdown with animation
                        $searchCities.stop().animate({top: '-1000px'}, {duration: 500});
                        
                        // Remove open state after animation completes
                        setTimeout(function() {
                            $heroHeader.removeClass('open-cities-list');
                        }, 500);
                        
                        // Hide backdrop
                        $backdrop.removeClass('active');
                    }
                });
            }
        });
        
        // Handle Escape key to close dropdown - Close cities filter when Esc is pressed
        $(document).on('keydown', function(e) {
            // Check if Escape key (27) was pressed
            if (e.keyCode === 27 || e.key === 'Escape') {
                // Find all open cities dropdowns
                var $openHeaders = $('.hero-header.open-cities-list');
                
                if ($openHeaders.length > 0) {
                    $openHeaders.each(function() {
                        var $heroHeader = $(this);
                        var $heroSearch = $heroHeader.find('.hero-search');
                        var $searchCities = $heroSearch.find('.search-cities');
                        var $backdrop = $heroSearch.find('.cities-backdrop');
                        
                        // Close the cities dropdown with animation
                        $searchCities.stop().animate({top: '-1000px'}, {duration: 500});
                        
                        // Remove open state after animation completes
                        setTimeout(function() {
                            $heroHeader.removeClass('open-cities-list');
                        }, 500);
                        
                        // Hide backdrop
                        $backdrop.removeClass('active');
                    });
                    
                    // Prevent default behavior when dropdown is open
                    e.preventDefault();
                }
            }
        });
        
        // Debug form submission
        $('.hero-search form').on('submit', function(e) {
            var cityId = $('.hero-search .chosen-city').val();
            var query = $('.hero-search input[name="query"]').val();
            
            // Let the form submit normally
            return true;
        });
        
        // Debug: Show initial state
        $('.hero-search .search-cities a').each(function(index) {
                });
    }
    
    // Try multiple times to ensure it works
    setTimeout(initializeEventsPageCitySelection, 100);
    setTimeout(initializeEventsPageCitySelection, 500);
    setTimeout(initializeEventsPageCitySelection, 1000);
    
    // Also try when window is fully loaded
    $(window).on('load', function() {
        setTimeout(initializeEventsPageCitySelection, 100);
    });
    });
</script>
@endpush 