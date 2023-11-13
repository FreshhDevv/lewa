<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
           [ 'id' => 1,
            'facultyId' => 1,
            'name' => 'Computer Engineering',
            'slug' => 'ce',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'id' => 2,
            'facultyId' => 1,
            'name' => 'Electrical Engineering',
            'slug' => 'ee',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'id' => 3,
            'facultyId' => 1,
            'name' => 'Civil Engineering',
            'slug' => 'cv',
            'created_at' => now(),
            'updated_at' => now(),
        ]
    ]);
    }
}
