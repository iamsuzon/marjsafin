<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'username' => 'test_user',
            ],
            [
                'name' => 'Test User',
                'username' => 'test_user',
                'email' => 'test@user.com',
                'password' => Hash::make(12345678),
            ]
        );
    }
}
