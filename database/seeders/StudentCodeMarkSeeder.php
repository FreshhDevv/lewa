<?php

namespace Database\Seeders;

use App\Models\StudentCodeMark;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentCodeMarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StudentCodeMark::create([
            'studentCodeId' => 1,
            'mark' => 70,
          ]);
    }
}
