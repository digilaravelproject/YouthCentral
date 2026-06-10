<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
    <title>Youth Central - User Login</title>
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
        /* Use one of the existing numbered background images instead of looking for login-bg.jpg */
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
      .login-forgot-password {
        color: var(--primary-color);
      }
      .invalid-feedback {
        color: #dc3545;
        display: block;
        margin-top: 5px;
      }
      /* Fix checkbox alignment */
      .form-group.text-center {
        text-align: left;
      }
      #remember {
        margin-right: 5px;
      }

      /* Custom Responsive Styles */
      @media (max-width: 767px) {
        .panel-form {
          margin: 40px auto;
          padding: 20px 15px !important; /* Decrease padding to give more horizontal room for inputs */
          width: 95% !important; /* Expand panel width on mobile */
          max-width: 100% !important;
        }
        .panel-heading a {
          font-size: 15px !important; /* Slightly scale down tab links */
        }
        .panel-heading .row {
          display: flex !important;
          justify-content: space-between;
          align-items: center;
          flex-wrap: wrap; /* Ensure columns wrap if the text is too long */
        }
        .panel-heading .row > div {
          width: auto !important;
          float: none !important;
        }
        .panel-heading a {
          height: auto !important;
          line-height: normal !important;
          display: block;
          padding: 5px 0;
        }
      }
      @media (max-width: 480px) {
        .logo-text {
          display: none !important; /* Hide wide logo text on small screens to prevent overlap with the header action button */
        }
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
        <!-- Start User Buttons -->
        <div class="user-buttons">
          <a href="{{ route('vendor.register') }}" class="add-listing">Add Listing</a>
        </div>
        <!-- End User Buttons -->
      </header>
      <!-- End header (topbar) -->
      
      <!-- Start Page Content -->
      <div class="container">
      	<div class="row">
    			<div class="col-sm-12">
    				<div class="panel panel-form">
    					<div class="panel-heading">
    						<div class="row">
    							<div class="col-xs-6">
    								<a href="#" class="active" id="login-form-link">Login</a>
    							</div>
    							<div class="col-xs-6 text-right">
    								<a href="{{ route('user.register') }}" id="register-form-link">Register</a>
    							</div>
    						</div>
    						<hr>
    					</div>
    					<div class="panel-body">
    						<div class="row">
    							<div class="col-lg-12">
                  @if (session('error'))
                    <div class="alert alert-danger">
                      {{ session('error') }}
                    </div>
                  @endif
    								<form id="login-form" action="{{ route('user.login') }}" method="post">
                    @csrf
    									<div class="form-group">
    										<input type="email" name="email" id="login-email" tabindex="1" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" required>
                      @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
    									</div>
    									<div class="form-group">
    										<input type="password" name="password" id="login-password" tabindex="2" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                      @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
    									</div>
    									<div class="form-group text-center">
    										<input type="checkbox" tabindex="3" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
    										<label for="remember"> Remember Me</label>
    									</div>
    									<div class="form-group">
    										<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-submit" value="Log In">
    									</div>
    									<div class="form-group">
    										<div class="row">
    											<div class="col-lg-12">
    												<div class="text-center">
    													<a href="{{ route('password.request') }}" tabindex="5" class="login-forgot-password">Forgot Password?</a>
                              <br>
                              <a href="{{ route('otp.login', ['role' => 'user']) }}" class="login-forgot-password">Login with OTP</a>
                              <br>
                              <a href="{{ route('vendor.register') }}">Signup as Vendor</a>
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
    <script>
      // Login/Register form toggle script
      $(function() {
        $('#login-form-link').click(function(e) {
          $("#login-form").delay(100).fadeIn(100);
          e.preventDefault();
        });
      });
    </script>
  </body>
</html> 