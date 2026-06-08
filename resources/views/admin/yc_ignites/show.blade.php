@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>YC Ignite Registration Details</h6>
                        <a href="{{ route('admin.yc-ignites.index') }}" class="btn btn-sm btn-primary">Back to List</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Event Info -->
                        <div class="col-md-6">
                            <h6 class="mb-3 text-uppercase text-xs">Event Information</h6>
                            <div class="card card-plain">
                                <div class="card-body p-3">
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Event Title:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->event->title ?? 'N/A' }}</div>
                                    </div>
                                    @if($registration->event)
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Date:</div>
                                        <div class="col-md-8 text-sm">
                                            {{ $registration->event->start_date->format('M d, Y') }}
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Venue:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->event->venue }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Participant Info -->
                        <div class="col-md-6">
                            <h6 class="mb-3 text-uppercase text-xs">Participant Information</h6>
                            <div class="card card-plain">
                                <div class="card-body p-3">
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Name:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->first_name }} {{ $registration->last_name }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Email:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->email }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Mobile:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->mobile }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Address:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->address }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Registration Details -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="mb-3 text-uppercase text-xs">Registration Details</h6>
                            <div class="card card-plain">
                                <div class="card-body p-3">
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Reference:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->id }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Date:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->created_at->format('M d, Y h:i A') }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">Sport/Event:</div>
                                        <div class="col-md-8 text-sm">{{ $registration->sport_event }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 text-sm font-weight-bold">School Type:</div>
                                        <div class="col-md-8 text-sm">
                                            {{ \DB::table('school_types')->where('id', $registration->age_category)->value('school_type') ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 text-sm font-weight-bold">Price:</div>
                                        <div class="col-md-8 text-sm">
                                            ?{{ number_format(\DB::table('school_types')->where('id', $registration->age_category)->value('price') ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

               
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
