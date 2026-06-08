@extends('layouts.user_type.auth')

@section('content')

<div class="container-fluid py-4">
  <?php /*<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Your Purchases</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $totalPurchases ?? 0 }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Wishlist</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $wishlistItems ?? 0 }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                <i class="ni ni-favourite-28 text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Cart Items</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $cartItems ?? 0 }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Orders</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $totalOrders ?? 0 }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                <i class="ni ni-delivery-fast text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> */?>
  
  <div class="row mt-4">
    <div class="col-lg-12 mb-lg-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-lg-6">
              <div class="d-flex flex-column h-100">
                <p class="mb-1 pt-2 text-bold">Welcome, {{ Auth::user()->name }}!</p>
                <h5 class="font-weight-bolder">Your Personal Dashboard</h5>
                <p class="mb-5">Track your orders, manage your profile, and browse through products from our vendors.</p>
                <?php /*<a class="text-body text-sm font-weight-bold mb-0 icon-move-right mt-auto" href="#">
                  Browse Products
                  <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                </a>*/ ?>
              </div>
            </div>
            <div class="col-lg-5 ms-auto text-center mt-5 mt-lg-0">
              <div class="bg-gradient-info border-radius-lg h-100">
                <img src="{{ asset('assets/img/shapes/waves-white.svg') }}" class="position-absolute h-100 w-50 top-0 d-lg-block d-none" alt="waves">
                <div class="position-relative d-flex align-items-center justify-content-center h-100">
                  <img class="w-100 position-relative z-index-2 pt-4" src="{{ asset('assets/img/illustrations/rocket-white.png') }}" alt="rocket">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-lg-8">
      <div class="card h-100">
        <div class="card-header pb-0 p-3">
          <div class="row">
            <div class="col-6 d-flex align-items-center">
              <h6 class="mb-0">My Activities</h6>
            </div>
          </div>
        </div>
        <div class="card-body p-3">
          @if($activities->count() > 0)
            <div class="table-responsive">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">Activity</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($activities as $activity)
                    <tr>
                      <td class="px-2">
                        <div class="d-flex py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">
                              @if($activity->type === 'registration')
                                <i class="fas fa-ticket-alt text-info me-2"></i>
                              @else
                                <i class="fas fa-plus-circle text-success me-2"></i>
                              @endif
                              {{ $activity->title }}
                            </h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">{{ $activity->date->format('M d, Y h:i A') }}</p>
                      </td>
                      <td>
                        <span class="badge badge-sm {{ $activity->status === 'paid' || $activity->status === 'active' || $activity->status === 'approved' ? 'bg-gradient-success' : ($activity->status === 'pending' ? 'bg-gradient-warning' : 'bg-gradient-secondary') }}">
                          {{ ucfirst($activity->status) }}
                        </span>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="alert alert-secondary">
              <span class="text-white">
                <strong>No recent activities found.</strong> Register for events or create new ones to see your activity feed.
              </span>
            </div>
          @endif
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header pb-0 p-3">
          <h6 class="mb-0">Your Profile</h6>
        </div>
        <div class="card-body p-3">
          <ul class="list-group">
            <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
              <div class="avatar me-3">
                <img src="{{ asset('assets/img/team-3.jpg') }}" alt="profile image" class="border-radius-lg shadow">
              </div>
              <div class="d-flex align-items-start flex-column justify-content-center">
                <h6 class="mb-0 text-sm">{{ Auth::user()->name }}</h6>
                <p class="mb-0 text-xs">{{ Auth::user()->email }}</p>
              </div>
            </li>
            <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
              <div class="avatar me-3">
                <i class="ni ni-mobile-button text-dark opacity-6" style="font-size: 1.2rem;"></i>
              </div>
              <div class="d-flex align-items-start flex-column justify-content-center">
                <h6 class="mb-0 text-sm">Phone</h6>
                <p class="mb-0 text-xs">{{ Auth::user()->phone ?? 'Not provided' }}</p>
              </div>
            </li>
            <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
              <div class="avatar me-3">
                <i class="ni ni-pin-3 text-dark opacity-6" style="font-size: 1.2rem;"></i>
              </div>
              <div class="d-flex align-items-start flex-column justify-content-center">
                <h6 class="mb-0 text-sm">Location</h6>
                <p class="mb-0 text-xs">{{ Auth::user()->location ?? 'Not provided' }}</p>
              </div>
            </li>
            <li class="list-group-item border-0 px-0">
              <a href="#" class="btn btn-outline-dark btn-sm w-100 mb-0">Edit Profile</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection 