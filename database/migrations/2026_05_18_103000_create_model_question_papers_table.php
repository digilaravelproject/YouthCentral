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
        Schema::create('model_question_papers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['PDF', 'Worksheet', 'STEM']);
            $table->string('file_path')->nullable();
            $table->string('video_link')->nullable();
            $table->boolean('status')->default(true);
            $table->string('subject')->nullable();
            $table->date('month')->nullable();
            $table->string('topic')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_question_papers');
    }
};
