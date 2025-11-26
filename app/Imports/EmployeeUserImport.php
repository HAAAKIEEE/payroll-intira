<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\User;
use App\Models\UserBranche;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class EmployeeUserImport implements
    ToCollection,
    SkipsEmptyRows,
    WithCalculatedFormulas
{
    private $imported = 0;
    private $skipped = 0;
    private $errors = [];

    public function collection(Collection $rows)
    {
        // Skip header row (baris pertama)
        $rows = $rows->skip(1);

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // Baris real pada Excel

            try {
                // ================================
                // MAPPING KOLOM BERDASARKAN INDEKS
                // ================================
                // Kolom A (0) = No Urut
                // Kolom B (1) = Nama Karyawan
                // Kolom C (2) = username
                // Kolom D (3) = gol
                // Kolom E (4) = status
                // Kolom F (5) = no rek
                // Kolom G (6) = NPWP
                // Kolom H (7) = Nik
                // Kolom I (8) = Nama Penerima sesuai nik
                // Kolom J (9) = Alamat penerima sesuai nik
                // Kolom K (10) = status tanggungan
                // Kolom L (11) = nama cabang
                // Kolom M (12) = Tgl masuk
                // Kolom M (13) = lama bekrja
                // Kolom M (14) = tahun
                // Kolom M (15) = bulan 
                // Kolom M (16) = area 
                // Kolom M (17) = Pendidikan 


                $nama = trim($row[1] ?? '');
                $nik  = trim($row[7] ?? '');

                // VALIDASI WAJIB
                if ($nama === '' || $nik === '') {
                    $this->skipped++;
                    $this->errors[] = [
                        'row'    => $rowNumber,
                        'reason' => 'Nama karyawan atau NIK kosong',
                        'data'   => $row->toArray()
                    ];
                    continue;
                }

                $branchName   = trim($row[11] ?? '');
                $grade        = $row[3] ?? null;
                $hireDate     = $this->parseDate($row[12] ?? null);
                $yearOfService = $hireDate
                    ? Carbon::now()->diffInYears(Carbon::parse($hireDate))
                    : 0;

                $npwp         = $row[6] ?? null;
                $noRek        = $row[5] ?? null;
                $address      = $row[9] ?? null;
                $ptkpStatus   = $row[10] ?? null;
                // $username     = trim($row[2] ?? '' if null $nama);
                $username = !empty($row[2])
                    ? trim($row[2])
                    : (!empty($nama) ? $nama : '');
                $education    = $row[17] ?? null;
                $status       = $row[4] ?? 'AKTIF';


                retry(5, function () use (
                    $nik,
                    $nama,
                    $grade,
                    $address,
                    $hireDate,
                    $yearOfService,
                    $education,
                    $noRek,
                    $npwp,
                    $ptkpStatus,
                    $status
                ) {


                    Employee::updateOrCreate(
                        ['nik' => $nik],
                        [
                            'full_name'        => $nama,
                            'grade'            => $grade,
                            'nik' => $nik,
                            'address'          => $address,
                            'hire_date'        => $hireDate,
                            'years_of_service' => $yearOfService,
                            'employee_code'    => $nik,
                            'education'        => $education,
                            'account_number'   => $noRek,
                            'npwp_number'      => $npwp,
                            'position'         => $ptkpStatus,
                            'is_active'        => $this->parseStatus($status),
                        ]
                    );
                }, 200);


                // ================================
                // SAVE / UPDATE USER
                // ================================
                // Jika user_sistem kosong → gunakan nama tanpa spasi
                if ($username == '' || $username == null) {
                    $username = str_replace(' ', '', strtolower($nama));
                }

                // Password = NIK
                $password = bcrypt($nik);

                $user = retry(5, function () use ($username, $nama, $password, $nik) {
                    return User::updateOrCreate(
                        ['username' => $username],
                        [
                            'name'        => $nama,
                            'password'    => $password,
                            'employee_id' => Employee::where('nik', $nik)->value('id')
                        ]
                    );
                }, 200);


                // ================================
                // SAVE / UPDATE USER BRANCH
                // ================================
                if ($branchName) {
                    // Jika nama cabang diawali "KC" maka cari cabang sesuai nama
                    if (str_starts_with(strtoupper($branchName), 'KC')) {
                        $branch = Branch::where('name', $branchName)->first();
                    } else {
                        // Selain KC → langsung masukkan ke cabang MASTER
                        $branch = Branch::where('name', 'MASTER')->first();
                    }
                    if ($branch) {
                        retry(5, function () use ($user, $branch) {
                            UserBranche::updateOrCreate(
                                [
                                    'user_id'     => $user->id,
                                    'branches_id' => $branch->id,
                                ],
                                [
                                    'role'      => 'karyawan',
                                    'is_active' => true,
                                    'start_at'  => now(),
                                    'end_at'    => null
                                ]
                            );
                        }, 200);
                    } else {
                        $this->skipped++;
                        $this->errors[] = [
                            'row'    => $rowNumber,
                            'reason' => "Cabang '$branchName' tidak ditemukan",
                            'data'   => $row->toArray()
                        ];
                        continue;
                    }
                }

                $this->imported++;
            } catch (\Exception $e) {
                $this->skipped++;

                $this->errors[] = [
                    'row'    => $rowNumber,
                    'reason' => $e->getMessage(),
                    'data'   => $row->toArray(),
                ];

                Log::error("Import error on row {$rowNumber}", [
                    'message' => $e->getMessage(),
                    'row'     => $row->toArray()
                ]);
            }
        }
    }
    private function parseDate($value)
    {
        if (empty($value)) return null;

        try {
            // Excel serial number ke Carbon
            if (is_numeric($value)) {
                return Carbon::instance(ExcelDate::excelToDateTimeObject($value));
            }

            // String tanggal format umum
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseStatus($status): bool
    {
        return in_array(
            strtoupper(trim($status)),
            ['AKTIF', 'ACTIVE', '1', 'YES', 'Y', 'TRUE']
        );
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
