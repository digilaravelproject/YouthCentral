@extends('layouts.app-public')

@section('title', 'Privacy Policy - Youth Central')

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
  .policy-card h2 {
    color: #3399cc;
    margin-bottom: 20px;
    font-size: 22px;
  }
  .policy-card h3 {
    color: #555;
    margin: 25px 0 15px;
    font-weight: 600;
    font-size: 18px;
  }
  .policy-card p {
    color: #666;
    line-height: 1.7;
    margin-bottom: 15px;
  }
  .policy-card ul {
    margin-bottom: 20px;
    padding-left: 20px;
  }
  .policy-card ul li {
    line-height: 1.6;
    margin-bottom: 10px;
    color: #666;
  }
  .section-title {
    font-weight: bold;
    color: #333;
    margin-top: 30px;
    margin-bottom: 15px;
  }
  .policy-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }
  .policy-table th, .policy-table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
  }
  .policy-table th {
    background-color: #f5f5f5;
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
        <div class="card-header">Privacy Policy</div>

        <div class="policy-content">
          <p><strong>Privacy Policy</strong></p>
          <p><strong>A. Introduction:</strong></p>
          <p>We respect your privacy rights and recognize the importance of secure transactions and information privacy. This Privacy Policy describes how Youthcentuary Academy Private Limited and its affiliates (collectively "Youth Central, we, our, us") collect, use, share or otherwise process your personal information through Youth Central website www.youthcentral.co ((hereinafter referred to as the "Platform"), And its mobile application.</p>
          <p>This Privacy Policy is an electronic agreement formed under the Information Technology Act, 2000 and the rules and regulations made there under (as amended till date) including the Information Technology (Reasonable security practices and procedures and sensitive personal data or information) Rules, 2011 & the Information Technology (Intermediary Guidelines and Digital Media Ethics Code) Rules, 2021. It does not require any physical or digital signatures to make the terms of this policy binding. This privacy policy is a legally binding document. The terms of this privacy policy will be effective upon your use of our Platform /service. Please read this policy carefully, together with our Terms of Services (https://www.youthcentral.co/Terms-of-Use).</p>
          <p>By visiting our Platform, providing your information or availing our product/service, you expressly agree to be bound by the terms of this Privacy Policy and the applicable Terms of service. While accessing or using our platform/services, you have given explicit consent to collect, use, share or otherwise process your information in accordance with this Privacy Policy. If you do not agree, please do not access or use our Platform or service.</p>
          <p>You acknowledge that you are disclosing Personal Information voluntarily. Prior to the completion of any registration process on our platform or prior to availing of any services offered on our platform if you wish not to disclose any Personal Information you may refrain from doing so; however if you don't provide information that is requested, it is possible that the registration process would be incomplete and/or you would not be able to avail of the our services. If you are our corporate customer, it is possible that we have entered into a contract with you for non-disclosure of confidential information. This Policy shall not affect such a contract in any manner.</p>
          
          <p><strong>B. Application of Policy:</strong></p>
          <p>This policy governs each website, mobile site, application, and/or other service, regardless of how distributed, transmitted, published, or broadcast ("Service") provided by Youth Central a Brand of Youthcentuary Academy Private Limited and its affiliates ("we," "us," or "our" or "Youth Central") that links to this policy, which is binding on all those who access, visit and/or use Youth Central's Services, i.e., Listing Service, Search Plus Services, Youth Central Events, Youth Central Yearly Stem Scholarship etc.,. This policy shall apply equally to all of our vendors, service providers, subcontractors, partners, agents, representatives, employees, and any other third parties. Youth Central undertakes to only collect and use personal data voluntarily provided by you. You are able to browse our platform without disclosing any personal information about them. However you have to provide limited personal data to us, as may be required, in order to register or to avail certain services from us.</p>
          <p>This policy applies to all contractors, suppliers, customers, users, vendors, sellers, partners, and other third party working on behalf of Youth Central or accessing or using our platforms/service. It also applies to the head office, branches office and other offices of Youth Central and all employees/officials of Youth Central. It applies to all data that Youth Central holds relating to identifiable individuals, even if that information technically falls outside of the Privacy & Data Protection law. The said information includes, Names, address, mobile/telephone numbers, email Id of users and any other information collected or received by users while accessing or using our platforms/service.</p>
          <p>This Privacy Policy is part of Youth Central's Terms of Service and covers the treatment of user information, including personally identifying information, obtained by Youth Central, including information obtained when you access the Youth Central platform, use the Youth Central Service or any other software provided by Youth Central. Unless otherwise stated explicitly, this Policy applies to Personal Information as disclosed on any of our Platform. This policy does not apply to the practices of organizations that we do not own or to people that we do not employ or manage.</p>
          
          <p><strong>C. Objectives of policy:</strong></p>
          <p>Youth Central is required to collect & use certain information about individuals, i.e., customers, suppliers/vendors, business contacts, employees, and the third parties with whom Youth Central has a business relationship or may need to contact. This policy describes, how Youth Central collects, receives, possesses, stores, deals or handle personal information including sensitive personal information about you and ensure that the same are available for review by you. The personal information must be collected, handled and stored to meet the data protection standards as well as legislative requirements. This policy ensures to comply with data protection law and follows the good practice and protects the rights of employees, customers, suppliers/vendors, business contacts, employees, and the third parties and how to stores and processes data and protects from the risks of data breach.</p>
          
          <h2>D. Collection of Information:</h2>
          <p>In the course of carrying out its various functions and activities, Youth Central collects information from individuals & third parties and generates a wide range of information which is stored at our platform. These information can take many different forms, such as, corporate records, financial records, legal records, contracts, letters received from third parties, personnel/employees records, invoices, completed application forms, contact lists, email communications and attachments, photos etc.,. Youth Central collects information in order to provide and continually improve its products & services. The information may be collected online or offline. Regardless of the collection method, the same privacy protection shall apply to all data/information including personal information.</p>
          <p>We collect Personal Information from you in a variety of ways when you interact with us through our services, such as, installing, accessing, or using our Services; Create an account on our services; Request customer service or contact us; Conduct a transaction where we collect personal information, including when required by law and regulations; Submit a testimonial, rating or review, or other user-generated content that may be posted; Otherwise submit personal information to us along with any related content of the communication.</p>
          <p>In the course of registering for and availing various services we provide from time to time through our website www.youthcentral.co ("Website", telephone search, SMS and Apps) or any other medium in which Youth Central may provide services (collectively referred to as "the Platform") you may be required to give your name, residence address, workplace address, email address, date of birth, educational qualifications , Marital status , and similar Personal Information ("Personal Information") & credit card/debit card , and other payment instrument details. We, either through third party service provider or by our own, collect the Know-Your-Customer (KYC) related document from you. KYC related documents include but not limited to, driving licence, proof of possession of Aadhaar number, the Voter's Identity Card, Pan Card, GST, Passport, job card issued by NREGA (duly signed by an officer of the State Government and letter issued by the National Population Register containing details of name and address). If OVD (officially valid documents) furnished by you don't have an updated address, then in addition to OVD, we may also collect the utility bill (electricity, telephone, post-paid mobile phone, piped gas, water bill) not more than two months old, property or Municipal tax receipt; pension or family pension payment orders (PPOs) issued to retired employees by Government Departments or Public Sector Undertakings, containing the address; letter issued by the Foreign Embassy or Mission in India (in case of foreigner), as prescribed by RBI (Reserve Bank of India) from time to time. If you are Sole Proprietary firms, we may collect the documents which include, (a) Registration certificate; (b) Certificate/licence issued by the municipal authorities under Shop and Establishment Act. (c) Sales and income tax returns. (d) GST certificate (provisional/final). (e) IEC (Importer Exporter Code) issued to the proprietary concern by the office of DGFT or Licence/certificate of practice issued in your firm's name (in case of Importer /Exporter). (f) Income Tax Return (just the acknowledgement) in your firm's name, duly authenticated/acknowledged by the Income Tax authorities. (g) Utility bills such as electricity, water, landline telephone bills, etc., or, the documents relating to proof of business/activity. We may also conduct verification and collect such other information and clarification as would be required to establish the existence of your firm, as per our satisfaction. If you are Legal Entities, we may collect the documents which includes, (a) Certificate of incorporation; (b) Memorandum and Articles of Association; (c) Permanent Account Number of the company; (d) Board resolution & power of attorney granted to its managers, officers or employees to transact on its behalf or Documents relating to beneficial owner. If you are partnership firm, we may collect the documents which include, (a) the certified copies Registration certificate; Partnership deed; Permanent Account Number of the partnership firm and Documents relating to beneficial owner, managers, officers or employees. If you are Trust, we may collect the documents which include, (a) the certified copies Registration certificate; Trust deed; Permanent Account Number or Form No.60 of the trust and Documents relating to beneficial owner, managers, officers or employees. If you are unincorporated association or a body of individuals, we may collect the documents which include, (a) the certified copies Resolution of the managing body; Permanent Account Number or Form No.60 of the unincorporated association or a body of individuals and Documents relating to beneficial owner, managers, officers or employees. We, either through third party service provider or by our own, may also undertake Video based Customer Identification Process (V-CIP) in Compliance of KYC policy of RBI. The Personal Information is used for three general purposes: to customize the content you see, to fulfil your requests for certain services, and to contact you about our services via including but not limited to email's, sms's and other means of communication. Where possible, we indicate which fields are required and which fields are optional. You always have the option to not provide information by choosing not to use a particular service, product or feature on our Platform. Youth Central collects the personal information voluntarily provided by you while accessing or using our platforms/service. Youth Central also collects data (on regular basis) from various sources such as newspaper, visiting cards, pamphlets, magazines and journals (including both free and pay), by appointment of data collectors, join free option made available on Youth Central platforms. The example of the data/information collected by us is given below:</p>

          <h3>Types of information Collected by Youth Central:</h3>
          <table class="policy-table">
            <tr>
              <th>Sr. No.</th>
              <th>Source of Information</th>
              <th>Nature of Information Collected</th>
            </tr>
            <tr>
              <td>1)</td>
              <td>Users/Customers</td>
              <td>
                1. Personal Identifying information such as name, address and phone numbers; email Id, Age, personal description, profile photograph etc., & delivery address, <br>
                2. payment information. <br>
                3. location information. <br>
                4. Device information (if you provided). <br>
                5. IP address. <br>
                6. Name, addresses & phone numbers, e-mail IDs of friends and other people listed in Addresses; <br>
                7. Content of reviews and e-mails to us. <br>
                8. voice recordings when you call to us. <br>
                9. credit usage, login detail, device log files etc., while using our platform. <br>
                10. Contacts – address book for app users
              </td>
            </tr>
            <tr>
              <td>2)</td>
              <td>Vendors/Sellers</td>
              <td>
                1. Personal Identifying information such as name, address and phone numbers; email Id, Age, personal description, profile photograph, Marital status etc.,. <br>
                2. payment information. <br>
                3. location information. <br>
                4. Device information (if you provided) <br>
                5. IP address. <br>
                6. Name, addresses & phone numbers, e-mail IDs of friends and other people listed in Addresses. <br>
                7. content of reviews and e-mails to us. <br>
                8. voice recordings when you call to us. <br>
                9. images, videos and other content collected or stored in connection with our Services. <br>
                10. information and officially valid documents (KYC regarding identity and address information, including mobile & landline number, place of business, valid Email id, vendor's photo, id & address proof (such as Aadhar card, Pan Card, GST Voter Id Card, Passport, Shop and Establishment Certificate, etc.,. <br>
                11. credit usage <br>
                12. corporate and financial information, and <br>
                13. device log files and configurations etc.,.
              </td>
            </tr>
          </table>

          <h2>T. Grievance Officer:</h2>
          <p>1. In accordance with Information Technology Act 2000 and rules & regulations made thereunder, the name and contact details of the Grievance Officer are provided below:</p>
          <p>Name : Mr. Avinash Shukla<br>
          Address: Youth Central, C25/01, Palm Beach CHS, Sector 4, Nerul, Navi Mumbai Maharashtra 400706.<br>
          Contact No: +91-6387300685<br>
          Email: admin@youthcentral.co</p>

          <p>2. If you have a query, issue, concern, or complaint in relation to collection or usage of your personal information under this Privacy Policy, please contact us at the contact information provided above or write to us at admin@youthcentral.co /info@youthcentral.co.</p>

          <h2>DEFINITONS:</h2>
          <table class="policy-table">
            <tr>
              <td>"Act"</td>
              <td>"Act" means the Information Technology Act, 2000 (21 of 2000);</td>
            </tr>
            <tr>
              <td>"Automated means"</td>
              <td>"Automated means" means any equipment capable of operating automatically in response to instructions given for the purpose of processing data.</td>
            </tr>
            <tr>
              <td>"Biometrics"</td>
              <td>"Biometrics" means the technologies that measure and analyse human body characteristics, such as 'fingerprints', 'eye retinas and irises', 'voice patterns', "facial patterns', 'hand measurements' and 'DNA' for authentication purposes.</td>
            </tr>
            <!-- Add more definitions as needed -->
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Policy Content -->
@endsection
