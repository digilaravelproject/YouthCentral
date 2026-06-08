@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0"><h6>Model Question Papers</h6></div>
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
                            <span class="alert-text text-white">{{ session('error') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-12">
                            <form method="GET" class="row g-2">
                                <div class="col-md-3">
                                    <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search by title, subject or topic">
                                </div>
                                <div class="col-md-2">
                                    <select name="class" class="form-control">
                                        <option value="">Current / All</option>
                                        @foreach($availableClasses ?? collect() as $c)
                                            <option value="{{ $c }}" {{ (request('class') == $c || (isset($selectedClass) && $selectedClass == $c)) ? 'selected' : '' }}>{{ $c }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="subject" class="form-control">
                                        <option value="">All Subjects</option>
                                        @foreach($subjects ?? collect() as $sub)
                                            <option value="{{ $sub }}" {{ request('subject') == $sub ? 'selected' : '' }}>{{ $sub }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="topic" class="form-control">
                                        <option value="">All Topics</option>
                                        @foreach($topics ?? collect() as $t)
                                            <option value="{{ $t }}" {{ request('topic') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="month" class="form-control">
                                        <option value="">All Months</option>
                                        @foreach($months ?? collect() as $m)
                                            @php $val = \Carbon\Carbon::parse($m)->format('Y-m'); @endphp
                                            <option value="{{ $val }}" {{ request('month') == $val ? 'selected' : '' }}>{{ \Carbon\Carbon::parse($m)->format('F, Y') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex">
                                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                                    <a href="{{ route('student.model-question-papers.index') }}" class="btn btn-link">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-secondary text-xxs font-weight-bolder">Title</th>
                                    <th class="text-secondary text-xxs font-weight-bolder">Type</th>
                                    <th class="text-secondary text-xxs font-weight-bolder">Subject</th>
                                    <th class="text-secondary text-xxs font-weight-bolder">Topic</th>
                                    <th class="text-secondary text-xxs font-weight-bolder">Month</th>
                                    <th class="text-secondary text-xxs font-weight-bolder">Assigned On</th>
                                    <th class="text-secondary text-xxs font-weight-bolder">Status</th>
                                    <th class="text-secondary text-xxs font-weight-bolder">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($papers as $paper)
                                    <tr>
                                        <td class="align-middle">{{ $paper->title }}</td>
                                        <td class="align-middle"><span class="badge bg-gradient-info">{{ $paper->type }}</span></td>
                                        <td class="align-middle">{{ $paper->subject ?? '-' }}</td>
                                        <td class="align-middle">{{ $paper->topic ?? '-' }}</td>
                                        <td class="align-middle">{{ $paper->month ? \Carbon\Carbon::parse($paper->month)->format('M Y') : '-' }}</td>
                                        <td class="align-middle">{{ $paper->created_at->format('M d, Y') }}</td>
                                        <td class="align-middle">
                                            @php $done = $completedMap[$paper->id] ?? false; @endphp
                                            @if($done)
                                                <span class="badge bg-success">Completed</span>
                                            @else
                                                <span class="badge bg-secondary">Pending</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                          <div class="d-flex align-items-center gap-3 mt-3">

                                              @if($paper->type === 'STEM')
                                                  <a href="{{ route('student.model-question-papers.view', $paper->id) }}" target="_blank"
                                                    class="btn btn-link p-1 text-primary"
                                                    title="View">
                                                      <i class="fas fa-eye"></i>
                                                  </a>
                                              @else
                                                  <a href="{{ route('student.model-question-papers.view', $paper->id) }}" target="_blank"
                                                    class="btn btn-link p-1 text-primary"
                                                    title="View">
                                                      <i class="fas fa-eye"></i>
                                                  </a>

                                                  <a href="{{ route('student.model-question-papers.download', $paper->id) }}"
                                                    class="btn btn-link p-1 text-success"
                                                    title="Download">
                                                      <i class="fas fa-download"></i>
                                                  </a>
                                              @endif

                                              @unless($done)
                                                  <form action="{{ route('student.model-question-papers.complete', $paper->id) }}"
                                                        method="POST"
                                                        style="display:inline-block;">
                                                      @csrf
                                                      <input type="hidden" name="class" value="{{ request('class') ?? $selectedClass ?? '' }}">
                                                      <button type="submit"
                                                              class="btn btn-link p-1 text-success"
                                                              title="Mark Completed">
                                                          <i class="fas fa-check-circle"></i>
                                                      </button>
                                                  </form>
                                              @endunless

                                          </div>
                                      </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">No model question papers assigned yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $papers->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
