<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UpdateCategoryIcons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:update-icons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update category icons based on their names';

    /**
     * A mapping of categories to icon classes
     */
    protected $iconMapping = [
        'Sports' => 'trophy',
        'Academics' => 'graduation-hat',
        'Arts' => 'palette',
        'Medical' => 'heart-pulse'
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating category icons...');
        
        // First, ensure that all categories have a slug if it's null
        $this->ensureAllCategoriesHaveSlug();
        
        $categories = Category::all();
        $bar = $this->output->createProgressBar(count($categories));
        $bar->start();
        
        $updated = 0;
        
        foreach ($categories as $category) {
            $iconClass = $this->iconMapping[$category->name] ?? 'icon-default';
            
            // Update the icon_class field
            $category->icon_class = $iconClass;
            $category->save();
            
            $updated++;
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info("Completed! Updated {$updated} categories with appropriate icons.");
        
        // Output the results
        $this->newLine();
        $this->info("Category icon assignments:");
        foreach ($categories as $category) {
            $this->line("ID: {$category->id} | Name: {$category->name} | Icon: {$category->icon_class}");
        }
    }
    
    /**
     * Ensure all categories have a slug
     */
    private function ensureAllCategoriesHaveSlug()
    {
        $missingSlugCategories = Category::whereNull('slug')->get();
        
        if ($missingSlugCategories->isNotEmpty()) {
            $this->info("Found {$missingSlugCategories->count()} categories without slugs. Generating...");
            
            foreach ($missingSlugCategories as $category) {
                $category->slug = Str::slug($category->name);
                $category->save();
            }
            
            $this->info("Generated slugs for all categories.");
        }
    }
} 