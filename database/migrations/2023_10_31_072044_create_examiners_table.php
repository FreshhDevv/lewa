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
        Schema::create('examiners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained(table: 'users', indexName: 'examiners_userId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('semesterId')->constrained(table: 'semesters', indexName: 'examiners_semesterId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examiners');
    }
};
