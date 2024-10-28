<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::updateOrCreate(
            [
                'username' => 'super_admin',
            ],
            [
                'name' => 'Super Admin',
                'username' => 'super_admin',
                'email' => 'super@admin.com',
                'password' => Hash::make(12345678),
                'role' => 'super-admin',
            ]
        );
        $admin->assignRole('super-admin');

        $adminTwo = Admin::updateOrCreate(
            [
                'username' => 'medical_admin',
            ],
            [
                'name' => 'Admin',
                'username' => 'medical_admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make(12345678),
                'role' => 'analyst',
            ]
        );
        $adminTwo->assignRole('analyst');
    }
}
