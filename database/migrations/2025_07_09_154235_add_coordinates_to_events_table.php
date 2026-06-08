<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Add latitude and longitude fields after area_id for GPS-based distance sorting
            $table->decimal('latitude', 10, 8)->nullable()->after('area_id')->comment('Event GPS latitude for distance-based sorting');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude')->comment('Event GPS longitude for distance-based sorting');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
