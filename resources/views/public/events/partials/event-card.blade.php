<div class="event-card h-100" >
    <div class="event-image">
        @php
            // Logic to find the banner image (Featured banner -> Primary banner -> First banner -> Default)
              $bannerPath = $event->banners->where('is_primary', true)->first()?->image_path 
                      ?? $event->banners->first()?->image_path 
                      ?? null;
              $useStorage = !empty($bannerPath);
            
            // Fallback to default
            if (!$bannerPath) {
              $bannerPath = 'assets_public/images/defaults/event-placeholder.jpg';
              $useStorage = false;
            }
            
            $bannerUrl = $useStorage ? asset('storage/' . $bannerPath) : asset($bannerPath);
        @endphp
        <img src="{{ $bannerUrl }}" alt="{{ $event->title }}" class="img-fluid">
        <div class="event-date">
            <span class="day">{{ $event->start_date->format('d') }}</span>
            <span class="month">{{ $event->start_date->format('M') }}</span>
        </div>
    </div>
    <div class="event-content">
        <h3 style="font-size:20px;">{{ $event->title }}</h3>
        <div class="event-meta">
            <span style="font-size:18px;"><i class="fa-solid fa-location-dot"></i> {{ Str::limit($event->venue, 30) }}</span>
            <span style="font-size:18px;"><i class="fa-regular fa-clock"></i> {{ $event->start_date->format('h:i A') }}</span>
        </div>
        <p class="event-description" style="font-size:18px;">{{ Str::limit(strip_tags($event->description), 100) }}</p>
        <div class="event-footer">
            <span class="price" style="font-size:18px;">
                @if($event->registration_amount > 0)
                    ₹{{ number_format($event->registration_amount, 0) }}
                @else
                    Free
                @endif
            </span>
            <a href="{{ route('events.show', $event) }}" class="btn btn-primary" style="font-size:15px;">View Details</a>
        </div>
    </div>
</div>

{{-- Replace existing CSS with reference CSS --}}
<style>
.event-card { 
  background: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 15px rgba(0,0,0,0.1);
  margin-bottom: 20px; /* Add margin for grid spacing */
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  display: flex;
  flex-direction: column;
  height: 100%; /* Ensure full height */
}
.event-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
.event-image {
  position: relative;
  height: 200px; /* Fixed height for consistency */
  overflow: hidden;
  flex-shrink: 0; /* Prevent shrinking */
}
.event-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.event-date { /* Styles from reference */
  position: absolute;
  top: 15px; /* Adjusted position slightly */
  right: 15px;
  background: rgba(255,255,255,0.95);
  padding: 10px 12px; /* Adjusted padding */
  border-radius: 8px;
  text-align: center;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  line-height: 1.1;
}
.event-date .day { /* Styles from reference */
  font-size: 22px; /* Adjusted size */
  font-weight: bold;
  display: block;
  color: #333;
  line-height: 1;
}
.event-date .month { /* Styles from reference */
  font-size: 14px; /* Adjusted size */
  color: #666;
  text-transform: uppercase;
  margin-top: 4px; /* Adjusted spacing */
  display: block;
}
.event-content { /* Styles from reference */
  padding: 20px;
  display: flex;
  flex-direction: column;
  flex-grow: 1; /* Allow content to grow */
  justify-content: space-between; /* Distribute content evenly */
}
.event-content h3 { /* Styles from reference */
  margin: 0 0 10px;
  font-size: 1.25rem; /* Adjusted size */
  color: #333;
  font-weight: 600;
  /* Optional: Clamp title if needed */
   display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;  
  overflow: hidden;
  text-overflow: ellipsis;
  min-height: 2.5em; /* Ensure space for 2 lines */
  flex-shrink: 0; /* Prevent shrinking */
}
.event-meta { /* Styles from reference */
  margin-bottom: 10px;
  color: #666;
  font-size: 0.85rem;
  flex-shrink: 0; /* Prevent shrinking */
}
.event-meta span { /* Styles from reference */
  margin-right: 15px;
}
.event-meta i { /* Styles from reference */
  margin-right: 5px;
  color: var(--primary-color); /* Color from reference */
}
.event-description { /* Styles from reference */
  color: #666;
  margin-bottom: 15px;
  line-height: 1.5;
  font-size: 0.9rem;
  /* Optional: Clamp description */
   display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;  
  overflow: hidden;
  text-overflow: ellipsis;
  min-height: 4.05em; /* Ensure space for 3 lines */
  flex-grow: 1; /* Allow description to grow */
}
.event-footer { /* Styles from reference */
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 15px;
  border-top: 1px solid #eee;
  flex-shrink: 0; /* Prevent shrinking */
  margin-top: auto; /* Push to bottom */
}
.price { /* Styles from reference */
  font-size: 1.4rem; /* Adjusted size */
  font-weight: bold;
  color: var(--primary-color); /* Color from reference */
}
/* Use reference button styles */
.event-footer .btn-primary {
  background-color: var(--primary-color);
  border-color: var(--primary-color);
  padding: 6px 16px; /* Adjusted padding */
  font-weight: 600;
  font-size: 0.9rem;
  transition: all 0.3s ease;
}
.event-footer .btn-primary:hover {
  background-color: #0a0f1f; /* Darker shade for hover */
  border-color: #0a0f1f;
  transform: translateY(-2px);
}
</style> 