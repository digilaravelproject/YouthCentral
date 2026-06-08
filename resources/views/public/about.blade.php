@extends('layouts.app-public')

@section('title', 'About Us - Youth Central')

@push('styles')
{{-- Add any page-specific styles here if needed --}}
<style>
  .about-container {
    padding: 50px 0;
    /* margin-top: 80px; No need for extra margin if using layout */
  }
  .about-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    padding: 30px;
    margin-bottom: 30px;
  }
  .about-card .card-header {
    border-bottom: 1px solid #eee;
    margin-bottom: 20px;
    padding-bottom: 15px;
    font-size: 24px;
    font-weight: 600;
    color: #333;
  }
  .about-card h2 {
    color: #3399cc;
    margin-bottom: 20px;
  }
  .about-card h3 {
    color: #555;
    margin: 25px 0 15px;
    font-weight: 600;
  }
  .about-card p {
    color: #666;
    line-height: 1.7;
    margin-bottom: 15px;
  }
  .about-card ul {
    margin-bottom: 20px;
    padding-left: 20px; /* Add indentation for list */
  }
  .about-card ul li {
    line-height: 1.6;
    margin-bottom: 10px;
    color: #666;
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
                  <input type="text" name="query" class="form-control" data-placeholder="Explore and Enjoy...">
                  <span class="typingEffect" data-title="Dream Bigger, Start Here//Explore Opportunities//Explore Your Passion"></span>
                </fieldset>
                <!-- Start Search Cities -->
                <div class="search-cities-toggle"></div>
                <div class="search-cities">
                  <div class="cities-list">
                    @foreach($popularCities as $index => $city)
                      <a href="#" style="background-image:url('{{ asset('assets_public/images/cities/thumbs/' . (($index % 5) + 1) . '.jpg') }}')" data-city-id="{{ $city->id }}" {{ $index === 0 ? 'class="current"' : '' }}><span>{{ $city->name }}</span></a>
                    @endforeach
                    <a href="#" style="background-image:url('{{ asset('assets_public/images/cities/thumbs/5.jpg') }}')" data-city-id="more" class="go-more-cities"><span>More Cities</span></a>
                    <input class="chosen-city" type="hidden" name="city" value="0">
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

<!-- Start About Content -->
<div class="container about-container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="about-card">
                <div class="card-header">About Us</div>

                <div class="card-body">
                    <h2>Welcome to Youth Central</h2>
                    <p>At Youth Central, we believe every child deserves a chance to shine. We are India’s first all-in-one platform dedicated to helping children explore, engage, and excel across academics, sports, arts, wellness, and beyond. By bringing thousands of youth-focused services and opportunities under one roof, we aim to bridge the gap between a child’s dreams and the right environment to pursue them.</p>
                    
                    <h3>Our Mission</h3>
                    <p>Our mission is to nurture young talent by building a vibrant ecosystem where kids can discover inspiring mentors, local programs, competitions, and communities that support their growth journey.
                        We are committed to:</p>
                    <ul>
                        <li>Provide accurate and up-to-date business information</li>
                        <li>Facilitate meaningful connections between businesses and customers</li>
                        <li>Support local businesses in growing their online presence</li>
                        <li>Making the best local services and programs accessible to kids and parents</li>
                        <li>Celebrating academic excellence, creativity, and sporting spirit</li>
                    </ul>

                    <h3>Features</h3>
                    <ul>
                        <li>Curated listings of kids’ services across education, sports, creativity, and health</li>
                        <li>User reviews and ratings system</li>
                        <li>Advanced search and filtering options</li>
                        <li>Business hours and contact information</li>
                        <li>Interactive maps for easy location finding</li>
                        <li>Special events and scholarship opportunities like YC SPARK</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End About Content -->
@endsection
