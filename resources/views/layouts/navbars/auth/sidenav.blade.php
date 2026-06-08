        <li class="nav-item mt-3">
            <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Business Management</h6>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('admin.businesses.index') ? 'active' : '' }}" href="{{ route('admin.businesses.index') }}">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-briefcase text-dark"></i>
                </div>
                <span class="nav-link-text ms-1">All Businesses</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('admin.businesses.create') ? 'active' : '' }}" href="{{ route('admin.businesses.create') }}">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-plus-square text-dark"></i>
                </div>
                <span class="nav-link-text ms-1">Add New Business</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('adhite text-center me-2 d-flex align-items-center justify-content-center">min.businesses.pending') ? 'active' : '' }}" href="{{ route('admin.businesses.pending') }}">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-w
                    <i class="fas fa-clock text-warning"></i>
                </div>
                <span class="nav-link-text ms-1">Pending Businesses</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('admin.claims.index') ? 'active' : '' }}" href="{{ route('admin.claims.index') }}">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-check-circle text-info"></i>
                </div>
                <span class="nav-link-text ms-1">Business Claims</span>
            </a>
        </li>

        {{-- Vendor Links --}}
        @if(Auth::user()->hasRole('vendor'))
        <li class="nav-item mt-3">
            <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Vendor Area</h6>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('vendor.dashboard') ? 'active' : '' }}" href="{{ route('vendor.dashboard') }}">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-tachometer-alt text-primary"></i>
                </div>
                <span class="nav-link-text ms-1">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('vendor.reviews.index') ? 'active' : '' }}" href="{{ route('vendor.reviews.index') }}">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-star text-warning"></i>
                </div>
                <span class="nav-link-text ms-1">My Reviews</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('vendor.events.my-registrations') ? 'active' : '' }}" href="{{ route('vendor.events.my-registrations') }}">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-calendar-check text-info"></i>
                </div>
                <span class="nav-link-text ms-1">My Event Registrations</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('vendor.my-events.*') ? 'active' : '' }}" href="{{ route('vendor.my-events.index') }}">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-glass-cheers text-success"></i>
                </div>
                <span class="nav-link-text ms-1">My Events</span>
            </a>
        </li>
        @endif
        {{-- End Vendor Links --}}

        {{-- User Links --}}
        @if(Auth::user()->hasRole('user'))
        <li class="nav-item mt-3">
            <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">My Area</h6>
        </li>
         <li class="nav-item">
            <a class="nav-link {{ Route::is('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-tachometer-alt text-primary"></i>
                </div>
                <span class="nav-link-text ms-1">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.reviews.index') ? 'active' : '' }}" href="{{ route('user.reviews.index') }}">
                 <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-star text-warning"></i>
                </div>
                <span class="nav-link-text ms-1">My Reviews</span>
            </a>
        </li>
         <li class="nav-item">
            <a class="nav-link {{ Route::is('user.events.my-registrations') ? 'active' : '' }}" href="{{ route('user.events.my-registrations') }}">
                 <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-calendar-check text-info"></i>
                </div>
                <span class="nav-link-text ms-1">My Event Registrations</span>
            </a>
        </li>
        {{-- Add My Events Link --}}
        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.my-events.*') ? 'active' : '' }}" href="{{ route('user.my-events.index') }}">
                 <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-glass-cheers text-success"></i>
                </div>
                <span class="nav-link-text ms-1">My Events</span>
            </a>
        </li>
        {{-- End My Events Link --}}
        @endif
        {{-- End User Links --}}

        {{-- Common Links --}}
        <li class="nav-item mt-3">
            <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.profile') ? 'active' : '' }}" href="{{ route('user.profile') }}">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-user text-primary"></i>
                </div>
                <span class="nav-link-text ms-1">Profile</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.settings') ? 'active' : '' }}" href="{{ route('user.settings') }}">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-cog text-info"></i>
                </div>
                <span class="nav-link-text ms-1">Settings</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('user.logout') ? 'active' : '' }}" href="{{ route('user.logout') }}">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-sign-out-alt text-danger"></i>
                </div>
                <span class="nav-link-text ms-1">Logout</span>
            </a>
        </li>
        {{-- End Common Links --}}
 