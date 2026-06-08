@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Event Analytics - {{ $event->title }}</h6>
                        <div>
                            <form class="d-flex gap-2" method="GET">
                                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Stats Cards -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Registrations</p>
                                <h5 class="font-weight-bolder mb-0">{{ $stats['total_registrations'] }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Paid Registrations</p>
                                <h5 class="font-weight-bolder mb-0">{{ $stats['total_paid'] }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                <i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Amount</p>
                                <h5 class="font-weight-bolder mb-0">₹{{ number_format($stats['total_amount'], 2) }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Seats Remaining</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $stats['seats_remaining'] ?? 'Unlimited' }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Registration Trend Chart -->
        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Registration Trend</h6>
                </div>
                <div class="card-body">
                    <canvas id="registrationTrendChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- School Type Breakdown -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>School Type Breakdown</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="chart">
                        <canvas id="age-category-chart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Registrations -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Recent Registrations</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Participant</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contact</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRegistrations as $registration)
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $registration->first_name }} {{ $registration->last_name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $registration->age_category }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $registration->email }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $registration->mobile }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">₹{{ number_format($registration->amount, 2) }}</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm {{ $registration->payment_status === 'paid' ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                            {{ ucfirst($registration->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ $registration->created_at->format('M d, Y H:i') }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Registration Trend Chart
    const trendCtx = document.getElementById('registrationTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($registrationTrend->pluck('date')) !!},
            datasets: [{
                label: 'Registrations',
                data: {!! json_encode($registrationTrend->pluck('count')) !!},
                borderColor: '#5e72e4',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(94, 114, 228, 0.1)',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // School Type Chart
    var ageCtx = document.getElementById("age-category-chart").getContext("2d");
    var ageData = {
        labels: {!! json_encode($ageCategoryLabels) !!},
        datasets: [{
            label: "Registrations",
            backgroundColor: 'rgba(66, 135, 245, 0.5)',
            borderColor: 'rgb(66, 135, 245)',
            data: {!! json_encode($ageCategoryCounts) !!},
        }]
    };

    new Chart(ageCtx, {
        type: "bar",
        data: ageData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                title: {
                    display: true,
                    text: 'School Type Distribution'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
});
</script>
@endpush 