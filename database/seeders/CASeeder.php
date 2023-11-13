<?php

namespace Database\Seeders;

use App\Models\CA;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CASeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CA::create([
            'studentId' => 1,
            'courseId' => 1,
            'semesterId' => 1,
            'mark' => 23,
        ]);
    }
}
