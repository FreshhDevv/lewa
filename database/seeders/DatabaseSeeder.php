<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RoleSeeder::class,
            FacultySeeder::class,
            DepartmentSeeder::class,
            UserSeeder::class,
            UserFacultyDepartmentSeeder::class,
            StudentSeeder::class,
            SemesterSeeder::class,
            CoursesSeeder::class,
            // CASeeder::class,
            StudentCodeSeeder::class,
            StudentCodeMarkSeeder::class,
        ]);
    }
}
