<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // أدمن
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@university.ps',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // دكتور
        User::create([
            'name' => 'Dr. Ahmed',
            'email' => 'dr.ahmed@university.ps',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'is_active' => true,
        ]);

        // طالب
        User::create([
            'name' => 'Mohammed Al-Ghazzawi',
            'email' => 'student@university.ps',
            'student_id' => '202410001',
            'password' => Hash::make('password'),
            'role' => 'student',
            'is_active' => true,
        ]);
    }
}
