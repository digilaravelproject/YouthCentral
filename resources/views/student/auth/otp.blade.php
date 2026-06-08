<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
    <title>Youth Central - Verify OTP</title>
    <!-- Google Font(s) -->
    <link href="https://fonts.googleapis.com/css?family=Capriola|Roboto" rel="stylesheet">
    <!-- Bootstrap-->
    <link href="{{ asset('assets_public/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
    <!-- Custom styles -->
    <style>
      body.bg-login {
        background: url('{{ asset('assets_public/images/backgrounds/1.jpg') }}') no-repeat center center fixed;
        background-size: cover;
        position: relative;
      }
      body.bg-login:before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(10, 15, 31, 0.9);
        z-index: -1;
      }
      body.bg-login:after {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('{{ asset('assets_public/images/miscellaneous/dot-overlay.png') }}');
        z-index: -2;
      }
      .panel-form {
        background: rgba(255, 255, 255, 0.95);
        margin: 80px auto;
        padding: 30px;
        border-radius: 5px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        max-width: 500px !important;
        position: relative;
        z-index: 10;
        opacity: 1;
      }
      .panel-heading {
        border-bottom: 1px solid #ddd;
        margin-bottom: 20px;
        text-align: center;
      }
      .panel-heading a {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        text-decoration: none;
      }
      .panel-heading a.active {
        color: var(--primary-color);
      }
      .form-group {
        margin-bottom: 15px;
        position: relative;
      }
      .form-control {
        height: 45px;
        border: 1px solid #ddd;
        font-size: 14px;
        background-color: #fff;
        color: #333;
      }
      .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 5px rgba(38,163,135,0.3);
      }
      .btn-submit {
        background-color: var(--primary-color);
        color: white;
        font-weight: bold;
        height: 45px;
        border: none;
      }
      .btn-submit:hover {
        background-color: #6e5904;
        color: white;
        transform: scale(1.05);
      }
      .invalid-feedback {
        color: #dc3545;
        display: block;
        margin-top: 5px;
      }
      .text-muted {
          text-align: center;
          margin-bottom: 15px;
      }
    </style>
  </head>
  <body class="bg-login">
    <!-- Start Body Content Wrapper -->
    <div class="body-wrapper">
      <!-- Start header (topbar) -->
      <header class="header">
        <!-- Start Logo -->
        <div class="logo">
          <a href="{{ url('/') }}" class="logo-color-bg">
            <img alt="Youth Central" src="{{ asset('assets_public/images/logo.png') }}"/>
            <span class="logo-text" style="color: #fff; font-size: 20px; vertical-align: text-top;">Youth Central</span>
          </a>
        </div>
        <!-- End Logo -->
      </header>
      <!-- End header (topbar) -->
      
      <!-- Start Page Content -->
      <div class="container">
      	<div class="row">
    			<div class="col-sm-12">
    				<div class="panel panel-form">
    					<div class="panel-heading">
                <a href="#" class="active" id="login-form-link">Verify OTP</a>
    						<hr>
    					</div>
    					<div class="panel-body">
    						<div class="row">
    							<div class="col-lg-12">
                  @if (session('success'))
                    <div class="alert alert-success">
                      {{ session('success') }}
                    </div>
                  @endif
                  @if (session('error'))
                    <div class="alert alert-danger">
                      {{ session('error') }}
                    </div>
                  @endif
                  
                  <p class="text-muted">Enter the 6-digit code sent to your mobile number: <strong>{{ session('student_auth_phone') }}</strong></p>

    								<form id="otp-form" action="{{ route('student.verify-otp') }}" method="post">
                    @csrf
    									<div class="form-group">
    										<input type="text" name="otp" id="otp" tabindex="1" class="form-control @error('otp') is-invalid @enderror" placeholder="Enter 6-digit OTP" required>
                      @error('otp')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
    									</div>
    									<div class="form-group">
    										<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-submit" value="Verify and Login">
    									</div>
    									<div class="form-group">
    										<div class="row">
    											<div class="col-lg-12">
    												<div class="text-center">
    													<a href="{{ route('student.login') }}">Change Mobile Number</a>
    												</div>
    											</div>
    										</div>
    									</div>
    								</form>
    							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
      </div>
      <!-- End Page Content -->
      
      <!-- Footer -->
      <footer class="footer">
        <div class="container">
          <div class="row">
            <div class="col-lg-12 mx-auto text-center mb-4 mt-2">
              <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
                <span class="text-lg fab fa-facebook"></span>
              </a>
              <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
                <span class="text-lg fab fa-twitter"></span>
              </a>
              <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
                <span class="text-lg fab fa-instagram"></span>
              </a>
            </div>
          </div>
          <div class="row">
            <div class="col-8 mx-auto text-center mt-1">
              <p class="mb-0 text-secondary">
                Copyright © {{ date('Y') }} Youth Central. All rights reserved.
              </p>
            </div>
          </div>
        </div>
      </footer>
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('assets_public/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/bootstrap/js/bootstrap.min.js') }}"></script>
  </body>
</html>
