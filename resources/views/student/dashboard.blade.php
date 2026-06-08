@extends('layouts.user_type.auth')

@section('content')

<div class="container-fluid py-4">

  <!-- Stats Row -->
  <div class="row mb-4">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Study Materials</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $materialsCount ?? 0 }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                <i class="ni ni-books text-lg opacity-10" aria-hidden="true"></i>
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
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Model Question Papers</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $papersCount ?? 0 }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
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
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Badges Earned</p>
                <h5 class="font-weight-bolder mb-0">
                  7
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                <i class="ni ni-trophy text-lg opacity-10" aria-hidden="true"></i>
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
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Rewards Balance</p>
                <h5 class="font-weight-bolder mb-0">
                  350 <span class="text-xs font-weight-normal">pts</span>
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                <i class="ni ni-diamond text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Welcome & Countdown Section -->
  <div class="row mb-4">
    <div class="col-lg-8 mb-lg-0 mb-4">
      <div class="card h-100">
        <div class="card-body p-3">
          <div class="row h-100">
            <div class="col-lg-7 d-flex flex-column h-100">
              <p class="mb-1 pt-2 text-bold">Welcome back,</p>
              <h4 class="font-weight-bolder text-primary">{{ Auth::guard('student')->user()->name ?? 'Student' }}</h4>
              <p class="mb-2"><strong>Class/Grade:</strong> <span class="badge bg-gradient-info">{{ $studentClass ?? 'N/A' }}</span></p>
              <p class="mb-4 text-sm">You are doing great! Keep up the good work and prepare for your upcoming YC SPARK Exam.</p>
              
                <div class="mt-auto pt-4 border-top">
                 <h6 class="text-uppercase text-muted text-xs font-weight-bolder mb-3"><i class="fas fa-clock text-warning me-2"></i> YC SPARK Exam Begins In:</h6>
                 <div class="d-flex align-items-center">
                    <div class="bg-gradient-warning text-white rounded p-3 me-3 text-center shadow-lg countdown-box" style="min-width: 80px; transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        <h3 class="mb-0 text-white font-weight-bolder" id="cd-days">00</h3>
                        <span class="text-xs text-uppercase font-weight-bold opacity-8">Days</span>
                    </div>
                    <div class="bg-gradient-warning text-white rounded p-3 me-3 text-center shadow-lg countdown-box" style="min-width: 80px; transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        <h3 class="mb-0 text-white font-weight-bolder" id="cd-hours">00</h3>
                        <span class="text-xs text-uppercase font-weight-bold opacity-8">Hours</span>
                    </div>
                    <div class="bg-gradient-warning text-white rounded p-3 me-3 text-center shadow-lg countdown-box" style="min-width: 80px; transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        <h3 class="mb-0 text-white font-weight-bolder" id="cd-minutes">00</h3>
                        <span class="text-xs text-uppercase font-weight-bold opacity-8">Mins</span>
                    </div>
                    <div class="bg-gradient-warning text-white rounded p-3 text-center shadow-lg countdown-box" style="min-width: 80px; transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        <h3 class="mb-0 text-white font-weight-bolder" id="cd-seconds">00</h3>
                        <span class="text-xs text-uppercase font-weight-bold opacity-8">Secs</span>
                    </div>
                 </div>
              </div>
            </div>
            <div class="col-lg-5 ms-auto text-center mt-5 mt-lg-0">
              <div class="bg-gradient-primary border-radius-lg h-100 d-flex flex-column justify-content-center align-items-center">
                <img src="{{ asset('assets/img/shapes/waves-white.svg') }}" class="position-absolute h-100 w-50 top-0 d-lg-block d-none" alt="waves">
                <div class="position-relative d-flex align-items-center justify-content-center h-100 w-100">
                  <i class="fas fa-graduation-cap text-white opacity-8" style="font-size: 6rem;"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header pb-0 p-3">
          <h6 class="mb-0">Recent Notifications</h6>
        </div>
        <div class="card-body p-3">
          <div class="timeline timeline-one-side">
            @forelse($notifications as $notification)
              @php $data = (array) $notification->data; @endphp
              <div class="timeline-block mb-3">
                <span class="timeline-step">
                  <i class="ni ni-bell-55 text-success text-gradient"></i>
                </span>
                <div class="timeline-content">
                  <h6 class="text-dark text-sm font-weight-bold mb-0">{{ $data['title'] ?? 'Notification' }}</h6>
                  <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ optional($notification->created_at)->format('d M h:i A') }}</p>
                  <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $data['message'] ?? '' }}</p>
                </div>
              </div>
            @empty
              <div class="text-center text-secondary">No notifications</div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Progress Tracker -->
  <div class="row">
    <div class="col-lg-12">
      <div class="card z-index-2">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
          <div>
            <h6>Progress Tracker</h6>
            <p class="text-sm">
              <i class="fa fa-chart-line text-success"></i>
              <span class="font-weight-bold">Real-time</span> activity tracking
            </p>
          </div>
          <a href="{{ route('student.progress-tracker.index') }}" class="btn btn-sm btn-outline-primary mb-0">View Report Card</a>
        </div>
        <div class="card-body p-3">
          <div class="row align-items-center">
            <div class="col-lg-4 col-12 text-center mb-3 mb-lg-0">
              <div class="p-3 bg-light border-radius-lg">
                <h1 class="display-3 font-weight-bolder text-gradient text-primary mb-0">{{ $overallPercentage }}%</h1>
                <p class="text-xs text-secondary font-weight-bold mb-0">Overall Milestone Achieved</p>
              </div>
            </div>
            <div class="col-lg-8 col-12">
              <div class="p-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="text-xs font-weight-bold text-dark">Overall Completion Rate</span>
                  <span class="text-xs font-weight-bold text-primary">{{ $overallPercentage }}%</span>
                </div>
                <div class="progress progress-md mb-3">
                  <div class="progress-bar bg-gradient-primary" role="progressbar" aria-valuenow="{{ $overallPercentage }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $overallPercentage }}%; transition: width 1s ease;"></div>
                </div>
                <p class="text-sm text-secondary mb-0">
                  Great work! You have completed <strong>{{ $overallPercentage }}%</strong> of your target milestones. Complete your daily logins, read study guides, and complete question papers to advance your progress to 100%.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

@endsection

@push('dashboard')
<script>
  // Dynamic Real-Time Countdown
  document.addEventListener('DOMContentLoaded', function() {
    const targetDate = new Date("{{ $examDate }}").getTime();

    const updateCountdown = () => {
      const now = new Date().getTime();
      const distance = targetDate - now;

      if (distance < 0) {
        document.getElementById("cd-days").innerText = "00";
        document.getElementById("cd-hours").innerText = "00";
        document.getElementById("cd-minutes").innerText = "00";
        document.getElementById("cd-seconds").innerText = "00";
        return;
      }

      const days = Math.floor(distance / (1000 * 60 * 60 * 24));
      const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((distance % (1000 * 60)) / 1000);

      document.getElementById("cd-days").innerText = days.toString().padStart(2, '0');
      document.getElementById("cd-hours").innerText = hours.toString().padStart(2, '0');
      document.getElementById("cd-minutes").innerText = minutes.toString().padStart(2, '0');
      document.getElementById("cd-seconds").innerText = seconds.toString().padStart(2, '0');
    };

    updateCountdown(); // Initial call
    setInterval(updateCountdown, 1000); // Update every second
  });
</script>
@endpush
