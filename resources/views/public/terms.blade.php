@extends('layouts.app-public')

@section('title', 'Terms of Use - Youth Central')

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
        <div class="card-header">Terms of Use</div>

        <div class="policy-content">
          <p>These Terms of Use ("Terms") govern your access to and use of Youth Central's website, applications, and services (collectively, the "Platform"). By accessing or using the Platform, you agree to be bound by these Terms. If you do not agree to these Terms, please do not access or use the Platform.</p>
          
          <h4>1. Acceptance of Terms</h4>
          <p>By accessing or using Youth Central's Platform, you acknowledge that you have read, understood, and agree to be bound by these Terms, as well as our Privacy Policy. These Terms constitute a legally binding agreement between you and Youth Central.</p>
          
          <h4>2. User Eligibility</h4>
          <p>You must be at least 13 years of age to access and use our Platform. If you are under 18 years of age, you represent that you have your parent or guardian's permission to access and use the Platform. Some features or services may have additional age restrictions that will be stated in the specific terms for those features or services.</p>
          
          <h4>3. User Accounts</h4>
          <p>To access certain features of the Platform, you may need to create a user account. When creating an account, you agree to provide accurate, current, and complete information. You are responsible for safeguarding your account password and for all activities that occur under your account. You agree to notify Youth Central immediately of any unauthorized use of your account or any other breach of security.</p>
          
          <h4>4. User Content</h4>
          <p>The Platform may allow users to post, upload, publish, submit, or transmit content ("User Content"). By submitting User Content to the Platform, you grant Youth Central a worldwide, non-exclusive, royalty-free, transferable, sublicensable license to use, reproduce, modify, adapt, publish, distribute, and display such User Content in connection with the Platform.</p>
          
          <p>You represent and warrant that:</p>
          <ul>
            <li>You own or have the necessary rights to the User Content you submit</li>
            <li>The User Content does not infringe upon the intellectual property rights of any third party</li>
            <li>The User Content complies with these Terms and applicable laws</li>
          </ul>
          
          <h4>5. Prohibited Conduct</h4>
          <p>You agree not to engage in any of the following prohibited activities:</p>
          <ul>
            <li>Violating any applicable laws or regulations</li>
            <li>Infringing upon the intellectual property rights of others</li>
            <li>Posting or transmitting any content that is unlawful, harmful, threatening, abusive, harassing, defamatory, vulgar, obscene, or otherwise objectionable</li>
            <li>Impersonating any person or entity, or falsely stating or otherwise misrepresenting your affiliation with a person or entity</li>
            <li>Interfering with or disrupting the Platform or servers or networks connected to the Platform</li>
            <li>Using the Platform to send unsolicited or unauthorized advertising, promotional materials, or any other form of solicitation</li>
            <li>Collecting or storing personal data about other users without their consent</li>
            <li>Attempting to gain unauthorized access to the Platform, other user accounts, or computer systems or networks connected to the Platform</li>
          </ul>
          
          <h4>6. Intellectual Property Rights</h4>
          <p>The Platform and its original content (excluding User Content), features, and functionality are owned by Youth Central and are protected by copyright, trademark, and other intellectual property laws. You may not copy, modify, distribute, sell, or lease any part of the Platform without our prior written consent.</p>
          
          <h4>7. Third-Party Services and Links</h4>
          <p>The Platform may contain links to third-party websites or services that are not owned or controlled by Youth Central. Youth Central has no control over, and assumes no responsibility for, the content, privacy policies, or practices of any third-party websites or services. You further acknowledge and agree that Youth Central shall not be responsible or liable, directly or indirectly, for any damage or loss caused or alleged to be caused by or in connection with the use of or reliance on any such content, goods, or services available on or through any such websites or services.</p>
          
          <h4>8. Disclaimer of Warranties</h4>
          <p>THE PLATFORM IS PROVIDED "AS IS" AND "AS AVAILABLE" WITHOUT WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT. Youth Central does not warrant that the Platform will function uninterrupted, secure, or available at any particular time or location, or that any errors or defects will be corrected.</p>
          
          <h4>9. Limitation of Liability</h4>
          <p>TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW, IN NO EVENT SHALL YOUTH CENTRAL BE LIABLE FOR ANY INDIRECT, PUNITIVE, INCIDENTAL, SPECIAL, CONSEQUENTIAL DAMAGES, OR ANY DAMAGES WHATSOEVER, INCLUDING, WITHOUT LIMITATION, DAMAGES FOR LOSS OF USE, DATA, OR PROFITS, ARISING OUT OF OR IN ANY WAY CONNECTED WITH THE USE OR PERFORMANCE OF THE PLATFORM.</p>
          
          <h4>10. Indemnification</h4>
          <p>You agree to indemnify, defend, and hold harmless Youth Central and its officers, directors, employees, and agents from and against any and all claims, liabilities, damages, losses, costs, expenses, or fees (including reasonable attorneys' fees) that arise from or relate to your use of the Platform or violation of these Terms.</p>
          
          <h4>11. Termination</h4>
          <p>Youth Central reserves the right to terminate or suspend your access to the Platform immediately, without prior notice or liability, for any reason, including without limitation if you breach these Terms. Upon termination, your right to use the Platform will immediately cease.</p>
          
          <h4>12. Changes to Terms</h4>
          <p>Youth Central reserves the right to modify or replace these Terms at any time. We will provide notification of any significant changes by posting the new Terms on the Platform. Your continued use of the Platform after any such changes constitutes your acceptance of the new Terms.</p>
          
          <h4>13. Governing Law</h4>
          <p>These Terms shall be governed by and construed in accordance with the laws of India, without regard to its conflict of law provisions. Any dispute arising from or relating to these Terms or your use of the Platform shall be subject to the exclusive jurisdiction of the courts in Mumbai, Maharashtra, India.</p>
          
          <h4>14. Contact Information</h4>
          <p>If you have any questions about these Terms, please contact us at:</p>
          <p>Email: legal@youthcentral.co<br>
          Phone: +91-9819567434</p>
          
          <p><strong>Last Updated:</strong> July 31, 2023</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Policy Content -->
@endsection 