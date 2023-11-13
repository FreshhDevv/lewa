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
        Schema::create('g_p_a_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studentId')->constrained(table: 'students', indexName: 'g_p_a_s_studentId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('semesterId')->constrained(table: 'semesters', indexName: 'g_p_a_s_semesterId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedSmallInteger('year');
            $table->decimal('gpa', 4, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('g_p_a_s');
    }
};
