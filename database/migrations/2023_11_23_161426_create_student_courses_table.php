<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studentId')->constrained(table: 'students', indexName: 'student_courses_studentId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('courseId')->constrained(table: 'courses', indexName: 'student_courses_courseId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('semesterId')->constrained(table: 'semesters', indexName: 'student_courses_semesterId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_courses');
    }
};
