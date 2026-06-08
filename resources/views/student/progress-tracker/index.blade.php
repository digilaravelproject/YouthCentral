@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Block -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary border-radius-xl p-4 shadow-lg position-relative overflow-hidden">
                <img src="{{ asset('assets/img/shapes/waves-white.svg') }}" class="position-absolute h-100 w-50 top-0 end-0 d-md-block d-none opacity-2" alt="waves">
                <div class="position-relative z-index-1">
                    <h4 class="text-white font-weight-bolder mb-1">My Progress Tracker</h4>
                    <p class="text-white text-sm opacity-8 mb-0">Monitor your participation milestones, download streaks, and study accomplishments.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Index Card -->
    <div class="row mb-4">
        <div class="col-lg-6 col-12 mb-lg-0 mb-4">
            <div class="card h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h6 class="text-sm text-uppercase text-muted font-weight-bold mb-3">Overall Progress Index</h6>
                        <h2 class="font-weight-bolder text-primary mb-1">{{ $overallPercentage }}%</h2>
                        <p class="text-xs text-secondary mb-3">Your cumulative activity score calculated from rule weights: <strong>{{ $totalEarned }}%</strong> earned out of <strong>{{ $totalConfigured }}%</strong> total weight.</p>
                    </div>

                    <div class="progress-wrapper">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-xs font-weight-bold text-dark">Completion Rate</span>
                            <span class="text-xs font-weight-bold text-primary">{{ $overallPercentage }}%</span>
                        </div>
                        <div class="progress progress-md mb-0">
                            <div class="progress-bar bg-gradient-primary" role="progressbar" aria-valuenow="{{ $overallPercentage }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $overallPercentage }}%; transition: width 1s ease;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-12">
            <div class="card h-100">
                <div class="card-body p-4">
                    <h6 class="text-sm text-uppercase text-muted font-weight-bold mb-3">Streak Status</h6>
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md me-3">
                            <i class="fas fa-fire text-white fa-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-weight-bolder text-dark mb-0">{{ $student->login_streak }} Days</h4>
                            <p class="text-xs text-secondary mb-0">Consecutive Login Streak</p>
                        </div>
                    </div>
                    <div class="alert alert-info text-white text-xs border-0 border-radius-lg mb-0" role="alert">
                        <i class="fas fa-info-circle me-1"></i> Log in daily to maintain and increment your login streak! Reaching your login streak limit awards you the full weight on your progress tracker.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activities Cards Grid -->
    <div class="row">
        <div class="col-12">
            <h5 class="font-weight-bolder text-dark mb-3">Activity Breakdowns</h5>
        </div>

        @forelse($activities as $activity)
            <div class="col-xl-4 col-md-6 col-12 mb-4">
                <div class="card h-100 shadow-sm border border-radius-lg bg-white overflow-hidden transition-transform" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                    @if($activity['current_count'] >= $activity['limit'])
                        <div class="bg-gradient-success" style="height: 4px;"></div>
                    @else
                        <div class="bg-gradient-info" style="height: 4px;"></div>
                    @endif
                    
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="text-xs font-weight-bold text-muted">{{ $activity['title'] }}</span>
                                <span class="badge badge-sm {{ $activity['badge_class'] }}">{{ $activity['status'] }}</span>
                            </div>
                            
                            <h4 class="font-weight-bolder text-dark mb-1">
                                {{ $activity['current_count'] }}
                                <span class="text-xs font-weight-normal text-muted">/ {{ $activity['limit'] }}</span>
                            </h4>
                            <p class="text-xxs text-secondary mb-3">Actions completed towards the goal</p>
                        </div>

                        <div class="mt-auto">
                            <!-- Mini Progress Bar -->
                            @php 
                                $miniRatio = $activity['limit'] > 0 ? min(($activity['current_count'] / $activity['limit']) * 100, 100) : 0;
                            @endphp
                            <div class="progress progress-xs mb-2">
                                <div class="progress-bar bg-gradient-{{ $activity['current_count'] >= $activity['limit'] ? 'success' : 'info' }}" role="progressbar" style="width: {{ $miniRatio }}%;"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center text-xs">
                                <span class="text-secondary font-weight-bold">Contribution:</span>
                                <span class="text-success font-weight-bolder">+{{ $activity['earned_percentage'] }}% <span class="text-xxs font-weight-normal text-muted">/ {{ $activity['percentage'] }}%</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-chart-line fa-3x text-secondary opacity-3"></i>
                </div>
                <h5 class="text-secondary">No tracking activities assigned by admin yet.</h5>
            </div>
        @endforelse
    </div>
</div>
@endsection
