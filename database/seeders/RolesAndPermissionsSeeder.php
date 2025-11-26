<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ===========================
        // 1. PERMISSIONS
        // ===========================
        $permissions = [
            'pieces:manage',
            'payroll:import',
            'payroll:manage',
            'payroll:show',
            'regions:manage',
            'branches:manage',
            'branches:import',
            'users:manage',
            'users:import',
            'master:download template',
            'master:preview data',
            // --- Izin Otorisasi (Asumsi Head Office dapat mengatur user dan peran) ---
            'authorization:manage roles',


            // // SDM
            // 'manage employees',
            // 'view employee performance',

            // // Marketing
            // 'create marketing plan',
            // 'approve marketing proposal',

            // // Operational
            // 'approve operations',
            // 'monitor performance',

            // // BOD
            // 'view financial reports',
            // 'full system control',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ===========================
        // 2. ROLES
        // ===========================
        $frontOffice = Role::firstOrCreate(['name' => 'Front Office']);
        $areaManager  = Role::firstOrCreate(['name' => 'Area Manager']);
        $headOffice   = Role::firstOrCreate(['name' => 'Head Office']);


        $sdm          = Role::firstOrCreate(['name' => 'SDM']);
        $marketing    = Role::firstOrCreate(['name' => 'Marketing']);
        $finance    = Role::firstOrCreate(['name' => 'Finance']);
        $operational  = Role::firstOrCreate(['name' => 'Operational']);
        $bod          = Role::firstOrCreate(['name' => 'BOD']);

        // ===========================
        // 3. ASSIGN PERMISSIONS
        // ===========================

       $headOffice->givePermissionTo(Permission::all());


    }
}
