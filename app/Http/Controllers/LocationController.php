<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\City;
use App\Models\Area;
use App\Models\Zipcode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    /**
     * Get cities for a specific state.
     *
     * @param State $state
     * @return JsonResponse
     */
    public function getCities(State $state): JsonResponse
    {
        try {
            $cities = $state->cities()->orderBy('name')->get();
            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get areas for a specific city.
     *
     * @param City $city
     * @return JsonResponse
     */
    public function getAreas(City $city): JsonResponse
    {
        try {
            $areas = $city->areas()->orderBy('name')->get();
            return response()->json($areas);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all states for dropdown population.
     *
     * @return JsonResponse
     */
    public function getStates(): JsonResponse
    {
        try {
            $states = State::orderBy('name')->get(['id', 'name']);
            return response()->json([
                'success' => true,
                'states' => $states
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load states'
            ], 500);
        }
    }

    /**
     * Handle auto-detection of user location using coordinates.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function autoDetect(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'accuracy' => 'nullable|numeric',
                'address_info' => 'nullable|array'
            ]);

            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $accuracy = $request->input('accuracy');
            $addressInfo = $request->input('address_info', []);

            Log::info('Auto-detecting location', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'accuracy' => $accuracy,
                'address_info' => $addressInfo
            ]);

            // Step 1: Get detailed address information from multiple services
            $detailedAddress = $this->getDetailedAddressFromCoordinates($latitude, $longitude);
            
            if (!$detailedAddress) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not determine address from coordinates. Please select manually.'
                ], 400);
            }

            // Step 2: Try to find zipcode from our database using coordinates
            $zipcode = Zipcode::findByCoordinates($latitude, $longitude, 5);
            $detectedZipcode = $detailedAddress['zipcode'];

            if ($zipcode) {
                $detectedZipcode = $zipcode->code;
                Log::info('Found zipcode in database', ['zipcode' => $detectedZipcode]);
            } elseif ($detectedZipcode) {
                // Create zipcode entry if it doesn't exist
                $zipcode = $this->createZipcodeFromDetailedAddress($detectedZipcode, $latitude, $longitude, $detailedAddress);
                Log::info('Created new zipcode from detailed address', ['zipcode' => $detectedZipcode]);
            }

            // Step 3: Find best matching area
            $matchedLocation = $this->findBestLocationMatch($latitude, $longitude, $detectedZipcode, $detailedAddress);

            if (!$matchedLocation) {
                // Create a fallback location with the detected address
                $locationData = [
                    'type' => 'auto',
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'accuracy' => $accuracy,
                    'zipcode' => $detectedZipcode,
                    'area_id' => null,
                    'area_name' => $detailedAddress['area'] ?? $detailedAddress['suburb'] ?? 'Near ' . $detailedAddress['city'],
                    'city_id' => null,
                    'city_name' => $detailedAddress['city'],
                    'state_id' => null,
                    'state_name' => $detailedAddress['state'],
                    'country' => $detailedAddress['country'],
                    'full_address' => $detailedAddress['full_address'],
                    'navbar_display_name' => $detailedAddress['full_address'] ?? $this->formatDisplayName($detailedAddress),
                    'display_name' => $this->formatDisplayName($detailedAddress),
                    'coordinates' => "({$latitude}, {$longitude})",
                    'detected_at' => now()->toISOString(),
                    'precision' => $this->getLocationPrecision($accuracy),
                    'source' => 'reverse_geocoding'
                ];

                Session::put('user_location', $locationData);

                return response()->json([
                    'success' => true,
                    'message' => 'Location detected successfully',
                    'zipcode' => $detectedZipcode,
                    'display_name' => $locationData['display_name'],
                    'navbar_display_name' => $locationData['navbar_display_name'],
                    'coordinates' => $locationData['coordinates'],
                    'full_address' => $detailedAddress['full_address'],
                    'location_data' => $locationData
                ]);
            }

            // Step 4: Store in session with enhanced location data
            $locationData = [
                'type' => 'auto',
                'latitude' => $latitude,
                'longitude' => $longitude,
                'accuracy' => $accuracy,
                'zipcode' => $detectedZipcode,
                'area_id' => $matchedLocation['area']->id,
                'area_name' => $matchedLocation['area']->name,
                'city_id' => $matchedLocation['city']->id,
                'city_name' => $matchedLocation['city']->name,
                'state_id' => $matchedLocation['state']->id,
                'state_name' => $matchedLocation['state']->name,
                'country' => $detailedAddress['country'],
                'full_address' => $detailedAddress['full_address'],
                'navbar_display_name' => $detailedAddress['full_address'] ?? ($matchedLocation['area']->name . ', ' . $matchedLocation['city']->name),
                'display_name' => $matchedLocation['display_name'],
                'coordinates' => "({$latitude}, {$longitude})",
                'detected_at' => now()->toISOString(),
                'precision' => $this->getLocationPrecision($accuracy),
                'source' => 'database_match'
            ];

            Session::put('user_location', $locationData);

            Log::info('Location auto-detection successful', $locationData);

            return response()->json([
                'success' => true,
                'message' => 'Location detected successfully',
                'zipcode' => $detectedZipcode,
                'display_name' => $locationData['display_name'],
                'navbar_display_name' => $locationData['navbar_display_name'],
                'coordinates' => $locationData['coordinates'],
                'full_address' => $detailedAddress['full_address'],
                'location_data' => $locationData
            ]);

        } catch (\Exception $e) {
            Log::error('Auto-detection failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to detect location. Please try manual selection.'
            ], 500);
        }
    }

    /**
     * Set location manually.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setLocation(Request $request): JsonResponse
    {
        try {
            // Handle quick location setting for development/non-HTTPS environments
            if ($request->has('quick_location') && $request->boolean('quick_location')) {
                $request->validate([
                    'city_name' => 'required|string',
                    'zipcode' => 'required|string',
                    'type' => 'string'
                ]);

                $cityName = $request->input('city_name');
                $zipcode = $request->input('zipcode');

                // Find or create a generic area for this city
                $city = City::where('name', 'LIKE', "%{$cityName}%")->first();
                
                if (!$city) {
                    // For quick location, we'll create a mock location data without specific area
                    $operational_display_name = $cityName . ', India';
                    $locationData = [
                        'type' => $request->input('type', 'quick_select'),
                        'latitude' => null,
                        'longitude' => null,
                        'zipcode' => $zipcode,
                        'area_id' => null,
                        'area_name' => 'City Center',
                        'city_id' => null,
                        'city_name' => $cityName,
                        'state_id' => null,
                        'state_name' => 'India',
                        'display_name' => $operational_display_name,
                        'navbar_display_name' => $operational_display_name,
                        'full_address' => $operational_display_name,
                        'selected_at' => now()->toISOString(),
                        'is_quick_location' => true
                    ];
                } else {
                    // Use the first area in the found city
                    $area = $city->areas()->first();
                    $operational_display_name = $city->name . ', ' . ($city->state->name ?? 'India');
                    $locationData = [
                        'type' => $request->input('type', 'quick_select'),
                        'latitude' => null,
                        'longitude' => null,
                        'zipcode' => $zipcode,
                        'area_id' => $area->id ?? null,
                        'area_name' => $area->name ?? 'City Center',
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                        'state_id' => $city->state->id ?? null,
                        'state_name' => $city->state->name ?? 'India',
                        'display_name' => $operational_display_name,
                        'navbar_display_name' => $operational_display_name,
                        'full_address' => $operational_display_name,
                        'selected_at' => now()->toISOString(),
                        'is_quick_location' => true
                    ];
                }

                Session::put('user_location', $locationData);

                return response()->json([
                    'success' => true,
                    'message' => "Location set to {$cityName}",
                    'display_name' => $locationData['display_name'],
                    'navbar_display_name' => $locationData['navbar_display_name'],
                    'zipcode' => $zipcode,
                    'location_data' => $locationData
                ]);
            }

            // Regular manual location setting
            $request->validate([
                'state_id' => 'required|exists:states,id',
                'city_id' => 'required|exists:cities,id',
                'area_id' => 'required|exists:areas,id',
                'type' => 'string|in:manual,auto'
            ]);

            $area = Area::with('city.state')->find($request->area_id);
            
            if (!$area) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid area selected'
                ], 400);
            }

            // Get zipcode for this area if available
            $primaryZipcode = $area->primaryZipcode();
            $zipcodeCode = $primaryZipcode ? $primaryZipcode->code : null;
            
            $operational_display_name = $area->name . ', ' . $area->city->name;

            $locationData = [
                'type' => $request->input('type', 'manual'),
                'latitude' => null,
                'longitude' => null,
                'zipcode' => $zipcodeCode,
                'area_id' => $area->id,
                'area_name' => $area->name,
                'city_id' => $area->city->id,
                'city_name' => $area->city->name,
                'state_id' => $area->city->state->id,
                'state_name' => $area->city->state->name,
                'display_name' => $operational_display_name,
                'navbar_display_name' => $operational_display_name,
                'full_address' => $operational_display_name,
                'selected_at' => now()->toISOString()
            ];

            Session::put('user_location', $locationData);

            return response()->json([
                'success' => true,
                'message' => 'Location set successfully',
                'display_name' => $locationData['display_name'],
                'navbar_display_name' => $locationData['navbar_display_name'],
                'zipcode' => $zipcodeCode,
                'location_data' => $locationData
            ]);

        } catch (\Exception $e) {
            Log::error('Manual location setting failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to set location'
            ], 500);
        }
    }

    /**
     * Clear stored location.
     *
     * @return JsonResponse
     */
    public function clearLocation(): JsonResponse
    {
        Session::forget('user_location');
        
        return response()->json([
            'success' => true,
            'message' => 'Location cleared successfully'
        ]);
    }

    /**
     * Get current stored location.
     *
     * @return JsonResponse
     */
    public function getCurrentLocation(): JsonResponse
    {
        $userLocation = Session::get('user_location');
        
        return response()->json([
            'success' => true,
            'location' => $userLocation
        ]);
    }

    /**
     * Extract zipcode from coordinates using reverse geocoding services.
     *
     * @param float $latitude
     * @param float $longitude
     * @param array $addressInfo
     * @return string|null
     */
    private function getZipcodeFromCoordinates($latitude, $longitude, $addressInfo = [])
    {
        try {
            // First try to extract from provided address info
            if (isset($addressInfo['address']['postcode'])) {
                return $addressInfo['address']['postcode'];
            }

            // Try Nominatim API for detailed address
            $response = Http::timeout(10)->get('https://nominatim.openstreetmap.org/reverse', [
                'format' => 'json',
                'lat' => $latitude,
                'lon' => $longitude,
                'addressdetails' => 1,
                'accept-language' => 'en'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['address']['postcode'])) {
                    return $data['address']['postcode'];
                }
            }

            // Try alternative geocoding service
            $response = Http::timeout(10)->get('https://api.bigdatacloud.net/data/reverse-geocode-client', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'localityLanguage' => 'en'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['postcode'])) {
                    return $data['postcode'];
                }
            }

            return null;

        } catch (\Exception $e) {
            Log::warning('Zipcode extraction failed', [
                'error' => $e->getMessage(),
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);
            return null;
        }
    }

    /**
     * Create zipcode entry from geocoding data.
     *
     * @param string $zipcodeCode
     * @param float $latitude
     * @param float $longitude
     * @param array $addressInfo
     * @return Zipcode|null
     */
    private function createZipcodeFromGeocodingData($zipcodeCode, $latitude, $longitude, $addressInfo = [])
    {
        try {
            $cityName = null;
            $stateName = null;

            // Extract city and state from address info
            if (isset($addressInfo['address'])) {
                $addr = $addressInfo['address'];
                $cityName = $addr['city'] ?? $addr['town'] ?? $addr['municipality'] ?? null;
                $stateName = $addr['state'] ?? $addr['state_district'] ?? null;
            }

            return Zipcode::create([
                'code' => $zipcodeCode,
                'city_name' => $cityName,
                'state_name' => $stateName,
                'country_code' => 'IN',
                'latitude' => $latitude,
                'longitude' => $longitude,
                'is_active' => true
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create zipcode', [
                'error' => $e->getMessage(),
                'zipcode' => $zipcodeCode
            ]);
            return null;
        }
    }

    /**
     * Find best matching location (area/city/state) for given coordinates.
     *
     * @param float $latitude
     * @param float $longitude
     * @param string|null $zipcode
     * @param array $addressInfo
     * @return array|null
     */
    private function findBestLocationMatch($latitude, $longitude, $zipcode = null, $addressInfo = [])
    {
        try {
            // Step 1: Try to find area by zipcode if available
            if ($zipcode) {
                $zipcodeModel = Zipcode::where('code', $zipcode)->first();
                if ($zipcodeModel && $zipcodeModel->areas()->exists()) {
                    $area = $zipcodeModel->areas()->with('city.state')->first();
                    return [
                        'area' => $area,
                        'city' => $area->city,
                        'state' => $area->city->state,
                        'display_name' => $area->name . ', ' . $area->city->name
                    ];
                }
            }

            // Step 2: Try to match by city/state names from address info
            if (isset($addressInfo['address'])) {
                $addr = $addressInfo['address'];
                $cityName = $addr['city'] ?? $addr['town'] ?? $addr['municipality'] ?? null;
                $stateName = $addr['state'] ?? $addr['state_district'] ?? null;

                if ($cityName && $stateName) {
                    $city = City::whereHas('state', function($q) use ($stateName) {
                        $q->where('name', 'like', "%{$stateName}%");
                    })->where('name', 'like', "%{$cityName}%")->first();

                    if ($city) {
                        // Get a default area for this city
                        $area = $city->areas()->first();
                        if ($area) {
                            return [
                                'area' => $area,
                                'city' => $city,
                                'state' => $city->state,
                                'display_name' => $area->name . ', ' . $city->name
                            ];
                        }
                    }
                }
            }

            // Step 3: Find closest area using coordinates
            $earthRadius = 6371; // Earth's radius in kilometers
            
            $closestArea = Area::selectRaw("
                areas.*, cities.name as city_name, states.name as state_name,
                ({$earthRadius} * acos(
                    cos(radians(?)) * 
                    cos(radians(COALESCE((SELECT avg(latitude) FROM zipcodes JOIN area_zipcode ON zipcodes.id = area_zipcode.zipcode_id WHERE area_zipcode.area_id = areas.id), 0))) * 
                    cos(radians(COALESCE((SELECT avg(longitude) FROM zipcodes JOIN area_zipcode ON zipcodes.id = area_zipcode.zipcode_id WHERE area_zipcode.area_id = areas.id), 0)) - radians(?)) + 
                    sin(radians(?)) * 
                    sin(radians(COALESCE((SELECT avg(latitude) FROM zipcodes JOIN area_zipcode ON zipcodes.id = area_zipcode.zipcode_id WHERE area_zipcode.area_id = areas.id), 0)))
                )) AS distance
            ", [$latitude, $longitude, $latitude])
            ->join('cities', 'areas.city_id', '=', 'cities.id')
            ->join('states', 'cities.state_id', '=', 'states.id')
            ->orderBy('distance', 'asc')
            ->first();

            if ($closestArea) {
                $area = Area::with('city.state')->find($closestArea->id);
                return [
                    'area' => $area,
                    'city' => $area->city,
                    'state' => $area->city->state,
                    'display_name' => $area->name . ', ' . $area->city->name
                ];
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Location matching failed', [
                'error' => $e->getMessage(),
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);
            return null;
        }
    }

    /**
     * Get detailed address information from multiple services.
     *
     * @param float $latitude
     * @param float $longitude
     * @return array|null
     */
    private function getDetailedAddressFromCoordinates($latitude, $longitude)
    {
        try {
            // Service 1: Try Nominatim API (OpenStreetMap)
            $addressData = $this->tryNominatimGeocoding($latitude, $longitude);
            if ($addressData) {
                return $addressData;
            }

            // Service 2: Try BigDataCloud API
            $addressData = $this->tryBigDataCloudGeocoding($latitude, $longitude);
            if ($addressData) {
                return $addressData;
            }

            // Service 3: Try LocationIQ API (if you have API key)
            // $addressData = $this->tryLocationIQGeocoding($latitude, $longitude);
            // if ($addressData) {
            //     return $addressData;
            // }

            Log::warning('All geocoding services failed', [
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Geocoding completely failed', [
                'error' => $e->getMessage(),
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);
            return null;
        }
    }

    /**
     * Try Nominatim (OpenStreetMap) geocoding service.
     *
     * @param float $latitude
     * @param float $longitude
     * @return array|null
     */
    private function tryNominatimGeocoding($latitude, $longitude)
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'YouthCentral/1.0 (contact@youthcentral.com)'
                ])
                ->get('https://nominatim.openstreetmap.org/reverse', [
                    'format' => 'json',
                    'lat' => $latitude,
                    'lon' => $longitude,
                    'addressdetails' => 1,
                    'extratags' => 1,
                    'namedetails' => 1,
                    'accept-language' => 'en',
                    'zoom' => 18
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['address'])) {
                    $addr = $data['address'];
                    
                    return [
                        'zipcode' => $addr['postcode'] ?? null,
                        'area' => $addr['suburb'] ?? $addr['neighbourhood'] ?? $addr['quarter'] ?? null,
                        'city' => $addr['city'] ?? $addr['town'] ?? $addr['municipality'] ?? $addr['village'] ?? null,
                        'state' => $addr['state'] ?? $addr['state_district'] ?? null,
                        'country' => $addr['country'] ?? 'India',
                        'road' => $addr['road'] ?? null,
                        'house_number' => $addr['house_number'] ?? null,
                        'full_address' => $data['display_name'] ?? null,
                        'service' => 'nominatim'
                    ];
                }
            }

            return null;

        } catch (\Exception $e) {
            Log::warning('Nominatim geocoding failed', [
                'error' => $e->getMessage(),
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);
            return null;
        }
    }

    /**
     * Try BigDataCloud geocoding service.
     *
     * @param float $latitude
     * @param float $longitude
     * @return array|null
     */
    private function tryBigDataCloudGeocoding($latitude, $longitude)
    {
        try {
            $response = Http::timeout(15)->get('https://api.bigdatacloud.net/data/reverse-geocode-client', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'localityLanguage' => 'en'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'zipcode' => $data['postcode'] ?? null,
                    'area' => $data['locality'] ?? $data['localityInfo']['administrative'][3]['name'] ?? null,
                    'city' => $data['city'] ?? $data['principalSubdivision'] ?? null,
                    'state' => $data['principalSubdivision'] ?? null,
                    'country' => $data['countryName'] ?? 'India',
                    'road' => null,
                    'house_number' => null,
                    'full_address' => $data['locality'] . ', ' . ($data['city'] ?? '') . ', ' . ($data['principalSubdivision'] ?? '') . ', ' . ($data['countryName'] ?? 'India'),
                    'service' => 'bigdatacloud'
                ];
            }

            return null;

        } catch (\Exception $e) {
            Log::warning('BigDataCloud geocoding failed', [
                'error' => $e->getMessage(),
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);
            return null;
        }
    }

    /**
     * Create zipcode entry from detailed address information.
     *
     * @param string $zipcodeCode
     * @param float $latitude
     * @param float $longitude
     * @param array $addressInfo
     * @return Zipcode|null
     */
    private function createZipcodeFromDetailedAddress($zipcodeCode, $latitude, $longitude, $addressInfo)
    {
        try {
            $cityName = null;
            $stateName = null;

            // Extract city and state from address info
            if (isset($addressInfo['city'])) {
                $cityName = $addressInfo['city'];
            }
            if (isset($addressInfo['state'])) {
                $stateName = $addressInfo['state'];
            }

            return Zipcode::create([
                'code' => $zipcodeCode,
                'city_name' => $cityName,
                'state_name' => $stateName,
                'country_code' => 'IN',
                'latitude' => $latitude,
                'longitude' => $longitude,
                'is_active' => true
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create zipcode', [
                'error' => $e->getMessage(),
                'zipcode' => $zipcodeCode
            ]);
            return null;
        }
    }

    /**
     * Format display name for location.
     *
     * @param array $addressInfo
     * @return string
     */
    private function formatDisplayName($addressInfo)
    {
        $parts = [];
        if (isset($addressInfo['city'])) {
            $parts[] = $addressInfo['city'];
        }
        if (isset($addressInfo['state'])) {
            $parts[] = $addressInfo['state'];
        }
        if (isset($addressInfo['country'])) {
            $parts[] = $addressInfo['country'];
        }
        return implode(', ', array_filter($parts));
    }

    /**
     * Get location precision based on accuracy.
     *
     * @param float|null $accuracy
     * @return string|null
     */
    private function getLocationPrecision($accuracy)
    {
        if ($accuracy) {
            if ($accuracy < 100) {
                return 'High';
            } elseif ($accuracy < 500) {
                return 'Medium';
            } else {
                return 'Low';
            }
        }
        return null;
    }
} 