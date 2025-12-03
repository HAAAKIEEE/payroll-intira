<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\User;
use App\Models\UserBranche;
use App\Models\PayrollPiece;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PayrollPieceImport implements ToCollection
{
    private $imported = 0;
    private $skipped = 0;
    private $errors = [];
    private $selectedPeriode;

    public function __construct($selectedPeriode)
    {
        $this->selectedPeriode = $selectedPeriode;
    }

    /**
     * Konversi Excel serial date ke format Y-m-d
     */
    private function convertExcelDate($value)
    {
        if (empty($value)) {
            return null;
        }

        // Jika sudah berupa string tanggal yang valid
        if (is_string($value) && !is_numeric($value)) {
            try {
                return Carbon::parse($value)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        // Jika berupa Excel serial number
        if (is_numeric($value)) {
            try {
                // Excel date serial number dimulai dari 1900-01-01
                // Tapi Excel salah menghitung 1900 sebagai leap year
                // Jadi kita mulai dari 1899-12-30 dan tambah serial number
                $unixTimestamp = ($value - 25569) * 86400;
                return Carbon::createFromTimestamp($unixTimestamp)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }

    public function collection(Collection $rows)
    {
        // Lewati header
        $rows = $rows->skip(1);

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            try {
                // Ambil kolom Excel yg akan di import
                $tanggalRaw = $row[2] ?? null;
                $namaExcel = trim($row[3] ?? '');
                $kesejahteraan = $row[6] ?? 0;
                $komunikasi = $row[7] ?? 0;
                $tunjangan = $row[8] ?? 0;
                $potongan = $row[9] ?? 0;
                $kategori = trim($row[10] ?? '');
                $keterangan = trim($row[11] ?? '');
                $nikExcel = trim($row[13] ?? '');

                // Konversi tanggal Excel
                $tanggal = $this->convertExcelDate($tanggalRaw);

                // ========================
                // Validasi NIK dari Excel
                // ========================
                if (empty($nikExcel) || $nikExcel == '#N/A' || $nikExcel == '0') {
                    $this->skipped++;
                    $this->errors[] = "Baris $rowNumber: NIK tidak valid atau kosong (NIK: '$nikExcel').";
                    continue;
                }

                Log::info("Processing baris $rowNumber - NIK Excel: $nikExcel, Nama Excel: $namaExcel");

                // ========================
                // STEP 1: Cari Employee berdasarkan NIK dari Excel
                // ========================
                $employee = Employee::where('nik', $nikExcel)->first();
                
                if (!$employee) {
                    $this->skipped++;
                    $this->errors[] = "Baris $rowNumber: Employee dengan NIK '$nikExcel' tidak ditemukan di tabel employees.";
                    Log::warning("Employee tidak ditemukan untuk NIK: $nikExcel");
                    continue;
                }

                Log::info("Employee ditemukan - ID: {$employee->id}, Nama: {$employee->full_name}, NIK: {$employee->nik}");

                // ========================
                // STEP 2: Cari User yang terkait dengan Employee ini
                // ========================
                $user = User::where('employee_id', $employee->id)->first();
                
                if (!$user) {
                    $this->skipped++;
                    $this->errors[] = "Baris $rowNumber: Employee dengan NIK '$nikExcel' ({$employee->full_name}) tidak memiliki User account.";
                    Log::warning("User tidak ditemukan untuk Employee ID: {$employee->id}");
                    continue;
                }

                Log::info("User ditemukan - ID: {$user->id}, Username: {$user->username}");

                // ========================
                // STEP 3: Cari UserBranche untuk user ini
                // ========================
                $userBranche = UserBranche::where('user_id', $user->id)->first();
                
                if (!$userBranche) {
                    $this->skipped++;
                    $this->errors[] = "Baris $rowNumber: User dengan NIK '$nikExcel' ({$employee->full_name}) tidak memiliki data cabang (UserBranche).";
                    Log::warning("UserBranche tidak ditemukan untuk User ID: {$user->id}");
                    continue;
                }

                Log::info("UserBranche ditemukan - ID: {$userBranche->id}, Branch ID: {$userBranche->branches_id}");

                // ========================
                // STEP 4: VALIDASI ULANG - Pastikan NIK di Employee (via User->Employee) sama dengan NIK Excel
                // ========================
                $userEmployee = $user->employee; // Relasi User ke Employee
                
                if (!$userEmployee) {
                    $this->skipped++;
                    $this->errors[] = "Baris $rowNumber: User ID {$user->id} tidak memiliki relasi Employee yang valid.";
                    Log::error("User ID {$user->id} tidak memiliki relasi employee");
                    continue;
                }

                // Validasi NIK harus sama persis
                if ($userEmployee->nik !== $nikExcel) {
                    $this->skipped++;
                    $this->errors[] = "Baris $rowNumber: NIK tidak cocok! Excel: '$nikExcel' vs Database: '{$userEmployee->nik}'.";
                    Log::error("NIK mismatch pada baris $rowNumber", [
                        'nik_excel' => $nikExcel,
                        'nik_database' => $userEmployee->nik,
                        'employee_id' => $userEmployee->id,
                        'user_id' => $user->id,
                        'user_branche_id' => $userBranche->id
                    ]);
                    continue;
                }

                Log::info("✓ Validasi NIK berhasil - NIK Excel: $nikExcel == NIK Database: {$userEmployee->nik}");

                // ========================
                // STEP 5: Cek duplikasi 
                // ========================
                // STEP 5: Cek duplikasi data
$duplicateQuery = PayrollPiece::where('user_branche_id', $userBranche->id)
    ->where('periode', $this->selectedPeriode)
    ->where('kategori', $kategori);

if ($tanggal) {
    $duplicateQuery->where('tanggal', $tanggal);
}

if ($duplicateQuery->exists()) {
    $this->skipped++;
    $this->errors[] = "Baris $rowNumber: SKIP duplikat (Kategori '$kategori', Tanggal '$tanggal')";
    continue;
}

// STEP 6: Simpan
PayrollPiece::create([
    'user_branche_id' => $userBranche->id,
    'periode' => $this->selectedPeriode,
    'jabatan' => trim($row[4] ?? '-'),
    'kesejahteraan' => (float) $kesejahteraan,
    'komunikasi' => (float) $komunikasi,
    'tunjangan' => (float) $tunjangan,
    'potongan' => (float) $potongan,
    'kategori' => $kategori,
    'keterangan' => $keterangan ?: null,
    'tanggal' => $tanggal
]);

                // ========================
                // STEP 6: Simpan PayrollPiece
                // ========================
                // PayrollPiece::create([
                //     'user_branche_id' => $userBranche->id,
                //     'periode' => $this->selectedPeriode,
                //     'jabatan' => trim($row[4] ?? '-'), // Kolom D (index 3)
                //     'kesejahteraan' => (float) $kesejahteraan,
                //     'komunikasi' => (float) $komunikasi,
                //     'tunjangan' => (float) $tunjangan,
                //     'potongan' => (float) $potongan,
                //     'kategori' => $kategori,
                //     'keterangan' => $keterangan ?: null,
                //     'tanggal' => $tanggal
                // ]);

                Log::info("✅ Berhasil import baris $rowNumber", [
                    'nik' => $nikExcel,
                    'nama' => $employee->full_name,
                    'employee_id' => $employee->id,
                    'user_id' => $user->id,
                    'user_branche_id' => $userBranche->id,
                    'periode' => $this->selectedPeriode,
                    'kategori' => $kategori
                ]);

                $this->imported++;

            } catch (\Exception $e) {
                $this->skipped++;
                $this->errors[] = "Baris $rowNumber: Error - " . $e->getMessage();
                Log::error("Exception pada baris $rowNumber", [
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'nik' => $nikExcel ?? 'N/A',
                    'nama' => $namaExcel ?? 'N/A'
                ]);
            }
        }

        // ========================
        // Kirim feedback ke session
        // ========================
        if ($this->imported > 0) {
            session()->flash('message', "✅ Berhasil import {$this->imported} data ke periode '{$this->selectedPeriode}'. {$this->skipped} data gagal/dilewati.");
        } else {
            session()->flash('error', "❌ Tidak ada data yang berhasil diimport. {$this->skipped} data gagal/dilewati.");
        }

        if (!empty($this->errors)) {
            // Batasi jumlah error yang ditampilkan
            $errorCount = count($this->errors);
            $displayErrors = array_slice($this->errors, 0, 30);
            
            $errorMessage = implode("\n", $displayErrors);
            if ($errorCount > 30) {
                $errorMessage .= "\n\n... dan " . ($errorCount - 30) . " error lainnya. Cek log untuk detail lengkap.";
            }
            
            session()->flash('errors_detail', $errorMessage);
        }
    }
}