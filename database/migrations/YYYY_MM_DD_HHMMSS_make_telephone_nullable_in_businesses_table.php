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
            // Allow null for telephone as we primarily use phone
            $table->string('telephone')->nullable()->change();
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
            // Revert telephone to non-nullable (assuming original state)
            // Warning: This might fail if null values exist
            $table->string('telephone')->nullable(false)->change();
        });
    }
}; 
 
