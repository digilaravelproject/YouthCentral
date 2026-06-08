<div class="col-lg-4 col-md-6 mb-4">
    <div class="card h-100">
        @if($event->banners->isNotEmpty())
            <img src="{{ Storage::url($event->banners->first()->image_path) }}" 
                 class="card-img-top" alt="{{ $event->title }}" 
                 style="height: 200px; object-fit: cover;">
        @endif
        <div class="card-body">
            <h5 class="card-title">{{ $event->title }}</h5>
            <p class="card-text text-sm text-muted mb-2">
                <i class="fas fa-calendar-alt me-2"></i>
                {{ $event->start_date->format('M d, Y h:i A') }}
            </p>
            <p class="card-text text-sm text-muted mb-2">
                <i class="fas fa-map-marker-alt me-2"></i>
                {{ $event->venue }}
            </p>
            <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-primary font-weight-bold">
                    @if($event->registration_amount > 0)
                        ₹{{ number_format($event->registration_amount, 2) }}
                    @else
                        Free
                    @endif
                </span>
                <a href="{{ route('events.show', $event) }}" class="btn btn-primary">Register Now</a>
            </div>
        </div>
    </div>
</div> 