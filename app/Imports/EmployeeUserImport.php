<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\User;
use App\Models\UserBranche;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class EmployeeUserImport implements ToModel, WithHeadingRow, WithChunkReading
{
    protected $success = 0;
    protected $skipped = 0;
    protected $errors  = [];

    public function chunkSize(): int
    {
        return 100;
    }

    public function model(array $row)
    {
    
        $name       = trim($row['nama_karyawan'] ?? '');
        $username   = trim($row['user_sistem'] ?? '');
        $nik        = trim($row['nik'] ?? '');
        $cabangName = trim($row['nama_cabang'] ?? '');

        // Validasi wajib
        if (!$name || !$username || !$cabangName) {
            $this->skipped++;
            return null;
        }

        // Cari cabang
        $cabang = Branch::where('name', $cabangName)->first();

        if (!$cabang) {
            $this->errors[] = "Cabang '{$cabangName}' tidak ditemukan (Karyawan: {$name})";
            $this->skipped++;
            return null;
        }

        // Cek user berdasarkan username atau NIK
        $user = User::where('username', $username)
                    ->first();

        if ($user) {
            $this->errors[] = "User '{$name}' sudah terdaftar";
            $this->skipped++;
            return null;
        }

        // Buat user baru
        $user = User::create([
            'name'     => $name,
            'username' => $username,
            'password' => Hash::make($nik),
        ]);

        $user->assignRole('Karyawan');

        // Relasi user ↔ cabang
        UserBranche::updateOrCreate(
            [
                'user_id'     => $user->id,
                'branches_id'=> $cabang->id,
            ],
            [
                'role'      => 'Karyawan',
                'is_active' => 1,
            ]
        );

        $this->success++;

        return $user;
    }

    public function getSummary()
    {
        return [
            'success' => $this->success,
            'skipped' => $this->skipped,
            'errors'  => $this->errors,
        ];
    }
}
