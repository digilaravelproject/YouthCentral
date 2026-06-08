
@forelse($businesses as $index => $business)
  <!-- Start Listing Item Col -->
  <div class="col-sm-3">
    <div class="listing-item"> 
      <a href="#" class="category-icon">
        <i class="{{ $business->subcategory->getFormattedIconClass() ?? 'fas fa-bookmark' }}"></i>
      </a>
      <div class="listing-item-rating">{{ number_format($business->average_rating ?? 0, 1) }}</div>
      <a href="{{ route('public.business.show', $business) }}" class="listing-item-link">
        <div class="listing-item-title-centralizer">
          <div class="listing-item-title">
            {{ $business->business_name }}
            @if($business->description)
              <div class="ribbon description-ribbon">{{ Str::limit($business->description, 25) }}</div>
            @endif
            @if($business->has_active_subscription)
              <!-- <div class="ribbon premium-ribbon"><i class="fas fa-star"></i></div> -->
               <div class="premium-badge">
                    <i class="fas fa-crown"></i>
                </div>
            @endif
          </div>
        </div>
        <div class="image-wrapper">
          @if($business->images && $business->images->isNotEmpty())
            @php
              $primaryImage = $business->images->where('is_primary', true)->first();
              $fallbackImage = $business->images->first();
              $imagePath = '';
              
              if ($primaryImage && isset($primaryImage->path)) {
                  $imagePath = $primaryImage->path;
              } elseif ($fallbackImage && isset($fallbackImage->path)) {
                  $imagePath = $fallbackImage->path;
              }
            @endphp
            
            @if(!empty($imagePath))
              @if(filter_var($imagePath, FILTER_VALIDATE_URL))
                <img alt="{{ $business->business_name }}" src="{{ $imagePath }}" />
              @else
                <img alt="{{ $business->business_name }}" src="{{ asset('storage/' . $imagePath) }}" />
              @endif
            @else
              <img alt="{{ $business->business_name }}" src="{{ asset('assets_public/images/listings/' . (($index ?? 0) % 8 + 1) . '.jpg') }}" />
            @endif
          @else
            <img alt="{{ $business->business_name }}" src="{{ asset('assets_public/images/listings/' . (($index ?? 0) % 8 + 1) . '.jpg') }}" />
          @endif
        </div>
      </a>
      <div class="listing-item-data">
        <a class="listing-item-address" href="#"> <!-- Consider making this link to map or business detail -->
          {{ $business->street_address ?? ($business->area->name ?? 'N/A') }}, {{ $business->area->city->name ?? 'N/A' }}
        </a>
        <div class="listing-item-excerpt">
          {{ Str::limit($business->description, 50) ?? 'Visit our business for the best experience' }}
        </div>
      </div>
      <div class="listing-category-name">
        <a href="{{ route('listings', $business->subcategory) }}">{{ $business->subcategory->name }}</a>
      </div>
    </div>
  </div>
  <!-- End Listing Item Col -->
@empty
  @if(isset($isAjaxRequest) && $isAjaxRequest)
    <!-- No more items to load, do not show the full empty message on AJAX -->
  @else
    <div class="col-12 text-center p-5">
      <h3>No listings found</h3>
      <p>There are no businesses in this category yet matching your criteria.</p>
    </div>
  @endif
@endforelse
