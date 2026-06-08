@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Study Material Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="text-xs font-weight-bold opacity-7 text-uppercase">Title</label>
                        <h5 class="font-weight-bolder">{{ $studyMaterial->title }}</h5>
                    </div>

                    <div class="mb-4">
                        <label class="text-xs font-weight-bold opacity-7 text-uppercase">Subject</label>
                        <p>{{ $studyMaterial->subject ?? '-' }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="text-xs font-weight-bold opacity-7 text-uppercase">Topic</label>
                        <p>{{ $studyMaterial->topic ?? '-' }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="text-xs font-weight-bold opacity-7 text-uppercase">Month</label>
                        <p>{{ $studyMaterial->month ? \Carbon\Carbon::parse($studyMaterial->month)->format('F, Y') : '-' }}</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-xs font-weight-bold opacity-7 text-uppercase">Type</label>
                            <p><span class="badge bg-gradient-info">{{ $studyMaterial->type }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-xs font-weight-bold opacity-7 text-uppercase">Status</label>
                            <p><span class="badge bg-gradient-{{ $studyMaterial->status ? 'success' : 'secondary' }}">{{ $studyMaterial->status ? 'Active' : 'Inactive' }}</span></p>
                        </div>
                    </div>

                    @if($studyMaterial->type === 'STEM')
                        <div class="mb-4">
                            <label class="text-xs font-weight-bold opacity-7 text-uppercase">Video Link</label>
                            <p><a href="{{ $studyMaterial->video_link }}" target="_blank" class="text-primary">{{ $studyMaterial->video_link }}</a></p>
                            @php
                                $videoId = null;
                                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $studyMaterial->video_link, $match)) {
                                    $videoId = $match[1];
                                }
                            @endphp
                            @if($videoId)
                                <div class="ratio ratio-16x9 mt-3">
                                    <iframe src="https://www.youtube.com/embed/{{ $videoId }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="mb-4">
                            <label class="text-xs font-weight-bold opacity-7 text-uppercase">File</label>
                            <p>
                                <a href="{{ asset('storage/' . $studyMaterial->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-2"></i> View / Download File
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Assigned Students ({{ $studyMaterial->students->count() }})</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0" style="max-height: 500px; overflow-y: auto;">
                        <table class="table align-items-center mb-0">
                            <tbody>
                                @foreach($studyMaterial->students as $registration)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $registration->first_name }} {{ $registration->last_name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $registration->mobile }}</p>
                                                    <span class="badge badge-xxs bg-light text-dark mt-1" style="width: fit-content;">Class: {{ $registration->grade }}</span>
                                                </div>
                                            </div>
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
