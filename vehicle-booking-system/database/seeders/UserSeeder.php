<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@sekawan.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Approver users
        User::create([
            'name' => 'Approver 1',
            'email' => 'approver1@sekawan.com',
            'password' => Hash::make('password'),
            'role' => 'approver',
        ]);

        User::create([
            'name' => 'Approver 2',
            'email' => 'approver2@sekawan.com',
            'password' => Hash::make('password'),
            'role' => 'approver',
        ]);

        User::create([
            'name' => 'Approver 3',
            'email' => 'approver3@sekawan.com',
            'password' => Hash::make('password'),
            'role' => 'approver',
        ]);
    }
}
