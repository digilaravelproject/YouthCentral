@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>My Study Materials</h6>
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
                            <span class="alert-text text-white">{{ session('error') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <style>
                        /* Make study material cards uniform height */
                        .study-col { display: flex; }
                        .study-material-card { display: flex; flex-direction: column; height: 100%; width: 100%; }
                        .study-material-card .card-body { flex: 1 1 auto; display:flex; flex-direction:column; justify-content:space-between; }
                        .study-thumb { height: 220px; overflow: hidden; }
                        .study-thumb img { width: 100%; height: 100%; object-fit: cover; display:block; }
                        .study-thumb .d-flex { height: 100%; }
                    </style>

                    <div class="row">
                        <div class="col-12 mb-4">
                            <form method="GET" class="row g-2 align-items-end">
                                <div class="col-md-2">
                                        <label class="form-label text-xs">Class</label>
                                        <select name="class" class="form-control">
                                            <option value="">Current / All</option>
                                            @foreach($availableClasses ?? collect() as $c)
                                                <option value="{{ $c }}" {{ (request('class') == $c || (isset($selectedClass) && $selectedClass == $c)) ? 'selected' : '' }}>{{ $c }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-xs">Subject</label>
                                        <select name="subject" class="form-control">
                                        <option value="">All Subjects</option>
                                        @foreach($subjects ?? collect() as $sub)
                                            <option value="{{ $sub }}" {{ request('subject') == $sub ? 'selected' : '' }}>{{ $sub }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-xs">Topic</label>
                                        <select name="topic" class="form-control">
                                        <option value="">All Topics</option>
                                        @foreach($topics ?? collect() as $t)
                                            <option value="{{ $t }}" {{ request('topic') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-xs">Month</label>
                                        <select name="month" class="form-control">
                                        <option value="">All Months</option>
                                        @foreach($months ?? collect() as $m)
                                            @php $val = \Carbon\Carbon::parse($m)->format('Y-m'); @endphp
                                            <option value="{{ $val }}" {{ request('month') == $val ? 'selected' : '' }}>{{ \Carbon\Carbon::parse($m)->format('F, Y') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2 mb-0">
                                        Filter
                                    </button>

                                    <a href="{{ route('student.study-materials.index') }}"
                                    class="btn btn-link mb-0">
                                        Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                        <hr>
                        @forelse($materials as $material)
                            <div class="col-xl-3 col-md-6 mb-4 study-col">
                                <div class="card card-blog card-plain study-material-card">
                                    <div class="position-relative">
                                        <div class="d-block shadow-xl border-radius-xl overflow-hidden study-thumb">
                                            @if($material->type === 'STEM')
                                                @php
                                                    $videoId = null;
                                                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $material->video_link, $match)) {
                                                        $videoId = $match[1];
                                                    }
                                                @endphp
                                                @if($videoId)
                                                    <img src="https://img.youtube.com/vi/{{ $videoId }}/mqdefault.jpg" alt="img-blur-shadow" class="img-fluid shadow border-radius-xl">
                                                @else
                                                    <div class="bg-gradient-info d-flex align-items-center justify-content-center">
                                                        <i class="fab fa-youtube text-white fa-4x"></i>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="bg-gradient-{{ $material->type === 'PDF' ? 'danger' : 'primary' }} d-flex align-items-center justify-content-center">
                                                    <i class="fas {{ $material->type === 'PDF' ? 'fa-file-pdf' : 'fa-file-alt' }} text-white fa-4x"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body px-1 pb-0">
                                        <p class="text-gradient text-dark mb-2 text-sm">{{ $material->type }}</p>
                                        <a href="javascript:;">
                                            <h5>{{ $material->title }}</h5>
                                        </a>
                                        <p class="mb-4 text-sm text-secondary">
                                            Assigned on: {{ $material->created_at->format('M d, Y') }}
                                        </p>
                                        <div class="d-flex align-items-center justify-content-between">
                                            @if($material->type === 'STEM')
                                                <a href="{{ route('student.study-materials.view', $material->id) }}" target="_blank" class="btn btn-outline-primary btn-sm mb-0">View Video</a>
                                            @else
                                                <div class="btn-group" role="group" aria-label="Study material actions">
                                                    <a href="{{ route('student.study-materials.view', $material->id) }}" target="_blank" class="btn btn-outline-primary btn-sm mb-0">View</a>
                                                    <a href="{{ route('student.study-materials.download', $material->id) }}" class="btn btn-primary btn-sm mb-0">Download</a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-book-open fa-3x text-secondary opacity-3"></i>
                                </div>
                                <h5 class="text-secondary">No study materials assigned yet.</h5>
                                <p class="text-sm text-secondary">Your assigned materials will appear here once they are uploaded by the admin.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $materials->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
