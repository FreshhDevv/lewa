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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studentId')->constrained(table: 'students', indexName: 'results_studentId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('semesterId')->constrained(table: 'semesters', indexName: 'results_semesterId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('courseId')->constrained(table: 'courses', indexName: 'results_courseId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('mark');
            $table->string('grade');
            $table->decimal('gradePoint', 5, 2);
            $table->unique(['studentId', 'semesterId', 'courseId']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
