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
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'additional_images')) {
                $table->json('additional_images')->nullable()->after('featured_banner');
            }
            if (!Schema::hasColumn('events', 'long_description')) {
                $table->longText('long_description')->nullable()->after('additional_images');
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
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'long_description')) {
                $table->dropColumn('long_description');
            }
            if (Schema::hasColumn('events', 'additional_images')) {
                $table->dropColumn('additional_images');
            }
        });
    }
};
