<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $category->name }} - Categories - Youth Central</title>
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
    <link href="https://fonts.googleapis.com/css?family=Comfortaa" rel="stylesheet">
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
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <!-- Main CSS -->
    <link href="{{ asset('assets_public/css/style.css') }}" rel="stylesheet">
    <!-- Font Awesome 6 Override CSS -->
    <link href="{{ asset('assets_public/css/fa6-override.css') }}" rel="stylesheet">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="{{ asset('assets_public/lib/html5shiv-master/dist/html5shiv.min.js') }}"></script>
      <script src="{{ asset('assets_public/lib/Respond-master/dest/respond.min.js') }}"></script>
    <![endif]-->
    <script src="{{ asset('assets_public/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/bootstrap/js/bootstrap.min.js') }}"></script>
    
    <script src="{{ asset('assets_public/lib/typed.js-master/dist/typed.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/jquery.dragscroll.js') }}"></script>
    <script src="{{ asset('assets_public/lib/jquery-mousewheel-master/jquery.mousewheel.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/bootstrap-select-master/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets_public/lib/bideo.js-master/bideo.js') }}"></script>
    <script src="{{ asset('assets_public/lib/Leaflet-1.0.2/build/deps.js') }}"></script>
    <script src="{{ asset('assets_public/lib/Leaflet-1.0.2/debug/leaflet-include.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="{{ asset('assets_public/js/main.js') }}"></script>
<script src="{{ asset('js/search-validation.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
    .banner_img img {
    width: 70%;
    border-radius: 30px;
}
.banner_content {
    padding: 50px;
    border-radius: 30px;

}
@media(max-width:768px) {
    .banner_content {
        padding:50px 0;
    }
    .banner_img img {
        width: 100%;
    }

    
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
            <img alt="{{ config('app.name', 'Youth Central') }}" src="{{ asset('assets_public/images/logo.png') }}"/>
            <span class="logo-text" style="color: #fff; vertical-align: -webkit-baseline-middle; font-size: 20px; font-weight: 550;">Youth Central</span>
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
              <form action="{{ route('search') }}" method="GET">
                <fieldset>
                  <input type="text" name="query" class="form-control" data-placeholder="Explore and Enjoy...">
                  <span class="typingEffect" data-title="Explore Opportunities//Explore Your Passion//Explore Yourself//Do Memorable Check-ins//Go Beyond and Beyond"></span>
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
                @foreach($customCategories as $customCategory)
                  <a class="child" href="{{ $customCategory['route'] }}">
                    <i class="{{ $customCategory['icon'] }}"></i>
                    <span>{{ $customCategory['name'] }}</span>
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
      <!-- Start Category Subcategories -->
      <div class="container-fluid front-categories vertical" style="margin-top: 80px;">
      <div class="row row-title" style="margin-top:0;position:relative;margin-bottom:20px;">
    
    @if(strtolower($category->slug) === 'sports')
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
              <div class="swiper-slide">
                  <div class="banner_img">
                      <img src="{{ asset('assets/img/For youth central2.JPG') }}" alt="" class="w-100 h-auto"/>
                  </div>
              </div>
              <div class="swiper-slide">
                  <div class="banner_img">
                      <img src="{{ asset('assets/img/For youth central3.JPG') }}" alt="" class="w-100 h-auto"/>
                  </div>
              </div>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
   

    <div class="banner_content">
        <h1>{{ $category->name }}</h1>
        <h4>Your winning edge with Learning</h4>
        <div class="mt-3">
            <div class="back-to-home">
                <a href="{{ url('/') }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
    @else
    
    <h1>{{ $category->name }}</h1>
        <h4>Your winning edge with Learning</h4>
     <div>
            <div class="back-to-home">
                <a href="{{ url('/') }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
     @endif
</div>

        <div class="row cat-itens" style="margin-bottom:30px;">
          @foreach($subcategories as $index => $subcategory)
          <div class="cat-item">
            <div class="cat-overlay"></div>
            <div class="cat-image" style="background-image:url('{{ $subcategory->image ? asset($subcategory->image) : asset('assets_public/images/categories/' . ($index % 10 + 1) . '.jpg') }}')"></div>
            <div class="cat-icon" style="padding-top: 0px;">
                <i class="{{ $subcategory->getFormattedIconClass() }}"></i>
            </div>
            <div class="cat-counter">{{ $subcategory->businesses_count }}</div>
            <a href="{{ route('listings', $subcategory) }}" style="align-content: center;">
              <div class="cat-text">{{ $subcategory->name }}</div>
            </a>
            </div>
          @endforeach
        </div>
      </div>
      <!-- End Category Subcategories -->
      
      <!-- Include Footer -->
      @include('layouts.footer')
    </div>
    <!-- End Body Content Wrapper -->
    
    <!-- jQuery and all JS libraries -->
    <script src="{{ asset('assets_public/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/lightbox2-master/dist/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/typed.js-master/dist/typed.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/jquery.dragscroll.js') }}"></script>
    <script src="{{ asset('assets_public/lib/jquery-mousewheel-master/jquery.mousewheel.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/bootstrap-select-master/js/bootstrap-select.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    
    <script>
      // Toggle Search Button
      document.addEventListener('DOMContentLoaded', function() {
          var searchButton = document.querySelector('.header-search-button');
          var searchPopup = document.querySelector('.search-popup');
          
          if (searchButton) {
              searchButton.addEventListener('click', function() {
                  // Show the search popup when clicked
                  if (!searchPopup.classList.contains('active')) {
                      searchPopup.classList.add('active');
                  } else {
                      searchPopup.classList.remove('active');
                  }
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
          
          // Ensure search popup is initially visible when needed
          if (window.location.hash === '#search') {
              searchPopup.classList.add('active');
          }
      });
    </script>
    <script>
      // Explicitly re-bind Bootstrap dropdowns within the slide menu on mobile
      // This is an attempt to override potential conflicts from main.js or other scripts
      // specifically for the category page context.
      document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.dropdown !== 'undefined') {
          var slideMenu = document.getElementById('slidemenu');
          if (slideMenu) {
            // Find all dropdown toggles within the slide menu
            var dropdownToggles = slideMenu.querySelectorAll('.dropdown-toggle[data-toggle="dropdown"]');
            
            dropdownToggles.forEach(function(toggle) {
              // Try to remove any existing Bootstrap event and re-apply
              // This is a more aggressive approach
              $(toggle).off('.bs.dropdown').dropdown();

              // Alternative: Just trigger a click to see if it responds at all
              // $(toggle).on('click', function(e){
              //   e.preventDefault(); // Prevent default link behavior
              //   e.stopPropagation(); // Stop event from bubbling further up, which might be an issue
              //   $(this).parent().toggleClass('open'); // Manually toggle for BS3/4
              // });
            });
          }
          }
      });
    </script>
     <script>
        var swiper = new Swiper(".mySwiper", {
             loop: true,                // keeps it looping
                autoplay: {
                  delay: 3000,             // 3 seconds between slides
                  disableOnInteraction: false, // keeps autoplay even after manual swipe
                },
                
          navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
          },
        });
  </script>
    
    <!-- Location Selector Modal - Auto Location Detection Feature -->
    @include('partials.location-selector-modal')
  </body>
</html>

