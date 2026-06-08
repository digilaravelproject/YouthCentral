@extends('layouts.app-public')

@section('title', 'Copyright Infringement Policy - Youth Central')

@push('styles')
<style>
  .policy-container {
    padding: 50px 0;
  }
  .policy-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    padding: 30px;
    margin-bottom: 30px;
  }
  .policy-card .card-header {
    border-bottom: 1px solid #eee;
    margin-bottom: 20px;
    padding-bottom: 15px;
    font-size: 24px;
    font-weight: 600;
    color: #333;
  }
  .policy-content {
    color: #666;
    line-height: 1.7;
  }
</style>

<!-- Flaticon -->
<link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css">
<link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css">
<link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css">
<link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-straight/css/uicons-thin-straight.css">
<link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-rounded/css/uicons-thin-rounded.css">
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
            <span class="typingEffect" data-title="Dream Bigger, Start Here//Explore Opportunities//Explore Your Passion//Explore Yourself"></span>
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
                  // Get dynamic categories with admin-set icons (Flaticon priority)
                  $customCategories = \App\Helpers\SearchCategoryHelper::getHeroSearchCategories();
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

<!-- Start Policy Content -->
<div class="container policy-container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="policy-card">
        <div class="card-header">Copyright Infringement Policy</div>

        <div class="policy-content">
          <p>Youth Central respects the intellectual property rights of others and expects its users to do the same. In accordance with the Digital Millennium Copyright Act (DMCA) and other applicable laws, we have established this Copyright Infringement Policy to address claims of copyright infringement on our platform.</p>
          
          <h4>1. Reporting Copyright Infringement</h4>
          <p>If you believe that your copyrighted work has been copied in a way that constitutes copyright infringement and appears on our platform, please provide our designated copyright agent with the following information:</p>
          <ol>
            <li>A physical or electronic signature of the copyright owner or a person authorized to act on their behalf</li>
            <li>Identification of the copyrighted work claimed to have been infringed</li>
            <li>Identification of the material that is claimed to be infringing or to be the subject of infringing activity and that is to be removed or access to which is to be disabled, and information reasonably sufficient to permit us to locate the material</li>
            <li>Your contact information, including your address, telephone number, and an email address</li>
            <li>A statement by you that you have a good faith belief that use of the material in the manner complained of is not authorized by the copyright owner, its agent, or the law</li>
            <li>A statement that the information in the notification is accurate, and, under penalty of perjury, that you are authorized to act on behalf of the copyright owner</li>
          </ol>
          
          <h4>2. Counter-Notification Procedures</h4>
          <p>If material that you have posted to our platform has been removed or disabled in response to a notice of copyright infringement, you may send a counter-notification to our designated agent if you believe that the material was removed or disabled as a result of mistake or misidentification. Your counter-notification must include:</p>
          <ol>
            <li>Your physical or electronic signature</li>
            <li>Identification of the material that has been removed or to which access has been disabled and the location at which the material appeared before it was removed or access to it was disabled</li>
            <li>A statement under penalty of perjury that you have a good faith belief that the material was removed or disabled as a result of mistake or misidentification</li>
            <li>Your name, address, and telephone number, and a statement that you consent to the jurisdiction of the Federal District Court for the judicial district in which your address is located, or if your address is outside of India, for any judicial district in which Youth Central may be found, and that you will accept service of process from the person who provided notification of the alleged infringement</li>
          </ol>
          
          <h4>3. Repeat Infringer Policy</h4>
          <p>Youth Central has adopted a policy of terminating, in appropriate circumstances, the accounts of users who are deemed to be repeat infringers. We may also limit access to our platform and/or terminate the accounts of any users who infringe any intellectual property rights of others, whether or not there is any repeat infringement.</p>
          
          <h4>4. Modifications to Our Copyright Infringement Policy</h4>
          <p>Youth Central reserves the right to modify this Copyright Infringement Policy at any time by posting the revised policy on our website. Your continued use of our platform after such changes constitutes your acceptance of the revised policy.</p>
          
          <h4>5. Contact Information</h4>
          <p>Please contact our designated copyright agent with any questions or notifications concerning copyright infringement:</p>
          
          <p>Designated Copyright Agent<br>
          Youth Central<br>
          Email: copyright@youthcentral.co<br>
          Phone: +91-9819567434<br>
          Address: 123 Main Street, Mumbai, Maharashtra 400001, India</p>
          
          <p><strong>Last Updated:</strong> July 31, 2023</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Policy Content -->
@endsection
