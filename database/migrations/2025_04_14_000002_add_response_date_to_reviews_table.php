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
        Schema::table('reviews', function (Blueprint $table) {
            // Add response_date column after vendor_response
            if (!Schema::hasColumn('reviews', 'response_date')) {
                $table->timestamp('response_date')->nullable()->after('vendor_response');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Remove response_date column
            if (Schema::hasColumn('reviews', 'response_date')) {
                $table->dropColumn('response_date');
            }
        });
    }
}; 