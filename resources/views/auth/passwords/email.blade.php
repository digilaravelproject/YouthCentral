<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <title>Youth Central - Forgot Password</title>
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
        background: url('{{ asset('assets_public/images/backgrounds/8.jpg') }}') no-repeat center center fixed;
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
        max-width: 500px;
        position: relative;
        z-index: 10;
        opacity: 1;
      }
      .panel-heading {
        border-bottom: 1px solid #ddd;
        margin-bottom: 20px;
      }
      .panel-heading h4 {
        font-size: 22px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
      }
      .panel-heading p {
        color: #666;
        font-size: 14px;
      }
      .form-group {
        margin-bottom: 20px;
        position: relative;
      }
      .form-group label {
        font-weight: bold;
        margin-bottom: 8px;
        display: block;
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
        box-shadow: 0 0 5px rgba(10, 15, 31, 0.3);
      }
      .btn-submit {
        background-color: var(--primary-color);
        color: white;
        font-weight: bold;
        height: 45px;
        border: none;
      }
      .btn-submit:hover {
        background-color: var(--primary-color);
        color: white;
        transform: scale(1.05);
      }
      .login-links {
        margin-top: 20px;
        text-align: center;
      }
      .login-links a {
        color: var(--primary-color);
        text-decoration: none;
        margin: 0 10px;
      }
      .login-links a:hover {
        text-decoration: underline;
      }
      .text-danger {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
      }
      .alert {
        border-radius: 4px;
        padding: 15px;
        margin-bottom: 20px;
      }
      .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
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
            <span class="logo-text" style="color: #fff;">Youth Central</span>
          </a>
        </div>
        <!-- End Logo -->
        <!-- Start User Buttons -->
        <div class="user-buttons">
          <a href="{{ route('login') }}" class="add-listing">Back to Login</a>
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
                <h4>Forgot Your Password?</h4>
                <p>Enter your email address below, and we'll send you a link to reset your password.</p>
                <hr>
              </div>
              <div class="panel-body">
                @if (session('status'))
                  <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                  </div>
                @endif
                
                <form method="POST" action="{{ route('password.email') }}">
                  @csrf
                  <div class="form-group">
                    <label for="email">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="your@email.com">
                    @error('email')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="form-group">
                    <button type="submit" class="form-control btn btn-submit">Send Password Reset Link</button>
                  </div>
                </form>
                
                <div class="login-links">
                  <a href="{{ route('login') }}">Return to Login</a>
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