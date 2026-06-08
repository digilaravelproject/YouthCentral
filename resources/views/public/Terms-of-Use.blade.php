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
          <p>Welcome to Youth Central. By accessing or using our website, mobile applications, services, or any of our platforms (collectively, the "Services"), you agree to be bound by these Terms of Use. Please read these terms carefully before using our Services.</p>
          
          <h4>1. Acceptance of Terms</h4>
          <p>By accessing or using our Services, you agree to be bound by these Terms of Use, our Privacy Policy, and any additional terms and conditions that may apply to specific sections of our Services. If you do not agree with any part of these terms, you may not access or use our Services.</p>
          
          <h4>2. User Registration</h4>
          <p>To access certain features of our Services, you may be required to register for an account. You agree to:</p>
          <ul>
            <li>Provide accurate, current, and complete information during registration</li>
            <li>Maintain and promptly update your account information</li>
            <li>Keep your password confidential and secure</li>
            <li>Be responsible for all activities that occur under your account</li>
            <li>Notify us immediately of any unauthorized use of your account</li>
          </ul>
          
          <h4>3. User Conduct</h4>
          <p>When using our Services, you agree not to:</p>
          <ul>
            <li>Violate any applicable laws or regulations</li>
            <li>Infringe on the rights of others</li>
            <li>Use our Services to transmit harmful code or malware</li>
            <li>Interfere with the proper functioning of our Services</li>
            <li>Post or share content that is unlawful, harmful, threatening, abusive, defamatory, or otherwise objectionable</li>
            <li>Impersonate any person or entity</li>
            <li>Engage in any activity that could harm, disable, or impair our servers or networks</li>
          </ul>
          
          <h4>4. Intellectual Property Rights</h4>
          <p>All content on our Services, including but not limited to text, graphics, logos, icons, images, audio clips, digital downloads, and software, is the property of Youth Central or its content suppliers and is protected by copyright and other intellectual property laws. You may not use, reproduce, distribute, modify, or create derivative works from any content without our express written permission.</p>
          
          <h4>5. User-Generated Content</h4>
          <p>By posting, uploading, or sharing content through our Services, you:</p>
          <ul>
            <li>Grant us a non-exclusive, royalty-free, worldwide, perpetual license to use, reproduce, modify, adapt, publish, translate, distribute, and display such content</li>
            <li>Represent that you own or have the necessary rights to the content you submit</li>
            <li>Acknowledge that your content may be viewable by others and may be used by other users</li>
          </ul>
          
          <h4>6. Third-Party Links and Services</h4>
          <p>Our Services may contain links to third-party websites or services that are not owned or controlled by Youth Central. We have no control over, and assume no responsibility for, the content, privacy policies, or practices of any third-party websites or services.</p>
          
          <h4>7. Payment Terms</h4>
          <p>When making payments through our Services:</p>
          <ul>
            <li>You agree to provide accurate and complete payment information</li>
            <li>You authorize us to charge the designated payment method for all fees and charges</li>
            <li>All payments are subject to our Refund Policy</li>
          </ul>
          
          <h4>8. Limitation of Liability</h4>
          <p>To the maximum extent permitted by law, Youth Central shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to, damages for loss of profits, goodwill, use, data, or other intangible losses resulting from your access to or use of, or inability to access or use, our Services.</p>
          
          <h4>9. Indemnification</h4>
          <p>You agree to indemnify, defend, and hold harmless Youth Central and its officers, directors, employees, agents, and affiliates from and against any and all claims, liabilities, damages, losses, costs, expenses, or fees (including reasonable attorneys' fees) arising from your use of our Services, violation of these Terms of Use, or infringement of any rights of a third party.</p>
          
          <h4>10. Modifications to Terms</h4>
          <p>We reserve the right, at our sole discretion, to modify or replace these Terms of Use at any time. We will provide notice of any significant changes through our Services. Your continued use of our Services after any such changes constitutes your acceptance of the new Terms of Use.</p>
          
          <h4>11. Termination</h4>
          <p>We may terminate or suspend your account and access to our Services immediately, without prior notice or liability, for any reason, including but not limited to, breach of these Terms of Use.</p>
          
          <h4>12. Governing Law</h4>
          <p>These Terms of Use shall be governed by and construed in accordance with the laws of India, without regard to its conflict of law provisions.</p>
          
          <h4>13. Contact Information</h4>
          <p>If you have any questions about these Terms of Use, please contact us at:</p>
          <p>Email: info@youthcentral.co<br>
          Phone: +91-6387300685</p>
          
          <p><strong>Last Updated:</strong> August 7, 2025</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Policy Content -->
@endsection 