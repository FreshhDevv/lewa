<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::create([
            'userId' => 3,
            'gender' => 'male',
            'status' => 'single',
            'dob' => fake()->date('Y-m-d'),
            'placeOfBirth' => fake()->city(),
            'address' => 'Buea',
            'phone' => fake()->phoneNumber(),
            'nationalIdentification' => 123456789,
            'country' => 'Cameroon',
            'matriculationNumber' => 'FE19A102',
            'year' => fake()->year(),
            'certificateObtained' => 'A level',
            'yearObtained' => fake()->year(),
            'guardianFirstName' => 'Olivier',
            'guardianLastName' => 'Twist',
            'guardianEmail' => fake()->email(),
            'guardianAddress' => fake()->city(),
            'guardianPhone' => fake()->phoneNumber(),
        ]);

    }
}


