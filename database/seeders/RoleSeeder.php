<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'dean']);
        Role::create(['name' => 'hod']);
        Role::create(['name' => 'coordinator']);
        Role::create(['name' => 'lecturer']);
        Role::create(['name' => 'examiner']);
        Role::create(['name' => 'supportStaff']);
        Role::create(['name' => 'student']);
    }
}
