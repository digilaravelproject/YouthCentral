<?php

namespace App\Imports;

use App\Models\Business;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\State;
use App\Models\City;
use App\Models\Area;
use App\Models\Zipcode;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class BusinessesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private $errors = [];
    private $successCount = 0;
    private $skipCount = 0;

    /**
     * Prepare the data for validation.
     *
     * @param  array  $data
     * @param  int  $index
     * @return array
     */
    public function prepareForValidation($data, $index)
    {
        // Clean and prepare all fields, making them nullable except business_name
        $cleanedData = [];
        
        // Business name is required - trim and validate
        $cleanedData['business_name'] = isset($data['business_name']) ? trim((string) $data['business_name']) : '';
        
        // All other fields are nullable - clean and set to null if empty
        $cleanedData['category'] = $this->cleanField($data, 'category');
        $cleanedData['subcategory'] = $this->cleanField($data, 'subcategory');
        $cleanedData['state'] = $this->cleanField($data, 'state');
        $cleanedData['city'] = $this->cleanField($data, 'city');
        $cleanedData['area'] = $this->cleanField($data, 'area');
        
        // Contact fields
        $cleanedData['phone'] = $this->cleanField($data, 'phone');
        $cleanedData['whatsapp_number'] = $this->cleanField($data, 'whatsapp_number');
        $cleanedData['email'] = $this->cleanField($data, 'email');
        $cleanedData['website'] = $this->cleanUrlField($data, 'website');
        
        // Address fields
        $cleanedData['street_address'] = $this->cleanField($data, 'street_address');
        $cleanedData['zipcode'] = $this->cleanField($data, 'zipcode');
        
        // Location coordinates
        $cleanedData['latitude'] = $this->cleanNumericField($data, 'latitude');
        $cleanedData['longitude'] = $this->cleanNumericField($data, 'longitude');
        
        // Social media links
        $cleanedData['facebook_link'] = $this->cleanUrlField($data, 'facebook_link');
        $cleanedData['instagram_link'] = $this->cleanUrlField($data, 'instagram_link');
        $cleanedData['twitter_link'] = $this->cleanUrlField($data, 'twitter_link');
        $cleanedData['pinterest_link'] = $this->cleanUrlField($data, 'pinterest_link');
        
        // Description
        $cleanedData['description'] = $this->cleanField($data, 'description');

        return $cleanedData;
    }

    /**
     * Clean a text field - trim and convert empty strings to null
     */
    private function cleanField($data, $fieldName)
    {
        if (!isset($data[$fieldName])) {
            return null;
        }
        
        $value = trim((string) $data[$fieldName]);
        return $value === '' ? null : $value;
    }

    /**
     * Clean a URL field - trim, normalize, and convert empty strings to null
     */
    private function cleanUrlField($data, $fieldName)
    {
        if (!isset($data[$fieldName])) {
            return null;
        }
        
        $value = trim((string) $data[$fieldName]);
        if ($value === '') {
            return null;
        }
        
        // Normalize URL - add https:// if no protocol is present
        if (!preg_match('/^https?:\/\//', $value)) {
            $value = 'https://' . $value;
        }
        
        return $value;
    }

    /**
     * Clean a numeric field - validate and convert to proper format
     */
    private function cleanNumericField($data, $fieldName)
    {
        if (!isset($data[$fieldName])) {
            return null;
        }
        
        $value = trim((string) $data[$fieldName]);
        if ($value === '' || !is_numeric($value)) {
            return null;
        }
        
        return (float) $value;
    }

    public function model(array $row)
    {
        try {
            // Skip if business name is empty
            if (empty($row['business_name'])) {
                $this->skipCount++;
                Log::warning('Skipping import row - business name is empty', ['row' => $row]);
                return null;
            }

            // Create default category and subcategory if not provided
            $category = $this->getOrCreateCategory($row['category'] ?? 'General');
            $subcategory = $this->getOrCreateSubcategory($row['subcategory'] ?? 'General', $category->id);
            
            // Create default location if not provided
            $state = $this->getOrCreateState($row['state'] ?? 'Unknown');
            $city = $this->getOrCreateCity($row['city'] ?? 'Unknown', $state->id);
            $area = $this->getOrCreateArea($row['area'] ?? 'Unknown', $city->id);

            // Handle zipcode if provided
            $businessZipcode = $row['zipcode'];
            if ($businessZipcode) {
                $this->createZipcodeIfNeeded($businessZipcode, $city, $state, $area);
            }

            // Prepare business data with all nullable fields
            $businessData = [
                'subcategory_id' => $subcategory->id,
                'phone' => $row['phone'],
                'whatsapp_number' => $row['whatsapp_number'],
                'website' => $row['website'],
                'email' => $row['email'],
                'facebook_link' => $row['facebook_link'],
                'instagram_link' => $row['instagram_link'],
                'twitter_link' => $row['twitter_link'],
                'pinterest_link' => $row['pinterest_link'],
                'street_address' => $row['street_address'],
                'description' => $row['description'],
                'latitude' => $row['latitude'],
                'longitude' => $row['longitude'],
                'zipcode' => $businessZipcode,
                'status' => 'active',
                'slug' => Str::slug($row['business_name'])
            ];

            // Create or update business
            $business = Business::updateOrCreate(
                [
                    'business_name' => $row['business_name'],
                    'area_id' => $area->id
                ],
                $businessData
            );

            $this->successCount++;
            Log::info('Business imported successfully', ['business_name' => $row['business_name']]);
            
            return $business;

        } catch (\Exception $e) {
            $this->skipCount++;
            $errorMessage = "Error importing business '{$row['business_name']}': " . $e->getMessage();
            $this->errors[] = $errorMessage;
            Log::error($errorMessage, ['row' => $row, 'exception' => $e]);
            return null;
        }
    }

    /**
     * Get or create category with error handling
     */
    private function getOrCreateCategory($categoryName)
    {
        try {
            return Category::firstOrCreate(
            ['name' => $categoryName],
            ['slug' => Str::slug($categoryName)]
        );
        } catch (\Exception $e) {
            Log::error("Error creating category: {$categoryName}", ['exception' => $e]);
            // Return default category
            return Category::firstOrCreate(
                ['name' => 'General'],
                ['slug' => 'general']
            );
        }
    }

    /**
     * Get or create subcategory with error handling
     */
    private function getOrCreateSubcategory($subcategoryName, $categoryId)
    {
        try {
            return Subcategory::firstOrCreate(
                ['name' => $subcategoryName, 'category_id' => $categoryId],
            ['slug' => Str::slug($subcategoryName)]
        );
        } catch (\Exception $e) {
            Log::error("Error creating subcategory: {$subcategoryName}", ['exception' => $e]);
            // Return default subcategory
            return Subcategory::firstOrCreate(
                ['name' => 'General', 'category_id' => $categoryId],
                ['slug' => 'general']
            );
        }
    }

    /**
     * Get or create state with error handling
     */
    private function getOrCreateState($stateName)
    {
        try {
            return State::firstOrCreate(
            ['name' => $stateName],
                ['slug' => Str::slug($stateName)]
            );
        } catch (\Exception $e) {
            Log::error("Error creating state: {$stateName}", ['exception' => $e]);
            // Return default state
            return State::firstOrCreate(
                ['name' => 'Unknown'],
                ['slug' => 'unknown']
            );
        }
    }

    /**
     * Get or create city with error handling
     */
    private function getOrCreateCity($cityName, $stateId)
    {
        try {
            return City::firstOrCreate(
                ['name' => $cityName, 'state_id' => $stateId],
                ['slug' => Str::slug($cityName)]
            );
        } catch (\Exception $e) {
            Log::error("Error creating city: {$cityName}", ['exception' => $e]);
            // Return default city
            return City::firstOrCreate(
                ['name' => 'Unknown', 'state_id' => $stateId],
                ['slug' => 'unknown']
            );
        }
    }

    /**
     * Get or create area with error handling
     */
    private function getOrCreateArea($areaName, $cityId)
    {
        try {
            return Area::firstOrCreate(
                ['name' => $areaName, 'city_id' => $cityId],
                ['slug' => Str::slug($areaName)]
            );
        } catch (\Exception $e) {
            Log::error("Error creating area: {$areaName}", ['exception' => $e]);
            // Return default area
            return Area::firstOrCreate(
                ['name' => 'Unknown', 'city_id' => $cityId],
                ['slug' => 'unknown']
            );
        }
    }

    /**
     * Create zipcode if needed with error handling
     */
    private function createZipcodeIfNeeded($zipcodeValue, $city, $state, $area)
    {
        try {
            $zipcodeModel = Zipcode::firstOrCreate(
                ['code' => $zipcodeValue],
                [
                    'city_name' => $city->name,
                    'state_name' => $state->name,
                    'country_code' => 'IN',
                    'is_active' => true,
                ]
            );

            // Link area to zipcode
            if ($area && $zipcodeModel) {
                $area->zipcodes()->syncWithoutDetaching([$zipcodeModel->id]);
            }
        } catch (\Exception $e) {
            Log::error("Error creating zipcode: {$zipcodeValue}", ['exception' => $e]);
        }
    }

    /**
     * Validation rules - only business_name is required
     */
    public function rules(): array
    {
        return [
            // Only business name is required
            'business_name' => 'required|string|max:255',
            
            // All other fields are nullable
            'category' => 'nullable|string|max:255',
            'subcategory' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'website' => ['nullable', 'string', 'max:255', function ($attribute, $value, $fail) {
                if ($value && !$this->isValidUrl($value)) {
                    $fail('Please provide a valid website URL.');
                }
            }],
            'email' => 'nullable|email|max:255',
            'facebook_link' => ['nullable', 'string', 'max:255', function ($attribute, $value, $fail) {
                if ($value && !$this->isValidUrl($value)) {
                    $fail('Please provide a valid Facebook URL.');
                }
            }],
            'instagram_link' => ['nullable', 'string', 'max:255', function ($attribute, $value, $fail) {
                if ($value && !$this->isValidUrl($value)) {
                    $fail('Please provide a valid Instagram URL.');
                }
            }],
            'twitter_link' => ['nullable', 'string', 'max:255', function ($attribute, $value, $fail) {
                if ($value && !$this->isValidUrl($value)) {
                    $fail('Please provide a valid Twitter URL.');
                }
            }],
            'pinterest_link' => ['nullable', 'string', 'max:255', function ($attribute, $value, $fail) {
                if ($value && !$this->isValidUrl($value)) {
                    $fail('Please provide a valid Pinterest URL.');
                }
            }],
            'street_address' => 'nullable|string|max:1255',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'zipcode' => 'nullable|string|max:20'
        ];
    }

    /**
     * Custom URL validation that's more flexible
     */
    private function isValidUrl($url)
    {
        // If URL is empty or null, it's valid (nullable field)
        if (empty($url)) {
            return true;
        }

        // Check if it's a valid URL after normalization
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        }

        // If it doesn't have a protocol, try adding https:// and validate again
        $normalizedUrl = 'https://' . ltrim($url, '/');
        if (filter_var($normalizedUrl, FILTER_VALIDATE_URL)) {
            return true;
        }

        // Additional check for common domain patterns
        $pattern = '/^(https?:\/\/)?(www\.)?[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?(\.[a-zA-Z]{2,})+([\/\w\-._~:?#[\]@!$&\'()*+,;=]*)?$/';
        return preg_match($pattern, $url);
    }

    /**
     * Custom error messages
     */
    public function customValidationMessages()
    {
        return [
            'business_name.required' => 'Business name is required and cannot be empty.',
            'email.email' => 'Please provide a valid email address.',
            'website.url' => 'Please provide a valid website URL.',
            'facebook_link.url' => 'Please provide a valid Facebook URL.',
            'instagram_link.url' => 'Please provide a valid Instagram URL.',
            'twitter_link.url' => 'Please provide a valid Twitter URL.',
            'pinterest_link.url' => 'Please provide a valid Pinterest URL.',
            'latitude.between' => 'Latitude must be between -90 and 90.',
            'longitude.between' => 'Longitude must be between -180 and 180.',
        ];
    }

    /**
     * Handle validation failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $errorMessage = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            $this->errors[] = $errorMessage;
            Log::warning('Import validation failure', [
                'row' => $failure->row(),
                'errors' => $failure->errors(),
                'values' => $failure->values()
            ]);
        }
    }

    /**
     * Get import statistics
     */
    public function getImportStats()
    {
        return [
            'success_count' => $this->successCount,
            'skip_count' => $this->skipCount,
            'error_count' => count($this->errors),
            'errors' => $this->errors
        ];
    }
}