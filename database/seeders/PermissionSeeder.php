<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Application Permissions
        Permission::firstOrCreate([
            'name' => 'view-application',
            'guard_name' => 'admin'
        ]);

        Permission::firstOrCreate([
            'name' => 'modify-application',
            'guard_name' => 'admin'
        ]);

        Permission::firstOrCreate([
            'name' => 'update-application',
            'guard_name' => 'admin'
        ]);

        Permission::firstOrCreate([
            'name' => 'delete-application',
            'guard_name' => 'admin'
        ]);

        // Medical Center Allocation Permissions
        Permission::firstOrCreate([
            'name' => 'view-allocation-center-details',
            'guard_name' => 'admin'
        ]);

        Permission::firstOrCreate([
            'name' => 'modify-allocation-center-details',
            'guard_name' => 'admin'
        ]);

        // Customer List Permissions
        Permission::firstOrCreate([
            'name' => 'view-customer',
            'guard_name' => 'admin'
        ]);

        Permission::firstOrCreate([
            'name' => 'create-customer',
            'guard_name' => 'admin'
        ]);

        Permission::firstOrCreate([
            'name' => 'modify-customer',
            'guard_name' => 'admin'
        ]);

        Permission::firstOrCreate([
            'name' => 'delete-customer',
            'guard_name' => 'admin'
        ]);

        // Medical Center List Permissions
        Permission::firstOrCreate([
            'name' => 'view-medical-center',
            'guard_name' => 'admin'
        ]);

        Permission::firstOrCreate([
            'name' => 'create-medical-center',
            'guard_name' => 'admin'
        ]);

        Permission::firstOrCreate([
            'name' => 'change-password-medical-center',
            'guard_name' => 'admin'
        ]);

        Permission::firstOrCreate([
            'name' => 'update-medical-center',
            'guard_name' => 'admin'
        ]);

        Permission::firstOrCreate([
            'name' => 'delete-medical-center',
            'guard_name' => 'admin'
        ]);

        // Allocation List Permissions
        Permission::firstOrCreate([
            'name' => 'view-allocation',
            'guard_name' => 'admin'
        ]);

        Permission::firstOrCreate([
            'name' => 'create-allocation',
            'guard_name' => 'admin'
        ]);

        Permission::firstOrCreate([
            'name' => 'update-allocation',
            'guard_name' => 'admin'
        ]);

        // Admin Password Permissions
        Permission::firstOrCreate([
            'name' => 'admin-change-password',
            'guard_name' => 'admin'
        ]);

        // General Settings Permissions
        Permission::firstOrCreate([
            'name' => 'view-general-settings',
            'guard_name' => 'admin'
        ]);

        Permission::firstOrCreate([
            'name' => 'update-general-settings',
            'guard_name' => 'admin'
        ]);



        $superAdmin = Role::where('name', 'super-admin')->first();
        $superAdmin->givePermissionTo(Permission::all());

        $analyst = Role::where('name', 'analyst')->first();
        $analyst->givePermissionTo([
            'view-allocation-center-details',

            'view-medical-center',
            'update-medical-center',
            'change-password-medical-center',

            'view-application',
            'modify-application',
            'update-application',
        ]);
    }
}
