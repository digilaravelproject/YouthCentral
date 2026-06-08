<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('businesses', function (Blueprint $table) {
            // Add missing columns based on user decisions
            $table->string('email')->nullable()->after('website'); 
            $table->decimal('latitude', 10, 7)->nullable()->after('description'); // Precision 10, Scale 7 is common for lat
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude'); // Precision 10, Scale 7 is common for lon
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['email', 'latitude', 'longitude']);
        });
    }
}; 
 