<!-- Start Navbar -->
<div class="navbar navbar-inverse" role="navigation" id="slide-nav">
  <div class="container">
    <div id="slidemenu">
      <ul class="nav navbar-nav navbar-right">
                <!-- Location Display - positioned before search as requested -->
        <li class="navbar-location">
          <a href="#" id="navbarLocationDisplay" onclick="forceShowLocationModal(); return false;" title="Click to change location" style="background-color: #fff;">
            @php
              $userLocation = Session::get('user_location');
              $displayName = 'Select Location'; // Default
              if ($userLocation) {
                  if (!empty($userLocation['navbar_display_name'])) {
                      $displayName = $userLocation['navbar_display_name'];
                  } elseif (!empty($userLocation['display_name'])) {
                      $displayName = $userLocation['display_name'];
                  } elseif (!empty($userLocation['full_address'])) { // Fallback to full_address if others are missing
                      $displayName = $userLocation['full_address'];
                  }
              }
            @endphp
            <i class="fa fa-map-marker-alt"></i> <span class="current-location" style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;">{{ $displayName }}</span>
          </a>
        </li>
        <li class="{{ request()->is('/') ? 'active' : '' }}">
          <a href="{{ url('/') }}">Home</a>
        </li>
        <li class="dropdown {{ request()->is('directory*') || request()->is('category*') ? 'active' : '' }}">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Categories<b class="caret"></b></a>
          <ul class="dropdown-menu">
            @isset($categories)
                @foreach($categories->sortByDesc(function($category) {
                    return optional($category->subcategories)->sum('businesses_count');
                }) as $category)
                  <li><a href="{{ route('public.category', $category) }}">{{ $category->name }}</a></li>
                @endforeach
                <li><hr class="dropdown-divider"></li>
            @endisset
            <li><a href="{{ route('categories.all') }}">All Categories</a></li>
          </ul>
        </li>
        <li class="dropdown {{ request()->is('directory*') || request()->is('category*') ? 'active' : '' }}">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Events<b class="caret"></b></a>
          <ul class="dropdown-menu">
            {{--<li>
              <a href="{{ url('/events/16') }}" style="color: var(--primary-color);">YC SPARK 2026</a>
              <li><a href="{{ url('/about-yc-spark') }}">About YC SPARK</a></li>
            </li>--}}

            <!--<li class="{{ request()->is('events*') ? 'active' : '' }}" style="color: var(--primary-color);">-->
            <!--  <a href="{{ route('events.index') }}" style="color: var(--primary-color);">Events</a>-->
            <!--</li>-->
             <?php /*<li>
              <a href="{{ url('/events/16') }}" style="color: var(--primary-color);">YC SPARK 2026</a>
            </li> */?>

            {{-- YC IGNITE Event Link --}}
            <?php /*@php
              $ycIgniteEvent = \App\Models\Event::where('id', 80)->withTrashed()->first();
            @endphp
            @if($ycIgniteEvent)
              <li>
                <a href="{{ route('events.yc_ignite', $ycIgniteEvent) }}" style="color: var(--primary-color);">YC IGNITE</a>
              </li>
            @endif */?>

            <li class="{{ request()->is('events*') ? 'active' : '' }}" style="color: var(--primary-color);">
              <a href="{{ route('events.index') }}" style="color: var(--primary-color);">Events</a>
            </li>

            <!-- <li class="{{ request()->is('about_yc*') ? 'active' : '' }}">-->
            <!--  <a href="{{ route('about_yc') }}">About YC Spark</a>-->
            <!--</li>-->
          </ul>
        </li>
        {{-- <li class="{{ request()->is('events*') ? 'active' : '' }}">
          <a href="{{ route('events.index') }}">Events</a>
        </li> --}}

        <?php /*<li class="{{ request()->is('about_yc*') ? 'active' : '' }}">
          <a href="{{ route('about_yc') }}">YC Spark</a>
        </li> */?>

        <!-- YC Spark Dropdown (NEW - same as Events111 design) -->
        <li class="dropdown {{ request()->is('about_yc*') || request()->is('yc-spark-result*') ? 'active' : '' }}">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">YC Spark<b class="caret"></b></a>
          <ul class="dropdown-menu">
            {{-- Fetch YC SPARK 2026 event dynamically --}}
            @php
              $ycSparkEvent = \App\Models\Event::where('id', 16)->withTrashed()->first();
            @endphp
            
            @if($ycSparkEvent)
              <li>
                <a href="{{ route('events.show', $ycSparkEvent) }}" style="color: var(--primary-color);">
                  YC SPARK 2027
                </a>
              </li>

              <li>
                <a href="{{ str_replace('2027', '2026', route('events.result', $ycSparkEvent)) }}" style="color: var(--primary-color);">

                  YC SPARK 2026 Result
                </a>
              </li>
            @endif

            <li class="{{ request()->is('about_yc*') ? 'active' : '' }}">
              <a href="{{ route('about_yc') }}">
                Youth Century Foundation
              </a>
            </li>

          </ul>
        </li>

        <li class="{{ request()->is('about*') ? 'active' : '' }}">
          <a href="{{ route('about') }}">About Us</a>
        </li>
        <li class="{{ request()->is('contact*') ? 'active' : '' }}">
          <a href="{{ route('contact') }}">Contact Us</a>
        </li>



        @auth
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Account<b class="caret"></b></a>
            <ul class="dropdown-menu">
              @if(Auth::user()->role == 'admin')
                <li><a href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
              @elseif(Auth::user()->role == 'vendor')
                <li><a href="{{ route('vendor.dashboard') }}">Vendor Dashboard</a></li>
              @else
                <li><a href="{{ route('user.dashboard') }}">User Dashboard</a></li>
              @endif
              <li><hr class="dropdown-divider"></li>
              <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-navbar').submit();">Logout</a>
                <form id="logout-form-navbar" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </li>
            </ul>
          </li>
        @else
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sign In<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="{{ route('login') }}">User Login/Register</a></li>
              <li><a href="{{ route('vendor.login') }}">Vendor Login/Register</a></li>
              <?php /*<li><a href="{{ route('student.login') }}">Student Login/Register</a></li> */?>
              <li><a href="{{ route('password.request') }}">Recover Password</a></li>
            </ul>
          </li>
        @endauth
      </ul>
    </div>
  </div>
