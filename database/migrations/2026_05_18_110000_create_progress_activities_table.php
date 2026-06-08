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
        Schema::create('progress_activities', function (Blueprint $table) {
            $table->id();
            $table->string('activity_type')->unique();
            $table->string('title');
            $table->integer('percentage');
            $table->integer('max_limit');
            $table->timestamps();
        });

        // Seed default activities
        $defaults = [
            [
                'activity_type' => 'study_material_view',
                'title' => 'Study materials viewed',
                'percentage' => 30,
                'max_limit' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_type' => 'study_material_download',
                'title' => 'Study materials download',
                'percentage' => 15,
                'max_limit' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_type' => 'model_question_paper_download',
                'title' => 'Model question papers downloads',
                'percentage' => 15,
                'max_limit' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_type' => 'model_question_paper_view',
                'title' => 'Model question papers viewed',
                'percentage' => 20,
                'max_limit' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_type' => 'paper_completed',
                'title' => 'Papers completed',
                'percentage' => 10,
                'max_limit' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_type' => 'login_streak',
                'title' => 'Login streak',
                'percentage' => 20,
                'max_limit' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('progress_activities')->insert($defaults);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_activities');
    }
};
