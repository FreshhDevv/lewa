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
        Schema::create('student_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studentId')->constrained(table: 'students', indexName: 'student_codes_studentId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('semesterId')->constrained(table: 'semesters', indexName: 'student_codes_semesterId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('courseId')->constrained(table: 'courses', indexName: 'student_codes_courseId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('studentCode');
            $table->unique(['studentId', 'courseId', 'studentCode']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_codes');
    }
};
