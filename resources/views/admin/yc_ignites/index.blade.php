@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6>YC Ignite Registrations</h6>
                        </div>
                    </div>

                    <div class="card-body pt-3 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Event</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Participant</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Contact</th>
                                        {{-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">School</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Grade</th> --}}
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Sport Event</th>
                                        {{-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">School Type</th> --}}
                                        {{-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Price</th> --}}
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Date</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($registrations as $key => $reg)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        @if ($reg->event)
                                                            <h6 class="mb-0 text-sm">{{ $reg->event->title }}</h6>
                                                            <p class="text-xs text-secondary mb-0">
                                                                {{ $reg->event->start_date->format('M d, Y') }}</p>
                                                        @else
                                                            <h6 class="mb-0 text-sm text-danger">Event not found</h6>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 text-sm">{{ $reg->first_name }} {{ $reg->last_name }}</h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    {{ $reg->user_id ? 'Registered User' : 'Guest' }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $reg->email }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $reg->mobile }}</p>
                                            </td>
                                            {{-- <td>{{ $reg->school }}</td>
                                    <td>{{ $reg->grade }}</td> --}}
                                            <td>{{ $reg->sport_event }}</td>
                                            {{-- <td>
                                        {{ \DB::table('school_types')->where('id', $reg->age_category)->value('school_type') ?? 'N/A' }}
                                    </td> --}}
                                            {{-- <td>
                                        ₹{{ number_format(\DB::table('school_types')->where('id', $reg->age_category)->value('price') ?? 0, 2) }}
                                    </td> --}}
                                            <td>
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ $reg->created_at->format('M d, Y H:i') }}
                                                </span>
                                            </td>

                                            <td class="align-middle">
                                                <a href="{{ route('admin.yc-ignites.show', $reg->id) }}"
                                                    class="btn btn-link text-info text-gradient px-3 mb-0">
                                                    <i class="fas fa-eye me-2"></i>View
                                                </a>

                                                <a href="{{ route('yc-ignites.receipt', $reg->id) }}" class="btn btn-link text-dark px-3 mb-0" target="_blank">
                                                     <i class="fas fa-download text-dark me-2"></i> Receipt
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <p class="text-sm text-secondary mb-0">No registrations found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if (method_exists($registrations, 'links'))
                            <div class="px-3 pt-4">
                                {{ $registrations->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
