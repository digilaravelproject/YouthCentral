@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header pb-0"><h6>Model Question Paper Details</h6></div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="text-xs font-weight-bold opacity-7 text-uppercase">Title</label>
                        <h5 class="font-weight-bolder">{{ $paper->title }}</h5>
                    </div>

                    <div class="mb-4">
                        <label class="text-xs font-weight-bold opacity-7 text-uppercase">Subject</label>
                        <p>{{ $paper->subject ?? '-' }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="text-xs font-weight-bold opacity-7 text-uppercase">Topic</label>
                        <p>{{ $paper->topic ?? '-' }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="text-xs font-weight-bold opacity-7 text-uppercase">Month</label>
                        <p>{{ $paper->month ? \Carbon\Carbon::parse($paper->month)->format('F, Y') : '-' }}</p>
                    </div>

                    @if($paper->type === 'STEM')
                        <div class="mb-4">
                            <label class="text-xs font-weight-bold opacity-7 text-uppercase">Video Link</label>
                            <p><a href="{{ $paper->video_link }}" target="_blank">{{ $paper->video_link }}</a></p>
                        </div>
                    @else
                        <div class="mb-4">
                            <label class="text-xs font-weight-bold opacity-7 text-uppercase">File</label>
                            <p><a href="{{ asset('storage/' . $paper->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">View / Download File</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header pb-0"><h6>Assigned Students ({{ $paper->students->count() }})</h6></div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0" style="max-height: 500px; overflow-y: auto;">
                        <table class="table align-items-center mb-0">
                            <tbody>
                                @foreach($paper->students as $registration)
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