</div>
<!-- End Navbar -->

<style>
/* Location Display Styles */
.navbar-location a {
  position: relative;
  color: var(--primary-color) !important;
  font-weight: 500;
  transition: all 0.3s ease;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
}

.navbar-location a .current-location {
  display: inline-block;
  max-width: 250px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  vertical-align: middle;
}

.navbar-location a:hover {
  color: var(--primary-lighter) !important;
  text-decoration: none;
  transform: translateY(-1px);
}

.navbar-location a i {
  margin-right: 5px;
  font-size: 14px;
}

/* Mobile responsive adjustments */
@media (max-width: 768px) {
  .navbar-location {
    border-top: 1px solid rgba(255,255,255,0.1);
    margin-top: 5px;
    padding-top: 5px;
  }

  .navbar-location a {
    font-size: 14px;
    padding: 8px 15px;
    display: block;
  }
}

/* Highlight effect for location display */
.navbar-location a.location-set {
  background: rgba(10, 15, 31, 0.1);
  border-radius: 4px;
  padding: 8px 12px;
}

.navbar-location a.location-auto {
  color: #28a745 !important;
}

.navbar-location a.location-manual {
  color: var(--primary-lighter) !important;
}

/* Animation for location updates */
.navbar-location a.updating {
  animation: locationPulse 1s infinite;
}

@keyframes locationPulse {
  0% { opacity: 1; }
  50% { opacity: 0.7; }
  100% { opacity: 1; }
}
</style>

<script>
// Location navbar functionality
document.addEventListener('DOMContentLoaded', function() {
  const locationDisplay = document.getElementById('navbarLocationDisplay');

  // Check if user has no location set and show modal on first visit
  @if(!$userLocation)
    // Show location selector after a short delay for better UX
    setTimeout(function() {
      if (typeof showLocationSelector === 'function') {
        showLocationSelector();
      }
    }, 1000);
  @endif

  // Add appropriate classes based on location type
  @if($userLocation)
    @if($userLocation['type'] === 'auto')
      locationDisplay.classList.add('location-set', 'location-auto');
    @else
      locationDisplay.classList.add('location-set', 'location-manual');
    @endif
  @endif

  // Update location display function
  window.updateNavbarLocation = function(locationName, type = 'manual') {
    if (locationDisplay) {
      locationDisplay.innerHTML = `<i class="fa fa-map-marker-alt"></i> ${locationName}`;

      // Remove old classes
      locationDisplay.classList.remove('location-auto', 'location-manual', 'updating');

      // Add new classes
      locationDisplay.classList.add('location-set', `location-${type}`);

      // Brief animation
      locationDisplay.classList.add('updating');
      setTimeout(() => {
        locationDisplay.classList.remove('updating');
      }, 1000);
    }
  };

  // Clear location function
  window.clearNavbarLocation = function() {
    if (locationDisplay) {
      locationDisplay.innerHTML = '<i class="fa fa-map-marker-alt"></i> Select Location';
      locationDisplay.classList.remove('location-set', 'location-auto', 'location-manual');
    }
  };
});
</script>

<!-- Header Search Button -->
{{-- <div class="header-search-button"></div> --}}

{{-- Removed redundant script pushed here, should be handled by the view/layout including the popup --}}
