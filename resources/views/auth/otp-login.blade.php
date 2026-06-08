<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Youth Central - OTP Login</title>
    <!-- Google Font(s) -->
    <link href="https://fonts.googleapis.com/css?family=Capriola|Roboto" rel="stylesheet">
    <!-- Bootstrap-->
    <link href="{{ asset('assets_public/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Main CSS -->
    <link href="{{ asset('assets_public/css/style.css') }}" rel="stylesheet">
    <!-- Custom styles -->
    <style>
      body.bg-login {
        background: url('{{ asset('assets_public/images/backgrounds/3.jpg') }}') no-repeat center center fixed;
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
        font-size: 18px;
        font-weight: bold;
        color: #333;
        text-align: center;
        margin: 0;
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
        box-shadow: 0 0 5px rgba(10, 15, 31, 0.3);
      }
      .btn-submit {
        background-color: var(--primary-color);
        color: white !important;
        font-weight: bold;
        height: 45px;
        border: none;
        width: 100%;
      }
      .btn-submit:hover {
        background-color: #b8950a;
        color: white !important;
        transform: scale(1.02);
      }
      .btn-submit:disabled {
        background-color: #ccc;
        cursor: not-allowed;
        transform: none;
        color: white !important;
      }
      .login-forgot-password {
        color: var(--primary-color);
      }
      .alert {
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 4px;
      }
      .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
      }
      .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
      }
      .otp-step {
        display: none;
      }
      .otp-step.active {
        display: block;
      }
      .otp-input {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        letter-spacing: 5px;
      }
      .timer {
        text-align: center;
        color: #666;
        margin-top: 10px;
      }
      .resend-link {
        color: var(--primary-color);
        cursor: pointer;
        text-decoration: underline;
      }
      .resend-link:hover {
        color: #b8950a;
      }
      .role-badge {
        background: var(--primary-color);
        color: white;
        padding: 3px 10px;
        border-radius: 15px;
        display: inline-block;
        margin-left: 10px;
        font-size: 12px;
      }
      .back-link {
        text-align: center;
        margin-top: 15px;
      }
      .back-link a {
        color: #666;
        text-decoration: none;
        font-size: 14px;
      }
      .back-link a:hover {
        color: var(--primary-color);
      }
      /* Header Button Styling */
      .header-auth-btn {
        position: relative;
        display: inline-block;
        line-height: 12px;
        font-family: 'Capriola', sans-serif;
        font-size: 14px;
        margin-left: 0;
        white-space: nowrap;
        height: 100%;
        padding: 26px 20px 0 45px;
        color: #fff;
        background: rgba(0,0,0,0.10);
        border-radius: 0;
        border-left: 1px dashed rgba(255,255,255,0.5);
        text-decoration: none;
      }
      .header-auth-btn:hover {
        color: #fff;
        opacity: 0.8;
        text-decoration: none;
        background: rgb(212,171,0);
        border-radius: 20px 0;
      }
      .header-auth-btn:before {
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        font-size: 18px;
        left: 15px;
        top: 24px;
        display: inline-block;
      }
      .header-auth-btn.vendor-btn:before {
        content: "\f19d"; /* FA6 user-tie icon */
      }
      .header-auth-btn.user-btn:before {
        content: "\f007"; /* FA6 user icon */
      }
      
      /* Footer Social Media Icons */
      .footer .fab {
        font-family: 'Font Awesome 6 Brands' !important;
        font-weight: 400 !important;
        font-size: 18px !important;
        display: inline-block !important;
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
          @if($role === 'user')
            <a href="{{ route('vendor.login') }}" class="header-auth-btn vendor-btn">Vendor Login</a>
          @else
            <a href="{{ route('user.login') }}" class="header-auth-btn user-btn">Customer Login</a>
          @endif
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
    						<h4>
                  OTP Login 
                  <span class="role-badge">{{ ucfirst($role) }}</span>
                </h4>
    						<hr>
    					</div>
    					<div class="panel-body">
    						<div class="row">
    							<div class="col-lg-12">
                  
                  <!-- Alert Messages -->
                  <div id="alert-container"></div>
                  
                  <!-- Step 1: Phone Number Input -->
                  <div id="phone-step" class="otp-step active">
                    <form id="phone-form">
                      <input type="hidden" name="role" value="{{ $role }}">
                      
                      <div class="form-group">
                        <label>Mobile Number</label>
                        <input type="tel" name="phone" id="phone" class="form-control" placeholder="Enter 10-digit mobile number" maxlength="10" required>
                        <small class="text-muted">Enter the mobile number registered with your {{ $role }} account</small>
                      </div>
                      
                      <div class="form-group">
                        <button type="submit" id="send-otp-btn" class="btn btn-submit">
                          <span class="btn-text" style="color:white;">Send OTP</span>
                          <span class="btn-loading" style="display: none;">
                            <i class="fa fa-spinner fa-spin"></i> Sending...
                          </span>
                        </button>
                      </div>
                    </form>
                  </div>
                  
                  <!-- Step 2: OTP Verification -->
                  <div id="otp-step" class="otp-step">
                    <form id="otp-form">
                      <input type="hidden" name="role" value="{{ $role }}">
                      <input type="hidden" name="phone" id="hidden-phone">
                      
                      <div class="form-group">
                        <label>Enter OTP</label>
                        <input type="text" name="otp" id="otp" class="form-control otp-input" placeholder="000000" maxlength="6" required>
                        <small class="text-muted">Enter the 6-digit OTP sent to <span id="display-phone"></span></small>
                      </div>
                      
                      <div class="timer">
                        <span id="timer-text">OTP expires in: <strong id="countdown">05:00</strong></span>
                        <div id="resend-container" style="display: none;">
                          <span>Didn't receive OTP? </span>
                          <span class="resend-link" id="resend-otp">Resend OTP</span>
                        </div>
                      </div>
                      
                      <div class="form-group" style="margin-top: 20px;">
                        <button type="submit" id="verify-otp-btn" class="btn btn-submit">
                          <span class="btn-text">Verify & Login</span>
                          <span class="btn-loading" style="display: none;">
                            <i class="fa fa-spinner fa-spin"></i> Verifying...
                          </span>
                        </button>
                      </div>
                      
                      <div class="form-group">
                        <button type="button" id="back-to-phone" class="btn btn-link" style="width: 100%; color: #666;">
                          <i class="fa fa-arrow-left"></i> Change Mobile Number
                        </button>
                      </div>
                    </form>
                  </div>
                  
                  <!-- Alternative Login Options -->
                  <div class="form-group">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="text-center">
                          @if($role === 'user')
                            <p><a href="{{ route('user.login') }}" class="login-forgot-password">Login with Password</a></p>
                            <p>Don't have an account? <a href="{{ route('user.register') }}" class="login-forgot-password">Sign up</a></p>
                            <a href="{{ route('vendor.login') }}">Login as Vendor</a>
                          @else
                            <p><a href="{{ route('vendor.login') }}" class="login-forgot-password">Login with Password</a></p>
                            <p>Don't have an account? <a href="{{ route('vendor.register') }}" class="login-forgot-password">Sign up</a></p>
                            <a href="{{ route('user.login') }}">Login as Customer</a>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                  
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
      $(document).ready(function() {
        let countdownTimer;
        let timeLeft = 300; // 5 minutes
        
        // CSRF token setup
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        
        // Phone number input validation
        $('#phone').on('input', function() {
          this.value = this.value.replace(/[^0-9]/g, '');
        });
        
        // OTP input validation
        $('#otp').on('input', function() {
          this.value = this.value.replace(/[^0-9]/g, '');
        });
        
        // Send OTP Form
        $('#phone-form').on('submit', function(e) {
          e.preventDefault();
          
          const phone = $('#phone').val();
          const role = $('input[name="role"]').val();
          
          if (phone.length !== 10) {
            showAlert('Please enter a valid 10-digit mobile number.', 'danger');
            return;
          }
          
          setButtonLoading('#send-otp-btn', true);
          
          $.ajax({
            url: '{{ route("otp.send") }}',
            method: 'POST',
            data: {
              phone: phone,
              role: role
            },
            success: function(response) {
              if (response.success) {
                showAlert(response.message, 'success');
                $('#hidden-phone').val(phone);
                $('#display-phone').text(phone);
                switchToOtpStep();
                startCountdown();
              } else {
                showAlert(response.message, 'danger');
              }
            },
            error: function(xhr) {
              const response = xhr.responseJSON;
              showAlert(response.message || 'An error occurred. Please try again.', 'danger');
            },
            complete: function() {
              setButtonLoading('#send-otp-btn', false);
            }
          });
        });
        
        // Verify OTP Form
        $('#otp-form').on('submit', function(e) {
          e.preventDefault();
          
          const phone = $('#hidden-phone').val();
          const otp = $('#otp').val();
          const role = $('input[name="role"]').val();
          
          if (otp.length !== 6) {
            showAlert('Please enter a valid 6-digit OTP.', 'danger');
            return;
          }
          
          setButtonLoading('#verify-otp-btn', true);
          
          $.ajax({
            url: '{{ route("otp.verify") }}',
            method: 'POST',
            data: {
              phone: phone,
              otp: otp,
              role: role
            },
            success: function(response) {
              if (response.success) {
                showAlert(response.message, 'success');
                setTimeout(function() {
                  window.location.href = response.redirect_url;
                }, 1000);
              } else {
                showAlert(response.message, 'danger');
              }
            },
            error: function(xhr) {
              const response = xhr.responseJSON;
              showAlert(response.message || 'An error occurred. Please try again.', 'danger');
            },
            complete: function() {
              setButtonLoading('#verify-otp-btn', false);
            }
          });
        });
        
        // Resend OTP
        $('#resend-otp').on('click', function() {
          const phone = $('#hidden-phone').val();
          const role = $('input[name="role"]').val();
          
          $.ajax({
            url: '{{ route("otp.resend") }}',
            method: 'POST',
            data: {
              phone: phone,
              role: role
            },
            success: function(response) {
              if (response.success) {
                showAlert(response.message, 'success');
                timeLeft = 300;
                startCountdown();
              } else {
                showAlert(response.message, 'danger');
              }
            },
            error: function(xhr) {
              const response = xhr.responseJSON;
              showAlert(response.message || 'An error occurred. Please try again.', 'danger');
            }
          });
        });
        
        // Back to phone step
        $('#back-to-phone').on('click', function() {
          switchToPhoneStep();
          clearCountdown();
        });
        
        // Helper functions
        function switchToOtpStep() {
          $('#phone-step').removeClass('active');
          $('#otp-step').addClass('active');
          $('#otp').focus();
        }
        
        function switchToPhoneStep() {
          $('#otp-step').removeClass('active');
          $('#phone-step').addClass('active');
          $('#phone').focus();
          $('#otp').val('');
        }
        
        function startCountdown() {
          $('#timer-text').show();
          $('#resend-container').hide();
          
          countdownTimer = setInterval(function() {
            timeLeft--;
            
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            $('#countdown').text(
              String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0')
            );
            
            if (timeLeft <= 0) {
              clearInterval(countdownTimer);
              $('#timer-text').hide();
              $('#resend-container').show();
            }
          }, 1000);
        }
        
        function clearCountdown() {
          if (countdownTimer) {
            clearInterval(countdownTimer);
          }
          timeLeft = 300;
        }
        
        function setButtonLoading(selector, loading) {
          const btn = $(selector);
          if (loading) {
            btn.prop('disabled', true);
            btn.find('.btn-text').hide();
            btn.find('.btn-loading').show();
          } else {
            btn.prop('disabled', false);
            btn.find('.btn-text').show();
            btn.find('.btn-loading').hide();
          }
        }
        
        function showAlert(message, type) {
          const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
          const alertHtml = `
            <div class="alert ${alertClass}" role="alert">
              ${message}
            </div>
          `;
          
          $('#alert-container').html(alertHtml);
          
          // Auto-hide success messages
          if (type === 'success') {
            setTimeout(function() {
              $('#alert-container').html('');
            }, 5000);
          }
        }
      });
    </script>
  </body>
</html> 