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
        $frontOffice = User::create([
            'name' => 'FO 12',
            'username' => 'ibay5212',
            'password' => bcrypt('123123123'),
        ]);
        $frontOffice->assignRole('Front Office');

        // User Area Manager
        $areaManager = User::create([
            'name' => 'AM 12',
            'username' => 'haci5212',
            'password' => bcrypt('123123123'),
        ]);
        $areaManager->assignRole('Area Manager');

        // User Head Office
        $headOffice = User::create([
            'name' => 'HO 12',
            'username' => 'admin5212',
            'password' => bcrypt('123123123'),
        ]);
        $headOffice->assignRole('Head Office');

        $userSDM = User::create([
            'name' => 'SDM User',
            'username' => 'sdmuser',
            'password' => bcrypt('123123123'),
        ]);
        $userSDM->assignRole('SDM');

        $userMarketing = User::create([
            'name' => 'Marketing User',
            'username' => 'marketinguser',
            'password' => bcrypt('123123123'),
        ]);
        $userMarketing->assignRole('Marketing');
    }
}
