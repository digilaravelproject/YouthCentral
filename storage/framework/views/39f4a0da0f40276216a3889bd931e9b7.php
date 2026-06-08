<!--
=========================================================
* Soft UI Dashboard - v1.0.3
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>

<?php if(\Request::is('rtl')): ?>
  <html dir="rtl" lang="ar">
<?php else: ?>
  <html lang="en" >
<?php endif; ?>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">

  <?php if(env('IS_DEMO')): ?>
      <?php if (isset($component)) { $__componentOriginalb028e3c120f9eb4dcf5f37307a919070 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb028e3c120f9eb4dcf5f37307a919070 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.demo-metas','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('demo-metas'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb028e3c120f9eb4dcf5f37307a919070)): ?>
<?php $attributes = $__attributesOriginalb028e3c120f9eb4dcf5f37307a919070; ?>
<?php unset($__attributesOriginalb028e3c120f9eb4dcf5f37307a919070); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb028e3c120f9eb4dcf5f37307a919070)): ?>
<?php $component = $__componentOriginalb028e3c120f9eb4dcf5f37307a919070; ?>
<?php unset($__componentOriginalb028e3c120f9eb4dcf5f37307a919070); ?>
<?php endif; ?>
  <?php endif; ?>

   <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e(asset('assets/img/favicon.png')); ?>">
  <link rel="icon" type="image/png" href="<?php echo e(asset('assets/img/favicon.png')); ?>">
  <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
  <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
  <title>
    Youth Central Dashboard
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="<?php echo e(asset('assets/css/nucleo-icons.css')); ?>" rel="stylesheet" />
  <link href="<?php echo e(asset('assets/css/nucleo-svg.css')); ?>" rel="stylesheet" />
  <!-- Font Awesome 6 Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Flaticon -->
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css">
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css">
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css">
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-straight/css/uicons-thin-straight.css">
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-rounded/css/uicons-thin-rounded.css">
  <link href="<?php echo e(asset('assets/css/nucleo-svg.css')); ?>" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="<?php echo e(asset('assets/css/soft-ui-dashboard.css?v=1.0.3')); ?>" rel="stylesheet" />
  <style>
    /* Custom toast notification styling */
    .toast-notification {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 2000; /* Higher z-index to appear above sidebar */
      max-width: 350px;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      transition: all 0.3s ease;
    }
    .toast-notification.success {
      background-color: #2dce89;
      color: white;
    }
    .toast-notification.error {
      background-color: #f5365c;
      color: white;
    }
    
    /* Fix double scrollbar issue */
    html, body {
      overflow-x: hidden;
    }
    
    body {
      overflow-y: auto;
    }
    
    .main-content {
      min-height: 100vh;
      overflow: visible;
    }
    
    /* Ensure proper scrolling for infinite scroll */
    .table-responsive {
      overflow-x: auto;
      overflow-y: visible;
    }
  </style>
</head>

<body class="g-sidenav-show  bg-gray-100 <?php echo e((\Request::is('rtl') ? 'rtl' : (Request::is('virtual-reality') ? 'virtual-reality' : ''))); ?> ">
  <?php if(auth()->guard()->check()): ?>
    <?php echo $__env->yieldContent('auth'); ?>
  <?php endif; ?>
  <?php if(auth()->guard()->guest()): ?>
    <?php echo $__env->yieldContent('guest'); ?>
  <?php endif; ?>

  
    <!--   Core JS Files   -->


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="<?php echo e(asset('assets/js/core/popper.min.js')); ?>"></script>
  <script src="<?php echo e(asset('assets/js/core/bootstrap.min.js')); ?>"></script>
  <script src="<?php echo e(asset('assets/js/plugins/perfect-scrollbar.min.js')); ?>"></script>
  <script src="<?php echo e(asset('assets/js/plugins/smooth-scrollbar.min.js')); ?>"></script>
  <script src="<?php echo e(asset('assets/js/plugins/fullcalendar.min.js')); ?>"></script>
  <script src="<?php echo e(asset('assets/js/plugins/chartjs.min.js')); ?>"></script>
  <?php echo $__env->yieldPushContent('rtl'); ?>
  <?php echo $__env->yieldPushContent('dashboard'); ?>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>

  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="<?php echo e(asset('assets/js/soft-ui-dashboard.min.js?v=1.0.3')); ?>"></script>
  
  <!-- Toast Notification -->
  <?php if(session()->has('success')): ?>
  <div id="successNotification" class="toast-notification success">
    <div class="d-flex align-items-center">
      <i class="fas fa-check-circle me-2"></i>
      <span><?php echo e(session('success')); ?></span>
    </div>
  </div>
  <script>
    // Auto-hide success notification after 4 seconds
    setTimeout(function() {
      var notification = document.getElementById('successNotification');
      if (notification) {
        notification.style.opacity = '0';
        setTimeout(function() {
          notification.style.display = 'none';
        }, 300);
      }
    }, 4000);
  </script>
  <?php endif; ?>
  
  <?php if(session()->has('error')): ?>
  <div id="errorNotification" class="toast-notification error">
    <div class="d-flex align-items-center">
      <i class="fas fa-exclamation-circle me-2"></i>
      <span><?php echo e(session('error')); ?></span>
    </div>
  </div>
  <script>
    // Auto-hide error notification after 4 seconds
    setTimeout(function() {
      var notification = document.getElementById('errorNotification');
      if (notification) {
        notification.style.opacity = '0';
        setTimeout(function() {
          notification.style.display = 'none';
        }, 300);
      }
    }, 4000);
  </script>
  <?php endif; ?>
  
  <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\YCcenter\resources\views/layouts/app.blade.php ENDPATH**/ ?>