<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        // User Front Manager
        $master = User::create([
            'name' => 'Master Shifu',
            'username' => 'master123',
            'password' => bcrypt('123123123'),
        ]);
        $master->assignRole('Master');


        // User Area Manager
        $karyawan = User::create([
            'name' => 'karyawan Sampai Mati',
            'username' => 'karyawan123',
            'password' => bcrypt('123123123'),
        ]);
        $karyawan->assignRole('Karyawan');

    }
}
