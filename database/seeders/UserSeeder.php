<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'firstName' => 'FET',
            'lastName' => 'Admin',
            'email' => 'admin@gmail.com',
            'profilePicture' => NULL,
        ])->assignRole('admin');

        User::create([
            'firstName' => 'FET',
            'lastName' => 'Dean',
            'email' => 'dean@gmail.com',
            'profilePicture' => NULL,
        ])->assignRole('dean');

        User::create([
            'firstName' => 'Billy',
            'lastName' => 'Hans',
            'email' => 'billyhans@gmail.com',
            'profilePicture' => NULL,
        ])->assignRole('student');

        User::create([
            'firstName' => 'Dr',
            'lastName' => 'Chrome',
            'email' => 'chrome@gmail.com',
            'profilePicture' => NULL,
        ])->assignRole('lecturer');

        User::create([
            'firstName' => 'Dr',
            'lastName' => 'Gonzo',
            'email' => 'gonzo@gmail.com',
            'profilePicture' => NULL,
        ])->assignRole('coordinator');

        User::create([
            'firstName' => 'Mr',
            'lastName' => 'Secretary',
            'email' => 'secretary@gmail.com',
            'profilePicture' => NULL,
        ])->assignRole('supportStaff');
    }
}
