<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Event;

return new class extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'slug')) {
                $table->string('slug')->unique()->nullable()->after('title');
            }
        });

        // Populate slugs for existing events
        Event::withTrashed()->whereNull('slug')->get()->each(function ($event) {
            $slug = preg_replace('/[^a-zA-Z0-9]/', '', $event->title);
            $slug = strtolower($slug);
            
            // Ensure uniqueness
            $originalSlug = $slug;
            $count = 1;
            while (Event::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                $slug = $originalSlug . $count;
                $count++;
            }
            
            $event->update(['slug' => $slug]);
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};
