@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Student Progress Tracking</h5>
                        <p class="text-sm mb-0 text-muted">Manage participation rules and monitor students overall achievements.</p>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
                            <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                            <span class="alert-text">{{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
                            <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
                            <span class="alert-text">{{ session('error') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Custom Tab Headers -->
                    <div class="progress-tabs mb-4">
                        <ul class="nav" id="progressTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link tab-pill active me-2 px-3 py-2" id="rules-tab" data-bs-toggle="tab" data-bs-target="#rules" type="button" role="tab" aria-controls="rules" aria-selected="true">
                                    <i class="fas fa-cog me-2"></i> Activity Rules
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link tab-pill px-3 py-2" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab" aria-controls="students" aria-selected="false">
                                    <i class="fas fa-graduation-cap me-2"></i> Student Progress
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="progressTabsContent">
                        
                        <!-- TAB 1: Activity Rules -->
                        <div class="tab-pane fade show active" id="rules" role="tabpanel" aria-labelledby="rules-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Configured Participation Activities</h6>
                                <a href="{{ route('admin.progress-tracking.create') }}" class="btn btn-primary btn-sm mb-0">
                                    <i class="fas fa-plus me-1"></i> Add Activity Rule
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Activity Type</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">Limit / Max Count</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">Percentage Weight</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7 text-end pe-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $totalPercentage = 0; @endphp
                                        @forelse($rules as $rule)
                                            @php $totalPercentage += $rule->percentage; @endphp
                                            <tr>
                                                <td class="ps-3">
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-gradient-info text-center me-3 d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-tasks text-white"></i>
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $rule->title }}</h6>
                                                            <p class="text-xs text-secondary mb-0">Code: {{ $rule->activity_type }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-sm font-weight-bold text-dark">{{ $rule->max_limit }} times</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-gradient-success">{{ $rule->percentage }}%</span>
                                                </td>
                                                <td class="align-middle text-end pe-4">
                                                    <a href="{{ route('admin.progress-tracking.edit', $rule->id) }}" class="btn btn-link text-info px-3 mb-0" title="Edit Rule">
                                                        <i class="fas fa-pencil-alt me-1"></i> Edit
                                                    </a>
                                                    <form action="{{ route('admin.progress-tracking.destroy', $rule->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this rule? Student logs for this rule will be preserved but won\'t contribute to overall progress.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger text-gradient px-3 mb-0">
                                                            <i class="far fa-trash-alt me-1"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5">
                                                    <p class="text-secondary mb-0">No progress rules configured yet. Hit "Add Activity Rule" above.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($rules->isNotEmpty())
                                <div class="mt-4 p-3 bg-light border-radius-lg d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-sm font-weight-bold text-dark">Combined Weights Total:</span>
                                        <p class="text-xs text-secondary mb-0">Calculated as the sum of all configurations. Sum can exceed 100%.</p>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 text-gradient text-success font-weight-bolder">{{ $totalPercentage }}%</h4>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- TAB 2: Student Progress -->
                        <div class="tab-pane fade" id="students" role="tabpanel" aria-labelledby="students-tab">
                            <div class="row mb-3">
                                <div class="col-md-6 col-12">
                                    <form method="GET" action="{{ route('admin.progress-tracking.index') }}" class="d-flex">
                                        <!-- Include query target when searching -->
                                        <input type="hidden" name="tab" value="students">
                                        <div class="input-group">
                                            <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                                            <input type="text" name="search" class="form-control" placeholder="Search by name, phone or class..." value="{{ request('search') }}">
                                        </div>
                                        <button type="submit" class="btn btn-primary mb-0 ms-2">Search</button>
                                        @if(request()->filled('search'))
                                            <a href="{{ route('admin.progress-tracking.index') }}" class="btn btn-link mb-0 ms-2">Reset</a>
                                        @endif
                                    </form>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Student Info</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">Class/Grade</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">Overall Progress</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7 text-center">Streak</th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7 text-end pe-4">Activity breakdown</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($students as $student)
                                            <tr>
                                                <td class="ps-3">
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm text-dark">{{ $student->name }}</h6>
                                                            <p class="text-xs text-secondary mb-0"><i class="fas fa-phone fa-xs me-1"></i>{{ $student->phone }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-gradient-light text-dark">{{ $student->student_class ?? 'N/A' }}</span>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2 text-xs font-weight-bold">{{ $student->overall_progress }}%</span>
                                                        <div class="progress-wrapper w-75">
                                                            <div class="progress progress-xs mb-0">
                                                                <div class="progress-bar bg-gradient-{{ $student->overall_progress >= 100 ? 'success' : 'primary' }}" role="progressbar" aria-valuenow="{{ $student->overall_progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $student->overall_progress }}%;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-gradient-warning"><i class="fas fa-fire me-1"></i>{{ $student->login_streak }} Days</span>
                                                </td>
                                                <td class="align-middle text-end pe-4">
                                                    <button class="btn btn-sm btn-outline-primary mb-0" type="button" data-bs-toggle="collapse" data-bs-target="#student-details-{{ $student->id }}" aria-expanded="false" aria-controls="student-details-{{ $student->id }}">
                                                        <i class="fas fa-chart-line me-1"></i> Details
                                                    </button>
                                                </td>
                                            </tr>
                                            <!-- Collapsible Details Row -->
                                            <tr class="collapse-row">
                                                <td colspan="5" class="p-0 border-0">
                                                    <div class="collapse" id="student-details-{{ $student->id }}">
                                                        <div class="p-4 bg-light border-radius-lg m-3 shadow-inner">
                                                            <h6 class="text-sm font-weight-bold text-dark mb-3"><i class="fas fa-info-circle text-info me-2"></i>Detailed Activity Logs for {{ $student->name }}</h6>
                                                            <div class="row">
                                                                @forelse($student->progress_details as $detail)
                                                                    <div class="col-md-4 col-sm-6 mb-3">
                                                                        <div class="card p-3 h-100 border border-radius-md shadow-none bg-white">
                                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                                <span class="text-xs font-weight-bold text-muted">{{ $detail['title'] }}</span>
                                                                                <span class="badge badge-sm {{ $detail['badge_class'] }}">{{ $detail['status'] }}</span>
                                                                            </div>
                                                                            <div class="d-flex justify-content-between align-items-end mt-auto">
                                                                                <div>
                                                                                    <h4 class="mb-0 font-weight-bolder">{{ $detail['current_count'] }}<span class="text-xs font-weight-normal text-muted"> / {{ $detail['limit'] }}</span></h4>
                                                                                    <p class="text-xxs text-secondary mb-0">Times performed</p>
                                                                                </div>
                                                                                <div class="text-end">
                                                                                    <span class="badge bg-gradient-success text-xxs">+{{ $detail['earned_percentage'] }}%</span>
                                                                                    <p class="text-xxs text-secondary mb-0">of overall weight ({{ $detail['percentage'] }}%)</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @empty
                                                                    <div class="col-12 text-center py-3">
                                                                        <p class="text-xs text-secondary mb-0">No configured rules.</p>
                                                                    </div>
                                                                @endforelse
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5">
                                                    <p class="text-secondary mb-0">No registered students found.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $students->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Automatically open correct tab based on query param
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        if (tabParam === 'students') {
            const studentsTabBtn = document.querySelector('#students-tab');
            if (studentsTabBtn) {
                bootstrap.Tab.getOrCreateInstance(studentsTabBtn).show();
            }
        }
    });
</script>

<style>
    .collapse-row > td {
        border-bottom-width: 0 !important;
    }

    /* Improved tab pill styles for Student Progress Tracking */
    .progress-tabs {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: .5rem;
    }

    .progress-tabs .nav {
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .tab-pill {
        border-radius: 999px;
        border: 1px solid rgba(34,41,47,0.06);
        color: #4b5563;
        background: #fff;
        box-shadow: 0 1px 3px rgba(16,24,40,0.04);
        transition: all .15s ease-in-out;
        font-weight: 600;
    }

    .tab-pill i {
        color: #6b7280;
    }

    .tab-pill:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(15,23,42,0.06);
    }

    .tab-pill.active {
        color: #fff !important;
        background: linear-gradient(90deg,#ff4da6 0%, #ff7bb3 100%);
        border-color: transparent;
        box-shadow: 0 8px 30px rgba(255,77,166,0.15);
    }

    /* small screens: stack pills nicely */
    @media (max-width: 576px) {
        .progress-tabs .nav { flex-direction: column; align-items: stretch; }
        .tab-pill { width: 100%; text-align: left; }
    }
</style>
@endsection
