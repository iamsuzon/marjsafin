<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::insert(
            [
                [
                    'guard_name' => 'admin',
                    'name' => 'super-admin'
                ],
                [
                    'guard_name' => 'admin',
                    'name' => 'admin'
                ],
                [
                    'guard_name' => 'admin',
                    'name' => 'analyst'
                ]
            ]
        );

        $this->call([
            AdminSeeder::class,
            PermissionSeeder::class,

//            UserSeeder::class,
        ]);
    }
}
