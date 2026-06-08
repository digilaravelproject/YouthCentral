<style>
  .navbar-vertical .nav-link::before {
      display: none !important;
      content: none !important;
  }

</style>
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <?php /*<a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ Auth::check() ? (Auth::user()->role == 'admin' ? route('admin.dashboard') : (Auth::user()->role == 'vendor' ? route('vendor.dashboard') : route('user.dashboard'))) : '#' }}">
        <img src="{{ asset('assets/img/favicon.png') }}" class="navbar-brand-img h-100" alt="Youth Central Logo">
        <span class="ms-3 font-weight-bold">Youth Central</span>
    </a> */?>
    <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="<?php echo e(route('home')); ?>">
        <img src="<?php echo e(asset('assets/img/favicon.png')); ?>" class="navbar-brand-img h-100" alt="Youth Central Logo">
        <span class="ms-3 font-weight-bold">Youth Central</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">

      
      <?php /*<li class="nav-item">
        <a class="nav-link" 
           href="{{ route('home') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-home text-primary"></i>
          </div>
          <span class="nav-link-text ms-1">Home</span>
        </a>
      </li> */?>

      <li class="nav-item">
        <a class="nav-link <?php echo e((Request::is('*/dashboard') || Request::is('dashboard')) ? 'active' : ''); ?>" 
           href="<?php echo e(Auth::check() ? (Auth::user()->role == 'admin' ? route('admin.dashboard') : (Auth::user()->role == 'vendor' ? route('vendor.dashboard') : route('user.dashboard'))) : '#'); ?>">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-tachometer-alt text-primary"></i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('profile') ? 'active' : ''); ?>" href="<?php echo e(route('profile')); ?>"> 
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-user text-secondary"></i>
          </div>
          <span class="nav-link-text ms-1">Profile</span>
        </a>
      </li>

      
      <?php if(Auth::check() && Auth::user()->role == 'admin'): ?>
      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Admin Management</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/users') ? 'active' : ''); ?>" href="<?php echo e(route('admin.users.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-users text-primary"></i></div>
          <span class="nav-link-text ms-1">User Management</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/users/create') ? 'active' : ''); ?>" href="<?php echo e(route('admin.users.create')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-user-plus text-dark"></i></div>
          <span class="nav-link-text ms-1">Create User</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/vendors/create') ? 'active' : ''); ?>" href="<?php echo e(route('admin.vendors.create')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-store-alt text-dark"></i></div>
          <span class="nav-link-text ms-1">Create Vendor</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/vendors/pending') ? 'active' : ''); ?>" href="<?php echo e(route('admin.vendors.pending')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-clock text-warning"></i></div>
          <span class="nav-link-text ms-1">Pending Vendors</span>
        </a>
      </li>
      
      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Content Management</h6>
      </li>
       <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/categories*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.categories.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-sitemap text-info"></i></div>
            <span class="nav-link-text ms-1">Categories</span>
        </a>
      </li>
       <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/subcategories*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.subcategories.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-list text-info"></i></div>
            <span class="nav-link-text ms-1">Subcategories</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/businesses*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.businesses.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-building text-success"></i></div>
            <span class="nav-link-text ms-1">Businesses</span>
        </a>
      </li>
       <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/claims*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.claims.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-flag text-warning"></i></div>
            <span class="nav-link-text ms-1">Business Claims</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Route::is('adhite text-center me-2 d-flex align-items-center justify-content-center">min.businesses.pending') ? 'active' : ''); ?>" href="<?php echo e(route('admin.businesses.pending')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-w
                <i class="fas fa-clock text-warning"></i>
            </div>
            <span class="nav-link-text ms-1">Pending Businesses</span>
        </a>
    </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/reviews*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.reviews.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-star text-warning"></i></div>
            <span class="nav-link-text ms-1">Manage Reviews</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/events*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.events.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-calendar-alt text-danger"></i></div>
            <span class="nav-link-text ms-1">Events</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/event-registrations*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.event-registrations.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-users text-danger"></i></div>
            <span class="nav-link-text ms-1">Event Registrations</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/static-content*') ? 'active' : ''); ?>" data-bs-toggle="collapse" href="#staticContentMenu" role="button" aria-expanded="<?php echo e(Request::is('admin/static-content*') ? 'true' : 'false'); ?>" aria-controls="staticContentMenu">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-file-alt text-info"></i></div>
            <span class="nav-link-text ms-1">Static Content</span>
        </a>
        <div class="collapse <?php echo e(Request::is('admin/static-content*') ? 'show' : ''); ?>" id="staticContentMenu">
          <ul class="nav ms-4">
            <li class="nav-item">
              <a class="nav-link <?php echo e(Request::is('admin/static-content/manage-event*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.static-content.manage-event')); ?>">
                <span class="nav-link-text">Event Featured Banner</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo e(Request::is('admin/static-content/home-slider*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.static-content.home-slider.index')); ?>">
                <span class="nav-link-text">Homepage Slider</span>
              </a>
            </li>
          </ul>
        </div>
      </li>
      
      <li class="nav-item">
                    <a class="nav-link <?php echo e(Request::is('admin/yc-ignites*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('admin.yc-ignites.index')); ?>">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 
                    d-flex align-items-center justify-content-center">
                            <i class="fas fa-fire text-warning"></i>
                        </div>
                        <span class="nav-link-text ms-1">YC Ignite Registrations</span>
                    </a>
                </li>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Location Management</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/states*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.states.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-map-marked text-dark"></i></div>
            <span class="nav-link-text ms-1">States</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/cities*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.cities.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-city text-dark"></i></div>
            <span class="nav-link-text ms-1">Cities</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/areas*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.areas.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-map-pin text-dark"></i></div>
            <span class="nav-link-text ms-1">Areas</span>
        </a>
      </li>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Student Management</h6>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/study-materials*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.study-materials.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-book text-info"></i></div>
            <span class="nav-link-text ms-1">Study Material</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/model-question-papers*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.model-question-papers.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-file-alt text-primary"></i></div>
            <span class="nav-link-text ms-1">Model Question Papers</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/progress-tracking*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.progress-tracking.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-chart-line text-success"></i></div>
            <span class="nav-link-text ms-1">Progress Tracking</span>
        </a>
      </li>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Subscription Management</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/plans*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.plans.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-users text-success"></i></div>
            <span class="nav-link-text ms-1">Plans</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/subscriptions*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.subscriptions.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="ni ni-credit-card text-success"></i></div>
            <span class="nav-link-text ms-1">Subscriptions</span>
        </a>
      </li>

       <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Data Tools</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('admin/bulk-import-export') ? 'active' : ''); ?>" href="<?php echo e(route('admin.bulk-import-export')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-database text-primary"></i></div>
            <span class="nav-link-text ms-1">Bulk Import/Export</span>
        </a>
      </li>
      <?php endif; ?>

      
      <?php if(Auth::check() && Auth::user()->role == 'vendor'): ?>
      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">My Business</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('vendor/businesses*') ? 'active' : ''); ?>" href="<?php echo e(route('vendor.businesses.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-building text-success"></i></div>
            <span class="nav-link-text ms-1">My Listings</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('vendor/claims*') || Request::is('vendor/available-claims') ? 'active' : ''); ?>" href="<?php echo e(route('vendor.claims.myClaims')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-flag text-warning"></i></div>
            <span class="nav-link-text ms-1">My Claims</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('vendor/reviews*') ? 'active' : ''); ?>" href="<?php echo e(route('vendor.reviews.index')); ?>">
             <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-star text-warning"></i></div>
             <span class="nav-link-text ms-1">Business Reviews</span>
        </a>
      </li>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Subscription</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('vendor/subscriptions/plans') ? 'active' : ''); ?>" href="<?php echo e(route('vendor.subscriptions.plans')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="ni ni-money-coins text-success"></i></div>
            <span class="nav-link-text ms-1">Pricing Plans</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('vendor/subscriptions/current*') ? 'active' : ''); ?>" href="<?php echo e(route('vendor.subscriptions.current')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="ni ni-credit-card text-success"></i></div>
            <span class="nav-link-text ms-1">My Subscription</span>
        </a>
      </li>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">My Events</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('vendor/events*') ? 'active' : ''); ?>" href="<?php echo e(route('vendor.events.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-calendar-alt text-danger"></i></div>
            <span class="nav-link-text ms-1">Manage Events</span>
        </a>
      </li>

		<li class="nav-item">
			<a class="nav-link <?php echo e(Request::is('vendor/event-registrations*') ? 'active' : ''); ?>" href="<?php echo e(route('vendor.event-registrations.get_registrations')); ?>">
				<div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-users text-danger"></i></div>
				<span class="nav-link-text ms-1">Event Registrations</span>
			</a>
		</li>
      
      
      
      <?php endif; ?>

      
      <?php if(Auth::check() && Auth::user()->role == 'user'): ?>
      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">My Activities</h6>
      </li>
       <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('user/events/my-registrations') ? 'active' : ''); ?>" href="<?php echo e(route('user.events.my-registrations')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-calendar-check text-success"></i></div>
            <span class="nav-link-text ms-1">My Event Registrations</span>
        </a>
      </li>
      
      <li class="nav-item">
                    <a class="nav-link <?php echo e(Request::is('user/yc-ignite/my-registrations') ? 'active' : ''); ?>"
                        href="<?php echo e(route('user.yc-ignite.index')); ?>">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-fire text-warning"></i>
                        </div>
                        <span class="nav-link-text ms-1">My YC Ignite</span>
                    </a>
                </li>
                
      <li class="nav-item">
        <a class="nav-link <?php echo e(Request::is('user/reviews*') ? 'active' : ''); ?>" href="<?php echo e(route('user.reviews.index')); ?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-star text-warning"></i></div>
            <span class="nav-link-text ms-1">My Reviews</span>
        </a>
      </li>
      
      
      <li class="nav-item mt-3">
            <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">My Events</h6>
      </li>
       <li class="nav-item">
            <a class="nav-link <?php echo e(Route::is('user.my-events.*') ? 'active' : ''); ?>" href="<?php echo e(route('user.my-events.index')); ?>">
                 <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-calendar-alt text-success"></i>
                </div>
                <span class="nav-link-text ms-1">Manage My Events</span>
            </a>
        </li>
      <?php endif; ?>

      
       <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fas fa-sign-out-alt text-danger"></i>
          </div>
          <span class="nav-link-text ms-1">Logout</span>
        </a>
        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
            <?php echo csrf_field(); ?>
            
            <?php if(Auth::check() && Auth::user()->role == 'admin'): ?>
                 <input type="hidden" name="_logout_route" value="<?php echo e(route('admin.logout')); ?>">
            <?php elseif(Auth::check() && Auth::user()->role == 'vendor'): ?>
                 <input type="hidden" name="_logout_route" value="<?php echo e(route('vendor.logout')); ?>">
            <?php endif; ?>
        </form>
      </li>
    </ul>
  </div>
</aside>




        </div>
</aside>




        </div>
</aside>




  </div>
</aside>



<?php /**PATH C:\xampp\htdocs\YCcenter\resources\views/layouts/navbars/auth/sidebar.blade.php ENDPATH**/ ?>