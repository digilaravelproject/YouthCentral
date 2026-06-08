@extends('layouts.app-public')

@section('title', 'Refund Policy - Youth Central')

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
        <div class="card-header">Refund Policy</div>

        <div class="policy-content">
          <p>At Youth Central, we strive to ensure your satisfaction with all our services and events. This Refund Policy outlines the terms and conditions regarding refunds for event registrations, premium memberships, and other paid services offered through our platform.</p>
          
          <h4>1. Event Registration Refunds</h4>
          <p>Our refund policy for event registrations is as follows:</p>
          <ul>
            <li><strong>Cancellation by User:</strong>
              <ul>
                <li>More than 7 days before the event: Full refund minus a 10% processing fee</li>
                <li>3-7 days before the event: 50% refund</li>
                <li>Less than 3 days before the event: No refund</li>
              </ul>
            </li>
            <li><strong>Cancellation by Event Organizer or Youth Central:</strong>
              <ul>
                <li>If an event is cancelled by the organizer or Youth Central for any reason, participants will receive a full refund of their registration fees</li>
                <li>In some cases, we may offer the option to transfer your registration to a similar future event instead of a refund</li>
              </ul>
            </li>
            <li><strong>Event Rescheduling:</strong>
              <ul>
                <li>If an event is rescheduled, your registration will automatically transfer to the new date</li>
                <li>If you cannot attend on the new date, you may request a refund within 7 days of the rescheduling announcement</li>
              </ul>
            </li>
          </ul>
          
          <h4>2. Premium Membership Refunds</h4>
          <p>For premium membership subscriptions:</p>
          <ul>
            <li>Within 7 days of purchase: Full refund if you have not utilized any premium features</li>
            <li>After 7 days: No refunds for partially used subscription periods</li>
            <li>You may cancel your subscription at any time to prevent automatic renewal, but no partial refunds will be provided for the current billing cycle</li>
          </ul>
          
          <h4>3. Processing Time</h4>
          <p>All approved refunds will be processed within 7-10 business days. The time it takes for the refund to appear in your account depends on your payment method and financial institution:</p>
          <ul>
            <li>Credit/Debit Cards: 10-15 business days</li>
            <li>UPI Transactions: 3-5 business days</li>
            <li>Net Banking: 5-7 business days</li>
            <li>Digital Wallets: 1-3 business days</li>
          </ul>
          
          <h4>4. Refund Method</h4>
          <p>Refunds will be issued using the same payment method used for the original transaction. If this is not possible, we will contact you to arrange an alternative refund method.</p>
          
          <h4>5. Special Circumstances</h4>
          <p>In cases of technical issues, service unavailability, or other exceptional circumstances that significantly impact your experience, please contact our customer support team within 48 hours of the incident for consideration of a refund outside our standard policy.</p>
          
          <h4>6. Non-Refundable Items</h4>
          <p>The following are strictly non-refundable:</p>
          <ul>
            <li>Donations made through our platform</li>
            <li>Transaction fees and service charges</li>
            <li>Merchandise purchases (unless damaged or defective)</li>
          </ul>
          
          <h4>7. How to Request a Refund</h4>
          <p>To request a refund:</p>
          <ol>
            <li>Log in to your Youth Central account</li>
            <li>Navigate to "My Registrations" or "Transaction History"</li>
            <li>Select the transaction for which you want a refund</li>
            <li>Click on "Request Refund" and follow the prompts</li>
            <li>Alternatively, contact our customer support team at info@youthcentral.co</li>
          </ol>
          
          <h4>8. Modifications to this Policy</h4>
          <p>Youth Central reserves the right to modify this Refund Policy at any time. We will provide notification of any significant changes by posting the new policy on our platform. Your continued use of Youth Central after such modifications will constitute your acknowledgment of the modified policy.</p>
          
          <h4>9. Contact Information</h4>
          <p>If you have any questions about our Refund Policy, please contact us at:</p>
          <p>Email: info@youthcentral.co<br>
          Phone: +91-8857950463</p>
          
          <p><strong>Last Updated:</strong> August 7, 2025</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Policy Content -->
@endsection
