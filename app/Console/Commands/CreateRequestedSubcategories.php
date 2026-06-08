<?php

namespace App\Console\Commands;

use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateRequestedSubcategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subcategories:create-requested';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the specific subcategories requested by the user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating requested subcategories...');
        
        // Map of subcategory names to their respective categories and icons
        $requestedSubcategories = [
            // Sports category (id=1)
            'Football/Soccer' => ['category_id' => 1, 'icon_class' => 'soccer'],
            'Cricket' => ['category_id' => 1, 'icon_class' => 'cricket-bat'],
            'Swimming' => ['category_id' => 1, 'icon_class' => 'swim'],
            'Chess' => ['category_id' => 1, 'icon_class' => 'chess-knight'],
            'Table Tennis' => ['category_id' => 1, 'icon_class' => 'ping-pong'],
            'Martial Arts/Karate' => ['category_id' => 1, 'icon_class' => 'karate'],
            
            // Academics category (id=2)
            'Coaching Classes' => ['category_id' => 2, 'icon_class' => 'graduation-hat'],
            'Tuitions' => ['category_id' => 2, 'icon_class' => 'book'],
            'Foundational Stem' => ['category_id' => 2, 'icon_class' => 'atom'],
            'Maths/Science' => ['category_id' => 2, 'icon_class' => 'calculator'],
            'Library' => ['category_id' => 2, 'icon_class' => 'library'],
            'Counselling' => ['category_id' => 2, 'icon_class' => 'chat-counseling'],
            
            // Arts category (id=3)
            'Theatre/Acting' => ['category_id' => 3, 'icon_class' => 'theater'],
            'Music' => ['category_id' => 3, 'icon_class' => 'music-note'],
            'Painting/Sketching' => ['category_id' => 3, 'icon_class' => 'palette'],
            
            // Medical category (id=4)
            'Day Care' => ['category_id' => 4, 'icon_class' => 'baby2'],
            'Pediatrician' => ['category_id' => 4, 'icon_class' => 'stethoscope'],
        ];

        $created = 0;
        $updated = 0;

        foreach ($requestedSubcategories as $name => $data) {
            // Check if subcategory already exists
            $existingSubcategory = Subcategory::where('name', $name)->first();
            
            if (!$existingSubcategory) {
                // Create new subcategory
                try {
                    Subcategory::create([
                        'name' => $name,
                        'category_id' => $data['category_id'],
                        'slug' => Str::slug($name),
                        'icon_class' => $data['icon_class'],
                    ]);
                    
                    $this->line("✅ Created subcategory: {$name}");
                    $created++;
                } catch (\Exception $e) {
                    $this->error("❌ Failed to create subcategory {$name}: " . $e->getMessage());
                }
            } else {
                // Update existing subcategory if needed
                $needsUpdate = false;
                $updates = [];
                
                if ($existingSubcategory->category_id != $data['category_id']) {
                    $updates['category_id'] = $data['category_id'];
                    $needsUpdate = true;
                }
                
                if ($existingSubcategory->icon_class != $data['icon_class']) {
                    $updates['icon_class'] = $data['icon_class'];
                    $needsUpdate = true;
                }
                
                if ($needsUpdate) {
                    try {
                        $existingSubcategory->update($updates);
                        $this->line("🔄 Updated subcategory: {$name}");
                        $updated++;
                    } catch (\Exception $e) {
                        $this->error("❌ Failed to update subcategory {$name}: " . $e->getMessage());
                    }
                } else {
                    $this->line("✅ Subcategory already exists and is correct: {$name}");
                }
            }
        }
        
        $this->info("Completed! Created {$created} new subcategories and updated {$updated} existing ones.");
        
        // Show final list
        $this->newLine();
        $this->info("Current requested subcategories:");
        $subcategories = Subcategory::whereIn('name', array_keys($requestedSubcategories))
            ->with('category')
            ->get();
            
        foreach ($subcategories as $subcategory) {
            $this->line("- {$subcategory->name} (Category: {$subcategory->category->name}, Icon: {$subcategory->icon_class})");
        }
        
        return 0;
    }
} 