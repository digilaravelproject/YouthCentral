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
            // Add claimed_by column that references the users table
            $table->foreignId('claimed_by')->nullable()->after('area_id')->constrained('users')->onDelete('set null');
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
            // Remove claimed_by column
            $table->dropForeign(['claimed_by']);
            $table->dropColumn('claimed_by');
        });
    }
}; 