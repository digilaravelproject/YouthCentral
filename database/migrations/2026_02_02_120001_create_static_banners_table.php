<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Make migration idempotent (some DBs already have this table)
        if (!Schema::hasTable('static_banners')) {
            Schema::create('static_banners', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->string('image_path')->nullable();
                $table->timestamps();
            });
        }

        // Insert default record for events listing banner (if missing)
        try {
            $exists = DB::table('static_banners')->where('key', 'events_listing')->exists();
            if (!$exists) {
                DB::table('static_banners')->insert([
                    'key' => 'events_listing',
                    'image_path' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // ignore when table doesn't exist yet for some reason
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('static_banners');
    }
};
