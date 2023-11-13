<?php

namespace Database\Seeders;

use App\Models\StudentCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StudentCode::create([
            'studentId' => 1,
            'semesterId' => 1,
            'courseId' => 1,
            'studentCode' => 001,

        ]);
    }
}
