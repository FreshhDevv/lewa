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
        Schema::create('student_code_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studentCodeId')->constrained(table: 'student_codes', indexName: 'student_code_marks_studentCodeId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('mark');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_code_marks');
    }
};
