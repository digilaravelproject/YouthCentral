<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subcategory;
use Illuminate\Support\Str;

class NewSubcategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // New subcategories requested by the user with category mapping
        $newSubcategories = [
            // Sports category (id=1)
            ['name' => 'Football/Soccer', 'category_id' => 1, 'icon_class' => 'soccer'],
            ['name' => 'Cricket', 'category_id' => 1, 'icon_class' => 'cricket-bat'],
            ['name' => 'Swimming', 'category_id' => 1, 'icon_class' => 'swim'],
            ['name' => 'Chess', 'category_id' => 1, 'icon_class' => 'chess-knight'],
            ['name' => 'Table Tennis', 'category_id' => 1, 'icon_class' => 'ping-pong'],
            ['name' => 'Martial Arts/Karate', 'category_id' => 1, 'icon_class' => 'karate'],
            
            // Academics category (id=2)
            ['name' => 'Coaching Classes', 'category_id' => 2, 'icon_class' => 'graduation-hat'],
            ['name' => 'Tuitions', 'category_id' => 2, 'icon_class' => 'book'],
            ['name' => 'Foundational Stem', 'category_id' => 2, 'icon_class' => 'atom'],
            ['name' => 'Maths/Science', 'category_id' => 2, 'icon_class' => 'calculator'],
            ['name' => 'Library', 'category_id' => 2, 'icon_class' => 'library'],
            ['name' => 'Counselling', 'category_id' => 2, 'icon_class' => 'chat-counseling'],
            
            // Arts category (id=3)
            ['name' => 'Theatre/Acting', 'category_id' => 3, 'icon_class' => 'theater'],
            ['name' => 'Music', 'category_id' => 3, 'icon_class' => 'music-note'],
            ['name' => 'Painting/Sketching', 'category_id' => 3, 'icon_class' => 'palette'],
            
            // Medical category (id=4)
            ['name' => 'Day Care', 'category_id' => 4, 'icon_class' => 'baby-care'],
            ['name' => 'Pediatrician', 'category_id' => 4, 'icon_class' => 'stethoscope'],
        ];

        foreach ($newSubcategories as $subcategoryData) {
            // Check if subcategory with this name already exists
            $existingSubcategory = Subcategory::where('name', $subcategoryData['name'])->first();
            
            if (!$existingSubcategory) {
                // Create new subcategory
                Subcategory::create([
                    'name' => $subcategoryData['name'],
                    'category_id' => $subcategoryData['category_id'],
                    'slug' => Str::slug($subcategoryData['name']),
                    'icon_class' => $subcategoryData['icon_class'],
                ]);
                
                echo "Created subcategory: {$subcategoryData['name']}\n";
            } else {
                // Update existing subcategory if needed
                $existingSubcategory->update([
                    'category_id' => $subcategoryData['category_id'],
                    'icon_class' => $subcategoryData['icon_class'],
                ]);
                
                echo "Updated subcategory: {$subcategoryData['name']}\n";
            }
        }
        
        echo "Finished processing new subcategories.\n";
    }
} 