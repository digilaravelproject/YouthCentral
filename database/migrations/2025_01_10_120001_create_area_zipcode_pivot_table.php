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
        Schema::create('area_zipcode', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->foreignId('zipcode_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Ensure unique combinations
            $table->unique(['area_id', 'zipcode_id']);
            
            // Indexes for better performance
            $table->index(['area_id']);
            $table->index(['zipcode_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area_zipcode');
    }
}; 