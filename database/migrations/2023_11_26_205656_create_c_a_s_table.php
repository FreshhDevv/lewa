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
        Schema::create('c_a_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studentId')->constrained(table: 'students', indexName: 'c_a_s_studentId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('courseId')->constrained(table: 'courses', indexName: 'c_a_s_courseId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('semesterId')->constrained(table: 'semesters', indexName: 'c_a_s_semesterId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('mark');
            $table->unique(['studentId', 'courseId']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_a_s');
    }
};
