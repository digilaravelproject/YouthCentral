<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we already have plans
        if (Plan::count() > 0) {
            // Skip seeding if plans already exist
            $this->command->info('Plans already exist. Skipping seeding.');
            return;
        }
        
        // Create sample plans
        Plan::create([
            'name' => 'Basic',
            'price' => 9.99,
            'duration_type' => 'monthly',
            'duration_value' => 1,
            'max_businesses' => 1,
            'max_images' => 5,
            'featured_listing' => false,
            'is_active' => true,
            'description' => 'Perfect for new businesses looking to establish their online presence.',
            'priority' => 3,
        ]);
        
        Plan::create([
            'name' => 'Standard',
            'price' => 19.99,
            'duration_type' => 'monthly',
            'duration_value' => 1,
            'max_businesses' => 3,
            'max_images' => 10,
            'featured_listing' => false,
            'is_active' => true,
            'description' => 'Ideal for growing businesses with multiple locations.',
            'priority' => 2,
        ]);
        
        Plan::create([
            'name' => 'Premium',
            'price' => 49.99,
            'duration_type' => 'monthly',
            'duration_value' => 1,
            'max_businesses' => 10,
            'max_images' => 20,
            'featured_listing' => true,
            'is_active' => true,
            'description' => 'The perfect choice for established businesses with multiple locations and premium features.',
            'priority' => 1,
        ]);
        
        Plan::create([
            'name' => 'Basic Annual',
            'price' => 99.99,
            'duration_type' => 'yearly',
            'duration_value' => 1,
            'max_businesses' => 1,
            'max_images' => 5,
            'featured_listing' => false,
            'is_active' => true,
            'description' => 'Save with our annual basic plan for a single business.',
            'priority' => 6,
        ]);
        
        Plan::create([
            'name' => 'Standard Annual',
            'price' => 199.99,
            'duration_type' => 'yearly',
            'duration_value' => 1,
            'max_businesses' => 3,
            'max_images' => 10,
            'featured_listing' => false,
            'is_active' => true,
            'description' => 'Our most popular annual plan for growing businesses.',
            'priority' => 5,
        ]);
        
        Plan::create([
            'name' => 'Premium Annual',
            'price' => 499.99,
            'duration_type' => 'yearly',
            'duration_value' => 1,
            'max_businesses' => 10,
            'max_images' => 20,
            'featured_listing' => true,
            'is_active' => true,
            'description' => 'Best value annual plan for established businesses with premium features.',
            'priority' => 4,
        ]);
        
        $this->command->info('Sample plans created successfully!');
    }
} 