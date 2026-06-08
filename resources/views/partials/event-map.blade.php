@php
    // default coordinates (Mumbai) if not provided
    $lat = old('latitude', isset($defaultLat) ? $defaultLat : (isset($event) ? $event->latitude : 19.0760));
    $lng = old('longitude', isset($defaultLng) ? $defaultLng : (isset($event) ? $event->longitude : 72.8777));
@endphp

<div class="card mt-3 mb-3" style="border:1px solid #dee2e6;">
    <div class="card-header bg-gradient-primary text-white" style="padding:.75rem 1rem;">
        <h6 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Event Location (GPS Coordinates)</h6>
        <small class="opacity-8">Drop a pin on the map or enter coordinates manually for distance-based event sorting</small>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-control-label" for="latitude">Latitude</label>
                <input type="text" id="latitude" name="latitude" class="form-control form-control-sm" value="{{ $lat }}" placeholder="e.g. 19.0760">
            </div>
            <div class="col-md-6">
                <label class="form-control-label" for="longitude">Longitude</label>
                <input type="text" id="longitude" name="longitude" class="form-control form-control-sm" value="{{ $lng }}" placeholder="e.g. 72.8777">
            </div>
        </div>

        <label class="form-control-label">Interactive Map</label>
        <p class="text-sm text-muted mb-2">Click on the map to set the event location or drag the marker to adjust the position</p>
        <div class="map-container-wrapper mb-3">
            <div id="event-map" style="height: 400px; width: 100%;"></div>
        </div>

        <div class="d-flex gap-2 mb-2">
            <button type="button" id="use-location" class="btn btn-sm bg-gradient-info w-100"><i class="fas fa-location-arrow me-1"></i>Use My Location</button>
            <button type="button" id="clear-location" class="btn btn-sm bg-gradient-secondary w-100"><i class="fas fa-times me-1"></i>Clear Coordinates</button>
        </div>
        <p class="text-xs text-muted">GPS coordinates allow for accurate distance-based sorting when users search for nearby events.</p>
    </div>
</div>

@once
    @push('styles')
        <style>
            .map-container-wrapper{border:1px solid #dee2e6;border-radius:.5rem;overflow:hidden}
            #event-map{position:relative}
        </style>
    @endpush

    @push('scripts')
        <script>
            function initEventMap() {
                console.log("Attempting to initialize Google Map... Callback has been fired.");

                const mapContainer = document.getElementById('event-map');
                if (!mapContainer) {
                    console.error("CRITICAL: Map container #event-map not found in the DOM.");
                    return;
                }
                console.log("Map container found:", mapContainer);
                console.log(`Map container dimensions: ${mapContainer.clientWidth}px width, ${mapContainer.clientHeight}px height.`);
                if (mapContainer.clientHeight === 0) {
                    console.warn("WARNING: Map container height is 0. The map will not be visible. Check for CSS conflicts.");
                }

                const latInp=document.getElementById('latitude');
                const lngInp=document.getElementById('longitude');
                const useBtn=document.getElementById('use-location');
                const clrBtn=document.getElementById('clear-location');
                const initLat=parseFloat(latInp.value)||19.0760;
                const initLng=parseFloat(lngInp.value)||72.8777;

                console.log(`Initial coordinates: Lat=${initLat}, Lng=${initLng}`);
                const apiKey = "{{ config('services.google_maps.key') }}";
                console.log(`Using API Key starting with: ${apiKey.substring(0, 8)}...`);
                
                try {
                    const map=new google.maps.Map(mapContainer,{
                        zoom:10,
                        center:{lat:initLat,lng:initLng},
                        mapId: 'YOUTH_CENTRAL_MAP'
                    });
                    console.log("Google Maps object created successfully.");

                    let marker = null;
                    function placeMarker(lat, lng) {
                        const position = { lat, lng };
                         console.log("Placing marker at:", position);
                        if (marker) {
                            marker.position = position;
                        } else {
                            marker = new google.maps.marker.AdvancedMarkerElement({
                                map: map,
                                position: position,
                                gmpDraggable: true
                            });
                            console.log("New AdvancedMarkerElement created.");
                            marker.addListener('dragend', () => {
                                if(marker){
                                    latInp.value = marker.position.lat.toFixed(8);
                                    lngInp.value = marker.position.lng.toFixed(8);
                                    console.log("Marker dragged to new position:", marker.position);
                                }
                            });
                        }
                        latInp.value = lat.toFixed(8);
                        lngInp.value = lng.toFixed(8);
                        map.setCenter(position);
                    }
                    
                    placeMarker(initLat, initLng);

                    map.addListener('click', e => placeMarker(e.latLng.lat(), e.latLng.lng()));

                    useBtn.addEventListener('click', () => {
                        if(!navigator.geolocation){alert('Geolocation is not supported by your browser.');return;}
                        useBtn.disabled = true; useBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Detecting...';
                        navigator.geolocation.getCurrentPosition(pos=>{
                            placeMarker(pos.coords.latitude, pos.coords.longitude);
                            useBtn.disabled=false;useBtn.innerHTML='<i class="fas fa-location-arrow me-1"></i>Use My Location';
                        },err=>{
                            alert(`Error: ${err.message}`);
                            useBtn.disabled=false;useBtn.innerHTML='<i class="fas fa-location-arrow me-1"></i>Use My Location';
                        });
                    });

                    clrBtn.addEventListener('click', () => {
                        if(marker){ marker.map = null; marker = null; }
                        latInp.value = ''; lngInp.value = '';
                        map.setCenter({lat: initLat, lng: initLng});
                    });
                } catch (e) {
                    console.error("ERROR during Google Map initialization:", e);
                }
            }
        </script>
        <script async src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=marker&loading=async&callback=initEventMap"></script>
    @endpush
@endonce 