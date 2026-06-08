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
        Schema::create('zipcodes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // zipcode/pincode
            $table->string('city_name')->nullable(); // city name for this zipcode
            $table->string('state_name')->nullable(); // state name for this zipcode
            $table->string('country_code', 2)->default('IN'); // country code (IN for India)
            $table->decimal('latitude', 10, 8)->nullable(); // latitude for zipcode center
            $table->decimal('longitude', 11, 8)->nullable(); // longitude for zipcode center
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['code']);
            $table->index(['city_name']);
            $table->index(['state_name']);
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zipcodes');
    }
}; 