<!-- Navbar -->
<nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3 navbar-transparent mt-4">
    <div class="container">
        <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 text-white" href="{{ route('directory.index') }}">
            Youth Central Directory
        </a>
        <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
            </span>
        </button>
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center me-2 text-white" href="{{ route('directory.index') }}">
                        <i class="fas fa-search opacity-6 me-1"></i>
                        Business Directory
                    </a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link me-2 text-white" href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : (Auth::user()->role == 'vendor' ? route('vendor.dashboard') : route('user.dashboard')) }}">
                            <i class="fas fa-user-circle opacity-6 me-1"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="nav-link me-2 text-white" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt opacity-6 me-1"></i>
                                Sign Out
                            </a>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link me-2 text-white" href="{{ route('login') }}">
                            <i class="fas fa-key opacity-6 me-1"></i>
                            Sign In
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2 text-white" href="{{ route('register') }}">
                            <i class="fas fa-user-plus opacity-6 me-1"></i>
                            Sign Up
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->
