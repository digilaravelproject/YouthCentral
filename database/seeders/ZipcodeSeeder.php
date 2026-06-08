<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Zipcode;
use App\Models\Area;

class ZipcodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample Indian zipcodes with major cities
        $zipcodes = [
            // Mumbai
            ['code' => '400001', 'city_name' => 'Mumbai', 'state_name' => 'Maharashtra', 'latitude' => 18.9322, 'longitude' => 72.8264],
            ['code' => '400002', 'city_name' => 'Mumbai', 'state_name' => 'Maharashtra', 'latitude' => 18.9388, 'longitude' => 72.8354],
            ['code' => '400020', 'city_name' => 'Mumbai', 'state_name' => 'Maharashtra', 'latitude' => 19.0176, 'longitude' => 72.8562],
            ['code' => '400050', 'city_name' => 'Mumbai', 'state_name' => 'Maharashtra', 'latitude' => 19.0596, 'longitude' => 72.8295],
            
            // Delhi
            ['code' => '110001', 'city_name' => 'New Delhi', 'state_name' => 'Delhi', 'latitude' => 28.6139, 'longitude' => 77.2090],
            ['code' => '110002', 'city_name' => 'New Delhi', 'state_name' => 'Delhi', 'latitude' => 28.6129, 'longitude' => 77.2295],
            ['code' => '110016', 'city_name' => 'New Delhi', 'state_name' => 'Delhi', 'latitude' => 28.5355, 'longitude' => 77.2709],
            ['code' => '110017', 'city_name' => 'New Delhi', 'state_name' => 'Delhi', 'latitude' => 28.5706, 'longitude' => 77.2147],
            
            // Bangalore
            ['code' => '560001', 'city_name' => 'Bangalore', 'state_name' => 'Karnataka', 'latitude' => 12.9716, 'longitude' => 77.5946],
            ['code' => '560025', 'city_name' => 'Bangalore', 'state_name' => 'Karnataka', 'latitude' => 12.9698, 'longitude' => 77.7500],
            ['code' => '560066', 'city_name' => 'Bangalore', 'state_name' => 'Karnataka', 'latitude' => 13.1206, 'longitude' => 77.6475],
            ['code' => '560100', 'city_name' => 'Bangalore', 'state_name' => 'Karnataka', 'latitude' => 12.9279, 'longitude' => 77.6271],
            
            // Chennai
            ['code' => '600001', 'city_name' => 'Chennai', 'state_name' => 'Tamil Nadu', 'latitude' => 13.0827, 'longitude' => 80.2707],
            ['code' => '600018', 'city_name' => 'Chennai', 'state_name' => 'Tamil Nadu', 'latitude' => 13.1067, 'longitude' => 80.2206],
            ['code' => '600028', 'city_name' => 'Chennai', 'state_name' => 'Tamil Nadu', 'latitude' => 13.0569, 'longitude' => 80.2084],
            ['code' => '600041', 'city_name' => 'Chennai', 'state_name' => 'Tamil Nadu', 'latitude' => 13.0475, 'longitude' => 80.1564],
            
            // Pune
            ['code' => '411001', 'city_name' => 'Pune', 'state_name' => 'Maharashtra', 'latitude' => 18.5204, 'longitude' => 73.8567],
            ['code' => '411006', 'city_name' => 'Pune', 'state_name' => 'Maharashtra', 'latitude' => 18.5679, 'longitude' => 73.9143],
            ['code' => '411038', 'city_name' => 'Pune', 'state_name' => 'Maharashtra', 'latitude' => 18.4088, 'longitude' => 73.9524],
            ['code' => '411057', 'city_name' => 'Pune', 'state_name' => 'Maharashtra', 'latitude' => 18.4633, 'longitude' => 73.8737],
            
            // Hyderabad
            ['code' => '500001', 'city_name' => 'Hyderabad', 'state_name' => 'Telangana', 'latitude' => 17.3850, 'longitude' => 78.4867],
            ['code' => '500016', 'city_name' => 'Hyderabad', 'state_name' => 'Telangana', 'latitude' => 17.4065, 'longitude' => 78.4772],
            ['code' => '500034', 'city_name' => 'Hyderabad', 'state_name' => 'Telangana', 'latitude' => 17.4399, 'longitude' => 78.3489],
            ['code' => '500081', 'city_name' => 'Hyderabad', 'state_name' => 'Telangana', 'latitude' => 17.4126, 'longitude' => 78.3097],
            
            // Kolkata
            ['code' => '700001', 'city_name' => 'Kolkata', 'state_name' => 'West Bengal', 'latitude' => 22.5726, 'longitude' => 88.3639],
            ['code' => '700016', 'city_name' => 'Kolkata', 'state_name' => 'West Bengal', 'latitude' => 22.5448, 'longitude' => 88.3426],
            ['code' => '700027', 'city_name' => 'Kolkata', 'state_name' => 'West Bengal', 'latitude' => 22.5355, 'longitude' => 88.3209],
            ['code' => '700091', 'city_name' => 'Kolkata', 'state_name' => 'West Bengal', 'latitude' => 22.4697, 'longitude' => 88.4142],
        ];

        foreach ($zipcodes as $zipcodeData) {
            Zipcode::create([
                'code' => $zipcodeData['code'],
                'city_name' => $zipcodeData['city_name'],
                'state_name' => $zipcodeData['state_name'],
                'country_code' => 'IN',
                'latitude' => $zipcodeData['latitude'],
                'longitude' => $zipcodeData['longitude'],
                'is_active' => true
            ]);
        }

        // Now try to associate zipcodes with existing areas based on city names
        $this->associateZipcodesWithAreas();
    }

    /**
     * Associate zipcodes with areas based on city names.
     */
    private function associateZipcodesWithAreas(): void
    {
        $zipcodes = Zipcode::all();
        
        foreach ($zipcodes as $zipcode) {
            // Find areas in cities with similar names
            $areas = Area::whereHas('city', function($query) use ($zipcode) {
                $query->where('name', 'like', '%' . $zipcode->city_name . '%')
                      ->orWhere('name', 'like', '%' . explode(' ', $zipcode->city_name)[0] . '%');
            })->get();

            if ($areas->isNotEmpty()) {
                // Associate this zipcode with these areas
                $zipcode->areas()->attach($areas->pluck('id')->toArray());
                
                echo "Associated zipcode {$zipcode->code} with " . $areas->count() . " areas in {$zipcode->city_name}\n";
            } else {
                echo "No areas found for zipcode {$zipcode->code} in {$zipcode->city_name}\n";
            }
        }
    }
} 