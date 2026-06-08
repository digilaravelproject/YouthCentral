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
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('whatsapp_number')->nullable()->after('phone');
            $table->string('facebook_link')->nullable()->after('website');
            $table->string('instagram_link')->nullable()->after('facebook_link');
            $table->string('twitter_link')->nullable()->after('instagram_link');
            $table->string('pinterest_link')->nullable()->after('twitter_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('whatsapp_number');
            $table->dropColumn('facebook_link');
            $table->dropColumn('instagram_link');
            $table->dropColumn('twitter_link');
            $table->dropColumn('pinterest_link');
        });
    }
}; 