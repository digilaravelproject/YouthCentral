<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_slider_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('icon_class')->nullable();
            $table->string('color')->nullable();
            $table->string('image_path')->nullable();
            $table->string('link_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed initial data from the old $sliderItems array (homepage carousel)
        DB::table('home_slider_items')->insert([
            [
                'title' => 'Tuitions',
                'subtitle' => 'Expert Tutors',
                'icon_class' => 'fi fi-rr-book-alt',
                'color' => '#FF6B6B',
                'image_path' => 'assets_public/images/backgrounds/slideshow/1.jpg',
                'link_url' => '/search?query=tuition',
                'is_active' => 1,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Sports',
                'subtitle' => 'Cricket, Football',
                'icon_class' => 'fi fi-rr-football-player',
                'color' => '#4ECDC4',
                'image_path' => 'assets_public/images/backgrounds/slideshow/2.jpg',
                'link_url' => '/search?query=sports',
                'is_active' => 1,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Music',
                'subtitle' => 'Learn Instruments',
                'icon_class' => 'fi fi-rs-music-alt',
                'color' => '#45B7D1',
                'image_path' => 'assets_public/images/backgrounds/slideshow/3.jpg',
                'link_url' => '/search?query=music',
                'is_active' => 1,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Doctors',
                'subtitle' => 'Pediatricians',
                'icon_class' => 'fi fi-rr-stethoscope',
                'color' => '#96CEB4',
                'image_path' => 'assets_public/images/backgrounds/slideshow/1.jpg',
                'link_url' => '/search?query=doctor',
                'is_active' => 1,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Dance',
                'subtitle' => 'Classical & Western',
                'icon_class' => 'fi fi-ts-theater-masks',
                'color' => '#FFEEAD',
                'image_path' => 'assets_public/images/backgrounds/slideshow/2.jpg',
                'link_url' => '/search?query=dance',
                'is_active' => 1,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Events',
                'subtitle' => 'Book Now',
                'icon_class' => 'fi fi-rs-calendar',
                'color' => '#D4A5A5',
                'image_path' => 'assets_public/images/backgrounds/slideshow/3.jpg',
                'link_url' => '/events',
                'is_active' => 1,
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('home_slider_items');
    }
};

