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
        Schema::create('user_faculty_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained(table: 'users', indexName: 'user_faculty_departments_userId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('facultyId')->nullable()->constrained(table: 'faculties', indexName: 'user_faculty_departments_facultyId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('departmentId')->nullable()->constrained(table: 'departments', indexName: 'user_faculty_departments_departmentId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_faculty_departments');
    }
};
