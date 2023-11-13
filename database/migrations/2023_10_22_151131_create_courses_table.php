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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facultyId')->constrained(table: 'faculties', indexName: 'courses_facultyId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('userId')->constrained(table: 'users', indexName: 'courses_userId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('semesterId')->constrained(table: 'semesters', indexName: 'courses_semesterId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name');
            $table->string('courseCode');
            $table->enum('level', ['200', '300', '400', '500', '600', '700', '800', '900']);
            $table->enum('status', ['compulsory', 'required', 'elective', 'universityRequirement']);
            $table->integer('creditValue');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
