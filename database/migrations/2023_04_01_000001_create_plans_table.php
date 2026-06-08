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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->enum('duration_type', ['monthly', 'yearly', 'one-time']);
            $table->integer('duration_value')->default(1);
            $table->integer('max_businesses')->default(1);
            $table->integer('max_images')->default(1);
            $table->boolean('featured_listing')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->integer('priority')->default(0); // For ordering plans in the UI
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
}; 