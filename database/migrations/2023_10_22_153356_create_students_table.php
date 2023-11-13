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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained(table: 'users', indexName: 'students_userId')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('gender', ['male', 'female']);
            $table->enum('status', ['single', 'married']);
            $table->date('dob');
            $table->string('placeOfBirth');
            $table->string('address');
            $table->string('phone');
            $table->enum('region', ['far_north', 'north', 'adamawa', 'west', 'north_west', 'south_west', 'littoral', 'centre', 'east', 'south'])->nullable();
            $table->integer('nationalIdentification');
            $table->string('country');
            $table->string('matriculationNumber');
            $table->enum('level', ['200', '300', '400', '500', '600', '700', '800', '900']);
            $table->year('year');
            $table->enum('program', ['software', 'network']);
            $table->string('certificateObtained');
            $table->year('yearObtained');
            $table->string('guardianFirstName');
            $table->string('guardianLastName');
            $table->string('guardianEmail');
            $table->string('guardianAddress');
            $table->string('guardianPhone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
