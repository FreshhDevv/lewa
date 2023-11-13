<?php

namespace Database\Seeders;

use App\Models\UserFacultyDepartment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserFacultyDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserFacultyDepartment::create([
            'userId' => 1
        ]);

        UserFacultyDepartment::create([
            'userId' => 2,
            'facultyId' => 1,
            'departmentId' => 1
        ]);

        UserFacultyDepartment::create([
            'userId' => 3,
            'facultyId' => 1,
            'departmentId' => 1
        ]);
        UserFacultyDepartment::create([
            'userId' => 4,
            'facultyId' => 1,
            'departmentId' => 1
        ]);
        UserFacultyDepartment::create([
            'userId' => 5,
            'facultyId' => 1,
            'departmentId' => 1
        ]);
    }
}
