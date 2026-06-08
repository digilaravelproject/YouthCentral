<?php

namespace App\Console\Commands;

use App\Models\Subcategory;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UpdateSubcategoryIcons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subcategories:update-icons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update subcategory icons based on their names';

    /**
     * A mapping of categories/subcategories to icon classes
     */
    protected $iconMapping = [
        // Sports Category (id=1)
        'archery' => 'archery',
        'athletics' => 'run',
        'badminton' => 'tennis2',
        'basketball' => 'basketball',
        'billiards' => '8ball',
        'pool' => '8ball',
        'boxing' => 'boxing-glove',
        'chess' => 'chess-knight',
        'cricket' => 'cricket-bat',
        'football' => 'soccer',
        'soccer' => 'soccer',
        'golf' => 'golf',
        'gymnastic' => 'gym-equipment',
        'hockey' => 'hockey',
        'kickboxing' => 'karate',
        'tennis' => 'tennis',
        'lawn-tennis' => 'tennis',
        'shooting' => 'gun',
        'swimming' => 'swim',
        'table-tennis' => 'ping-pong',
        'taekwondo' => 'karate',
        'martial' => 'karate',
        'weightlifting' => 'dumbbell',
        'wrestling' => 'wrestler',
        
        // Education Category (id=2)
        'stem' => 'atom',
        'engineering' => 'robot',
        'medical' => 'stethoscope',
        'grade' => 'book',
        'math' => 'calculator',
        'language' => 'translate',
        'tutor' => 'presentation',
        'counselling' => 'chat-counseling',
        'libraries' => 'library',
        'after-school' => 'book',
        
        // Arts Category (id=3)
        'music' => 'music-note',
        'dance' => 'dancer',
        'theatre' => 'theater',
        'acting' => 'mask-theater',
        'painting' => 'palette',
        'portrait' => 'portrait',
        'sketch' => 'pencil',
        'writing' => 'pencil3',
        'poetry' => 'feather',
        'cooking' => 'chef',
        'baking' => 'cake',
        
        // Health Category (id=4)
        'allergy' => 'pills',
        'behavioral' => 'brain',
        'counselling' => 'bubble-user',
        'dental' => 'tooth',
        'dietary' => 'diet-plan',
        'nutritional' => 'apple',
        'endocrinology' => 'dna',
        'ent' => 'ear-doctor',
        'neurology' => 'brain',
        'ortho' => 'bone',
        'pediatrician' => 'baby-care',
        'vaccination' => 'syringe',
        'physical' => 'dumbbell',
        'therapy' => 'rehabilitation',
        'rehabilitation' => 'rehabilitation',
        'skin' => 'face-detection',
        'speech' => 'mic2',
        'educational' => 'graduation-hat',
        'dyslexia' => 'book2',
        'sports-medicine' => 'first-aid',
        'acupuncture' => 'acupuncture-needle',
        'addiction' => 'addiction-help',
        'alternative-medicine' => 'herbalism',
        'allergist' => 'allergy-test',

        // Generic subcategories by category ID
        // Sports subcategories (category_id = 1)
        'subcategory-1' => 'trophy',
        'subcategory-2' => 'sports-medal',
        'subcategory-3' => 'fitness-center',
        'subcategory-4' => 'run',
        'subcategory-5' => 'swim',
        'subcategory-6' => 'soccer',
        'subcategory-7' => 'basketball',
        'subcategory-8' => 'tennis',
        'subcategory-9' => 'golf',
        'subcategory-10' => 'hockey',
        'subcategory-11' => 'archery',
        'subcategory-12' => 'cricket',
        'subcategory-13' => 'bowling',
        'subcategory-14' => 'ping-pong',
        'subcategory-15' => 'boxing-glove',
        'subcategory-16' => 'dumbbell',
        'subcategory-17' => 'karate',
        'subcategory-18' => 'wrestling',
        'subcategory-19' => 'chess-knight',
        'subcategory-20' => 'gun',
        
        // Education subcategories (category_id = 2)
        'subcategory-21' => 'graduation-hat',
        'subcategory-22' => 'book',
        'subcategory-23' => 'calculator',
        'subcategory-24' => 'atom',
        'subcategory-25' => 'robot',
        'subcategory-26' => 'presentation',
        'subcategory-27' => 'translate',
        'subcategory-28' => 'chat-counseling',
        'subcategory-29' => 'library',
        'subcategory-30' => 'document',
        
        // Arts subcategories (category_id = 3)
        'subcategory-31' => 'music-note',
        'subcategory-32' => 'dancer',
        'subcategory-33' => 'theater',
        'subcategory-34' => 'mask-theater',
        'subcategory-35' => 'palette',
        'subcategory-36' => 'portrait',
        'subcategory-37' => 'pencil',
        'subcategory-38' => 'feather',
        'subcategory-39' => 'chef',
        'subcategory-40' => 'cake',
        
        // Health subcategories (category_id = 4)
        'subcategory-41' => 'heart-pulse',
        'subcategory-42' => 'stethoscope',
        'subcategory-43' => 'pills',
        'subcategory-44' => 'brain',
        'subcategory-45' => 'tooth',
        'subcategory-46' => 'apple',
        'subcategory-47' => 'dna',
        'subcategory-48' => 'ear-doctor',
        'subcategory-49' => 'face-detection',
        'subcategory-50' => 'first-aid'
    ];

    /**
     * Icon fallbacks by category ID
     */
    protected $categoryFallbacks = [
        1 => 'trophy', // Sports
        2 => 'graduation-hat', // Education
        3 => 'palette', // Arts
        4 => 'heart-pulse', // Health
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating subcategory icons...');
        
        // First, ensure that all subcategories have a slug if it's null
        $this->ensureAllSubcategoriesHaveSlug();
        
        // Fix problematic icons if specified
        if ($this->option('fix')) {
            $this->fixProblematicIcons();
            return;
        }
        
        // Now update the icon mappings
        $subcategories = Subcategory::all();
        $bar = $this->output->createProgressBar(count($subcategories));
        $bar->start();
        
        $iconAssignments = [];
        $updated = 0;
        
        foreach ($subcategories as $subcategory) {
            $iconClass = $this->findIconForSubcategory($subcategory);
            
            // Keep the original slug for URL purposes
            // We'll store the icon class in a model property
            $subcategory->icon_class = $iconClass;
            $subcategory->save();
            
            $iconAssignments[$subcategory->id] = $iconClass;
            $updated++;
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info("Completed! Updated {$updated} subcategories with appropriate icons.");
        
        // Output some examples
        $this->newLine();
        $this->info("Icon assignments (sample):");
        $samples = $subcategories->random(min(5, count($subcategories)));
        foreach ($samples as $sample) {
            $this->line("ID: {$sample->id} | Name: {$sample->name} | Icon: {$sample->icon_class}");
        }
        
        if ($this->option('diagnostic')) {
            $this->runDiagnostic($subcategories);
        }
    }
    
    /**
     * Ensure all subcategories have a slug
     */
    private function ensureAllSubcategoriesHaveSlug()
    {
        $missingSlugSubcategories = Subcategory::whereNull('slug')->get();
        
        if ($missingSlugSubcategories->isNotEmpty()) {
            $this->info("Found {$missingSlugSubcategories->count()} subcategories without slugs. Generating...");
            
            foreach ($missingSlugSubcategories as $subcategory) {
                $subcategory->slug = Str::slug($subcategory->name);
                $subcategory->save();
            }
            
            $this->info("Generated slugs for all subcategories.");
        }
    }
    
    /**
     * Find the appropriate icon for a subcategory
     * 
     * @param Subcategory $subcategory
     * @return string
     */
    private function findIconForSubcategory(Subcategory $subcategory)
    {
        // First check if we have an exact match for the subcategory name (converted to slug format)
        $slugName = Str::slug($subcategory->name);
        
        if (isset($this->iconMapping[$slugName])) {
            return $this->iconMapping[$slugName];
        }
        
        // Next, check if any of the words in the subcategory name match an icon
        $words = explode('-', $slugName);
        foreach ($words as $word) {
            if (isset($this->iconMapping[$word])) {
                return $this->iconMapping[$word];
            }
        }
        
        // Try the generic subcategory-X mapping if it exists
        $genericKey = 'subcategory-' . $subcategory->id;
        if (isset($this->iconMapping[$genericKey])) {
            return $this->iconMapping[$genericKey];
        }
        
        // Map similar words to appropriate existing icons
        $similarWordMappings = [
            'acupuncture' => 'first-aid',      // Alternative to acupuncture-needle
            'addiction' => 'brain',            // Alternative to addiction-help
            'alternative' => 'leaf',           // Alternative to herbalism
            'allergy' => 'pills',              // Alternative to allergy-test
            'allergist' => 'pills',            // Alternative to allergy-test
            'ear' => 'hearing',                // Alternative to ear-doctor
            'ent' => 'hearing',                // Alternative to ear-doctor
            'diet' => 'apple',                 // Alternative to diet-plan
            'nutritional' => 'apple',          // Alternative to diet-plan
            'baby' => 'baby2',                 // Alternative to baby-care
            'rehab' => 'bone',                 // Alternative to rehabilitation
            'rehabilitation' => 'bone',        // Alternative to rehabilitation
            'sports' => 'trophy',              // Alternative to sports-medal
            'fitness' => 'dumbbell',           // Alternative to fitness-center
            'cricket' => 'baseball-bat',       // Alternative to cricket-bat
            'boxing' => 'fist',                // Alternative to boxing-glove
            'acting' => 'theater',             // Alternative to mask-theater
            'counselling' => 'bubble-user',    // Alternative to chat-counseling
            'gym' => 'dumbbell',               // Alternative to gym-equipment
            'gymnastic' => 'dumbbell',         // Alternative to gym-equipment
        ];
        
        foreach ($similarWordMappings as $keyword => $icon) {
            if (Str::contains($slugName, $keyword)) {
                return $icon;
            }
        }
        
        // Fall back to category-based defaults
        if (isset($this->categoryFallbacks[$subcategory->category_id])) {
            return $this->categoryFallbacks[$subcategory->category_id];
        }
        
        // Final fallback
        return 'question';  // Default icon instead of question-mark
    }
    
    /**
     * Add command options
     */
    protected function configure()
    {
        parent::configure();
        $this->addOption('diagnostic', 'd', null, 'Run diagnostic to check for icon issues');
        $this->addOption('fix', 'f', null, 'Fix problematic icons using standard mappings');
    }
    
    /**
     * Fix problematic icons with standard icons from the CSS
     */
    private function fixProblematicIcons()
    {
        $this->info("Fixing problematic icons...");
        
        // Direct database update for specific subcategory IDs with problematic icons
        $specificFixes = [
            6 => 'fist',             // Boxing
            8 => 'baseball-bat',     // Cricket
            11 => 'dumbbell',        // Gymnastic
            34 => 'chef',            // Cooking/Baking
            39 => 'apple',           // Dietary, Nutritional care
            41 => 'hearing',         // ENT
            45 => 'bone',            // Physical/Occupational Therapy
            46 => 'bone',            // Rehabilitation
            48 => 'bubble-text',     // Speech/Educational Support/Dyslexia
            51 => 'trophy',          // Subcategory 2
            52 => 'dumbbell',        // Subcategory 3
            64 => 'fist',            // Subcategory 15
            77 => 'bubble-user',     // Subcategory 28 (chat-counseling)
            83 => 'theater',         // Subcategory 34 (mask-theater)
            97 => 'hearing',         // Subcategory 48 (ear-doctor)
            170 => 'first-aid',      // Acupuncture clinic
            171 => 'brain',          // Addiction treatment center
            173 => 'pills',          // Allergist
            174 => 'leaf',           // Alternative medicine practitioner
        ];
        
        $count = 0;
        
        // Use direct database update to avoid any model issues
        foreach ($specificFixes as $id => $icon) {
            DB::table('subcategories')
                ->where('id', $id)
                ->update(['icon_class' => $icon]);
            
            $subcategory = Subcategory::find($id);
            if ($subcategory) {
                $this->line("Fixed subcategory ID {$id}: {$subcategory->name} - set icon to {$icon}");
                $count++;
            }
        }
        
        // Also fix any general issues with problematic icon classes
        $replacements = [
            'acupuncture-needle' => 'first-aid',
            'addiction-help' => 'brain',
            'herbalism' => 'leaf',
            'allergy-test' => 'pills',
            'ear-doctor' => 'hearing',
            'diet-plan' => 'apple',
            'baby-care' => 'baby2',
            'rehabilitation' => 'bone',
            'sports-medal' => 'trophy',
            'fitness-center' => 'dumbbell',
            'cricket-bat' => 'baseball-bat',
            'boxing-glove' => 'fist',
            'mask-theater' => 'theater',
            'chat-counseling' => 'bubble-user',
            'gym-equipment' => 'dumbbell',
            'question-mark' => 'question'
        ];
        
        foreach ($replacements as $problematic => $replacement) {
            $affected = DB::table('subcategories')
                ->where('icon_class', $problematic)
                ->update(['icon_class' => $replacement]);
            
            if ($affected > 0) {
                $this->line("Fixed all remaining occurrences of {$problematic} icon (replaced with {$replacement})");
                $count += $affected;
            }
        }
        
        $this->info("Fixed {$count} icon issues. Database updated directly.");
        
        // No need to flush event listeners - just clear the models
        Subcategory::clearBootedModels();
    }
    
    /**
     * Run diagnostics on subcategory icons
     */
    private function runDiagnostic($subcategories)
    {
        $this->newLine();
        $this->info("Running icon diagnostics...");
        
        // Check for potentially problematic icons
        $potentialIssues = [
            'acupuncture-needle',
            'addiction-help',
            'herbalism',
            'allergy-test',
            'ear-doctor',
            'diet-plan',
            'baby-care',
            'rehabilitation',
            'sports-medal',
            'fitness-center',
            'cricket-bat',
            'boxing-glove',
            'mask-theater',
            'chat-counseling',
            'gym-equipment',
            'question-mark',
        ];
        
        $issueCount = 0;
        
        foreach ($subcategories as $subcategory) {
            if (in_array($subcategory->icon_class, $potentialIssues)) {
                $this->line("Potential issue: Subcategory ID: {$subcategory->id}, Name: {$subcategory->name}, Icon: {$subcategory->icon_class}");
                $issueCount++;
            }
        }
        
        if ($issueCount == 0) {
            $this->info("No potential icon issues found.");
        } else {
            $this->warn("Found {$issueCount} subcategories with potential icon issues.");
            $this->info("These use custom icons that need to be defined in the CSS.");
        }
    }
} 