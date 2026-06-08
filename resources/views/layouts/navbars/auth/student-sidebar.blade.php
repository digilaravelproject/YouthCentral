<style>
  .navbar-vertical .nav-link::before {
      display: none !important;
      content: none !important;
  }
</style>
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ route('home') }}">
        <img src="{{ asset('assets/img/favicon.png') }}" class="navbar-brand-img h-100" alt="Youth Central Logo">
        <span class="ms-3 font-weight-bold">Youth Central</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">

      {{-- Dashboard --}}
      <li class="nav-item">
        <a class="nav-link {{ (Request::is('student/dashboard')) ? 'active' : '' }}" 
           href="{{ route('student.dashboard') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-tachometer-alt text-primary"></i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>

      {{-- Student Menu --}}
      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">My Activities</h6>
      </li>
      <?php /*<li class="nav-item">
        <a class="nav-link {{ Request::is('student/dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-calendar-check text-success"></i></div>
            <span class="nav-link-text ms-1">My Events</span>
        </a>
      </li> */?>
      <li class="nav-item">
        <a class="nav-link {{ Request::is('student/study-materials*') ? 'active' : '' }}" href="{{ route('student.study-materials.index') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-book text-info"></i></div>
            <span class="nav-link-text ms-1">Study Material</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ Request::is('student/model-question-papers*') ? 'active' : '' }}" href="{{ route('student.model-question-papers.index') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-file-alt text-primary"></i></div>
            <span class="nav-link-text ms-1">Model Question Paper</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ Request::is('student/progress-tracker*') ? 'active' : '' }}" href="{{ route('student.progress-tracker.index') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-chart-line text-success"></i></div>
            <span class="nav-link-text ms-1">Progress Tracker</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-medal text-warning"></i></div>
            <span class="nav-link-text ms-1">Badges Earned</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="fas fa-gift text-danger"></i></div>
            <span class="nav-link-text ms-1">Rewards Section</span>
        </a>
      </li>

      {{-- Logout --}}
       <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('student.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fas fa-sign-out-alt text-danger"></i>
          </div>
          <span class="nav-link-text ms-1">Logout</span>
        </a>
        <form id="logout-form" action="{{ route('student.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
      </li>
    </ul>
  </div>
</aside>
