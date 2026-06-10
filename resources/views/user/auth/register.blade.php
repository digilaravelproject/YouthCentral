<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
    <title>Youth Central - User Registration</title>
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
        max-width: 500px;
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
      .form-check {
        text-align: left;
        margin-bottom: 15px;
      }
      .form-check-input {
        margin-right: 5px;
      }
      .form-check-label a {
        color: var(--primary-color);
        font-weight: bold;
      }

      /* LOCATION: Styles for current-location icon in the location input */
      .location-input-wrap {
        position: relative;
      }
      .use-location-btn {
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        height: 34px;
        width: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--primary-color);
        font-size: 16px;
        outline: none;
      }
      .use-location-btn[disabled] {
        opacity: 0.6;
        cursor: not-allowed;
      }
      .location-feedback {
        margin-top: 6px;
        font-size: 13px;
        color: #6c757d;
      }
      .location-feedback.error {
        color: #dc3545;
      }
      /* small spinner inside button */
      .use-location-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(0,0,0,0.15);
        border-top-color: var(--primary-color);
        border-radius: 50%;
        animation: spin 0.9s linear infinite;
      }
      @keyframes spin {
        to { transform: rotate(360deg); }
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
    								<a href="{{ route('user.login') }}" id="login-form-link">Login</a>
    							</div>
    							<div class="col-xs-6 text-right">
    								<a href="#" class="active" id="register-form-link">Register</a>
    							</div>
    						</div>
    						<hr>
    					</div>
    					<div class="panel-body">
    						<div class="row">
    							<div class="col-lg-12">
                  <form role="form text-left" method="POST" action="{{ route('user.register') }}">
                    @csrf
                    <div class="form-group">
                      <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Name" aria-label="Name" aria-describedby="name-addon" name="name" value="{{ old('name') }}" required>
                      @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group">
                      <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" aria-label="Email" aria-describedby="email-addon" name="email" value="{{ old('email') }}" required>
                      @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control @error('phone') is-invalid @enderror" placeholder="Phone" aria-label="Phone" aria-describedby="phone-addon" name="phone" value="{{ old('phone') }}" required>
                      @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <!-- LOCATION: location input with current-location icon -->
                      <div class="form-group">
                        <div class="location-input-wrap">
                          <input id="location-input" type="text" class="form-control @error('location') is-invalid @enderror" placeholder="Location" aria-label="Location" aria-describedby="location-addon" name="location" value="{{ old('location') }}">
                          <!-- clickable button to get current location -->
                          <button id="use-location-btn" type="button" class="use-location-btn" title="Use current location" aria-label="Use current location">
                            <i id="use-location-icon" class="fa-solid fa-location-dot"></i>
                          </button>
                        </div>

                        <!-- new hidden inputs to store coordinates -->
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">

                        <div id="location-feedback" class="location-feedback" aria-live="polite"></div>
                        @error('location')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    <!-- END LOCATION -->

                    <div class="form-group">
                      <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" aria-label="Password" aria-describedby="password-addon" name="password" required>
                      @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control" placeholder="Confirm Password" aria-label="Password" aria-describedby="password-addon" name="password_confirmation" required>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                      <label class="form-check-label" for="flexCheckDefault">
                        I agree to the <a href="javascript:;" class="text-dark font-weight-bolder">Terms and Conditions</a>
                      </label>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="form-control btn btn-submit">Sign up</button>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="text-center">
                            <p>Already have an account? <a href="{{ route('user.login') }}" class="login-forgot-password">Sign in</a></p>
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
      // Register/Login form toggle script
      $(function() {
        $('#register-form-link').click(function(e) {
          e.preventDefault();
        });
      });
    </script>

    <!-- LOCATION: Improved current-location script (drop-in replacement) -->
    <script>
(function () {

  const btn = document.getElementById('use-location-btn');
  const icon = document.getElementById('use-location-icon');
  const input = document.getElementById('location-input');
  const feedback = document.getElementById('location-feedback');
  const latInput = document.getElementById('latitude');
  const lonInput = document.getElementById('longitude');

  const csrfToken =
    document.querySelector('meta[name="csrf-token"]')?.content
    || '{{ csrf_token() }}';

  function setFeedback(msg, isError = false) {
    feedback.textContent = msg;
    feedback.classList.toggle('error', isError);
  }

  function setLoading(loading) {
    if (loading) {
      btn.disabled = true;
      icon.innerHTML = '<span class="use-location-spinner"></span>';
    } else {
      btn.disabled = false;
      icon.innerHTML = '';
    }
  }

  function geoError(error) {
    let msg = 'Unable to detect location.';
    if (error.code === error.PERMISSION_DENIED) {
      msg = 'Location permission denied.';
    } else if (error.code === error.TIMEOUT) {
      msg = 'Location request timed out. Please try again.';
    }
    setFeedback(msg, true);
    setLoading(false);
  }

  btn.addEventListener('click', function () {

    setFeedback('');
    setLoading(true);

    if (!navigator.geolocation) {
      setFeedback('Geolocation not supported by your browser.', true);
      setLoading(false);
      return;
    }

    navigator.geolocation.getCurrentPosition(
      function (position) {

        const lat = position.coords.latitude;
        const lng = position.coords.longitude;

        // store coordinates
        latInput.value = lat;
        lonInput.value = lng;

        // ✅ SAME API AS MODAL
        fetch('/location/auto-detect', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            latitude: lat,
            longitude: lng,
            accuracy: position.coords.accuracy
          })
        })
        .then(res => res.json())
        .then(data => {
          if (!data.success) {
            throw new Error(data.message || 'Detection failed');
          }

          input.value = data.navbar_display_name;

        })
        .catch(err => {
          console.error(err);
          setFeedback('Unable to detect exact location. You can enter it manually.', true);
        })
        .finally(() => setLoading(false));
      },
      geoError,
      {
        enableHighAccuracy: true,
        timeout: 20000,
        maximumAge: 0
      }
    );
  });

})();
</script>

  </body>
</html>
