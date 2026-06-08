<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $business->business_name }} - Youth Central Directory</title>
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GP39H4GFSS"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-GP39H4GFSS');
    </script>
    <!-- Google Font(s) -->
    <link href="https://fonts.googleapis.com/css?family=Capriola|Roboto" rel="stylesheet">
    <!-- Bootstrap-->
    <link href="{{ asset('assets_public/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Flaticon -->
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-straight/css/uicons-thin-straight.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-rounded/css/uicons-thin-rounded.css">
    <!-- Listing Filter -->
    <link href="{{ asset('assets_public/lib/bootstrap-select-master/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <!-- Lightbox -->
    <link href="{{ asset('assets_public/lib/lightbox2-master/dist/css/lightbox.min.css') }}" rel="stylesheet">
    <!-- Map -->
    <link href="{{ asset('assets_public/lib/Leaflet-1.0.2/dist/leaflet.css') }}" rel="stylesheet">
    <!-- City Listing Icons -->
    <link href="{{ asset('assets_public/fonts/icons/css/import-icons.css') }}" rel="stylesheet">
    <!-- Main CSS -->
    <link href="{{ asset('assets_public/css/style.css') }}" rel="stylesheet">
    <!-- Font Awesome 6 Override CSS -->
    <link href="{{ asset('assets_public/css/fa6-override.css') }}" rel="stylesheet">
    <style>
      /* Business hours styling */
      .business-hours {
        margin-top: 20px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 4px;
      }
      .business-hours h5 {
        margin-bottom: 15px;
        font-weight: bold;
        color: #333;
      }
      .business-hours .day {
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 5px 0;
        border-bottom: 1px solid #eee;
      }
      .business-hours .day.today {
        font-weight: bold;
        color: #007bff;
      }
      .business-hours .hours {
        color: #666;
      }
      .business-hours .closed {
        color: #dc3545;
      }
      
      /* Fix for business images */
      .gallery {
        position: relative;
        width: 100%;
        height: 500px;
        background: #f8f9fa;
        overflow: hidden;
        user-select: none;
      }
      
      /* Review popup positioning - override main.js dynamic positioning */
      @media (min-width: 768px) {
        .review-popup .panel.panel-form {
          top: 25% !important;
          margin-top: 0 !important;
          position: relative !important;
          display: inline-block !important;
        }
        
        .review-popup .row {
          overflow-y: auto !important;
          height: auto !important;
          margin-top: 0 !important;
        }
      }
      
      /* Mobile review popup positioning */
      @media (max-width: 767px) {
        .review-popup .panel.panel-form {
          top: 55px !important;
          margin-top: 0 !important;
          position: relative !important;
        }
      }
      
      /* Prevent body scroll when review popup is open */
      body.review-popup-open {
        overflow: hidden !important;
      }
      
      /* Ensure proper z-index for review popup */
      .review-popup {
        z-index: 9999 !important;
      }
      
      .gallery-wrapper {
        display: flex;
        height: 100%;
        cursor: grab;
        overflow-x: auto;
        overflow-y: hidden;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE and Edge */
      }
      
      .gallery-wrapper::-webkit-scrollbar {
        display: none; /* Chrome, Safari and Opera */
      }
      
      .gallery-item {
        flex: 0 0 auto;
        width: 500px;
        height: 100%;
        margin-right: 5px;
        position: relative;
      }
      
      .gallery-item a {
        display: block;
        height: 100%;
        width: 100%;
      }
      
      .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        vertical-align: middle;
      }
      
      /* Remove any transform effects */
      .gallery-item:hover a {
        transform: none !important;
        transition: none !important;
      }
      
      /* Gallery Navigation */
      .gallery-nav {
        position: absolute;
        top: 50%;
        margin-top: -20px; /* Half of height */
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.9);
        border-radius: 50%;
        cursor: pointer;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 5px rgba(0,0,0,0.2);
      }

      .gallery-nav.prev {
        left: 10px;
      }

      .gallery-nav.next {
        right: 10px;
      }
      
      .gallery-nav:hover {
        background: rgba(255,255,255,1);
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
      }
      
      /* Gallery counter */
      .gallery-counter {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0,0,0,0.7);
        color: #fff;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        z-index: 10;
      }
      
      /* Fix for listing avatar */
      .listing-avatar {
        width: 220px;
        height: 220px;
        margin: 0 auto 20px;
        border-radius: 50%;
        overflow: hidden;
        border: 5px solid #fff;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
      }
      .listing-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
      
      /* Review styling */
      .star-rating fieldset {
        border: none;
        display: inline-block;
      }
      .star-rating input[type="radio"] {
        display: none;
      }
      .star-rating label {
        color: #ddd;
        cursor: pointer;
        font-size: 2em;
        padding: 0 0.1em;
      }
      .star-rating input[type="radio"]:checked ~ label {
        color: #FFD700;
      }
      .star-rating label:hover, 
      .star-rating label:hover ~ label {
        color: #FFD700;
      }
      
      /* Social contact buttons */
      .social-contact-buttons {
        margin: 20px 0;
      }
      .social-contact-buttons .btn {
        margin: 5px;
        border-radius: 20px;
        padding: 8px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
      }
      .social-contact-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      }
      .share-buttons {
        margin-top: 15px;
      }
      .share-buttons .btn {
        width: 35px;
        height: 35px;
        padding: 0;
        line-height: 35px;
        text-align: center;
        border-radius: 50%;
        margin: 2px;
      }
      
      /* Search button styles */
      .search-submit button {
        background: transparent;
        border: none;
        width: 100%;
        height: 100%;
        cursor: pointer;
      }
      
      .search-submit button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
      }
      
      .search-submit button:not(:disabled):hover {
        opacity: 0.8;
      }
      
      /* a. Change social icon color */
      .listing-social .social-networks a {
          color: var(--primary-color) !important; /* Gold color */
          font-size: 24px !important; /* Make icons larger */
          display: inline-block !important; /* Ensure they are visible */
          margin: 0 8px !important; /* Add spacing */
          transition: all 0.3s ease !important;
          text-decoration: none !important;
      }
      .listing-social .social-networks a:hover {
          color: #b89500 !important; /* Darker gold on hover */
          transform: scale(1.2) !important; /* Scale effect on hover */
      }
      
      /* Ensure Font Awesome brand icons are properly displayed */
      .listing-social .social-networks a.fa-brands {
          font-family: 'Font Awesome 6 Brands' !important;
          font-weight: 400 !important;
      }
      
      /* Make sure the social networks container is visible */
      .listing-social .social-networks {
          text-align: center;
          margin: 20px 0;
          padding: 15px;
          background: rgba(10, 15, 31, 0.1);
          border-radius: 10px;
      }

      /* Lightbox fixes - Force data container and close button to be visible */
      .lb-dataContainer {
          display: block !important;
          opacity: 1 !important;
          position: relative;
          width: 100% !important;
      }
      
      .lb-closeContainer {
          position: absolute;
          top: -30px;
          right: 0;
      }
      
      .lb-close {
          display: block !important;
          opacity: 1 !important;
      }
      
      /* Make sure the lightbox nav arrows are visible */
      .lb-nav a {
          opacity: 0.7 !important;
      }
      
      .lb-nav a:hover {
          opacity: 1 !important;
      }
      
      /* Simple Slider Styles (replacing Owl Carousel) */
      .simple-slider {
          position: relative;
          width: 100%;
          height: 500px;
          overflow: hidden;
      }
      
      .simple-slider-container {
          display: flex;
          width: 100%;
          height: 100%;
          transition: transform 0.5s ease;
      }
      
      .simple-slider-slide {
          flex: 0 0 100%;
          width: 100%;
          height: 100%;
          background-position: center;
          background-size: cover;
          position: relative;
      }
      
      .simple-slider-slide a {
          display: block;
          width: 100%;
          height: 100%;
      }
      
      .simple-slider-nav {
          position: absolute;
          top: 50%;
          transform: translateY(-50%);
          width: 40px;
          height: 40px;
          background: rgba(255,255,255,0.8);
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          cursor: pointer;
          z-index: 10;
          color: #333;
          font-size: 18px;
          box-shadow: 0 2px 5px rgba(0,0,0,0.2);
          opacity: 0.7;
          transition: opacity 0.3s;
      }
      
      .simple-slider-nav:hover {
          opacity: 1;
      }
      
      .simple-slider-prev {
          left: 15px;
      }
      
      .simple-slider-next {
          right: 15px;
      }
      
      .simple-slider-indicators {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
      }
      
      .simple-slider-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
        cursor: pointer;
        transition: background 0.3s;
      }
      
      .simple-slider-indicator.active {
        background: rgba(255,255,255,0.9);
      }
      
      /* Review Popup Styles */
      .review-popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        z-index: 9999;
        overflow-y: auto;
      }
      
      .review-popup .panel {
        margin: 50px auto;
        max-width: 600px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
      }
      
      /* Desktop specific positioning for review popup */
      @media (min-width: 768px) {
        .review-popup .panel.panel-form {
          margin-top: 25vh;
        }
      }
      
      .review-popup .back-site {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255,255,255,0.9);
        color: #333;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 20px;
        z-index: 10000;
      }
      
      .review-popup .back-site:hover {
        background: rgba(255,255,255,1);
      }
      
      /* Star rating styles */
      .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        margin: 20px 0;
      }
      
      .star-rating input {
        display: none;
      }
      
      .star-rating label {
        color: #ddd;
        font-size: 30px;
        padding: 0 5px;
        cursor: pointer;
        transition: color 0.2s;
      }
      
      .star-rating label:hover,
      .star-rating label:hover ~ label,
      .star-rating input:checked ~ label {
        color: #ffd700;
      }
      


    </style>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="{{ asset('assets_public/lib/html5shiv-master/dist/html5shiv.min.js') }}"></script>
      <script src="{{ asset('assets_public/lib/Respond-master/dest/respond.min.js') }}"></script>
    <![endif]-->
  </head>
  <body>
    <!-- Start Body Content Wrapper -->
    <div class="body-wrapper">
      <!-- Start header (topbar) -->
      <header class="header">
        <!-- Start Logo -->
        <div class="logo">
          <a href="{{ url('/') }}" class="logo-color-bg">
            <img alt="{{ config('app.name', 'Youth Central') }}" src="{{ asset('assets_public/images/logo.png') }}"/>
            <span class="logo-text" style="color: #fff; vertical-align: -webkit-baseline-middle; font-size: 20px; font-weight: 550;">Youth Central</span>
          </a>
        </div>
        <!-- End Logo -->
        <!-- Start User Buttons -->
        <div class="user-buttons">
          @auth
            <a href="{{ route('vendor.dashboard') }}" class="add-listing">Add Listing</a>
          @else
            <a href="{{ route('vendor.register') }}" class="add-listing">Add Listing</a>
          @endauth
        </div>
        <!-- End User Buttons -->
        <!-- Start Navbar -->
        @include('layouts.navbar')
        <!-- End Navbar -->
        <!-- Header Search Button -->
        <div class="header-search-button"></div>
      </header>
      <!-- End header (topbar) -->

      <!-- Start Search Popup -->
      <div class="search-popup container-fluid hero-header">
        <!-- Start Header Centralizer -->
        <div class="header-centralizer">
          <div class="content-centralized">
            <!-- Start Hero Search -->
            <div class="hero-search">
              <form action="{{ route('search') }}" method="GET" id="hero-search-form">
                <fieldset>
                  <input type="text" name="query" id="search-query" class="form-control" data-placeholder="Explore and Enjoy..." placeholder="Search businesses...">
                </fieldset>
                <div class="search-cities-toggle"></div>
                <div class="search-cities">
                  <div class="cities-list">
                    @php
                      // Fetch dynamically as in app-public
                      $popularCitiesFromPopup = \App\Models\City::withCount('businesses')
                          ->orderBy('businesses_count', 'desc')
                          ->take(5)
                          ->get();
                    @endphp
                    @foreach($popularCitiesFromPopup as $index => $cityPopup)
                    <a href="#" style="background-image:url('{{ asset('assets_public/images/cities/thumbs/' . (($index % 5) + 1) . '.jpg') }}')" data-city-id="{{ $cityPopup->id }}" {{ $index === 0 ? 'class="current"' : '' }}><span>{{ $cityPopup->name }}</span></a>
                    @endforeach
                    <input class="chosen-city" type="hidden" name="city_id" value="{{ $popularCitiesFromPopup->first()->id ?? '0' }}">
                  </div>
                </div>
                <!-- End Search Cities -->
                <div class="search-submit">
                  <button type="submit" id="search-submit-btn" disabled>
                    <i class="hero-search-icon"></i>
                  </button>
                </div>
              </form>
            </div>
            <!-- End Hero Search -->
            <!-- Start Search Categories -->
            <div class="search-categories">
              <div class="categories">
                @php
                  // Map custom categories to real subcategories with their icons and routes
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
                  
                  // Find matching subcategories for each custom category
                  $customCategories = [];
                  foreach ($customCategoryMappings as $categoryName => $mapping) {
                      // Try to find a matching subcategory
                      $subcategory = null;
                      foreach ($mapping['search_terms'] as $term) {
                          $subcategory = \App\Models\Subcategory::where('name', 'LIKE', '%' . $term . '%')->first();
                          if ($subcategory) {
                              break;
                          }
                      }
                      
                      if ($subcategory) {
                          $customCategories[] = [
                              'name' => $categoryName,
                              'icon' => $subcategory->icon_class ? $subcategory->getFormattedIconClass() : $mapping['icon'],
                              'route' => route('listings', $subcategory)
                          ];
                      } else {
                          // Fallback to search route
                          $customCategories[] = [
                              'name' => $categoryName,
                              'icon' => $mapping['icon'],
                              'route' => route('search') . '?query=' . urlencode($categoryName)
                          ];
                      }
                  }
                @endphp
                @foreach($customCategories as $category)
                  <a class="child" href="{{ $category['route'] }}">
                    <i class="{{ $category['icon'] }}"></i>
                    <span>{{ $category['name'] }}</span>
                  </a>
                @endforeach
                <a href="{{ route('directory.index') }}">
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
      
      <!-- Start Review Popup -->
      <div class="review-popup container-fluid hero-header" style="display: none;">
        <button class="back-site" type="button">×</button>
        <div class="row">
          <div class="col-sm-12 text-center">
            <div class="panel panel-form">
              <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-12">
                    <h4>Write a Review for {{ $business->business_name }}</h4>
                  </div>
                </div>
                <hr>
              </div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-lg-12">
                    @auth
                      @if(Auth::user()->role == 'user')
                        <form id="review-form" class="review-form" action="{{ route('user.reviews.store') }}" method="post">
                          @csrf
                          <input type="hidden" name="business_id" value="{{ $business->id }}">
                          <div class="form-group">
                            <input type="text" name="title" id="review-title" tabindex="1" class="form-control" placeholder="Short Review Title" value="">
                          </div>
                          <div class="form-group">
                            <textarea class="form-control" name="comment" id="review-message" rows="8" placeholder="Describe your experience" required></textarea>
                          </div>
                          <div class="form-group">
                            <div class="star-rating">
                              <fieldset>
                                <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="Outstanding">5 stars</label>
                                <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="Very Good">4 stars</label>
                                <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="Good">3 stars</label>
                                <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="Poor">2 stars</label>
                                <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="Very Poor">1 star</label>
                              </fieldset>
                            </div>
                          </div>
                          <div class="form-group">
                            <input type="submit" name="review-submit" id="review-submit" tabindex="4" class="form-control btn btn-submit" value="Submit Review">
                          </div>
                        </form>
                      @else
                        <div class="alert alert-info">
                          <p>Only regular users can write reviews. Please log in with a user account to continue.</p>
                          <a href="/signin" class="btn btn-primary mt-3">Login as User</a>
                        </div>
                      @endif
                    @else
                      <div class="alert alert-info">
                        <p>Please login to write a review.</p>
                        <a href="/signin" class="btn btn-primary mt-3">Login</a>
                      </div>
                    @endauth
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Review Popup -->
      
      <!-- Start Fixed Quick Menu -->
      <div class="quick-menu">
        <a href="#" class="icon-arrow-up hide-menu" title="Hide Quick Menu" data-toggle="tooltip" data-placement="right"></a>
        <a href="#" class="icon-arrow-down hidden show-menu" title="Show Quick Menu" data-toggle="tooltip" data-placement="right"></a>
        <a href="#gal" class="icon-focus" title="Gallery" data-toggle="tooltip" data-placement="right"></a>
        <a href="#desc" class="icon-copy" title="Description" data-toggle="tooltip" data-placement="right"></a>
        <a href="#rev" class="icon-star" title="Reviews" data-toggle="tooltip" data-placement="right"></a>
        <a href="#mp" class="icon-map-marker" title="Map" data-toggle="tooltip" data-placement="right"></a>
        <a href="#rel" class="icon-link" title="Related Listings" data-toggle="tooltip" data-placement="right"></a>
      </div>
      <!-- End Fixed Quick Menu -->
      
      <!-- Start Fixed Pricing Button -->
      @if($business->price_range)
        <a href="#" class="fixed-pricing-button">
          <div class="ribbon">
            <div class="pricing-data">
              {{ $business->price_range }}
            </div>
            <div class="pricing-data-hover">
              Make a Reservation
            </div>
          </div>
        </a>
      @endif
      <!-- End Fixed Pricing Button -->
      
      <!-- Start Listing Title -->
      <div class="container-fluid listing-title">
        <div class="breadcrumbs text-left">
          <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ route('directory.index') }}">Directory</a></li>
            <li><a href="{{ route('public.category', $business->subcategory->category) }}">{{ $business->subcategory->category->name }}</a></li>
            <li><a href="{{ route('listings', $business->subcategory) }}">{{ $business->subcategory->name }}</a></li>
            <li>{{ $business->business_name }}</li>
          </ul>
        </div>
        <div class="row text-center">
          <h2>
            {{ $business->business_name }}
            <a href="#" class="listing-item-rating">{{ number_format($business->average_rating, 1) }}</a>
            <span class="rating-count">({{ $business->reviews_count }})</span>
          </h2>
        </div>
        <div class="pg-header-icon icon-focus"></div>
      </div>
      <!-- End Listing Title -->
      
      <!-- Start Gallery -->
      <div class="container-fluid gallery" id="gal">
        @php
          $logoExists = $business->logo_path && Storage::disk('public')->exists($business->logo_path);
          // Fetch gallery images once
          $galleryImages = $business->images;
          // Filter gallery images to only include those that exist in storage
          $validGalleryImages = $galleryImages->filter(function($image) {
              return $image->path && Storage::disk('public')->exists($image->path);
          });

          $hasUploadedImages = $logoExists || $validGalleryImages->isNotEmpty();
          $imageUrls = [];
          
          // Collect all image URLs
          if ($logoExists) {
              $imageUrls[] = [
                  'url' => asset('storage/' . $business->logo_path),
                  'title' => $business->business_name . ' Logo'
              ];
          }
          
          foreach ($validGalleryImages as $image) {
              $imageUrls[] = [
                  'url' => asset('storage/' . $image->path),
                  'title' => $business->business_name
              ];
          }
          
          // If no images, use defaults
          if (empty($imageUrls)) {
              for ($i = 1; $i <= 3; $i++) {
                  $imageUrls[] = [
                      'url' => asset('assets_public/images/' . $i . '.jpg'),
                      'title' => $business->business_name . ' Placeholder Image ' . $i
                  ];
              }
          }
          
          $totalImages = count($imageUrls);
        @endphp

        <div class="simple-slider">
            <div class="simple-slider-container" id="slider-container">
                @foreach($imageUrls as $index => $image)
                    <div class="simple-slider-slide" style="background-image: url('{{ $image['url'] }}');">
                        <a href="{{ $image['url'] }}" 
                           data-lightbox="business-gallery" 
                           data-title="{{ $image['title'] }}"
                           style="display: block; width: 100%; height: 100%;"
                           onclick="openLightbox(event, '{{ $image['url'] }}', '{{ $image['title'] }}')"></a>
                    </div>
                @endforeach
            </div>
            
            <div class="simple-slider-nav simple-slider-prev">
                <i class="fa-solid fa-chevron-left"></i>
            </div>
            <div class="simple-slider-nav simple-slider-next">
                <i class="fa-solid fa-chevron-right"></i>
            </div>
            
            <div class="simple-slider-indicators">
                @for($i = 0; $i < $totalImages; $i++)
                    <div class="simple-slider-indicator {{ $i === 0 ? 'active' : '' }}" onclick="goToSlide({{ $i }})"></div>
                @endfor
            </div>
        </div>
      </div>
      <!-- End Gallery -->
      
      <!-- Start Listing Description -->
      <div class="container listing-description" id="desc">
        <div class="pg-header-icon icon-copy"></div>
        <div class="row">
          <div class="col-sm-3 text-center">
            <div class="listing-avatar">
              @php
                $imageUrl = asset('assets_public/images/placeholder-logo.png'); // Default placeholder
                if ($business->logo_path && Storage::disk('public')->exists($business->logo_path)) {
                    $imageUrl = asset('storage/' . $business->logo_path);
                } elseif ($business->images->isNotEmpty() && Storage::disk('public')->exists($business->images->first()->path)) {
                    $imageUrl = asset('storage/' . $business->images->first()->path);
                }
              @endphp
              <img alt="{{ $business->business_name }}" src="{{ $imageUrl }}" class="center-block" />
            </div>
            @if($business->claimed_by)
              <div class="claimed text-center">
                <div class="ribbon">Claimed</div>
              </div>
            @else
              <div class="unclaimed text-center mt-3 mb-3">
                {{-- b. Removed the blue button: <a href="{{ route('vendor.claims.create', $business) }}" class="btn btn-primary"> --}}
                {{-- Removed: <i class="fa fa-check-circle"></i> Claim This Business --}}
                {{-- Removed: </a> --}}
                {{-- Removed: <p class="small text-muted mt-2">Are you the owner?</p> --}}
              </div>
            @endif
            <div class="listing-social">
              <div class="social-networks">
                
                @if($business->facebook_link)
                  <a href="{{ $business->facebook_link }}" class="fa-brands fa-facebook-f" target="_blank"></a>
                @endif
                @if($business->twitter_link)
                  <a href="{{ $business->twitter_link }}" class="fa-brands fa-twitter" target="_blank"></a>
                @endif
                @if($business->instagram_link)
                  <a href="{{ $business->instagram_link }}" class="fa-brands fa-instagram" target="_blank"></a>
                @endif
                @if($business->pinterest_link)
                  <a href="{{ $business->pinterest_link }}" class="fa-brands fa-pinterest" target="_blank"></a>
                @endif
              </div>
            </div>
            <div class="list-data">
              <div class="list-address">
                {{ $business->street_address }}, {{ $business->area->name }}, {{ $business->area->city->name }}
              </div>
              <div class="list-phones">
                @if($business->phone)
                  <div class="list-phone">
                    {{-- c. Made phone number clickable --}}
                    <a href="tel:{{ preg_replace('/[^0-9+]/ ', '', $business->phone) }}">{{ $business->phone }}</a>
                    {{-- <a href="tel:{{ preg_replace('/[^0-9+]/ ', '', $business->phone) }}" class="btn btn-sm btn-success ml-2"><i class="fa fa-phone"></i> Call</a> --}}
                  </div>
                @endif
                {{-- @if($business->whatsapp_number)
                  <div class="list-mobile">
                    {{ $business->whatsapp_number }} 
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp_number) }}" class="btn btn-sm btn-success" style="background-color: #25D366; border-color: #25D366;" target="_blank">
                      <i class="fa fa-whatsapp"></i> WhatsApp
                    </a>
                  </div>
                @endif --}}
              </div>
              
              <!-- Social Media Contact Buttons -->
              <div class="social-contact-buttons">
                @if($business->phone)
                  <a href="tel:{{ $business->phone }}" class="btn btn-primary"><i class="fa-solid fa-phone"></i> Call</a>
                @endif
                
                @if($business->whatsapp_number)
                  <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp_number) }}" class="btn btn-success" style="background-color: #25D366; border-color: #25D366;" target="_blank"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>
                @endif
                
                @if($business->email)
                  <a href="mailto:{{ $business->email }}" class="btn btn-info"><i class="fa-solid fa-envelope"></i> Email</a>
                @endif
                
                <div class="share-buttons">
                  <p style="font-weight: bold; margin-bottom: 5px;">Share:</p>
                  <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" class="btn btn-primary" style="background-color: #3b5998; border-color: #3b5998;" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                  <a href="https://twitter.com/intent/tweet?text={{ urlencode($business->business_name) }}&url={{ urlencode(url()->current()) }}" class="btn btn-info" style="background-color: #1DA1F2; border-color: #1DA1F2;" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                  <a href="https://api.whatsapp.com/send?text={{ urlencode($business->business_name . ' - ' . url()->current()) }}" class="btn btn-success" style="background-color: #25D366; border-color: #25D366;" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                  <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($business->business_name) }}" class="btn btn-primary" style="background-color: #0077B5; border-color: #0077B5;" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
                </div>
              </div>
              
              <!-- Claim Business Button -->
              <div class="claim-business-button" style="margin-top: 20px;">
                @auth
                  @if(Auth::user()->role === 'vendor')
                    <a href="{{ route('vendor.claims.create', $business) }}" class="btn btn-warning" style="width: 100%;">
                      <i class="fa-solid fa-flag"></i> Claim this Business
                    </a>
                  @else
                    <button type="button" class="btn btn-warning" style="width: 100%;" data-toggle="modal" data-target="#claimBusinessModal">
                      <i class="fa-solid fa-flag"></i> Claim this Business
                    </button>
                  @endif
                @else
                  <button type="button" class="btn btn-warning" style="width: 100%;" data-toggle="modal" data-target="#claimBusinessModal">
                    <i class="fa-solid fa-flag"></i> Claim this Business
                  </button>
                @endauth
                <p class="text-muted" style="font-size: 12px; margin-top: 5px;">Is this your business? Claim it now to manage and update your business information.</p>
              </div>
              
              <div class="list-online-data">
                @if($business->email)
                  <div class="list-email">
                    <a href="mailto:{{ $business->email }}">
                      {{ $business->email }}
                    </a>
                  </div>
                @endif
                @if($business->website)
                  <div class="list-website">
                    <a href="{{ $business->website }}" target="_blank">
                      {{ $business->website }}
                    </a>
                  </div>
                @endif
              </div>
            </div>
          </div>
          <div class="col-sm-9">
            <div>
              <div class="description-container">
                <table class="description-text table">
                  <tr>
                    <td class="description">
                      <h4 class="listing-subtitle text-left">
                        {{ $business->business_name }} - {{ $business->area->city->name }}
                      </h4>
                      <p>
                        {{ $business->description ?? 'No description available for this business yet.' }}
                      </p>
                    </td>
                    <td class="listing-opening">
                      <h5 class="opening-title text-left">Opening Hours</h5>
                      @php
                          $days = [
                              'monday' => 'Monday',
                              'tuesday' => 'Tuesday',
                              'wednesday' => 'Wednesday',
                              'thursday' => 'Thursday',
                              'friday' => 'Friday',
                              'saturday' => 'Saturday',
                              'sunday' => 'Sunday'
                          ];
                          $currentDay = strtolower(date('l'));

                          $defaultHours = [
                              'monday'    => ['open' => '09:30', 'close' => '18:30'],
                              'tuesday'   => ['open' => '09:30', 'close' => '18:30'],
                              'wednesday' => ['open' => '09:30', 'close' => '18:30'],
                              'thursday'  => ['open' => '09:30', 'close' => '18:30'],
                              'friday'    => ['open' => '09:30', 'close' => '18:30'],
                              'saturday'  => ['open' => '09:30', 'close' => '18:30'],
                              'sunday'    => ['open' => '12:00', 'close' => '18:00'],
                          ];
                      @endphp
                      <div class="business-hours-display">
                          @foreach ($days as $dayKey => $dayName)
                              @php
                                  $openKey = $dayKey . '_open';
                                  $closeKey = $dayKey . '_close';
                                  $closedKey = $dayKey . '_closed';
                                  
                                  $isDayClosed = $business->$closedKey;
                                  
                                  $openingTimeValue = $business->$openKey ?? $defaultHours[$dayKey]['open'];
                                  $closingTimeValue = $business->$closeKey ?? $defaultHours[$dayKey]['close'];

                                  $openingTime = $openingTimeValue ? \Carbon\Carbon::parse($openingTimeValue)->format('h:i A') : 'N/A';
                                  $closingTime = $closingTimeValue ? \Carbon\Carbon::parse($closingTimeValue)->format('h:i A') : 'N/A';
                              @endphp
                              <div class="day {{ $currentDay == $dayKey ? 'today font-weight-bold' : '' }}">
                                  <span class="day-name">{{ $dayName }}{{ $currentDay == $dayKey ? ' (Today)' : '' }}:</span>
                                  @if ($isDayClosed)
                                      <span class="hours closed">Closed</span>
                                  @else
                                      <span class="hours">{{ $openingTime }} - {{ $closingTime }}</span>
                                  @endif
                              </div>
                          @endforeach
                      </div>
                    </td>
                  </tr>
                </table>
              </div>
              
              <!-- Amenities section -->
              {{-- <div class="listing-amenities">
                <h5 class="amenities-title text-left">
                  Amenities
                </h5>
                <div class="amenities">
                  @if($business->accepts_card)
                    <a href="#" class="icon-credit-card">Accepts Credit Cards</a>
                  @endif
                  @if($business->has_parking)
                    <a href="#" class="icon-parking">Parking</a>
                  @endif
                  @if($business->has_bike_parking)
                    <a href="#" class="icon-bicycle">Bike Parking</a>
                  @endif
                  @if($business->is_wheelchair_accessible)
                    <a href="#" class="icon-wheelchair">Wheelchair Accessible</a>
                  @endif
                  @if($business->has_wifi)
                    <a href="#" class="icon-wifi">Wi-fi</a>
                  @endif
                  @if($business->has_tv)
                    <a href="#" class="icon-screen">TV</a>
                  @endif
                  @if($business->allows_smoking)
                    <a href="#" class="icon-pipe">Smoking Allowed</a>
                  @endif
                  @if($business->accepts_reservations)
                    <a href="#" class="icon-calendar-insert">Reservations</a>
                  @endif
                  @if($business->serves_alcohol)
                    <a href="#" class="icon-glass">Alcohol</a>
                  @endif
                </div>
              </div> --}}
              
              <!-- Target Audience section -->
              {{-- <div class="listing-amenities">
                <h5 class="amenities-title text-left">
                  Target Audience
                </h5>
                <div class="amenities">
                  @if($business->suitable_for_solo)
                    <a href="#" class="icon-man">Solo</a>
                  @endif
                  @if($business->suitable_for_couples)
                    <a href="#" class="icon-users2">Couple</a>
                  @endif
                  @if($business->suitable_for_families)
                    <a href="#" class="icon-users">Families</a>
                  @endif
                  @if($business->suitable_for_groups)
                    <a href="#" class="icon-group-work">Friends</a>
                  @endif
                  @if($business->suitable_for_parties)
                    <a href="#" class="icon-cake">Parties</a>
                  @endif
                  @if($business->suitable_for_business)
                    <a href="#" class="icon-tie">Business</a>
                  @endif
                </div>
              </div> --}}
              
              <!-- Tags section -->
              <div class="listing-amenities">
                <h5 class="amenities-title text-left">
                  Tags
                </h5>
                <div class="amenities tags">
                  <a href="{{ route('listings', $business->subcategory) }}">{{ $business->subcategory->name }}</a>
                  <a href="{{ route('public.category', $business->subcategory->category) }}">{{ $business->subcategory->category->name }}</a>
                  @foreach($business->tags ?? [] as $tag)
                    <a href="#">{{ $tag->name }}</a>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Listing Description -->
      
      <!-- Start Review -->
      <div class="container listing-review" id="rev">
        <div class="pg-header-icon icon-star"></div>
        <div class="row">
          <div class="col-sm-3 text-center">
            <div class="average-review">
              <div class="mood-icon icon-{{ $business->average_rating >= 4.5 ? 'happy' : ($business->average_rating >= 3.5 ? 'smile' : ($business->average_rating >= 2.5 ? 'neutral' : ($business->average_rating >= 1.5 ? 'wondering' : 'mad'))) }}"></div>
              <div class="rating">{{ number_format($business->average_rating, 1) }}</div>
              <div class="stars">
                @for($i = 1; $i <= 5; $i++)
                  @if($i <= round($business->average_rating))
                    <i class="fa-solid fa-star"></i>
                  @elseif($i - 0.5 <= $business->average_rating)
                    <i class="fa-regular fa-star-half-stroke"></i>
                  @else
                    <i class="fa-regular fa-star"></i>
                  @endif
                @endfor
              </div>
              <div class="review-stats">
                <div class="review-counter">{{ $business->reviews_count }}</div>
                <div>Reviews</div>
              </div>
              <img alt="" src="{{ asset('assets_public/images/miscellaneous/review-background-mask.png') }}" />
            </div>
            <div class="review-reputation text-center">
              <div>
                @php
                  $rating = $business->average_rating;
                  $reputation = 'Not Rated';
                  if($rating >= 4.5) $reputation = 'Excellent';
                  elseif($rating >= 4) $reputation = 'Very Good';
                  elseif($rating >= 3) $reputation = 'Good';
                  elseif($rating >= 2) $reputation = 'Average';
                  elseif($rating > 0) $reputation = 'Poor';
                @endphp
                <h4>{{ $reputation }}</h4>
              </div>
            </div>
            <div class="write-review">
              @auth
                @if(Auth::user()->role == 'user')
                  <a href="#" class="button" id="write-review-btn">
                    Write a Review
                  </a>
                @else
                  <a href="/signin" class="button">
                    Login as User to Review
                  </a>
                @endif
              @else
                <a href="/signin" class="button">
                  Login to Write a Review
                </a>
              @endauth
            </div>
          </div>
          <div class="col-sm-9">
            @if($business->reviews()->approved()->count() > 0)
              @foreach($business->reviews()->approved()->latest()->get() as $review)
                <!-- Start Review Author Block -->
                <div class="row author-block">
                  <div class="col-sm-12 text-left">
                    <a href="#" class="author-avatar pull-left" style="background-color: #eee; display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; border-radius: 50%; margin-right: 15px;">
                      <i class="fa-solid fa-user fa-2x text-muted"></i>
                      <div class="author-stats">
                        <div class="post-counter">{{ $review->user->reviews()->count() }}</div>
                        <div>Reviews</div>
                      </div>
                    </a>
                    <a href="#" class="author-name">{{ $review->user->name }}</a>
                    <div class="author-vote-mood icon-{{ $review->rating >= 4.5 ? 'happy' : ($review->rating >= 3.5 ? 'smile' : ($review->rating >= 2.5 ? 'neutral' : ($review->rating >= 1.5 ? 'wondering' : 'mad'))) }}"></div>
                    <div class="author-vote">
                      @if($review->rating >= 4.5) Excellent
                      @elseif($review->rating >= 3.5) Very Good
                      @elseif($review->rating >= 2.5) Good
                      @elseif($review->rating >= 1.5) Average
                      @else Poor
                      @endif
                      <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                          @if($i <= $review->rating)
                            <i class="fa-solid fa-star"></i>
                          @else
                            <i class="fa-regular fa-star"></i>
                          @endif
                        @endfor
                      </div>
                    </div>
                    <span class="data icon-calendar-full">{{ $review->created_at->format('d/m/Y') }}</span>
                    <div class="clear"></div>
                    <div class="author-title">
                      {{ $review->title ?? 'Review for ' . $business->business_name }}
                    </div>
                    <div class="author-description">
                      {{ $review->comment ?? 'No additional comments provided.' }}
                    </div>
                    
                    @if($review->vendor_response)
                      <div class="vendor-response" style="margin-top: 15px; margin-left: 30px; padding: 15px; background-color: #f8f9fa; border-radius: 4px;">
                        <h6 style="color: #333; margin-bottom: 10px;">Business Response</h6>
                        <p style="color: #666;">{{ $review->vendor_response }}</p>
                      </div>
                    @endif
                  </div>
                </div>
                <!-- End Review Author Block -->
              @endforeach
            @else
              <div class="text-center py-5">
                <h5>No Reviews Yet</h5>
                <p>Be the first to review this business!</p>
              </div>
            @endif
          </div>
        </div>
      </div>
      <!-- End Review -->
      
      <!-- Start Map -->
      <div class="container-fluid listing-map" id="mp">
        <div class="pg-header-icon icon-map-marker"></div>
        @if($business->latitude && $business->longitude)
        <div id="map" style="height: 400px;"></div>
        @else
          <div style="height: 400px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border: 1px solid #dee2e6; border-radius: 4px;">
            <div class="text-center">
              <i class="fa-solid fa-location-dot fa-3x text-muted mb-3"></i>
              <h5 class="text-muted">Map Not Available</h5>
              <p class="text-muted">Location coordinates are not available for this business.</p>
            </div>
          </div>
        @endif
      </div>
      <!-- End Map -->
      
      <!-- Start Related Listings -->
      <div class="container-fluid listing-block related" id="rel">
        <div class="pg-header-icon icon-link"></div>
        <h2>Related Businesses</h2>
        <div class="row listing white">
          @foreach($similarBusinesses as $similarBusiness)
            <!-- Start Listing Item Col -->
            <div class="col-sm-3">
              <div class="listing-item">
                <a href="#" class="category-icon">
                  <i class="{{ $similarBusiness->subcategory->getFormattedIconClass() ?? 'fas fa-bookmark' }}"></i>
                </a>
                <div class="listing-item-rating">{{ number_format($similarBusiness->average_rating, 1) }}</div>
                <a href="{{ route('public.business.show', $similarBusiness) }}" class="listing-item-link">
                  @if($similarBusiness->images && $similarBusiness->images->count() > 0)
                    <img src="{{ asset('storage/' . $similarBusiness->images->first()->path) }}" alt="{{ $similarBusiness->business_name }}">
                  @else
                    <img src="{{ asset('assets_public/images/listings/1.jpg') }}" alt="{{ $similarBusiness->business_name }}">
                  @endif
                  <div class="listing-item-title-centralizer">
                    <div class="listing-item-title">
                      {{ $similarBusiness->business_name }}
                    </div>
                    <div class="listing-item-address" style="margin-top: 10px;">
                      {{ $similarBusiness->street_address }}, {{ $similarBusiness->area->name }}, {{ $similarBusiness->area->city->name }}
                    </div>
                  </div>
                </a>
                <div class="listing-item-data" style="margin-top: 50px;">
                  <div class="listing-item-rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                      @if($i <= round($similarBusiness->average_rating))
                        <i class="fa-solid fa-star"></i>
                      @elseif($i - 0.5 <= $similarBusiness->average_rating)
                        <i class="fa-regular fa-star-half-stroke"></i>
                      @else
                        <i class="fa-regular fa-star"></i>
                      @endif
                    @endfor
                  </div>
                  <div class="listing-item-review-count">
                    {{ $similarBusiness->reviews_count }} Reviews
                  </div>
                  <div class="listing-item-category">
                    <a href="{{ route('listings', $similarBusiness->subcategory) }}">{{ $similarBusiness->subcategory->name }}</a>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Listing Item Col -->
          @endforeach
        </div>
      </div>
      <!-- End Related Listings -->

      <!-- Include Footer -->
      @include('layouts.footer')

    </div>
    <!-- End Body Content Wrapper -->
    
    <!-- Claim Business Modal -->
    <div class="modal fade" id="claimBusinessModal" tabindex="-1" role="dialog" aria-labelledby="claimBusinessModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="claimBusinessModalLabel" style="color: #fff;">Claim Your Business</h4>
            {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button> --}}
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <h5>How to Claim {{ $business->business_name }}</h5>
                <p>Taking control of your business listing allows you to update your information, respond to reviews, and get more visibility. Follow these steps to claim this listing:</p>
                
                <div class="claim-steps">
                  <div class="step" style="margin-bottom: 20px; border-left: 3px solid #ffc107; padding-left: 15px;">
                    <h6><i class="fa-solid fa-user-plus" style="color: #ffc107;"></i> Step 1: Register as a Vendor</h6>
                    <p>Sign up for a vendor account on our platform to get started with the claiming process.</p>
                    <a href="{{ route('vendor.register') }}" class="btn btn-outline-primary">Register as Vendor</a>
                  </div>
                  
                  <div class="step" style="margin-bottom: 20px; border-left: 3px solid #ffc107; padding-left: 15px;">
                    <h6><i class="fa-solid fa-credit-card" style="color: #ffc107;"></i> Step 2: Select a Subscription Plan</h6>
                    <p>After registration and account approval, choose a subscription plan that suits your business needs.</p>
                    <p>Having an active subscription is required to claim and manage your business listing.</p>
                  </div>
                  
                  <div class="step" style="margin-bottom: 20px; border-left: 3px solid #ffc107; padding-left: 15px;">
                    <h6><i class="fa-solid fa-flag" style="color: #ffc107;"></i> Step 3: Claim Your Business</h6>
                    <p>Once you have an active subscription, you can initiate the claim process for your business. Our team will verify your ownership and approve the claim.</p>
                  </div>
                  
                  <div class="step" style="margin-bottom: 20px; border-left: 3px solid #ffc107; padding-left: 15px;">
                    <h6><i class="fa-solid fa-circle-check" style="color: #ffc107;"></i> Step 4: Manage Your Listing</h6>
                    <p>After approval, you'll have full control to update business information, add photos, respond to reviews, and more!</p>
                  </div>
                </div>
                
                <div class="alert alert-info" style="margin-top: 20px;">
                  <p><strong>Already a vendor?</strong> Log in to your vendor account to claim this business.</p>
                  <a href="{{ route('vendor.login') }}" class="btn btn-info">Vendor Login</a>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <a href="{{ route('vendor.register') }}" class="btn btn-primary">Sign Up as Vendor</a>
          </div>
        </div>
      </div>
    </div>
    
    <!-- jQuery and all JS libraries -->
    <script src="{{ asset('assets_public/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/lightbox2-master/dist/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/typed.js-master/dist/typed.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/jquery.dragscroll.js') }}"></script>
    <script src="{{ asset('assets_public/lib/jquery-mousewheel-master/jquery.mousewheel.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/bootstrap-select-master/js/bootstrap-select.js') }}"></script>
    <!-- Map JavaScript -->
    <script src="{{ asset('assets_public/lib/Leaflet-1.0.2/dist/leaflet.js') }}"></script>
    <script src="{{ asset('assets_public/js/main.js') }}"></script>
    <script src="{{ asset('js/search-validation.js') }}"></script>
    
    <script>
    $(document).ready(function() {
      // Setup CSRF token for AJAX requests
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      
      // Enable/disable search button based on input
      $('#search-query').on('input', function() {
        if ($(this).val().trim().length > 0) {
          $('#search-submit-btn').prop('disabled', false);
        } else {
          $('#search-submit-btn').prop('disabled', true);
        }
      });
      
      // ==================== MAP FUNCTIONALITY ====================
      @if($business->latitude && $business->longitude)
        // Initialize map for single business
        var map = L.map('map').setView([{{ $business->latitude }}, {{ $business->longitude }}], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add marker for this business
        @php
          $imagePath = '';
          if($business->images && $business->images->isNotEmpty() && $business->images->first()) {
            $imagePath = asset('storage/' . $business->images->first()->path);
          } else {
            $imagePath = asset('assets_public/images/listings/1.jpg');
          }
        @endphp
        
        var marker = L.marker([{{ $business->latitude }}, {{ $business->longitude }}])
          .addTo(map);
      @endif

      // ==================== CAROUSEL FUNCTIONALITY ====================
      let currentSlide = 0;
      const totalSlides = {{ count($imageUrls) }};
      const sliderContainer = document.getElementById('slider-container');
      
      // Function to go to specific slide
      window.goToSlide = function(slideIndex) {
        currentSlide = slideIndex;
        updateSlider();
      };
      
      // Function to update slider position and indicators
      function updateSlider() {
        if (sliderContainer) {
          const translateX = -currentSlide * 100;
          sliderContainer.style.transform = `translateX(${translateX}%)`;
          
          // Update indicators
          $('.simple-slider-indicator').removeClass('active');
          $(`.simple-slider-indicator:nth-child(${currentSlide + 1})`).addClass('active');
        }
      }
      
      // Previous button click
      $('.simple-slider-prev').click(function() {
        currentSlide = currentSlide === 0 ? totalSlides - 1 : currentSlide - 1;
        updateSlider();
      });
      
      // Next button click
      $('.simple-slider-next').click(function() {
        currentSlide = currentSlide === totalSlides - 1 ? 0 : currentSlide + 1;
        updateSlider();
      });
      
      // Auto-advance slides every 5 seconds
      setInterval(function() {
        currentSlide = currentSlide === totalSlides - 1 ? 0 : currentSlide + 1;
        updateSlider();
      }, 5000);
      
      // ==================== LIGHTBOX FUNCTIONALITY ====================
      window.openLightbox = function(event, imageUrl, title) {
        event.preventDefault();
        lightbox.open();
      };

      // ==================== REVIEW POPUP FUNCTIONALITY ====================
      // Override main.js event handlers for review functionality
      $("body").off("click", ".write-review a");
      $("body").off("click", ".review-popup .back-site");
      
      // Prevent main.js from handling our review button
      $('#write-review-btn').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Prevent main.js event from bubbling
        e.stopImmediatePropagation();
        
        // Show the review popup
        $('body').addClass('review-popup-open');
        $('.review-popup').css({
          'display': 'block',
          'left': '-100%',
          'opacity': '0'
        }).animate({
          'left': '0',
          'opacity': '1'
        }, 500);
        
        // Override main.js positioning
        setTimeout(function() {
          if ($(window).width() >= 768) {
            // Desktop positioning
            $('.review-popup .panel.panel-form').css({
              'top': '25%',
              'margin-top': '0',
              'position': 'relative',
              'display': 'inline-block'
            });
            $('.review-popup .row').css({
              'overflow-y': 'auto',
              'height': 'auto',
              'margin-top': '0'
            });
          } else {
            // Mobile positioning
            $('.review-popup .panel.panel-form').css({
              'top': '55px',
              'margin-top': '0',
              'position': 'relative'
            });
          }
        }, 100);
      });
      
      // Hide review popup when back button is clicked or escape is pressed
      $('.review-popup .back-site, .review-popup .close-popup').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        hideReviewPopup();
      });
      
      // Close popup when clicking outside the form area
      $('.review-popup').click(function(e) {
        if (e.target === this) {
          hideReviewPopup();
        }
      });
      
      // Close popup with Escape key
      $(document).keyup(function(e) {
        if (e.keyCode === 27) { // Escape key
          hideReviewPopup();
        }
      });
      
      function hideReviewPopup() {
        $('.review-popup').animate({
          'left': '-100%',
          'opacity': '0'
        }, 500, function() {
          $(this).css('display', 'none');
          $('body').removeClass('review-popup-open');
        });
      }
      
      // Prevent main.js from interfering with our popup positioning
      $(window).resize(function() {
        if ($('.review-popup').is(':visible')) {
          setTimeout(function() {
            if ($(window).width() >= 768) {
              $('.review-popup .panel.panel-form').css({
                'top': '25%',
                'margin-top': '0',
                'position': 'relative',
                'display': 'inline-block'
              });
            } else {
              $('.review-popup .panel.panel-form').css({
                'top': '55px',
                'margin-top': '0',
                'position': 'relative'
              });
            }
          }, 50);
        }
      });

      // Handle review form submission
      $('#review-form').submit(function(e) {
        e.preventDefault();
        
        var form = $(this);
        var submitBtn = $('#review-submit');
        var originalText = submitBtn.val();
        
        // Disable submit button and show loading state
        submitBtn.prop('disabled', true).val('Submitting...');
        
        // Submit the form
        $.ajax({
          url: form.attr('action'),
          method: 'POST',
          data: form.serialize(),
          dataType: 'json'
        })
        .done(function(response) {
          // Show success message
          hideReviewPopup();
          
          // Show success notification
          $('body').prepend(`
            <div class="alert alert-success alert-dismissible fade show" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 300px;">
              <i class="fa-solid fa-check-circle me-2"></i>
              Review submitted successfully! It will be visible after approval.
              <button type="button" class="btn-close" data-bs-dismiss="alert" onclick="$(this).parent().remove()"></button>
            </div>
          `);
          
          // Auto-hide notification after 5 seconds
          setTimeout(function() {
            $('.alert-success').fadeOut();
          }, 5000);
          
          // Reset form
          form[0].reset();
          $('.star-rating input').prop('checked', false);
        })
        .fail(function(xhr) {
          // Show error message
          var errorMessage = 'An error occurred. Please try again.';
          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
          } else if (xhr.responseJSON && xhr.responseJSON.errors) {
            // Handle validation errors
            var errors = xhr.responseJSON.errors;
            errorMessage = Object.values(errors).flat().join(', ');
          }
          
          $('body').prepend(`
            <div class="alert alert-danger alert-dismissible fade show" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 300px;">
              <i class="fa-solid fa-exclamation-triangle me-2"></i>
              ${errorMessage}
              <button type="button" class="btn-close" data-bs-dismiss="alert" onclick="$(this).parent().remove()"></button>
            </div>
          `);
          
          // Auto-hide notification after 5 seconds
          setTimeout(function() {
            $('.alert-danger').fadeOut();
          }, 5000);
        })
        .always(function() {
          // Re-enable submit button
          submitBtn.prop('disabled', false).val(originalText);
        });
      });

      // ==================== QUICK MENU FUNCTIONALITY ====================
      // Handle quick menu navigation
      $('.quick-menu a').click(function(e) {
        e.preventDefault();
        var targetClass = $(this).attr('class').split(' ')[0]; // Get first class
        var targetSection = '';
        
        switch(targetClass) {
          case 'icon-focus':
            targetSection = '#gal';
            break;
          case 'icon-copy':
            targetSection = '#desc';
            break;
          case 'icon-star':
            targetSection = '#rev';
            break;
          case 'icon-map-marker':
            targetSection = '#mp';
            break;
          case 'icon-link':
            targetSection = '#rel';
            break;
        }
        
        if (targetSection) {
          $('html, body').animate({
            scrollTop: $(targetSection).offset().top - 100
          }, 600);
        }
      });
      
      // Handle hide/show quick menu
      $('.hide-menu').click(function(e) {
        e.preventDefault();
        $('.quick-menu a:not(.hide-menu, .show-menu)').fadeOut();
        $('.hide-menu').addClass('hidden');
        $('.show-menu').removeClass('hidden');
      });
      
      $('.show-menu').click(function(e) {
        e.preventDefault();
        $('.quick-menu a:not(.hide-menu, .show-menu)').fadeIn();
        $('.show-menu').addClass('hidden');
        $('.hide-menu').removeClass('hidden');
      });

      // ==================== STAR RATING FUNCTIONALITY ====================
      // Handle star rating interaction
      $('.star-rating label').click(function() {
        var rating = $(this).prev('input').val();
        console.log('Rating selected:', rating);
      });
      
      // Ensure close button exists in review popup
      if ($('.review-popup .back-site').length === 0) {
        $('.review-popup').prepend('<button class="back-site" type="button">×</button>');
      }
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Search Button
        var searchButton = document.querySelector('.header-search-button');
        var searchPopup = document.querySelector('.search-popup');
        
        if (searchButton && searchPopup) {
            searchButton.addEventListener('click', function(e) {
                e.preventDefault();
                searchPopup.classList.toggle('active');
            });
        }

        // City selection in search
        var cityLinks = document.querySelectorAll('.search-popup .cities-list a');
        var cityInput = document.querySelector('.search-popup .chosen-city');
        
        if (cityLinks.length > 0 && cityInput) {
          cityLinks.forEach(function(link) {
              link.addEventListener('click', function(e) {
                  e.preventDefault();
                  
                  // Remove current class from all links
                  cityLinks.forEach(function(el) {
                      el.classList.remove('current');
                  });
                  
                  // Add current class to clicked link
                  this.classList.add('current');
                  
                  // Update the hidden input with the city ID
                  cityInput.value = this.getAttribute('data-city-id');
              });
          });
        }
    });
    </script>
    
    <!-- Location Selector Modal -->
    @include('partials.location-selector-modal')
  </body>
</html>