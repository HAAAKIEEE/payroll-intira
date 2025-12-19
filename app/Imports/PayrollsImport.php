<?php

namespace App\Imports;

use App\Models\Payroll;
use App\Models\User;
use App\Models\UserBranche;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PayrollsImport implements ToModel, WithHeadingRow
{
    public array $errors = [];
      protected $imported = 0;
    protected $skipped = 0;
    protected int $rowCounter = 1;
    protected string $periode;

    // ✅ terima periode dari Livewire
    public function __construct(string $periode)
    {
        $this->periode = $periode;
    }

    public function headingRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $this->rowCounter++;
        $rowNumber = $this->rowCounter + 1;
        try {
            $col = function ($row, $key) {
                $clean = fn($str) => strtolower(str_replace([' ', '_'], '', $str));
                $search = $clean($key);

                foreach ($row as $k => $v) {
                    if ($clean($k) === $search) {
                        return $v;
                    }
                }
                return null;
            };

            $num = fn($val) =>
                $val === null || $val === '' ? 0 :
                floatval(str_replace(['%', ','], '', $val));

            $nama   = trim($col($row, 'nama') ?? '');
            $cabang = trim($col($row, 'cabang') ?? '');

            if (!$nama || !$cabang) {
                throw new \Exception("Nama atau Cabang kosong");
            }

            $user = User::where('name', $nama)->first();
            if (!$user) {
                throw new \Exception("User '$nama' tidak ditemukan");
            }

            $userBranch = UserBranche::where('user_id', $user->id)
                ->whereHas('branch', fn($q) => $q->where('name', $cabang))
                ->first();

            if (!$userBranch) {
                throw new \Exception("Cabang '$cabang' tidak ditemukan untuk user '$nama'");
            }

            return new Payroll([
                'user_branche_id' => $userBranch->id,
                'periode'         => $this->periode, // ✅ dari form
                'golongan'        => $row['golongan'] ?? null,

                'gaji_pokok'      => $num($col($row, 'gaji pokok')),
                'transportasi'    => $num($col($row, 'transport')),
                'makan'           => $num($col($row, 'makan')),
                'hari_kerja'      => $col($row, 'hari kerja') ?? 0,

                'bonus_revenue'   => $num($col($row, 'revenue cabang')),
                'revenue_persentase' => $num($col($row, 'persentase revenue')),
                'total_revenue'   => $num($col($row, 'bonus revenue full')),

                'tunjangan'       => $num($col($row, 'tunjangan')),
                'potongan'        => $num($col($row, 'potongan')),
                'simpanan'        => $num($col($row, 'simpanan')),

                'kpi_persentase'  => $num($col($row, 'persentase kpi')),
                'kpi'             => $num($col($row, 'bonus revenue')),
                'total_kpi'       => $num($col($row, 'pot kpi')),

                'grand_total'     => $num($col($row, 'grand total')),
                'take_home_pay'   => $num($col($row, 'thp')),
            ]);

        } catch (\Exception $e) {
            $this->errors[] = [
                'row'    => $rowNumber,
                'reason' => $e->getMessage(),
                'data'   => $row,
            ];
            return null;
        }
    }
     public function getImportedCount()
    {
        return $this->imported;
    }
    public function getSkippedCount()
    {
        return $this->skipped;
    }
    public function getErrors()
    {
        return $this->errors;
    }
}

