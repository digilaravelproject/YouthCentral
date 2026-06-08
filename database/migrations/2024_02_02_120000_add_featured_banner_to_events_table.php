<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            // Make migration idempotent (some DBs already have this column)
            if (!Schema::hasColumn('events', 'featured_banner')) {
                $table->string('featured_banner')->nullable()->after('banner_image');
            }
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'featured_banner')) {
                $table->dropColumn('featured_banner');
            }
        });
    }
};
