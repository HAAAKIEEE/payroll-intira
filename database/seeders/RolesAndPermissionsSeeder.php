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
        $master   = Role::firstOrCreate(['name' => 'Master']);
        $karyawan = Role::firstOrCreate(['name' => 'Karyawan']);
       

        // ===========================
        // 3. ASSIGN PERMISSIONS
        // ===========================

       $master->givePermissionTo(Permission::all());


    }
}
