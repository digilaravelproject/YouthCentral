<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Youth Central')</title>

<!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/favicon.png') }}">


    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GP39H4GFSS"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-GP39H4GFSS');
    </script>

    <!-- Public CSS -->
    <link href="https://fonts.googleapis.com/css?family=Capriola|Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Flaticon -->
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-straight/css/uicons-thin-straight.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-rounded/css/uicons-thin-rounded.css">
    <link href="{{ asset('assets_public/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets_public/lib/bootstrap-select-master/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets_public/lib/lightbox2-master/dist/css/lightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets_public/lib/Leaflet-1.0.2/dist/leaflet.css') }}" rel="stylesheet">
    <link href="{{ asset('assets_public/fonts/icons/css/import-icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link href="{{ asset('assets_public/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets_public/css/fa6-override.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body class="front-page">
    <div class="body-wrapper">
        <!-- Start header (topbar) -->
        <header class="header">
            <!-- Start Logo -->
            <div class="logo">
                <a href="{{ url('/') }}" class="logo-color-bg">
                    {{-- Use asset() helper for correct path --}}
                    <img alt="{{ config('app.name', 'Youth Central') }}" src="{{ asset('assets_public/images/logo.png') }}"/> 
                    <span class="logo-text" style="color: #fff; vertical-align: -webkit-baseline-middle; font-size: 20px; font-weight: 550;">Youth Central</span>
                </a>
            </div>
            <!-- End Logo -->
            <!-- Start User Buttons -->
            <div class="user-buttons">
                 {{-- Login icon link - simplified, as login is in dropdown --}}
                 {{-- <a href="{{ route('login') }}" class="user-login"></a> --}}
                 
                 {{-- Add Listing Button - Link to appropriate vendor route --}}
                <a href="{{ route('vendor.register') }}" class="add-listing" style="font-family: roboto;">Add Listing</a> 
            </div>
            <!-- End User Buttons -->
            
            <!-- Include the Navbar -->
            @include('layouts.navbar') 
            
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
                        <form action="{{ route('search') }}" method="GET">
                            <fieldset>
                                <input type="text" name="query" class="form-control" data-placeholder="Explore and Enjoy..." placeholder="Search businesses...">
                            </fieldset>
                            <div class="search-cities-toggle"></div>
                            <div class="search-cities">
                                <div class="cities-list">
                                    @php
                                        $popularCities = \App\Models\City::withCount('businesses')
                                            ->orderBy('businesses_count', 'desc')
                                            ->take(5)
                                            ->get();
                                    @endphp
                                    @foreach($popularCities as $index => $city)
                                    <a href="#" style="background-image:url('{{ asset('assets_public/images/cities/thumbs/' . (($index % 5) + 1) . '.jpg') }}')" data-city-id="{{ $city->id }}" {{ $index === 0 ? 'class="current"' : '' }}><span>{{ $city->name }}</span></a>
                                    @endforeach
                                    <input class="chosen-city" type="hidden" name="city_id" value="{{ $popularCities->first()->id ?? '' }}">
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
                                <button type="submit" value=" ">
                                    <i class="hero-search-icon"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- End Hero Search -->
                </div>
            </div>
        </div>
        <!-- End Search Popup -->

        <!-- Main Content -->
        @yield('content')

        <!-- Public Footer -->
        @include('layouts.footer') {{-- Assuming footer partial exists --}}
    </div>

    <!-- Public JS -->
    <script src="{{ asset('assets_public/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/lightbox2-master/dist/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/typed.js-master/dist/typed.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/jquery.dragscroll.js') }}"></script>
    <script src="{{ asset('assets_public/lib/jquery-mousewheel-master/jquery.mousewheel.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/bootstrap-select-master/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets_public/lib/bideo.js-master/bideo.js') }}"></script>
    <script src="{{ asset('assets_public/lib/Leaflet-1.0.2/build/deps.js') }}"></script>
    <script src="{{ asset('assets_public/lib/Leaflet-1.0.2/debug/leaflet-include.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="{{ asset('assets_public/js/main.js') }}"></script>
    <script src="{{ asset('assets_public/js/city-selection-fix.js') }}"></script>
    <script src="{{ asset('js/search-validation.js') }}"></script>

    @stack('scripts')
    
    <script>
        // Globally available function to show the location selector modal
        function showLocationSelector() {
            // Check if the new session-aware function exists
            if (typeof window.showLocationModal === 'function') {
                window.showLocationModal();
            } else if (typeof $ !== 'undefined' && typeof $.fn.modal !== 'undefined') {
                // Fallback to direct modal show if new function not available
                $('#locationSelectorModal').modal('show');
            } else {
                console.warn('jQuery or Bootstrap modal not available to show locationSelectorModal.');
            }
        }

        // Toggle Search Button
        document.addEventListener('DOMContentLoaded', function() {
            var searchButton = document.querySelector('.header-search-button');
            if (searchButton) {
                searchButton.addEventListener('click', function() {
                    document.querySelector('.search-popup').classList.toggle('active');
                });
            }
            
            // City selection is now handled by city-selection-fix.js

            // Location Modal Trigger - Use session-aware function
            const userLocationIsSet = {{ session()->has('user_location') ? 'true' : 'false' }};
            if (!userLocationIsSet) {
                // Delay slightly to ensure DOM is fully ready and other scripts have initialized
                setTimeout(function() {
                    if (typeof window.showLocationModal === 'function') {
                        window.showLocationModal();
                    } else if (typeof $ !== 'undefined' && typeof $.fn.modal !== 'undefined') {
                        $('#locationSelectorModal').modal('show');
                    } else {
                        console.warn('jQuery or Bootstrap modal not available for locationSelectorModal auto-show after delay.');
                    }
                }, 500); // 500ms delay
            }
        });
    </script>

    <!-- Location Selector Modal - Auto Location Detection Feature -->
    @include('partials.location-selector-modal')

</body>
</html>