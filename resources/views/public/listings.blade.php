<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $subcategory->name }} - Youth Central Directory</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
    {{-- <!--<style>-->
      /* Map visibility */
    <!--  .map-listing {-->
    <!--    position: relative;-->
    <!--    height: 450px;-->
    <!--    overflow: hidden;-->
    <!--    transition: height 0.3s ease;-->
    <!--  }-->
      
    <!--  .map-listing.map-hidden {-->
    <!--    height: 250px;-->
    <!--  }-->
      
    <!--  #map {-->
    <!--    height: 100%;-->
    <!--    width: 100%;-->
    <!--    z-index: 1;-->
    <!--  }-->
      
    <!--    .close-map-btn {-->
    <!--      position: absolute;-->
    <!--      top: 15px;-->
    <!--      right: 15px;-->
    <!--      z-index: 1000;-->
    <!--      background-color: #fff;-->
    <!--      color: #333;-->
    <!--      border: none;-->
    <!--      border-radius: 50%;-->
    <!--      width: 40px;-->
    <!--      height: 40px;-->
    <!--      font-size: 20px;-->
    <!--      cursor: pointer;-->
          display: none; /* Hidden by default */
    <!--      align-items: center;-->
    <!--      justify-content: center;-->
    <!--      box-shadow: 0 2px 5px rgba(0,0,0,0.2);-->
    <!--      transition: all 0.3s ease;-->
    <!--      outline: none;-->
    <!--      user-select: none;-->
    <!--    }-->
        
    <!--    .map-listing:not(.map-hidden) .close-map-btn {-->
          display: flex; /* Show only when map is visible */
    <!--    }-->

    <!--    .close-map-btn:hover {-->
    <!--      background-color: #f0f0f0;-->
    <!--      transform: scale(1.1);-->
    <!--    }-->

    <!--    .close-map-btn:active {-->
    <!--      transform: scale(0.95);-->
    <!--    }-->

      /* Business images */
    <!--  .listing-item-link {-->
    <!--    position: relative;-->
    <!--    display: block;-->
    <!--    overflow: hidden;-->
    <!--  }-->
      
    <!--  .listing-item-link img {-->
    <!--    width: 100%;-->
    <!--    height: 300px;-->
    <!--    object-fit: cover;-->
    <!--    transition: transform 0.3s ease;-->
    <!--  }-->
      
    <!--  .listing-item:hover .listing-item-link img {-->
        /* transform: scale(1.05); */
    <!--  }-->
      
      /* Filter buttons */
    <!--  .grid-icon {-->
    <!--    display: inline-block !important;-->
    <!--    visibility: visible !important;-->
    <!--    opacity: 1 !important;-->
    <!--    margin-right: 10px !important;-->
    <!--    font-size: 20px !important;-->
    <!--    color: #333 !important;-->
    <!--    cursor: pointer;-->
    <!--  }-->
      
    <!--  .grid-icon.active {-->
    <!--    color: var(--primary-color) !important;-->
    <!--  }-->
    <!--  .grid-icon.active:hover {-->
    <!--    color: #000000 !important;-->
    <!--  }-->
      
      /* Filter dropdown */
    <!--  .filter-dropdown {-->
    <!--    position: absolute;-->
    <!--    right: 0;-->
    <!--    top: 40px;-->
    <!--    width: 280px;-->
    <!--    background: white;-->
    <!--    padding: 20px;-->
    <!--    box-shadow: 0 2px 15px rgba(0,0,0,0.1);-->
    <!--    z-index: 40;-->
    <!--    border-radius: 8px;-->
    <!--    display: none;-->
    <!--  }-->
      
      /* List view */
    <!--  .list-view .listing-item {-->
    <!--    display: flex;-->
    <!--    margin-bottom: 20px;-->
    <!--  }-->
      
    <!--  .list-view .listing-item-link {-->
    <!--    width: 300px;-->
    <!--    flex-shrink: 0;-->
    <!--  }-->
      
    <!--  .list-view .listing-item-data {-->
    <!--    flex-grow: 1;-->
    <!--    padding: 20px;-->
    <!--  }-->
      
    <!--  .list-view .col-sm-3 {-->
    <!--    width: 100%;-->
    <!--  }-->
      
        /* --- Map Popup Customizations --- */
    <!--    .leaflet-popup-content-wrapper {-->
            background-color: #333; /* Dark background for the popup */
            color: #fff; /* White text for the content */
            border-radius: 8px; /* Softer corners */
    <!--    }-->

    <!--    .leaflet-popup-tip {-->
    <!--      background-color: #333;-->
    <!--    }-->

    <!--  .leaflet-popup-content {-->
    <!--      margin: 0;-->
    <!--      padding: 15px;-->
          width: 220px !important; /* Set a fixed width to create a square-like shape */
    <!--      height: auto;-->
    <!--  }-->
      
    <!--  .map-popup {-->
    <!--      text-align: center;-->
    <!--  }-->
      
    <!--  .map-popup img {-->
    <!--    width: 100%;-->
    <!--    height: 120px;-->
    <!--    object-fit: cover;-->
    <!--    margin-bottom: 10px;-->
    <!--      border-radius: 4px;-->
    <!--  }-->
      
    <!--  .map-popup h5 {-->
    <!--      margin: 0 0 10px;-->
    <!--    font-weight: bold;-->
    <!--      font-size: 1rem;-->
          color: #fff; /* Ensure heading is white */
    <!--  }-->
      
    <!--    .map-popup .rating {-->
    <!--        margin-bottom: 15px;-->
            color: #ffcc00; /* Gold color for stars */
    <!--    }-->
        
    <!--    .map-popup .btn-primary {-->
    <!--      background-color: #007bff;-->
          color: #fff !important; /* Ensure button text is white */
    <!--      padding: 8px 12px;-->
    <!--      border-radius: 4px;-->
    <!--      text-decoration: none;-->
    <!--      display: inline-block;-->
    <!--      border: none;-->
    <!--      font-weight: 600;-->
    <!--      transition: background-color 0.3s ease;-->
    <!--    }-->

    <!--    .map-popup .btn-primary:hover {-->
    <!--        background-color: #0056b3;-->
    <!--  }-->
        /* --- End Map Popup Customizations --- */
      
      /* Tooltip */
    <!--  .tooltip {-->
    <!--    position: relative;-->
    <!--    display: inline-block !important;-->
    <!--  }-->
      
    <!--  .tooltip .tooltiptext {-->
    <!--    visibility: hidden;-->
    <!--    width: 100px;-->
    <!--    background-color: #555;-->
    <!--    color: #fff;-->
    <!--    text-align: center;-->
    <!--    border-radius: 6px;-->
    <!--    padding: 5px;-->
    <!--    position: absolute;-->
    <!--    z-index: 0 !important;-->
    <!--    bottom: 125%;-->
    <!--    left: 50%;-->
    <!--    margin-left: -50px;-->
    <!--    opacity: 0;-->
    <!--    transition: opacity 0.3s;-->
    <!--    font-size: 12px;-->
    <!--  }-->
      
    <!--  .tooltip:hover .tooltiptext {-->
    <!--    visibility: visible;-->
    <!--    opacity: 1;-->
    <!--  }-->
    <!--</style>-->
    
    --}}
    
    <style>
        /* Map visibility */
        .map-listing {
            position: relative;
            height: 450px;
            overflow: hidden;
            transition: height 0.3s ease;
        }

        .map-listing.map-hidden {
            height: 250px;
        }

        #map {
            height: 100%;
            width: 100%;
            z-index: 1;
        }

        .close-map-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 1000;
            background-color: #fff;
            color: #333;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 20px;
            cursor: pointer;
            display: none;
            /* Hidden by default */
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            outline: none;
            user-select: none;
        }

        .map-listing:not(.map-hidden) .close-map-btn {
            display: flex;
            /* Show only when map is visible */
        }

        .close-map-btn:hover {
            background-color: #f0f0f0;
            transform: scale(1.1);
        }

        .close-map-btn:active {
            transform: scale(0.95);
        }

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

        /* Filter buttons */
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
            color: var(--primary-color) !important;
        }

        .grid-icon.active:hover {
            color: #000000 !important;
        }

        /* Filter dropdown */
        .filter-dropdown {
            position: absolute;
            right: 0;
            top: 40px;
            width: 280px;
            background: white;
            padding: 20px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            z-index: 40;
            border-radius: 8px;
            display: none;
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

        /* --- Map Popup Customizations --- */
        .leaflet-popup-content-wrapper {
            background-color: #333;
            /* Dark background for the popup */
            color: #fff;
            /* White text for the content */
            border-radius: 8px;
            /* Softer corners */
        }

        .leaflet-popup-tip {
            background-color: #333;
        }

        .leaflet-popup-content {
            margin: 0;
            padding: 15px;
            width: 220px !important;
            /* Set a fixed width to create a square-like shape */
            height: auto;
        }

        .map-popup {
            text-align: center;
        }

        .map-popup img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .map-popup h5 {
            margin: 0 0 10px;
            font-weight: bold;
            font-size: 1rem;
            color: #fff;
            /* Ensure heading is white */
        }

        .map-popup .rating {
            margin-bottom: 15px;
            color: #ffcc00;
            /* Gold color for stars */
        }

        .map-popup .btn-primary {
            background-color: #007bff;
            color: #fff !important;
            /* Ensure button text is white */
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            border: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .map-popup .btn-primary:hover {
            background-color: #0056b3;
        }

        /* --- End Map Popup Customizations --- */

        /* Tooltip */
        .tooltip {
            position: relative;
            display: inline-block !important;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 100px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 0 !important;
            bottom: 125%;
            left: 50%;
            margin-left: -50px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }





        <?php /*.premium-ribbon {
            background-color: #008049ff;
            top: 40px;
            padding: 5px;
            transform: rotate(45deg);
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .premium-ribbon i {
            font-size: 16px;
            transform: rotate(-45deg);
        } */ ?>

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
  </head>
  <body>
    <!-- Start Body Content Wrapper -->
    <div class="body-wrapper">
      <!-- Start header (topbar) -->
      <header class="header">
        <!-- Start Logo -->
        <div class="logo">
          <a href="{{ url('/') }}" class="logo-color-bg">
            <img alt="" src="{{ asset('assets_public/images/logo.png') }}"/>
            <span class="logo-text" style="color: #fff; vertical-align: -webkit-baseline-middle; font-size: 20px; font-weight: 550; ">Youth Central</span>
          </a>
        </div>
        <!-- End Logo -->
        <!-- Start User Buttons -->
        <div class="user-buttons">
          <a href="{{ route('vendor.dashboard') }}" class="add-listing">Add Listing</a>
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
              <form action="{{ route('listings', $subcategory) }}" method="GET">
                <fieldset>
                  <input type="text" name="search" class="form-control" data-placeholder="Explore and Enjoy..." value="{{ request('search') }}">
                  <span class="typingEffect" data-title="Explore and Enjoy//Find New Places//Discover Tasty Goodies//Do Memorable Check-ins//Go Beyond and Beyond"></span>
                </fieldset>
                <!-- Start Search Cities -->
                <div class="search-cities-toggle"></div>
                <div class="search-cities">
                  <div class="cities-list">
                    @foreach($popularCities ?? [] as $index => $city)
                      <a href="#" style="background-image:url('{{ asset('assets_public/images/cities/thumbs/' . ($index % 5 + 1) . '.jpg') }}')" data-city-id="{{ $city->id }}" {{ $index === 0 ? 'class="current"' : '' }}><span>{{ $city->name }}</span></a>
                    @endforeach

                    <input class="chosen-city" type="hidden" name="city" value="{{ request('city', '') }}">
                  </div>
                </div>
                <!-- End Search Cities -->
                <div class="search-submit">
                  <input type="submit" value=" ">
                  <i class="hero-search-icon"></i>
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
                        $matchedSubcategory = null;
                      foreach ($mapping['search_terms'] as $term) {
                            $matchedSubcategory = \App\Models\Subcategory::where('name', 'LIKE', '%' . $term . '%')->first();
                            if ($matchedSubcategory) {
                              break;
                          }
                      }
                      
                        if ($matchedSubcategory) {
                          $customCategories[] = [
                              'name' => $categoryName,
                                'icon' => $matchedSubcategory->icon_class ? $matchedSubcategory->getFormattedIconClass() : $mapping['icon'],
                                'route' => route('listings', $matchedSubcategory)
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
      
      <!-- Start Map Listing -->
      <div class="container-fluid map-listing map-hidden">
          <button class="close-map-btn" title="Close Map">&times;</button>
        <div class="row page-head">
          <div class="current-page-info">
            <div class="breadcrumbs text-left">
              <ul>
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ route('public.category', $subcategory->category) }}">{{ $subcategory->category->name }}</a></li>
                <li><a href="{{ route('listings', $subcategory) }}">{{ $subcategory->name }}</a></li>
              </ul>
            </div>
            <div class="current-category">
              <i class="current-page-icon {{ $subcategory->getFormattedIconClass() }}"></i>
              <a href="#">{{ $subcategory->name }}</a>
            </div>
            <div class="listing-flag icon-flag"></div>
          </div>
          <div class="map-buttom">
            <div class="map-button-text">
              Show Map View
            </div>
          </div>
        </div>
        <div id="map"></div>
        <div class="mapList">
          <div class="feat-posts-list">
            @foreach($businesses as $index => $business)
              <div class="feat-post {{ $business->id }}" style="text-align: -webkit-center;">
                <a href="{{ route('public.business.show', $business) }}">
                  <div class="feat-post-icon {{ $business->subcategory->getFormattedIconClass() }}"></div>
                  @if($business->price_range)
                    <div class="ribbon">{{ $business->price_range }}</div>
                  @endif
                  <div class="listing-item-rating">{{ number_format($business->average_rating ?? 0, 1) }}</div>
                  <div class="feat-post-pic">
                    <div style="background-image:url('{{ $business->images && $business->images->isNotEmpty() ? asset('storage/' . $business->images->first()->path) : asset('assets_public/images/listings/' . ($index % 8 + 1) . '.jpg') }}')"></div>
                  </div>
                  <div class="feat-post-data">
                    <div class="feat-post-title">{{ $business->business_name }}</div>
                    <div class="feat-post-category">{{ $subcategory->name }}</div>
                  </div>
                </a>
              </div>
            @endforeach
          </div>
        </div>
      </div>
      <!-- End Map Listing -->
      
      <!-- Start Listings Counter and Filter -->
      <div class="container-fluid listing-block">
        <div class="row listing-results">
          <div class="results-count">
            <span class="counter">{{ $businesses->total() }}</span> results
          </div>
          <!-- Start Listings Filter -->
          <div class="listing-filter">
            <form action="{{ route('listings', $subcategory) }}" method="GET" id="filter-form">
              <!--<a class="grid-icon icon-icons grid-view-btn active tooltip" href="#">-->
              <!--  <span class="tooltiptext">Grid View</span>-->
              <!--</a>-->
              <!--<a class="grid-icon icon-list4 list-view-btn tooltip" href="#">-->
              <!--  <span class="tooltiptext">List View</span>-->
              <!--</a>-->
              <!--<a class="grid-icon icon-enter-right2 sort-btn tooltip" href="#" data-sort="name">-->
              <!--  <span class="tooltiptext">Sort by Name</span>-->
              <!--</a>-->
              <a class="grid-icon icon-dial sort-btn tooltip" href="#" data-sort="rating">
                <span class="tooltiptext">Sort by Rating</span>
              </a>
              <!--<a class="grid-icon icon-menu2 filter-btn tooltip" href="#">-->
              <!--  <span class="tooltiptext">Filter Options</span>-->
              <!--</a>-->
              
              <!-- Hidden sort field -->
              <input type="hidden" name="sort" id="sort-field" value="{{ request('sort') }}">
              
              <div class="filter-dropdown">
                <h4>Filter Options</h4>
                <div class="form-group">
                  <label>Rating</label>
                  <select name="rating" class="form-control">
                    <option value="">Any Rating</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                  </select>
                </div>
                
                <div class="form-group">
                  <label>Related Categories</label>
                  <select name="related_category" class="form-control">
                    <option value="">All Categories</option>
                    @foreach($relatedSubcategories as $subcat)
                      <option value="{{ $subcat->id }}" {{ request('related_category') == $subcat->id ? 'selected' : '' }}>{{ $subcat->name }}</option>
                    @endforeach
                  </select>
                </div>
                
                <div class="form-group">
                  <label>Amenities</label>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="amenities[]" value="wifi" {{ in_array('wifi', request('amenities', [])) ? 'checked' : '' }}>
                      <i class="icon-wifi"></i> Wireless Internet
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="amenities[]" value="credit_card" {{ in_array('credit_card', request('amenities', [])) ? 'checked' : '' }}>
                      <i class="icon-credit-card"></i> Accepts Credit Cards
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="amenities[]" value="parking" {{ in_array('parking', request('amenities', [])) ? 'checked' : '' }}>
                      <i class="icon-parking"></i> Parking Available
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="amenities[]" value="wheelchair" {{ in_array('wheelchair', request('amenities', [])) ? 'checked' : '' }}>
                      <i class="icon-wheelchair"></i> Wheelchair Accessible
                    </label>
                  </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Apply Filters</button>
                <a href="{{ route('listings', $subcategory) }}" class="btn btn-default btn-block">Reset Filters</a>
              </div>
              
              <select class="selectpicker" multiple data-max-options="5" data-live-search="true" name="filters[]">
                <optgroup label="Categories">
                  @foreach($subcategory->category->subcategories as $sub)
                    <option data-icon="{{ $sub->getFormattedIconClass() }}" {{ $sub->id == $subcategory->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                  @endforeach
                </optgroup>
                <optgroup label="Amenities">
                  <option data-icon="icon-wifi">Wireless Internet</option>
                  <option data-icon="icon-credit-card">Accepts Credit Cards</option>
                  <option data-icon="icon-parking">Parking Available</option>
                  <option data-icon="icon-wheelchair">Wheelchair Accessible</option>
                  <option data-icon="icon-paw">Pet Friendly</option>
                  <option data-icon="icon-baby2">Family Friendly</option>
                </optgroup>
              </select>
            </form>
          </div>
          <!-- End Listings Filter -->
        </div>
      </div>
      <!-- End Listings Counter and Filter -->
      
      <!-- Start Listings Container  -->
      <div class="container-fluid listing-block">
        <div class="row listing white grid-view" id="business-listings-container">
          @include('public.partials.business-item-cards', ['businesses' => $businesses])
        </div>
        
        <!-- Loading Spinner -->
        <div id="loading" class="text-center" style="display: none;">
          <i class="fa-solid fa-spinner fa-spin fa-3x" style="color: var(--primary-color); margin-bottom: 15px;"></i>
          <p>Loading more listings...</p>
        </div>
      </div>
      <!-- End Listings Container -->
      
      <!-- Start Footer -->
      @include('layouts.footer')
      <!-- End Footer -->
    </div>

    <!-- End Body Content Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('assets_public/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/jquery-ui.min.js') }}"></script>
    <!-- Bootstrap JavaScript -->
    <script src="{{ asset('assets_public/lib/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- Lightbox JavaScript -->
    <script src="{{ asset('assets_public/lib/lightbox2-master/dist/js/lightbox.min.js') }}"></script>
    <!-- Map JavaScript -->
    <script src="{{ asset('assets_public/lib/Leaflet-1.0.2/dist/leaflet.js') }}"></script>
    <!-- Main JavaScript -->
    <script src="{{ asset('assets_public/js/main.js') }}"></script>
<script src="{{ asset('js/search-validation.js') }}"></script>
    
    <script>
      $(document).ready(function() {
        // Prevent conflicts with main.js
        window.listingsPageMap = true;
        
        // Initialize map
        var map = L.map('map').setView([20.5937, 78.9629], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add business markers
        var hasValidCoordinates = false;
        var markers = [];
        @foreach($businesses as $business)
          @if($business->latitude && $business->longitude)
            @php
              $imagePath = '';
              if($business->images && $business->images->isNotEmpty() && $business->images->first()) {
                $imagePath = asset('storage/' . $business->images->first()->path);
              } else {
                $imagePath = asset('assets_public/images/listings/1.jpg');
              }
            @endphp
            var marker = L.marker([{{ $business->latitude }}, {{ $business->longitude }}])
              .addTo(map)
              .bindPopup(`
                <div class="map-popup">
                  <img src="{{ $imagePath }}" alt="{{ $business->business_name }}">
                  <h5>{{ $business->business_name }}</h5>
                  <p>{{ $business->street_address }}</p>
                  <p><i class="fa-solid fa-star"></i> {{ number_format($business->average_rating ?? 0, 1) }}</p>
                  <a href="{{ route('public.business.show', $business) }}" class="btn btn-sm btn-primary">View Details</a>
                </div>
              `);
            markers.push(marker);
            hasValidCoordinates = true;
          @endif
        @endforeach
        
        // If we have valid coordinates, fit the map to these points
        if (hasValidCoordinates) {
          var bounds = [];
          @foreach($businesses as $business)
            @if($business->latitude && $business->longitude)
              bounds.push([{{ $business->latitude }}, {{ $business->longitude }}]);
            @endif
          @endforeach
          map.fitBounds(bounds);
        }
        


        // Override any conflicting handlers from main.js after a short delay
        setTimeout(function() {
          $(document).off('click', '.close-map-btn');
          $(document).off('click', '.map-buttom');
          
          // Re-bind our handlers
          $('.map-buttom').off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('.map-listing').toggleClass('map-hidden');
            if ($('.map-listing').hasClass('map-hidden')) {
              $('.map-button-text').text('Show Map View');
            } else {
              $('.map-button-text').text('Hide Map View');
              // Only invalidate size if map is initialized and visible
              if (typeof map !== 'undefined' && map && map.invalidateSize) {
                setTimeout(function() {
                  try {
                    map.invalidateSize();
                  } catch (error) {
                    console.log('Map invalidateSize error:', error);
                  }
                }, 300);
              }
            }
          });

          $('.close-map-btn').off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Close button clicked - refreshing page');
            // Refresh the page to close map and reset state
            window.location.reload();
          });
        }, 100);
        
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
        
        // Filter dropdown
        $('.filter-btn').on('click', function(e) {
          e.preventDefault();
          $('.filter-dropdown').toggle();
        });
        
        // Close filter dropdown when clicking outside
        $(document).on('click', function(e) {
          if (!$(e.target).closest('.filter-btn, .filter-dropdown').length) {
            $('.filter-dropdown').hide();
          }
        });
        
        // Sorting functionality
        $('.sort-btn').on('click', function(e) {
          e.preventDefault();
          var sortValue = $(this).data('sort');
          $('#sort-field').val(sortValue);
          $('#filter-form').submit();
        });
        
        // Initialize selectpicker if it exists
        if($.fn.selectpicker) {
          $('.selectpicker').selectpicker();
        }
        
        // Location selection persistence
        $('.cities-list a').on('click', function(e) {
          e.preventDefault();
          var cityId = $(this).data('city-id');
          var cityName = $(this).find('span').text();
          
          localStorage.setItem('selectedCityId', cityId);
          localStorage.setItem('selectedCityName', cityName);
          
          $('.chosen-city').val(cityId);
          $('.cities-list a').removeClass('current');
          $(this).addClass('current');
        });
        
        // Load stored location
        var storedCityId = localStorage.getItem('selectedCityId');
        if (storedCityId) {
          $('.chosen-city').val(storedCityId);
          $('.cities-list a[data-city-id="' + storedCityId + '"]').addClass('current');
        }
        
        // Map list interaction
        $('.feat-post').on('mouseenter', function() {
          var businessId = $(this).attr('class').split(' ')[1];
          markers.forEach(function(marker) {
            if (marker._popup._content.includes('business/' + businessId)) {
              marker.openPopup();
            }
          });
        }).on('mouseleave', function() {
          markers.forEach(function(marker) {
            marker.closePopup();
          });
        });
        
        // Mobile map icon
        if (window.innerWidth <= 768) {
          $('.feat-post a').prepend('<div class="map-icon icon-map2"></div>');
        }

        // Infinite Scroll Implementation
        let currentPage = 1;
        let isLoading = false;
        let hasMorePages = {{ $businesses->hasMorePages() ? 'true' : 'false' }};
        const $loadingSpinner = $('#loading');
        const $listingsContainer = $('#business-listings-container');

        // Get current filter parameters
        function getCurrentFilters() {
          const urlParams = new URLSearchParams(window.location.search);
          return {
            search: urlParams.get('search') || '',
            sort: urlParams.get('sort') || '',
            area: urlParams.get('area') || '',
            rating: urlParams.get('rating') || '',
            amenities: urlParams.getAll('amenities[]'),
            page: currentPage + 1
          };
        }

        // Load more listings function
        function loadMoreListings() {
          if (isLoading || !hasMorePages) return;
          
          isLoading = true;
          $loadingSpinner.show();

          const filters = getCurrentFilters();
          const loadMoreUrl = '{{ route("listings.load-more", $subcategory) }}';

          $.ajax({
            url: loadMoreUrl,
            method: 'GET',
            data: filters,
            success: function(response) {
              if (response.success) {
                // Append new content
                $listingsContainer.append(response.html);
                
                // Update pagination state
                currentPage = response.currentPage;
                hasMorePages = response.hasMorePages;
                
                // If no more pages, hide loading indicator
                if (!hasMorePages) {
                  $loadingSpinner.find('p').text('No more listings to load.');
                  setTimeout(() => {
                    $loadingSpinner.hide();
                  }, 2000);
                }
              } else {
                console.error('Failed to load more listings:', response.message);
                $loadingSpinner.find('p').text('Failed to load more listings.');
                setTimeout(() => {
                  $loadingSpinner.hide();
                }, 3000);
              }
            },
            error: function(xhr, status, error) {
              console.error('AJAX error:', error);
              $loadingSpinner.find('p').text('Failed to load more listings.');
              setTimeout(() => {
                $loadingSpinner.hide();
              }, 3000);
            },
            complete: function() {
              isLoading = false;
              if (hasMorePages) {
                $loadingSpinner.hide();
              }
            }
          });
        }

        // Scroll event listener for infinite scroll
        $(window).on('scroll', function() {
          if (!hasMorePages || isLoading) return;
          
          const scrollTop = $(window).scrollTop();
          const windowHeight = $(window).height();
          const documentHeight = $(document).height();
          
          // Trigger load when user is 200px from bottom
          if (scrollTop + windowHeight >= documentHeight - 200) {
            loadMoreListings();
          }
        });

        // Reset infinite scroll when filters change
        $('#filter-form').on('submit', function() {
          currentPage = 1;
          hasMorePages = true;
          isLoading = false;
        });

        // Reset infinite scroll when search form is submitted
        $('form[action*="listings"]').on('submit', function() {
          currentPage = 1;
          hasMorePages = true;
          isLoading = false;
        });
      });
    </script>
    
    <!-- Location Selector Modal - Auto Location Detection Feature -->
    @include('partials.location-selector-modal')
    
    <script>
      // Ensure showLocationSelector function is available globally
      window.showLocationSelector = function() {
        // Use session-aware function if available
        if (typeof window.showLocationModal === 'function') {
          window.showLocationModal();
        } else {
          $('#locationSelectorModal').modal('show');
        }
      };
      
      // Initialize location modal functionality on page load
      $(document).ready(function() {
        // Make sure the location modal can be triggered from navbar
        if (typeof window.showLocationSelector !== 'function') {
          window.showLocationSelector = function() {
            // Use session-aware function if available
            if (typeof window.showLocationModal === 'function') {
              window.showLocationModal();
            } else {
              $('#locationSelectorModal').modal('show');
            }
          };
        }
      });
    </script>
  </body>
</html> 