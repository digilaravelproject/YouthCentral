<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Business;
use App\Models\Area;
use App\Models\State;
use App\Models\City;

class DummyBusinessesSeeder extends Seeder
{
    public function run()
    {
        // Create Categories
        $sportsCategory = Category::firstOrCreate(['name' => 'Sports']);
        $academicsCategory = Category::firstOrCreate(['name' => 'Academics']);
        $artsCategory = Category::firstOrCreate(['name' => 'Arts']);
        $medicalCategory = Category::firstOrCreate(['name' => 'Medical']);

        // Define subcategories for each category
        $subcategories = [
            'Sports' => [
                'Archery', 'Athletics (Track/Field)', 'Badminton', 'Basketball',
                'Billiards/Table Pool (8 Ball)', 'Boxing', 'Chess', 'Cricket',
                'Football/Soccer', 'Golf', 'Gymnastic', 'Hockey', 'Kickboxing',
                'Lawn Tennis', 'Shooting', 'Swimming', 'Table Tennis',
                'Taekwondo/Martial Arts/Karate/Judo', 'Weightlifting', 'Wrestling'
            ],
            'Academics' => [
                'Foundational STEM', 'Engineering/Medical', 'Grade 6-8 Coachings',
                'Grade 1-5 Coachings', 'Advance Math (Grade 7 and above)',
                'Languages (English Grammar, French, German)', 'Private Tutors for all grades',
                'Career Counselling', 'Libraries'
            ],
            'Arts' => [
                'Music/Dance', 'Theatre/Acting', 'Painting/Portraits/Figure sketching',
                'Writing/Poetry', 'Cooking/Baking'
            ],
            'Medical' => [
                'Allergy', 'Behavioral Therapy', 'Counselling', 'Dental',
                'Dietary, Nutritional care', 'Endocrinology', 'ENT', 'Neurology',
                'Ortho', 'Pediatrician/Well child care/Vaccinations',
                'Physical/Occupational Therapy', 'Rehabilitation', 'Skin Care',
                'Speech/Educational Support/Dyslexia', 'Sports Medicine'
            ]
        ];

        // Get or create default locations
        $state = State::firstOrCreate(['name' => 'Maharashtra']);
        $city = City::firstOrCreate(
            ['name' => 'Mumbai'],
            ['state_id' => $state->id]
        );
        $areas = [
            Area::firstOrCreate(['name' => 'Andheri', 'city_id' => $city->id]),
            Area::firstOrCreate(['name' => 'Bandra', 'city_id' => $city->id]),
            Area::firstOrCreate(['name' => 'Juhu', 'city_id' => $city->id]),
            Area::firstOrCreate(['name' => 'Powai', 'city_id' => $city->id])
        ];

        // Add Navi Mumbai as a separate city
        $naviMumbaiCity = City::firstOrCreate(
            ['name' => 'Navi Mumbai'],
            ['state_id' => $state->id]
        );
        $naviMumbaiAreas = [
            Area::firstOrCreate(['name' => 'Vashi', 'city_id' => $naviMumbaiCity->id]),
            Area::firstOrCreate(['name' => 'CBD Belapur', 'city_id' => $naviMumbaiCity->id]),
            Area::firstOrCreate(['name' => 'Nerul', 'city_id' => $naviMumbaiCity->id]),
            Area::firstOrCreate(['name' => 'Kharghar', 'city_id' => $naviMumbaiCity->id]),
            Area::firstOrCreate(['name' => 'Airoli', 'city_id' => $naviMumbaiCity->id])
        ];
        
        // Combine all areas for business generation
        $allAreas = array_merge($areas, $naviMumbaiAreas);

        // Create subcategories and businesses
        foreach ($subcategories as $categoryName => $subcategoryList) {
            $category = Category::where('name', $categoryName)->first();
            
            foreach ($subcategoryList as $subcategoryName) {
                $subcategory = Subcategory::firstOrCreate(
                    ['name' => $subcategoryName],
                    ['category_id' => $category->id]
                );

                // Create 4 businesses for each subcategory
                for ($i = 1; $i <= 4; $i++) {
                    $areaIndex = array_rand($allAreas);
                    $businessName = $this->generateBusinessName($subcategoryName, $i);
                    
                    Business::create([
                        'business_name' => $businessName,
                        'description' => $this->generateDescription($subcategoryName, $businessName),
                        'telephone' => '98765' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT),
                        'website' => 'www.' . strtolower(str_replace(' ', '', $businessName)) . '.com',
                        'street_address' => rand(1, 999) . ' ' . $this->getRandomStreetName(),
                        'subcategory_id' => $subcategory->id,
                        'area_id' => $allAreas[$areaIndex]->id,
                        'status' => 'active',
                        'whatsapp_number' => '91' . rand(7000000000, 9999999999),
                        'facebook_link' => 'https://facebook.com/' . strtolower(str_replace(' ', '', $businessName)),
                        'instagram_link' => 'https://instagram.com/' . strtolower(str_replace(' ', '', $businessName))
                    ]);
                }
            }
        }
    }

    private function generateBusinessName($subcategory, $index)
    {
        $prefixes = ['Elite', 'Pro', 'Premier', 'Advanced'];
        $suffixes = ['Academy', 'Institute', 'Center', 'Club'];
        
        $simplifiedName = explode('/', $subcategory)[0];
        $simplifiedName = explode('(', $simplifiedName)[0];
        $simplifiedName = trim($simplifiedName);
        
        return $prefixes[array_rand($prefixes)] . ' ' . $simplifiedName . ' ' . $suffixes[array_rand($suffixes)];
    }

    private function generateDescription($subcategory, $businessName)
    {
        $descriptions = [
            'Professional training and coaching at ' . $businessName . '. Expert instructors with years of experience.',
            $businessName . ' offers comprehensive programs for beginners to advanced levels.',
            'Join ' . $businessName . ' for state-of-the-art facilities and personalized attention.',
            'Learn from the best at ' . $businessName . '. Modern facilities and expert guidance.'
        ];
        
        return $descriptions[array_rand($descriptions)];
    }

    private function getRandomStreetName()
    {
        $streets = ['Main Street', 'Park Road', 'Lake View', 'Hill Road', 'Station Road', 'Market Lane'];
        return $streets[array_rand($streets)];
    }
} 