<!-- How it Works Section -->
<section class="section-padding how-it-works">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">How It Works</h2>
                <p class="section-subtitle">Discover and connect with local businesses in a few simple steps</p>
            </div>
            <!-- ... existing how it works content ... -->
        </div>
    </div>
</section>

<!-- Upcoming Events Section -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Upcoming Events</h2>
                <p class="section-subtitle">Join exciting events and activities in your area</p>
            </div>
        </div>
        <div class="row">
            @forelse($events as $event)
                @include('front.partials.event-card', ['event' => $event])
            @empty
                <div class="col-12 text-center">
                    <p>No upcoming events at the moment.</p>
                </div>
            @endforelse
        </div>
        @if($events->isNotEmpty())
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="{{ route('events.index') }}" class="btn btn-outline-primary">View All Events</a>
                </div>
            </div>
        @endif
    </div>
</section> 