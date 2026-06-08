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
        Schema::table('study_material_assignments', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->renameColumn('student_id', 'event_registration_id');
            $table->foreign('event_registration_id', 'sma_registration_foreign')->references('id')->on('event_registrations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('study_material_assignments', function (Blueprint $table) {
            $table->dropForeign('sma_registration_foreign');
            $table->renameColumn('event_registration_id', 'student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }
};
