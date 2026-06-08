@foreach($events as $event)
<div class="col-md-4 mb-4">
    <div class="card">
        @if($event->images->count() > 0)
            <img src="{{ asset('storage/' . $event->images->first()->image_path) }}" class="card-img-top" alt="{{ $event->title }}">
        @else
            <img src="{{ asset('assets/img/default-event.jpg') }}" class="card-img-top" alt="{{ $event->title }}">
        @endif
        <div class="card-body">
            <h5 class="card-title">{{ $event->title }}</h5>
            <p class="card-text text-truncate">{{ $event->description }}</p>
            <p class="card-text"><i class="fas fa-map-marker-alt"></i> {{ $event->venue }}</p>
            @if($event->city || $event->state)
            <p class="card-text">
                <i class="fas fa-location-dot"></i> 
                {{ $event->city ? $event->city->name : '' }}
                {{ $event->state ? ($event->city ? ', ' . $event->state->name : $event->state->name) : '' }}
            </p>
            @endif
            <p class="card-text"><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</p>
            <a href="{{ route('events.show', $event) }}" class="btn btn-primary">View Details</a>
        </div>
    </div>
</div>
@endforeach 