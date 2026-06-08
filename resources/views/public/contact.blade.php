@extends('layouts.app-public')

@section('title', 'Contact Us - Youth Central')

@push('styles')
<style>
  .contact-container {
    padding: 50px 0;
    /* margin-top: 80px; No need for extra margin if using layout */
  }
  .contact-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    padding: 30px;
    margin-bottom: 30px;
  }
  .contact-card .card-header {
    border-bottom: 1px solid #eee;
    margin-bottom: 20px;
    padding-bottom: 15px;
    font-size: 24px;
    font-weight: 600;
    color: #333;
  }
  .contact-card h2 {
    color: #3399cc;
    margin-bottom: 20px;
  }
  .contact-card h3 {
    color: #555;
    margin: 25px 0 15px;
    font-weight: 600;
  }
  .contact-card p {
    color: #666;
    line-height: 1.7;
    margin-bottom: 15px;
  }
  .contact-info {
    background-color: #f8f9fa;
    border-radius: 5px;
    padding: 20px;
    margin-bottom: 25px;
    border: 1px solid #eee;
  }
  .contact-info p {
    margin-bottom: 10px;
  }
  .contact-card .form-group {
    margin-bottom: 1.5rem; /* Add spacing between form fields */
  }
  .contact-card .form-label {
      font-weight: 600;
      color: #555;
      margin-bottom: .5rem;
  }
  .btn-primary {
    background-color: #3399cc;
    border-color: #3399cc;
    padding: 10px 25px;
    font-weight: 600;
    transition: all 0.3s ease;
  }
  .btn-primary:hover {
    background-color: #2980b9;
    border-color: #2980b9;
    transform: translateY(-2px);
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
{{-- Start Search Popup - REMOVED AS IT IS IN app-public.blade.php --}}
{{-- End Search Popup --}}
<!-- Start Contact Content -->
<div class="container contact-container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="contact-card">
                <div class="card-header">Contact Us</div>

                <div class="card-body">
                    <h2>Get in Touch</h2>
                    <p>We'd love to hear from you! Whether you have a question about our services, need help with your account, or want to provide feedback, we're here to help.</p>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="contact-info mb-4">
                        <h3>Contact Information</h3>
                        <p><strong>Email:</strong> info@youthcentral.co</p>
                        <p><strong>Phone:</strong> +91 8857950463</p>
                        <p><strong>Address:</strong> C25/01, Palm Beach Society, Sector 4, Nerul, Navi Mumbai 400706</p>
                    </div>

                    <form method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="subject" class="form-label">Subject *</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                            @error('subject')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="message" class="form-label">Message *</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                            @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Contact Content -->
@endsection 