@extends('layouts.user_type.auth')

@section('content')

<div class="container-fluid py-4">
  <div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Users</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $totalUsers ?? 0 }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>
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
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Vendors</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $totalVendors ?? 0 }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                <i class="ni ni-shop text-lg opacity-10" aria-hidden="true"></i>
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
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Pending Vendors</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $pendingVendors ?? 0 }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                <i class="ni ni-time-alarm text-lg opacity-10" aria-hidden="true"></i>
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
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Products</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $totalProducts ?? 0 }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="row mt-4">
    <div class="col-lg-12 mb-lg-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-lg-6">
              <div class="d-flex flex-column h-100">
                <p class="mb-1 pt-2 text-bold">Welcome back, Admin!</p>
                <h5 class="font-weight-bolder">System Administration Dashboard</h5>
                <p class="mb-5">From here you can manage users, vendors, approve vendor registrations, and monitor your platform's activities.</p>
                <a class="text-body text-sm font-weight-bold mb-0 icon-move-right mt-auto" href="{{ route('admin.vendors.pending') }}">
                  View Pending Vendors
                  <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                </a>
              </div>
            </div>
            <div class="col-lg-5 ms-auto text-center mt-5 mt-lg-0">
              <div class="bg-gradient-primary border-radius-lg h-100">
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
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header pb-0">
          <h6>Quick Actions</h6>
        </div>
        <div class="card-body p-3">
          <div class="timeline timeline-one-side">
            <div class="timeline-block mb-3">
              <span class="timeline-step">
                <i class="ni ni-single-02 text-success text-gradient"></i>
              </span>
              <div class="timeline-content">
                <h6 class="text-dark text-sm font-weight-bold mb-0">Create New User</h6>
                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Add a new user account to the system</p>
                <a href="{{ route('admin.users.create') }}" class="btn btn-sm bg-gradient-dark mt-2">Create User</a>
              </div>
            </div>
            <div class="timeline-block mb-3">
              <span class="timeline-step">
                <i class="ni ni-shop text-info text-gradient"></i>
              </span>
              <div class="timeline-content">
                <h6 class="text-dark text-sm font-weight-bold mb-0">Create New Vendor</h6>
                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Add a new vendor account to the system</p>
                <a href="{{ route('admin.vendors.create') }}" class="btn btn-sm bg-gradient-dark mt-2">Create Vendor</a>
              </div>
            </div>
            <div class="timeline-block">
              <span class="timeline-step">
                <i class="ni ni-check-bold text-warning text-gradient"></i>
              </span>
              <div class="timeline-content">
                <h6 class="text-dark text-sm font-weight-bold mb-0">Approve Pending Vendors</h6>
                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Review and approve vendor registration requests</p>
                <a href="{{ route('admin.vendors.pending') }}" class="btn btn-sm bg-gradient-dark mt-2">View Pending</a>
              </div>
            </div>

            <div class="timeline-block">
              <span class="timeline-step">
                <i class="ni ni-cloud-upload-96 text-info text-gradient"></i>
              </span>
              <div class="timeline-content">
                <h6 class="text-dark text-sm font-weight-bold mb-0">Bulk Import/Export</h6>
                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Import or export business data in bulk</p>
                <div class="mt-2">
                  <a href="{{ route('admin.bulk-import-export') }}" class="btn btn-sm bg-gradient-dark">Import/Export</a>
                  <a href="{{ route('admin.bulk-sample-download') }}" class="btn btn-sm bg-gradient-info">Download Sample</a>
                  <a href="{{ route('admin.bulk-export') }}" class="btn btn-sm bg-gradient-success">Export All</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-6 mt-4 mt-lg-0">
      <div class="card h-100">
        <div class="card-header pb-0">
          <h6>Recent Activity</h6>
        </div>
        <div class="card-body p-3">
          <div class="alert alert-secondary">
            <span class="text-white">
              <strong>System activity will be displayed here.</strong> This section can be developed to show recent logins, user registrations, vendor applications, and other important events.
            </span>
          </div>
          <div class="alert alert-primary mt-4">
            <span class="text-white">
              <strong>Coming Soon!</strong> Additional dashboard features and analytics will be available in future updates.
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection 