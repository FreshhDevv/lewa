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
        Schema::create('course_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semesterId')->constrained(table: 'semesters', indexName: 'course_summaries_semesterId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('courseId')->constrained(table: 'courses', indexName: 'course_summaries_courseId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('cc');
            $table->integer('cr');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_summaries');
    }
};
