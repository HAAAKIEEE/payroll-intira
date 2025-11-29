<?php

namespace App\Imports;

use App\Models\Payroll;
use App\Models\User;
use App\Models\UserBranche;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class PayrollsImport implements ToCollection, WithCalculatedFormulas
{
    public $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $rowNumber => $row) {

            if ($rowNumber == 0) continue;

            $user = null;
            $userBranch = null;

            try {
                $nama   = trim($row[1] ?? '');
                $cabang = trim($row[2] ?? '');

                if (!$nama || !$cabang) {
                    throw new \Exception("Nama atau cabang kosong");
                }

                $user = User::where('name', $nama)->first();
                if (!$user) {
                    throw new \Exception("User '$nama' tidak ditemukan");
                }

                $userBranch = UserBranche::where('user_id', $user->id)
                    ->whereHas('branch', function ($q) use ($cabang) {
                        $q->where('name', $cabang);
                    })
                    ->first();

                if (!$userBranch) {
                    throw new \Exception("Cabang '$cabang' tidak ditemukan untuk user '$nama'");
                }

                $gaji = function ($val) {
                    if (!$val) return 0;

                    // Hapus koma (ribuan) tapi jangan hapus titik
                    $val = str_replace(',', '', $val);

                    // Kembalikan sebagai float (lebih aman dari int)
                    return (float) $val;
                };

                Payroll::create([
                    'user_branche_id' => $userBranch->id,
                    'periode'          => now()->format('Y-m'),
                    'hari_kerja'      => $row[5] ?? 0,
                    'gaji_pokok'      => $gaji($row[6] ?? 0),
                    'transportasi'    => $gaji($row[7] ?? 0),
                    'makan'           => $gaji($row[8] ?? 0),
                    'tunjangan'       => $gaji($row[9] ?? 0),
                    'bonus_revenue'   => $gaji($row[10] ?? 0),
                    'simpanan'        => $gaji($row[11] ?? 0),
                    'potongan'        => $gaji($row[12] ?? 0),
                    'total'           => $gaji($row[13] ?? 0),
                ]);
            } catch (\Exception $e) {

                $this->errors[] = [
                    'row'    => $rowNumber + 1,
                    'reason' => $e->getMessage(),
                    'data'   => [
                        'row_raw'      => $row->toArray(),
                        'user_found'   => optional($user)->toArray(),
                        'branch_found' => optional($userBranch)->toArray(),
                    ],
                ];
            }
        }
    }
}
